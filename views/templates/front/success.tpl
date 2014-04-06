<h1>TRANSACCIÓN EXITOSA</h1>
<div class="webpay_about">
    <h3>Cliente     : {$cookie->customer_firstname} {$cookie->customer_lastname} </h3> 
    <h3>Tipo de transacción   : Venta</h3> 
    <h3>Nro Pedido            : {$id_cart}</h3> 
    <h3>Monto                 : {$amount} {$moneda}</h3> 
    <h3>Código de autorización : {$code_autorization}</h3> 
    <h3>Fecha transacción      : {$date_transaction}</h3> 
    <h3>Hora transacción      : {$hour_transaction}</h3> 
    <h3>Tipo de cuotas        :{if ($pay_type=="VN")}
          Sin Cuotas
         {elseif ($pay_type=="VC")}
          Cuotas normales
         {elseif ($pay_type=="SI")}
          Sin interés   
         {elseif ($pay_type=="CI")}
          Cuotas Comercio
         {elseif ($pay_type=="VD")}
          Venta debito
     {/if}
    </h3> 
     <h3>Tipo pago         :{if ($pay_type=="VN")}
          Crédito
         {elseif ($pay_type=="VC")}
          Crédito
         {elseif ($pay_type=="SI")}
          Crédito   
         {elseif ($pay_type=="CI")}
          Crédito
         {elseif ($pay_type=="VD")}
          Redcompra
     {/if}
    </h3>   
    <h3>Número cuotas         :{if ($nro_fee=="0")}
         00 
        {else}
        {$nro_fee}
        {/if}
    </h3> 
    <h3>Número tarjeta        : {$tarjeta}</h3> 
    <h3> <p>En caso de problemas con su pago, no dude en contactarse con <a href="{$url_tienda}contact-form.php">nosotros</a> </h3> 
	
</div>
<p></p>
