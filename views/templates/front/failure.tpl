
{if $id_cart}
	<h1>Transacción Fracasada</h1>
	<div class="webpay_about">
		<h3>Nro pedido: {$id_cart}</h3>
		<p>Las posibles causas de este rechazo son:</p>
		<p>- Error en el ingreso de los datos de su tarjeta de crédito o Debito (fecha y/o código de seguridad).</p>
		<p>- Su tarjeta de crédito o debito no cuenta con el cupo necesario para cancelar la compra.</p>
		<p>- Tarjeta aún no habilitada en el sistema financiero.</p>
	</div>
{else}
	<h1>Error Webpay</h1>
	<div class="webpay_about">
		<h3>Intentelo más tarde</h3>
	</div>
{/if}
