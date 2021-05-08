<script>
$().ready(function(){
  $('.noticia_archivo').hide();
  
  $('#boton').click(function(){
    $('.noticia_archivo').show();
    return true;
  });
});
</script>

<h2>Subir Archivo TXT para actualizar Clientes</h2>
<br />
<br />
<div class="noticia_archivo">Espere mientras se carga el archivo, se guardan 1000 registros por minuto aproximadamente.</div>

<?php if ($sf_user->hasFlash('notice')): ?>
  <div class="notice"><?php echo __($sf_user->getFlash('notice'), array(), 'sf_admin') ?></div>
<?php endif; ?>

<?php if ($sf_user->hasFlash('error')): ?>
  <div class="error"><?php echo __($sf_user->getFlash('error'), array(), 'sf_admin') ?></div>
<?php endif; ?>

<form action="<?php echo url_for('cliente_archivo/cargarDesdeTxt') ?>" method="POST" enctype="multipart/form-data" >
  <?php //echo $form ?>
  <label for="archivo_nombre">Nombre Archivo</label>
  <input name="archivo_nombre" id="archivo_nombre" type="text" />
  <br><br>
  <label for="archivo_archivo">Archivo</label>
  <input name="archivo_archivo[]" id="archivo_archivo" type="file" multiple="" />
  <br><br>
  <input id="botonSubmit" type="submit" value="Subir" style="display: none;">
</form>

<button onclick="checkSave()">Subir</button>

<script type="text/javascript">
	function checkSave() {
		if($("#archivo_nombre").val()) {
			$("#botonSubmit").click();
		} else {
			alert("Es necesario el nombre del archivo para guardar.");
		}
	}
</script>