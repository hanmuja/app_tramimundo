<?php

/**
 * BaseDatosArchivo filter form base class.
 *
 * @package    tramimundo
 * @subpackage filter
 * @author     Your name here
 * @version    SVN: $Id: sfDoctrineFormFilterGeneratedTemplate.php 29570 2010-05-21 14:49:47Z Kris.Wallsmith $
 */
abstract class BaseBaseDatosArchivoFormFilter extends BaseFormFilterDoctrine
{
  public function setup()
  {
    $this->setWidgets(array(
      'nombre_archivo' => new sfWidgetFormFilterInput(),
      'fecha_subida'   => new sfWidgetFormFilterDate(array('from_date' => new sfWidgetFormDate(), 'to_date' => new sfWidgetFormDate())),
      'cantidad_datos' => new sfWidgetFormFilterInput(),
    ));

    $this->setValidators(array(
      'nombre_archivo' => new sfValidatorPass(array('required' => false)),
      'fecha_subida'   => new sfValidatorDateRange(array('required' => false, 'from_date' => new sfValidatorDateTime(array('required' => false, 'datetime_output' => 'Y-m-d 00:00:00')), 'to_date' => new sfValidatorDateTime(array('required' => false, 'datetime_output' => 'Y-m-d 23:59:59')))),
      'cantidad_datos' => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
    ));

    $this->widgetSchema->setNameFormat('base_datos_archivo_filters[%s]');

    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

    $this->setupInheritance();

    parent::setup();
  }

  public function getModelName()
  {
    return 'BaseDatosArchivo';
  }

  public function getFields()
  {
    return array(
      'id'             => 'Number',
      'nombre_archivo' => 'Text',
      'fecha_subida'   => 'Date',
      'cantidad_datos' => 'Number',
    );
  }
}
