<?php

if (!defined('_PS_VERSION_'))
	exit;

class WebpaySuccessModuleFrontController extends ModuleFrontController
{
	public function initContent() {
		parent::initContent();
		$TBK_ID_SESION = addslashes(isset($_POST["TBK_ID_SESION"])?$_POST["TBK_ID_SESION"]:"");
		$filename_txt = Configuration::get('URL_KCC')."cgi-bin/log/MAC01Normal$TBK_ID_SESION.txt";
		$datos=$this->leerDatos($filename_txt);
		$this->context->smarty->assign(array(
			'id_cart' => addslashes($_POST["TBK_ORDEN_COMPRA"]),
		));
		$this->context->smarty->assign($datos);
		$this->setTemplate('success.tpl');
	}

	public function leerDatos($myPath){
	   	$fic = fopen($myPath, "r");
	    $linea=fgets($fic);
	    fclose($fic);
	    $detalle=explode("&", $linea);
	    $TBK_ORDEN_COMPRA=explode("=",$detalle[0]);
	    $TBK_TIPO_TRANSACCION=explode("=",$detalle[1]);
	    $TBK_RESPUESTA=explode("=",$detalle[2]);
	    $TBK_MONTO=explode("=",$detalle[3]);
	    $TBK_CODIGO_AUTORIZACION=explode("=",$detalle[4]);
	    $TBK_FINAL_NUMERO_TARJETA=explode("=",$detalle[5]);
	    $TBK_FECHA_CONTABLE=explode("=",$detalle[6]);
	    $TBK_FECHA_TRANSACCION=explode("=",$detalle[7]);
	    $TBK_HORA_TRANSACCION=explode("=",$detalle[8]);
	    $TBK_ID_TRANSACCION=explode("=",$detalle[10]);
	    $TBK_TIPO_PAGO=explode("=",$detalle[11]);
	    $TBK_NUMERO_CUOTAS=explode("=",$detalle[12]);
	    $TBK_MAC=explode("=",$detalle[13]);
	    $TBK_FECHA_CONTABLE[1]=substr($TBK_FECHA_CONTABLE[1],2,2)."-".substr($TBK_FECHA_CONTABLE[1],0,2);
	    $TBK_FECHA_TRANSACCION[1]=substr($TBK_FECHA_TRANSACCION[1],2,2)."-".substr($TBK_FECHA_TRANSACCION[1],0,2);
	    $TBK_HORA_TRANSACCION[1]=substr($TBK_HORA_TRANSACCION[1],0,2).":".substr($TBK_HORA_TRANSACCION[1],2,2).":".substr($TBK_HORA_TRANSACCION[1],4,2);
	    $anio=date("Y");
	    $FECHA=explode("-",$TBK_FECHA_TRANSACCION[1]);

	    //$TBK_FECHA_TRANSACCION = $anio."-".$FECHA[1]."-".$FECHA[0];
	    $TBK_FECHA_TRANSACCION = $FECHA[0]."-".$FECHA[1]."-".$anio;
	    $data['id_cart']=$TBK_ORDEN_COMPRA[1];
	    $data['id_sesion']=$TBK_ID_SESION;
	    $data['transaction_type']=$TBK_TIPO_TRANSACCION[1];
	    $data['response']=$TBK_RESPUESTA[1];
	    $data['amount']=substr($TBK_MONTO[1], 0,  strlen($TBK_MONTO[1])-2).".".substr($TBK_MONTO[1],strlen($TBK_MONTO[1])-2,  strlen($TBK_MONTO[1]));
	    $data['code_autorization']=$TBK_CODIGO_AUTORIZACION[1];
	    $data['date_transaction']=$TBK_FECHA_TRANSACCION;
	    $data['hour_transaction']=$TBK_HORA_TRANSACCION[1];
	    $data['transaction_id']=$TBK_ID_TRANSACCION[1];
	    $data['pay_type']=$TBK_TIPO_PAGO[1];
	    $data['nro_fee']=$TBK_NUMERO_CUOTAS[1];
	    $data['date_upd']=$TBK_FECHA_TRANSACCION;
	    $data['tarjeta']="************".$TBK_FINAL_NUMERO_TARJETA[1];
	    return $data;
	}
}

?>