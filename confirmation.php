<?php 
include(dirname(__FILE__).'/../../config/config.inc.php');
$webpay = Module::getInstanceByName('webpay');
$path_kcc=$webpay->getkcc();

$TBK_RESPUESTA= addslashes($_POST["TBK_RESPUESTA"]);
$TBK_ORDEN_COMPRA=addslashes(isset ($_POST["TBK_ORDEN_COMPRA"])?$_POST["TBK_ORDEN_COMPRA"]:0);
$TBK_MONTO= addslashes($_POST["TBK_MONTO"]);
$TBK_MONTO=  substr($TBK_MONTO, 0,  strlen($TBK_MONTO)-2).".".substr($TBK_MONTO,strlen($TBK_MONTO)-2,  strlen($TBK_MONTO));
$TBK_ID_SESION= addslashes($_POST["TBK_ID_SESION"]);
$myPath = $path_kcc."cgi-bin/log/dato$TBK_ID_SESION.log";
$filename_txt = $path_kcc."cgi-bin/log/MAC01Normal$TBK_ID_SESION.txt";
$cmdline = $path_kcc."cgi-bin/tbk_check_mac.cgi $filename_txt";
$acepta=false;

if(file_exists($myPath)){
    if ($fic = fopen($myPath, "r")){
        $linea=fgets($fic);
        fclose($fic);
    }
    $detalle=  explode(";", $linea);
    if (count($detalle)>=1){
        $monto=$detalle[0];
        $ordenCompra=$detalle[1];
    }
    $fp=fopen($filename_txt,"wt");
    while(list($key, $val)=each($_POST)){
        fwrite($fp, "$key=$val&");
    }
    fclose($fp);

    exec ($cmdline, $result, $retint);
    $mac=$result[0];
    $TBK_MONTO=(double)$TBK_MONTO;
    $monto=(double)$monto;
    //validación de  mac y respuesta
    if(($TBK_RESPUESTA=="0") AND ($mac=="CORRECTO")){ 
        //validaci de monto y Orden de compra
       $acepta = (($TBK_MONTO==$monto) && ($TBK_ORDEN_COMPRA==$ordenCompra)) ? true : false ;
        if($acepta AND !$webpay->countRegWebPayData(array("id_cart"=>$TBK_ORDEN_COMPRA))>0){
            $extraVars = array();
            echo "ACEPTADO";
            $datos=leerDatos($filename_txt,$TBK_ID_SESION);
            $webpay->updateWebPayData($datos);
            $webpay->validateOrder($TBK_ORDEN_COMPRA, _PS_OS_PAYMENT_, $TBK_MONTO, $webpay->displayName, NULL, $extraVars);
        }else{
            echo "RECHAZADO";
        }
    }else{
        echo "RECHAZADO";
    }
}

function leerDatos($myPath,$TBK_ID_SESION){
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

    $TBK_FECHA_TRANSACCION = $anio."-".$FECHA[1]."-".$FECHA[0];
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
    return $data;
}
?>

