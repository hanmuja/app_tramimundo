<?php

/**
 * BaseDatosArchivo
 * 
 * This class has been auto-generated by the Doctrine ORM Framework
 * 
 * @package    tramimundo
 * @subpackage model
 * @author     Your name here
 * @version    SVN: $Id: Builder.php 7490 2010-03-29 19:53:27Z jwage $
 */
class BaseDatosArchivo extends BaseBaseDatosArchivo
{
  public function __toString()
  {
    if($this->getFechaSubida())
      return $this->getFechaSubida();
    else
      return $this->getId();
  }
}
