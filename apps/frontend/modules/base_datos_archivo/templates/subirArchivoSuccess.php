<script>
$().ready(function(){
  $('.noticia_archivo').hide();
  
  $('#boton').click(function(){
    $('.noticia_archivo').show();
    return true;
  });
});
</script>

<h2>Subir Archivo CSV para actualizar Base de Datos Tramimundo</h2>
<br />
<br />
<div class="noticia_archivo">Espere mientras se carga el archivo, se guardan 1000 registros por minuto aproximadamente.</div>

<?php if ($sf_user->hasFlash('notice')): ?>
  <div class="notice"><?php echo __($sf_user->getFlash('notice'), array(), 'sf_admin') ?></div>
<?php endif; ?>

<?php if ($sf_user->hasFlash('error')): ?>
  <div class="error"><?php echo __($sf_user->getFlash('error'), array(), 'sf_admin') ?></div>
<?php endif; ?>

<form action="<?php echo url_for('base_datos_archivo/subirArchivo') ?>" method="POST" enctype="multipart/form-data">
  <?php echo $form ?>
  <input id="boton" type="submit" value="Subir">
</form>
<div class="formato_archivo">
<h1>Formato del archivo</h1>
<p>
El archivo que se debe subir es de formato de valores separados por coma (CSV), el cual se puede generar exportandolo de Excel, para exportarlo los atributos deben ser: separador: punto y coma (;), quote text &oacute; citas textuales ninguno por defecto viene comillas (") pero se quita y se deja vac&iacute;o.
</p>
<br /><br />
<p>
El encabezado del archivo debe ser el siguiente:<br />
NO DE PARTE;DESCRIPCION;FRACCION;ARANCEL;U.C.;CANTIDAD;VALOR;ORIGEN
</p>
</div>