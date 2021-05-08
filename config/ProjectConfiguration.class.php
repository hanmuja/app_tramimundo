<?php

require_once '../lib/vendor/symfony/lib/autoload/sfCoreAutoload.class.php';
//require_once 'lib/vendor/symfony/lib/autoload/sfCoreAutoload.class.php';
//require_once '/home/sfprojects/tramimundo_archivos/lib/vendor/symfony/lib/autoload/sfCoreAutoload.class.php';
//require_once '/usr/share/php/symfony/symfony14/lib/autoload/sfCoreAutoload.class.php';
sfCoreAutoload::register();

class ProjectConfiguration extends sfProjectConfiguration
{
  public function setup()
  {
    $this->enablePlugins(array(
    	'sfDoctrinePlugin',
    	'sfDoctrineGuardPlugin',
    ));
  }
}
