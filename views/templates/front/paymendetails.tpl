<h3>Pago con Web Pay</h3>
<form action="{$url_cgi}" method="post" id="webpay_form" name="webpay_form" >
	<p>
		<img style="float:left; margin: 0px 10px 5px 0px;width: 140px;" alt="Web pay" src="{$this_path}img/webpay.jpeg">
		Ha elegido pago con Web Pay
		<br>
		<br>
		El total de su pedido es
		<span  class="price">${$tbk_monto}</span>
	</p>
			<input 	type=hidden name="TBK_TIPO_TRANSACCION" value="{$tbk_tipo_transaccion}">
            <input  type=hidden name="TBK_MONTO" value="{$tbk_monto}">
            <input  type=hidden name="TBK_ORDEN_COMPRA" value="{$tbk_orden_compra}">
            <input  type=hidden name="TBK_ID_SESION" value="{$tbk_id_sesion}">
            <input  type=hidden name="TBK_URL_EXITO" value="{$tbk_url_exito}">
            <input  type=hidden name="TBK_URL_FRACASO" value="{$tbk_url_fracaso}">
    <p></p>
    <p></p>
    <b>Por favor acepte su pedido pulsando en 'confirmo mi pedido'.</b>
	<p class="cart_navigation submit">
		<a class="button" title="Anterior" href="javascript:history.go(-1);">« Otros modos de pago</a>
		<input class="exclusive" type="submit" value="Confirmo mi Pedido »" name="processCarrier">
	</p>
</form>
