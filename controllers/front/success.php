<?php

if (!defined('_PS_VERSION_'))
	exit;

class WebpaySuccessModuleFrontController extends ModuleFrontController
{
	public function initContent() {
		parent::initContent();
		$TBK_ID_SESION = addslashes(isset($_POST["TBK_ID_SESION"])?$_POST["TBK_ID_SESION"]:"");
		$filename_txt = Configuration::get('URL_KCC')."log/MAC01Normal$TBK_ID_SESION.txt";
		$datos=$this->leerDatos($filename_txt);
		$id_cart=addslashes($_POST["TBK_ORDEN_COMPRA"]);

		$cart = new Cart(intval($id_cart));
		$orderId = Order::getOrderByCartId((int)$id_cart);
		$order = new Order((int)$orderId);
		$nombreTienda=Configuration::get('PS_SHOP_NAME');
		$urlTienda=Tools::getHttpHost(true).__PS_BASE_URI__;
		$products = $cart->getProducts(true);
		$this->context->smarty->assign(array(
			'id_cart' => $id_cart,
			'products' => $products,
			'nombreTienda' => $nombreTienda,
			'orderId' => $orderId,
			'urlTienda' => $urlTienda,
			'order' => $order,
		));
		$Webpay = new Webpay();
		if (file_exists($filename_txt) and $Webpay->countRegWebPayData(array("id_cart"=>$id_cart))>0) {
			$this->context->smarty->assign($datos);
			$this->setTemplate('success.tpl');
		}else{
			$this->context->smarty->assign(array('id_cart' => $id_cart));
			$this->setTemplate('failure.tpl');
		}
	}

	public function leerDatos($myPath){
	   return $data;
	}
}

?>
