<?php

/**
 * BaseBaseDatos
 * 
 * This class has been auto-generated by the Doctrine ORM Framework
 * 
 * @property integer $archivo_id
 * @property string $no_parte
 * @property string $descripcion
 * @property string $fraccion
 * @property string $arancel
 * @property string $uc
 * @property string $cantidad
 * @property double $valor
 * @property string $origen
 * @property BaseDatosArchivo $BaseDatosArchivo
 * 
 * @method integer          getArchivoId()        Returns the current record's "archivo_id" value
 * @method string           getNoParte()          Returns the current record's "no_parte" value
 * @method string           getDescripcion()      Returns the current record's "descripcion" value
 * @method string           getFraccion()         Returns the current record's "fraccion" value
 * @method string           getArancel()          Returns the current record's "arancel" value
 * @method string           getUc()               Returns the current record's "uc" value
 * @method string           getCantidad()         Returns the current record's "cantidad" value
 * @method double           getValor()            Returns the current record's "valor" value
 * @method string           getOrigen()           Returns the current record's "origen" value
 * @method BaseDatosArchivo getBaseDatosArchivo() Returns the current record's "BaseDatosArchivo" value
 * @method BaseDatos        setArchivoId()        Sets the current record's "archivo_id" value
 * @method BaseDatos        setNoParte()          Sets the current record's "no_parte" value
 * @method BaseDatos        setDescripcion()      Sets the current record's "descripcion" value
 * @method BaseDatos        setFraccion()         Sets the current record's "fraccion" value
 * @method BaseDatos        setArancel()          Sets the current record's "arancel" value
 * @method BaseDatos        setUc()               Sets the current record's "uc" value
 * @method BaseDatos        setCantidad()         Sets the current record's "cantidad" value
 * @method BaseDatos        setValor()            Sets the current record's "valor" value
 * @method BaseDatos        setOrigen()           Sets the current record's "origen" value
 * @method BaseDatos        setBaseDatosArchivo() Sets the current record's "BaseDatosArchivo" value
 * 
 * @package    tramimundo
 * @subpackage model
 * @author     Your name here
 * @version    SVN: $Id: Builder.php 7490 2010-03-29 19:53:27Z jwage $
 */
abstract class BaseBaseDatos extends sfDoctrineRecord
{
    public function setTableDefinition()
    {
        $this->setTableName('base_datos');
        $this->hasColumn('archivo_id', 'integer', null, array(
             'type' => 'integer',
             ));
        $this->hasColumn('no_parte', 'string', 255, array(
             'type' => 'string',
             'length' => 255,
             ));
        $this->hasColumn('descripcion', 'string', 255, array(
             'type' => 'string',
             'length' => 255,
             ));
        $this->hasColumn('fraccion', 'string', 255, array(
             'type' => 'string',
             'length' => 255,
             ));
        $this->hasColumn('arancel', 'string', 100, array(
             'type' => 'string',
             'length' => 100,
             ));
        $this->hasColumn('uc', 'string', 255, array(
             'type' => 'string',
             'length' => 255,
             ));
        $this->hasColumn('cantidad', 'string', 255, array(
             'type' => 'string',
             'length' => 255,
             ));
        $this->hasColumn('valor', 'double', null, array(
             'type' => 'double',
             ));
        $this->hasColumn('origen', 'string', 255, array(
             'type' => 'string',
             'length' => 255,
             ));
    }

    public function setUp()
    {
        parent::setUp();
        $this->hasOne('BaseDatosArchivo', array(
             'local' => 'archivo_id',
             'foreign' => 'id',
             'onDelete' => 'cascade'));
    }
}