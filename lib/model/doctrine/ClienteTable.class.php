<?php

/**
 * ClienteTable
 * 
 * This class has been auto-generated by the Doctrine ORM Framework
 */
class ClienteTable extends Doctrine_Table
{
    /**
     * Returns an instance of this class.
     *
     * @return object ClienteTable
     */
    public static function getInstance()
    {
        return Doctrine_Core::getTable('Cliente');
    }
    
    public static function getBillings($archivo_id)
    {
      $query = Doctrine_Query::create()
          ->select('DISTINCT(billing_document) as billing')
          ->from('Cliente c')
          ->where("c.archivo_id = '$archivo_id'")
          ->groupBy('c.billing_document');
          
      //echo $query->getSqlQuery(); exit;
      //echo "<pre>"; print_r($query->fetchArray()); exit;
      return $query->fetchArray();
    }
}