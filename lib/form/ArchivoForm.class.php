<?php

class ArchivoForm extends sfForm
{
  public function setup()
  {
    $this->setWidgets(array(
      'archivo' => new sfWidgetFormInputFile(),
    ));
    
    $this->setValidators(array(
      'archivo'    => new sfValidatorFile(array('mime_types' => array('application/vnd.ms-excel','text/plain','text/csv','text/tsv'), 'path' => 'uploads'), array('mime_types' => 'El archivo debe ser CSV')),
    ));

    $this->widgetSchema->setNameFormat('archivo[%s]');

    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

    parent::setup();
  }
}