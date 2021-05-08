<?php

require_once dirname(__FILE__).'/../lib/base_datos_archivoGeneratorConfiguration.class.php';
require_once dirname(__FILE__).'/../lib/base_datos_archivoGeneratorHelper.class.php';

/**
 * base_datos_archivo actions.
 *
 * @package    tramimundo
 * @subpackage base_datos_archivo
 * @author     Your name here
 * @version    SVN: $Id: actions.class.php 23810 2009-11-12 11:07:44Z Kris.Wallsmith $
 */
class base_datos_archivoActions extends autoBase_datos_archivoActions
{
  public function executeSubirArchivo($request)
  {
    $this->form = new ArchivoForm();
    
    if($request->isMethod('post'))
    {
      $this->form->bind($request->getParameter($this->form->getName()), $request->getFiles($this->form->getName()));
      
      if($this->form->isValid())
      {
        $files = $request->getFiles($this->form->getName());
        //echo "<pre>"; print_r($files);exit;
        $archivo_tmp = $files['archivo']['tmp_name'];
        $archivo_nombre = $files['archivo']['name'];
        
        $base_datos_archivo = new BaseDatosArchivo();
        $base_datos_archivo->setNombreArchivo($archivo_nombre);
        $base_datos_archivo->setFechaSubida(date('Y-m-d H:i:s'));
        $base_datos_archivo->save();
        
        /******csv****/
        $file_handle = fopen($archivo_tmp, "r");
        $i = 0;
        $columns = array();
        $error = false;
        while (!feof($file_handle))
        {
          $line_of_text = fgetcsv($file_handle, 1024, ";");
          if($i > 0)
          {
            $this->guardarLinea($line_of_text, $base_datos_archivo, $columns);
          }
          else
          {
            $columnas_buscadas = array(1 => 'NO DE PARTE', 2 => 'DESCRIPCION', 3 => 'FRACCION', 4 => 'ARANCEL', 5 => 'U.C.', 6 => 'CANTIDAD', 7 => 'VALOR', 8 => 'ORIGEN');
            foreach($line_of_text as $pos => $columna)
            {
              if($key = array_search(trim($columna), $columnas_buscadas))
              {
                $columns[$key] = $pos;
              }
            }
            if(count($columns) < 7)
            {
              $base_datos_archivo->delete();
              $error = true;
              break;
            }
          }
          $i++;
        }
        fclose($file_handle);
        /**********************/
        if(!$error)
        {
          $base_datos_archivo->setCantidadDatos(($i-2));
          $base_datos_archivo->save();
          $this->getUser()->setFlash('notice', "El Archivo '$archivo_nombre' fue cargado con exito.", false);
          $this->redirect('@base_datos_archivo');
        }
        else
        {
          $this->getUser()->setFlash('notice', "El Archivo '$archivo_nombre' tiene los titulos de las columnas malos.", false);
        }
      }
      else
      {
        $this->getUser()->setFlash('error', 'El Archivo no se pudo subir por algun error', false);
      }
    }
  }
  
  public function executeVerBasedatos($request)
  {
    $this->redirect('@base_datos');
  }
  
  public function guardarLinea($linea, $base_datos_archivo, $columns)
  {
    //echo "<pre>"; print_r($linea);
    $base_datos_obj = new BaseDatos();
    $base_datos_obj->setBaseDatosArchivo($base_datos_archivo);
    $no_parte = str_replace('.', '', str_replace(' ', '', $linea[$columns['1']]));
    $base_datos_obj->setNoParte($no_parte);
    $base_datos_obj->setDescripcion($linea[$columns['2']]);
    $base_datos_obj->setFraccion($linea[$columns['3']]);
    $base_datos_obj->setArancel($linea[$columns['4']]);
    $base_datos_obj->setUc($linea[$columns['5']]);
    $cantidad = str_replace('.', '', str_replace(' ', '', $linea[$columns['6']]));
    $base_datos_obj->setCantidad($cantidad);
    $valor = str_replace(',', '', str_replace('$', '', str_replace(' ', '', $linea[$columns['7']])));
    $base_datos_obj->setValor($valor);
    $base_datos_obj->setOrigen($linea[$columns['8']]);
    if($base_datos_obj->getNoParte() != '' && $base_datos_obj->getNoParte() != null)
      $base_datos_obj->save();
  }
  
  public function executeDelete(sfWebRequest $request)
  {
    $request->checkCSRFProtection();

    $this->dispatcher->notify(new sfEvent($this, 'admin.delete_object', array('object' => $this->getRoute()->getObject())));
    
    $id = $this->getRoute()->getObject()->getId();
    BaseDatosTable::deleteArchivoId($id);

    if ($this->getRoute()->getObject()->delete())
    {
      $this->getUser()->setFlash('notice', 'The item was deleted successfully.');
    }

    $this->redirect('@base_datos_archivo');
  }
}
