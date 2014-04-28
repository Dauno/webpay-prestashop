<?php

if (!defined('_PS_VERSION_'))
	exit;

class WebpayPaymendetailsModuleFrontController extends ModuleFrontController {
	public function initContent() {
		parent::initContent();
		$cart = $this->context->cart;
		$cookie = $this->context->cookie;
		$webpay = $this->module;

		if (!$this->module->checkCurrency($cart)){
			$cart->id_currency   =  Configuration::get('PS_CURRENCY_DEFAULT');
			$cookie->id_currency =  Configuration::get('PS_CURRENCY_DEFAULT');
			$cart->update();
		}
		$log =Configuration::get('URL_KCC')."log/dato".Tools::getToken(false).".log";
		$fic = fopen($log, "w+");
		$linea=number_format($cart->getOrderTotal(true,3),2, '.', '').";".intval($cart->id);
        fwrite ($fic,$linea);
        fclose($fic);
       	$this->context->smarty->assign(array(
			'tbk_monto'             =>number_format($cart->getOrderTotal(true,3),2, '.', ''),
		    'tbk_orden_compra'      =>intval($cart->id),
		    'tbk_id_sesion'         =>Tools::getToken(false), 
		    'tbk_tipo_transaccion'  =>Configuration::get('TBK_TIPO_TRANSACCION'),
		    'url_cgi'               =>Configuration::get('URL_CGI'),
		    'tbk_url_exito'         =>Configuration::get('TBK_URL_EXITO'),
		    'tbk_url_fracaso'       =>Configuration::get('TBK_URL_FRACASO'),
			'this_path'				=>$this->module->getPathUri(),
		));
		 if(file_exists($log)){
		 	$data=array("id_cart"=> intval($cart->id),"transaction_type"=>Configuration::get('TBK_TIPO_TRANSACCION'),"response"=>"INIT","amount"=>number_format($cart->getOrderTotal(true,3),2, '.', ''),"id_sesion"=>Tools::getToken(false));
        	if(!$webpay->existRegWebPayData($data)) $webpay->insertWebPayData($data);
			$this->setTemplate('paymendetails.tpl');
		}else{
			$this->setTemplate('paymendetails-err.tpl');
		}
	}
}

?>
