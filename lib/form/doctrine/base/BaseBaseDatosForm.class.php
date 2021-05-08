<?php

/**
 * BaseDatos form base class.
 *
 * @method BaseDatos getObject() Returns the current form's model object
 *
 * @package    tramimundo
 * @subpackage form
 * @author     Your name here
 * @version    SVN: $Id: sfDoctrineFormGeneratedTemplate.php 29553 2010-05-20 14:33:00Z Kris.Wallsmith $
 */
abstract class BaseBaseDatosForm extends BaseFormDoctrine
{
  public function setup()
  {
    $this->setWidgets(array(
      'id'          => new sfWidgetFormInputHidden(),
      'archivo_id'  => new sfWidgetFormDoctrineChoice(array('model' => $this->getRelatedModelName('BaseDatosArchivo'), 'add_empty' => true)),
      'no_parte'    => new sfWidgetFormInputText(),
      'descripcion' => new sfWidgetFormInputText(),
      'fraccion'    => new sfWidgetFormInputText(),
      'arancel'     => new sfWidgetFormInputText(),
      'uc'          => new sfWidgetFormInputText(),
      'cantidad'    => new sfWidgetFormInputText(),
      'valor'       => new sfWidgetFormInputText(),
      'origen'      => new sfWidgetFormInputText(),
    ));

    $this->setValidators(array(
      'id'          => new sfValidatorChoice(array('choices' => array($this->getObject()->get('id')), 'empty_value' => $this->getObject()->get('id'), 'required' => false)),
      'archivo_id'  => new sfValidatorDoctrineChoice(array('model' => $this->getRelatedModelName('BaseDatosArchivo'), 'required' => false)),
      'no_parte'    => new sfValidatorString(array('max_length' => 255, 'required' => false)),
      'descripcion' => new sfValidatorString(array('max_length' => 255, 'required' => false)),
      'fraccion'    => new sfValidatorString(array('max_length' => 255, 'required' => false)),
      'arancel'     => new sfValidatorString(array('max_length' => 100, 'required' => false)),
      'uc'          => new sfValidatorString(array('max_length' => 255, 'required' => false)),
      'cantidad'    => new sfValidatorString(array('max_length' => 255, 'required' => false)),
      'valor'       => new sfValidatorPass(array('required' => false)),
      'origen'      => new sfValidatorString(array('max_length' => 255, 'required' => false)),
    ));

    $this->widgetSchema->setNameFormat('base_datos[%s]');

    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

    $this->setupInheritance();

    parent::setup();
  }

  public function getModelName()
  {
    return 'BaseDatos';
  }

}
