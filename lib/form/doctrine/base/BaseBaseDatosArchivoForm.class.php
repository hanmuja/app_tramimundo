<?php

/**
 * BaseDatosArchivo form base class.
 *
 * @method BaseDatosArchivo getObject() Returns the current form's model object
 *
 * @package    tramimundo
 * @subpackage form
 * @author     Your name here
 * @version    SVN: $Id: sfDoctrineFormGeneratedTemplate.php 29553 2010-05-20 14:33:00Z Kris.Wallsmith $
 */
abstract class BaseBaseDatosArchivoForm extends BaseFormDoctrine
{
  public function setup()
  {
    $this->setWidgets(array(
      'id'             => new sfWidgetFormInputHidden(),
      'nombre_archivo' => new sfWidgetFormInputText(),
      'fecha_subida'   => new sfWidgetFormDateTime(),
      'cantidad_datos' => new sfWidgetFormInputText(),
    ));

    $this->setValidators(array(
      'id'             => new sfValidatorChoice(array('choices' => array($this->getObject()->get('id')), 'empty_value' => $this->getObject()->get('id'), 'required' => false)),
      'nombre_archivo' => new sfValidatorString(array('max_length' => 255, 'required' => false)),
      'fecha_subida'   => new sfValidatorDateTime(array('required' => false)),
      'cantidad_datos' => new sfValidatorInteger(array('required' => false)),
    ));

    $this->widgetSchema->setNameFormat('base_datos_archivo[%s]');

    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

    $this->setupInheritance();

    parent::setup();
  }

  public function getModelName()
  {
    return 'BaseDatosArchivo';
  }

}
