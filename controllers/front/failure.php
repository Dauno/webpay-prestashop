<?php

if (!defined('_PS_VERSION_'))
	exit;

class WebpayFailureModuleFrontController extends ModuleFrontController
{
	public function initContent() {
		parent::initContent();
		$this->context->smarty->assign(array(
			'id_cart' => addslashes($_POST["TBK_ORDEN_COMPRA"]),
		));

		$this->setTemplate('failure.tpl');
	}
}

?>