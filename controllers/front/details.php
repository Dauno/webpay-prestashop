<?php

if (!defined('_PS_VERSION_'))
	exit;

class WebpayDetailsModuleFrontController extends ModuleFrontController
{
	public function initContent()
	{
		parent::initContent();

		$this->context->smarty->assign(array(
			'exito' => Configuration::get('TBK_URL_EXITO'),
			'fracaso' => Configuration::get('TBK_URL_FRACASO'),
			'cgi' => Configuration::get('URL_CGI'),
			'kcc' => Configuration::get('URL_KCC'),
			'pay' => Configuration::get('_PS_OS_WEBPAY_'),
		));

		$this->setTemplate('details.tpl');
	}
}

?>
