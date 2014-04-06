{$message}

<fieldset>
	<p><img width="120" src="{$this_path}img/webpay.jpeg" alt="webpay" /></p>
	<legend>Configuración WebPay</legend>
	<form method="post">
		<p>
			<label for="TBK_URL_EXITO">Url pagina exito:</label>
			<input id="TBK_URL_EXITO" name="TBK_URL_EXITO" type="text" value="{$TBK_URL_EXITO}" />
			<span>Ej :http://"url tienda"/index.php?fc=module&module=webpay&controller=success&id_lang=4</span>
		</p>
		<p>
			<label for="TBK_URL_FRACASO">Url pagina fracaso:</label>
			<input id="TBK_URL_FRACASO" name="TBK_URL_FRACASO" type="text" value="{$TBK_URL_FRACASO}" />
			<span>Ej :http://"url tienda"/index.php?fc=module&module=webpay&controller=failure&id_lang=4</span>
		</p>
		<p>
			<label for="URL_CGI">Url CGI:</label>
			<input id="URL_CGI" name="URL_CGI" type="text" value="{$URL_CGI}" />
			<span>Ej :http://"url tienda"/kcc/cgi-bin/tbk_bp_pago.cgi</span>
		</p>
		<p>
			<label for="URL_KCC">Path KCC:</label>
			<input id="URL_KCC" name="URL_KCC" type="text" value="{$URL_KCC}" />
			<span>Ej :/var/www/path tienda/kcc</span>
		</p>
		<p>
			<label>&nbsp;</label>
			<input id="submit_{$module_name}" name="submit_{$module_name}" type="submit" value="Guardar" class="button" />
		</p>
	</form>
</fieldset>
