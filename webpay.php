<?php
if (!defined('_PS_VERSION_'))
	exit;

class Webpay extends PaymentModule {
	public function __construct() {
		$this->name = 'webpay';
		$this->tab = 'payments_gateways';
		$this->version = '1.0';
		$this->className = 'Webpay';
		$this->author = 'dmsoft';
		$config = Configuration::getMultiple(array('TBK_TIPO_TRANSACCION','TBK_URL_EXITO','TBK_URL_FRACASO','URL_CGI','URL_KCC'));
		if (isset($config['TBK_TIPO_TRANSACCION']))
            $this->tbk_tipo_transaccion = $config['TBK_TIPO_TRANSACCION'];

        if (isset($config['TBK_URL_EXITO']))
            $this->tbk_url_exito = $config['TBK_URL_EXITO'];

        if (isset($config['TBK_URL_FRACASO']))
            $this->tbk_url_fracaso = $config['TBK_URL_FRACASO'];

        if (isset($config['URL_CGI']))
            $this->url_cgi = $config['URL_CGI'];

        if (isset($config['URL_KCC']))
            $this->url_kcc = $config['URL_KCC'];


		parent::__construct();

		$this->displayName = $this->l('Webpay');
		$this->description = $this->l('Acepta pagos via Web Pay, compatible con prestashop 1.5.');

		$this->confirmUninstall = $this->l('Estas seguro que deseas desinstalar?');

		//$this->_checkContent();

		$this->context->smarty->assign('module_name', $this->name);
	}

	public function install() {
		if (!parent::install() or
			!$this->installDB() or
			!$this->registerHook('displayHeader') or
			!$this->registerHook('payment') or
			!$this->registerHook('displayRightColumn') or
			!Configuration::updateValue('TBK_TIPO_TRANSACCION', 'TR_NORMAL') or
			!Configuration::updateValue('TBK_URL_EXITO', '') or
			!Configuration::updateValue('TBK_URL_FRACASO', '') or
			!Configuration::updateValue('URL_CGI', '') or
			!Configuration::updateValue('URL_KCC', '') or
			!$this->_createContent())
			return false;
		return true;
	}

	private function installDB(){
        Db::getInstance()->Execute('
        CREATE TABLE IF NOT EXISTS`'._DB_PREFIX_.'webpay_data` (
            `id_record` INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
	        `id_cart` INT NOT NULL,
            `id_sesion`  VARCHAR(255),
            `transaction_type`  VARCHAR(20),
            `response` VARCHAR (20),
            `amount` VARCHAR (20),
 	        `code_autorization` VARCHAR (100),
            `date_transaction` date,
            `hour_transaction` VARCHAR (20),
            `transaction_id`  VARCHAR (100),
            `pay_type`  VARCHAR (50),
            `nro_fee` INT (11),
            `date_upd` datetime NOT NULL
         ) ENGINE = '._MYSQL_ENGINE_.' DEFAULT CHARSET=utf8 ;');
        return true;
    }

	public function uninstall()	{
		if (!parent::uninstall() or
			!$this->uninstallDB() or
			!Configuration::deleteByName('TBK_TIPO_TRANSACCION') or
			!Configuration::deleteByName('TBK_URL_EXITO') or
			!Configuration::deleteByName('TBK_URL_FRACASO') or
			!Configuration::deleteByName('URL_CGI') or
			!Configuration::deleteByName('URL_KCC') or
			!$this->_deleteContent())
			return false;
		return true;
	}
	
	private function uninstallDB(){
  		Db::getInstance()->Execute('DROP TABLE IF EXISTS `'._DB_PREFIX_.'webpay_data`;');
    		return true;
  	}

	public function hookDisplayHeader() {
		$this->context->controller->addCSS($this->_path.'css/style.css', 'all');
		$this->context->controller->addJS($this->_path.'js/script.js', 'all');
	}
	public function hookPayment($params) {
      if (!$this->active)
  			return ;
      if (!$this->checkCurrency($params['cart']))
  			return ;

		$this->context->smarty->assign(array(
	        'this_path' => $this->_path,
	        'this_path_bw' => $this->_path,
	        'module_link' => $this->context->link->getModuleLink('webpay', 'details'),
	        'this_path_ssl' => Tools::getShopDomainSsl(true, true).__PS_BASE_URI__.'modules/'.$this->name.'/'
	     ));
  		return $this->display(__FILE__, 'payment.tpl');
  	}
	
	public function hookDisplayRightColumn() {
		$this->context->smarty->assign(array(
			'module_link' => $this->context->link->getModuleLink('webpay', 'details'),
			'this_path' => $this->_path,
		));

		return $this->display(__FILE__, 'right.tpl');
	}
	public function checkCurrency($cart)
	{
		$currency_order = new Currency($cart->id_currency);
		$currencies_module = $this->getCurrency($cart->id_currency);

		if (is_array($currencies_module))
			foreach ($currencies_module as $currency_module)
				if ($currency_order->id == $currency_module['id_currency'])
					return true;
		return false;
	}
	public function getContent() {
		$message = '';

		if (Tools::isSubmit('submit_'.$this->name))
			$message = $this->_saveContent();

		$this->_displayContent($message);
		$this->context->smarty->assign(array(
			'module_link' => $this->context->link->getModuleLink('webpay', 'details'),
			'this_path' => $this->_path,
		));
		return $this->display(__FILE__, 'settings.tpl');
	}

	private function _saveContent() {
		$message = '';

		if (Configuration::updateValue('TBK_URL_EXITO', Tools::getValue('TBK_URL_EXITO')) and
			Configuration::updateValue('URL_CGI', Tools::getValue('URL_CGI')) and
			Configuration::updateValue('URL_KCC', Tools::getValue('URL_KCC')) and
			Configuration::updateValue('TBK_URL_FRACASO', Tools::getValue('TBK_URL_FRACASO')))
			$message = $this->displayConfirmation($this->l('configuración Guardada'));
		else
			$message = $this->displayError($this->l('Error al guardaar'));

		return $message;
	}

	private function _displayContent($message) {
		$this->context->smarty->assign(array(
			'message' => $message,
			'TBK_URL_EXITO' => Configuration::get('TBK_URL_EXITO'),
			'URL_CGI' => Configuration::get('URL_CGI'),
			'URL_KCC' => Configuration::get('URL_KCC'),
			'TBK_URL_FRACASO' => Configuration::get('TBK_URL_FRACASO'),
		));
	}

	private function _checkContent() {
		if (!Configuration::get('TBK_URL_EXITO') and
		 	!Configuration::get('URL_CGI') and
		 	!Configuration::get('URL_KCC') and
			!Configuration::get('TBK_URL_FRACASO'))
			$this->warning = $this->l('Configura el modulo.');
	}
	public function getkcc() {
		$log = (!Configuration::get('URL_KCC')) ? "" : Configuration::get('URL_KCC');
		return $log;
	}
	private function _createContent() {
		if (!Configuration::updateValue('TBK_URL_EXITO', '') or
			!Configuration::updateValue('URL_CGI', '') or
			!Configuration::updateValue('URL_KCC', '') or
			!Configuration::updateValue('TBK_URL_FRACASO', ''))
			return false;
		return true;
	}

	private function _deleteContent() {
		if (!Configuration::deleteByName('TBK_URL_EXITO') or
			!Configuration::deleteByName('URL_CGI') or
			!Configuration::deleteByName('URL_KCC') or
			!Configuration::deleteByName('TBK_URL_FRACASO'))
			return false;
		return true;

	}
	public function insertWebPayData($data) {
        return Db::getInstance()->autoExecute(_DB_PREFIX_.'webpay_data', $data ,'INSERT');
        return false;
    }

    public function updateWebPayData($data) {
        return Db::getInstance()->autoExecute(_DB_PREFIX_.'webpay_data',$data , 'UPDATE', 'id_cart = "'.$data['id_cart'].'"');
        return false;
    }
    public function existRegWebPayData($data) {
      $sql = 'SELECT id_record FROM '._DB_PREFIX_.'webpay_data where id_cart='.$data["id_cart"];
      if ($results = Db::getInstance()->ExecuteS($sql))
              return true;
      return false;
    }
    public function countRegWebPayData($data) {
      $sql = 'SELECT count( id_record) as cantidad FROM '._DB_PREFIX_."webpay_data where  response='0' AND  id_cart='".$data["id_cart"]."'";
      $results = Db::getInstance()->ExecuteS($sql);
        return $results[0]['cantidad'];
    }
    

}

?>
