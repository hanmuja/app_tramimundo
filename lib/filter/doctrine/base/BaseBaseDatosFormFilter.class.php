<?php

/**
 * BaseDatos filter form base class.
 *
 * @package    tramimundo
 * @subpackage filter
 * @author     Your name here
 * @version    SVN: $Id: sfDoctrineFormFilterGeneratedTemplate.php 29570 2010-05-21 14:49:47Z Kris.Wallsmith $
 */
abstract class BaseBaseDatosFormFilter extends BaseFormFilterDoctrine
{
  public function setup()
  {
    $this->setWidgets(array(
      'archivo_id'  => new sfWidgetFormDoctrineChoice(array('model' => $this->getRelatedModelName('BaseDatosArchivo'), 'add_empty' => true)),
      'no_parte'    => new sfWidgetFormFilterInput(),
      'descripcion' => new sfWidgetFormFilterInput(),
      'fraccion'    => new sfWidgetFormFilterInput(),
      'arancel'     => new sfWidgetFormFilterInput(),
      'uc'          => new sfWidgetFormFilterInput(),
      'cantidad'    => new sfWidgetFormFilterInput(),
      'valor'       => new sfWidgetFormFilterInput(),
      'origen'      => new sfWidgetFormFilterInput(),
    ));

    $this->setValidators(array(
      'archivo_id'  => new sfValidatorDoctrineChoice(array('required' => false, 'model' => $this->getRelatedModelName('BaseDatosArchivo'), 'column' => 'id')),
      'no_parte'    => new sfValidatorPass(array('required' => false)),
      'descripcion' => new sfValidatorPass(array('required' => false)),
      'fraccion'    => new sfValidatorPass(array('required' => false)),
      'arancel'     => new sfValidatorPass(array('required' => false)),
      'uc'          => new sfValidatorPass(array('required' => false)),
      'cantidad'    => new sfValidatorPass(array('required' => false)),
      'valor'       => new sfValidatorPass(array('required' => false)),
      'origen'      => new sfValidatorPass(array('required' => false)),
    ));

    $this->widgetSchema->setNameFormat('base_datos_filters[%s]');

    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

    $this->setupInheritance();

    parent::setup();
  }

  public function getModelName()
  {
    return 'BaseDatos';
  }

  public function getFields()
  {
    return array(
      'id'          => 'Number',
      'archivo_id'  => 'ForeignKey',
      'no_parte'    => 'Text',
      'descripcion' => 'Text',
      'fraccion'    => 'Text',
      'arancel'     => 'Text',
      'uc'          => 'Text',
      'cantidad'    => 'Text',
      'valor'       => 'Text',
      'origen'      => 'Text',
    );
  }
}
