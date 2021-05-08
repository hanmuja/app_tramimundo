<ul>
  <li><?php echo link_to('Users', 'sf_guard_user') ?></li>
  <li <?php echo ($modulo == 'base_datos_archivo') ? 'class="sel"' : '' ?>><?php echo link_to('Archivos Base de Datos Tramimundo', '@base_datos_archivo') ?></li>
  <li <?php echo ($modulo == 'base_datos') ? 'class="sel"' : '' ?>><?php echo link_to('Base de Datos Tramimundo', '@base_datos') ?></li>
  <li <?php echo ($modulo == 'cliente_archivo') ? 'class="sel"' : '' ?>><?php echo link_to('Archivos Clientes', '@cliente_archivo') ?></li>
  <li <?php echo ($modulo == 'cliente') ? 'class="sel"' : '' ?>><?php echo link_to('Clientes', '@cliente') ?></li>
  <li><?php echo link_to('Logout', 'sf_guard_signout') ?></li>
</ul>