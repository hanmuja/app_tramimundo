<?php

/**
 * BaseDatosTable
 * 
 * This class has been auto-generated by the Doctrine ORM Framework
 */
class BaseDatosTable extends Doctrine_Table
{
    /**
     * Returns an instance of this class.
     *
     * @return object BaseDatosTable
     */
    public static function getInstance()
    {
        return Doctrine_Core::getTable('BaseDatos');
    }
    
    public static function deleteArchivoId($id)
    {
      $q = Doctrine_Query::create()
          ->delete('BaseDatos b')
          ->where("b.archivo_id = '$id'");
      
      echo $q->execute();
    }
}