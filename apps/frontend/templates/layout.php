<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
  <head>
    <?php include_http_metas() ?>
    <?php include_metas() ?>
    <?php include_title() ?>
    <link rel="shortcut icon" href="/favicon.ico" />
    <?php include_stylesheets() ?>
    <?php include_javascripts() ?>
  </head>
  <body>
    <div id="cabecera">
      <h1>Base de datos Tramimundo</h1>
      <div id="menu">
        <?php if ($sf_user->isAuthenticated()): ?>
          <?php include_partial('inicio/menu', array('modulo' => $sf_params->get('module'))); ?>
        <?php endif; ?>
      </div>
    </div>
    <div id="contenido">
      <?php echo $sf_content ?>
    </div>
  </body>
</html>
