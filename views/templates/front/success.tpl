<div id="center_column" class="center_column col-xs-12 col-sm-12">
  <h1 class="page-heading bottom-indent">TRANSACCIÓN EXITOSA</h1>
    <div class="info-order">
      <p class="title_block">Detalle</p>
      <p><strong>Cliente: </strong> {$cookie->customer_firstname} {$cookie->customer_lastname}</p>
      <p><strong>Tipo de transacción: </strong>Venta</p>
      <p><strong>Nro Pedido: </strong>{$id_cart}</p>
      <p><strong>Monto: </strong>$ {$amount}</p>
      <p><strong>Código de autorización: </strong>{$code_autorization}</p>
      <p><strong>Fecha transacción: </strong>{$date_transaction}</p>
      <p><strong>Hora transacción: </strong>{$hour_transaction}</p>
      <p><strong>Tipo de cuotas: </strong>{if ($pay_type=="VN")}
                Sin Cuotas
               {elseif ($pay_type=="VC")}
                Cuotas normales
               {elseif ($pay_type=="SI")}
                Sin interés   
               {elseif ($pay_type=="CI")}
                Cuotas Comercio
               {elseif ($pay_type=="VD")}
                Venta debito
           {/if}</p>
      <p><strong>Tipo pago: </strong>{if ($pay_type=="VN")}
                Crédito
               {elseif ($pay_type=="VC")}
                Crédito
               {elseif ($pay_type=="SI")}
                Crédito   
               {elseif ($pay_type=="CI")}
                Crédito
               {elseif ($pay_type=="VD")}
                Redcompra
           {/if}</p>
      <p><strong>Número cuotas: </strong>{if ($nro_fee=="0")}
               00 
              {else}
              {$nro_fee}
              {/if}</p>
      <p><strong>Número tarjeta: </strong>{$tarjeta}</p>
      <p><strong>Nombre del comercio: </strong>{$nombreTienda}</p>
      <p><strong>URL del Comercio: </strong><a href="{$urlTienda}">{$urlTienda|replace:"http://":""|replace:"/":""}</a></p>

    </div>
    <p class="title_block">Detalle de la Compra</p>

    <div id="order-detail-content" class="table_block">
      <table class="std">
        <thead>
          <tr>
            
            <th class="first_item">{l s='Producto'}</th>
            <th class="item">{l s='Descripción'}</th>
            <th class="item">{l s='Disp.'}</th>
            {if $order->hasProductReturned()}
              <th class="item">{l s='Returned'}</th>
            {/if}
            <th class="item">{l s='Precio unitario'}</th>
            <th class="item">{l s='Cantidad'}</th>
            <th class="last_item">{l s='Precio total'}</th>
          </tr>
        </thead>
        <tfoot>
          {assign var='use_tax' value=false}
          {if $priceDisplay && $use_tax}
            <tr class="item">
              <td colspan="{if $return_allowed || $order->hasProductReturned()}{if $order->hasProductReturned() && $return_allowed}7{else}6{/if}{else}5{/if}">
                {l s='Total products (tax excl.)'} <span class="price">{displayWtPriceWithCurrency price=$order->getTotalProductsWithoutTaxes() currency=$currency}</span>
              </td>
            </tr>
          {/if}
          <tr class="item">
            <td colspan="{if $return_allowed || $order->hasProductReturned()}{if $order->hasProductReturned() && $return_allowed}7{else}6{/if}{else}5{/if}">
              {l s='Total products'} {if $use_tax}{l s='(tax incl.)'}{/if}: <span class="price">{displayWtPriceWithCurrency price=$order->getTotalProductsWithTaxes() currency=$currency}</span>
            </td>
          </tr>
          {if $order->total_discounts > 0}
          <tr class="item">
            <td colspan="{if $return_allowed || $order->hasProductReturned()}{if $order->hasProductReturned() && $return_allowed}7{else}6{/if}{else}5{/if}">
              {l s='Total vouchers:'} <span class="price-discount">{displayWtPriceWithCurrency price=$order->total_discounts currency=$currency convert=1}</span>
            </td>
          </tr>
          {/if}
          {if $order->total_wrapping > 0}
          <tr class="item">
            <td colspan="{if $return_allowed || $order->hasProductReturned()}{if $order->hasProductReturned() && $return_allowed}7{else}6{/if}{else}5{/if}">
              {l s='Total gift wrapping cost:'} <span class="price-wrapping">{displayWtPriceWithCurrency price=$order->total_wrapping currency=$currency}</span>
            </td>
          </tr>
          {/if}
          <tr class="item">
            <td colspan="{if $return_allowed || $order->hasProductReturned()}{if $order->hasProductReturned() && $return_allowed}7{else}6{/if}{else}5{/if}">
              {l s='Total shipping'} {if $use_tax}{l s='(tax incl.)'}{/if}: <span class="price-shipping">{displayWtPriceWithCurrency price=$order->total_shipping currency=$currency}</span>
            </td>
          </tr>
          <tr class="totalprice item">
            <td colspan="{if $return_allowed || $order->hasProductReturned()}{if $order->hasProductReturned() && $return_allowed}7{else}6{/if}{else}5{/if}">
              {l s='Total'} <span class="price">{displayWtPriceWithCurrency price=$order->total_paid currency=$currency}</span>
            </td>
          </tr>
        </tfoot>
        
        <tbody>
        {foreach from=$products item=product name=productLoop}
          {assign var='productId' value=$product.id_product}
          {assign var='productAttributeId' value=$product.id_product_attribute}
          {assign var='quantityDisplayed' value=0}
          {assign var='cannotModify' value=1}
          {assign var='odd' value=$product@iteration%2}
          {assign var='noDeleteButton' value=1}
          {* Display the product line *}
          {include file="$tpl_dir./shopping-cart-product-line.tpl"}
          {* Then the customized datas ones*}
          {if isset($customizedDatas.$productId.$productAttributeId)}
            {foreach from=$customizedDatas.$productId.$productAttributeId[$product.id_address_delivery] key='id_customization' item='customization'}
              <tr id="product_{$product.id_product}_{$product.id_product_attribute}_{$id_customization}" class="alternate_item cart_item">
                <td colspan="4">
                  {foreach from=$customization.datas key='type' item='datas'}
                    {if $type == $CUSTOMIZE_FILE}
                      <div class="customizationUploaded">
                        <ul class="customizationUploaded">
                          {foreach from=$datas item='picture'}
                            <li>
                              <img src="{$pic_dir}{$picture.value}_small" alt="" class="customizationUploaded" />
                            </li>
                          {/foreach}
                        </ul>
                      </div>
                    {elseif $type == $CUSTOMIZE_TEXTFIELD}
                      <ul class="typedText">
                        {foreach from=$datas item='textField' name='typedText'}
                          <li>
                            <p>{$textField.name}</p>
                            {if $textField.name}
                              {l s='%s:' sprintf=$textField.name}
                            {else}
                              {l s='Text #%s:' sprintf=$smarty.foreach.typedText.index+1}
                            {/if}
                            {$textField.value}
                          </li>
                        {/foreach}
                      </ul>
                    {/if}
                  {/foreach}
                </td>
              </tr>
              {assign var='quantityDisplayed' value=$quantityDisplayed+$customization.quantity}
            {/foreach}
            {* If it exists also some uncustomized products *}
            {if $product.quantity-$quantityDisplayed > 0}{include file="$tpl_dir./shopping-cart-product-line.tpl"}{/if}
          {/if}
        {/foreach}
        </tbody>
        
      </table>
    </div>


    <p class="title_block" >Políticas de Despacho</p>

      <p><a href="url_del_cms" target="_blank">Ver aquí > </a></p>

    <p class="title_block">Políticas de Garantía y Devolución</p>

      <p><a href="url_del_cms" target="_blank">Ver aquí > </a></p>

</div>


