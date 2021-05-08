<?php

/**
 * Cliente form base class.
 *
 * @method Cliente getObject() Returns the current form's model object
 *
 * @package    tramimundo
 * @subpackage form
 * @author     Your name here
 * @version    SVN: $Id: sfDoctrineFormGeneratedTemplate.php 29553 2010-05-20 14:33:00Z Kris.Wallsmith $
 */
abstract class BaseClienteForm extends BaseFormDoctrine
{
  public function setup()
  {
    $this->setWidgets(array(
      'id'               => new sfWidgetFormInputHidden(),
      'archivo_id'       => new sfWidgetFormDoctrineChoice(array('model' => $this->getRelatedModelName('ClienteArchivo'), 'add_empty' => true)),
      'billing_document' => new sfWidgetFormInputText(),
      'no_parte'         => new sfWidgetFormInputText(),
      'descripcion'      => new sfWidgetFormInputText(),
      'fraccion'         => new sfWidgetFormInputText(),
      'uc'               => new sfWidgetFormInputText(),
      'cantidad'         => new sfWidgetFormInputText(),
      'valor'            => new sfWidgetFormInputText(),
      'origen'           => new sfWidgetFormInputText(),
      'peso'             => new sfWidgetFormInputText(),
    ));

    $this->setValidators(array(
      'id'               => new sfValidatorChoice(array('choices' => array($this->getObject()->get('id')), 'empty_value' => $this->getObject()->get('id'), 'required' => false)),
      'archivo_id'       => new sfValidatorDoctrineChoice(array('model' => $this->getRelatedModelName('ClienteArchivo'), 'required' => false)),
      'billing_document' => new sfValidatorString(array('max_length' => 255, 'required' => false)),
      'no_parte'         => new sfValidatorString(array('max_length' => 255, 'required' => false)),
      'descripcion'      => new sfValidatorString(array('max_length' => 255, 'required' => false)),
      'fraccion'         => new sfValidatorString(array('max_length' => 255, 'required' => false)),
      'uc'               => new sfValidatorString(array('max_length' => 255, 'required' => false)),
      'cantidad'         => new sfValidatorString(array('max_length' => 255, 'required' => false)),
      'valor'            => new sfValidatorPass(array('required' => false)),
      'origen'           => new sfValidatorString(array('max_length' => 255, 'required' => false)),
      'peso'             => new sfValidatorPass(array('required' => false)),
    ));

    $this->widgetSchema->setNameFormat('cliente[%s]');

    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

    $this->setupInheritance();

    parent::setup();
  }

  public function getModelName()
  {
    return 'Cliente';
  }

}
