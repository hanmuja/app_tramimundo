<?php

require_once dirname(__FILE__).'/../lib/cliente_archivoGeneratorConfiguration.class.php';
require_once dirname(__FILE__).'/../lib/cliente_archivoGeneratorHelper.class.php';

/**
 * cliente_archivo actions.
 *
 * @package    tramimundo
 * @subpackage cliente_archivo
 * @author     Your name here
 * @version    SVN: $Id: actions.class.php 23810 2009-11-12 11:07:44Z Kris.Wallsmith $
 */
class cliente_archivoActions extends autoCliente_archivoActions
{
  public function executeCargarDesdeTxt($request) {
    $this->form = new ArchivoForm();
    
    if($request->isMethod('post'))
    {
      $files = $request->getFiles("archivo_archivo");
      $archivo_nombre = $request->getParameter("archivo_nombre");

      $cliente_archivo = new ClienteArchivo();
      $cliente_archivo->setNombreArchivo($archivo_nombre);
      $cliente_archivo->setFechaSubida(date('Y-m-d H:i:s'));
      $cliente_archivo->save();

      //echo "<pre>";
      $total_ies = 0;
      foreach($files as $file) {
        if($file['type'] == "text/plain") {
          $total_ies+= $this->doAll($file, $cliente_archivo);
        }
      }
      //var_dump($total_ies);
      //var_dump("termina");exit;

      $cliente_archivo->setCantidadDatos($total_ies);
      $cliente_archivo->save();
      $this->getUser()->setFlash('notice', "El Archivo '$archivo_nombre' fue cargado con exito.", false);
      $this->redirect('@cliente_archivo');
    }
  }

  private function doAll($file, $cliente_archivo) {
    $archivo_tmp = $file['tmp_name'];
    
    /******csv****/
    $file_handle = fopen($archivo_tmp, "r");
    $i = 0;
    $columns = array();
    $billing_document = "";
    $search_space = $get_info_line_1 = $get_info_line_2 = $search_after_space_1 = $search_after_space_2 = $jump_row = false;
    $all_data = array();
    while (!feof($file_handle))
    {
      $line = fgets($file_handle);
      //var_dump("hace fgets1");
      //var_dump($line);

      if(empty($billing_document) && strstr($line, "Invoice")) {
        //var_dump("entra if1");
        $matches = filter_var($line, FILTER_SANITIZE_NUMBER_INT);
        $billing_document = $matches;
      }

      if(strstr($line, "TOTAL")) {
        //var_dump("entra if2");
        break;
      }

      if($search_after_space_2) {
        //var_dump("entra if3");
        if(strstr($line, "Country")) {
          //var_dump("entra if4");
          $search_after_space_2 = false;
        }
      }

      if($search_after_space_1) {
        //var_dump("entra if5");
        if(strstr($line, "Mat.-No.")) {
          //var_dump("entra if6");
          $search_after_space_1 = false;
          $line = fgets($file_handle);
          //var_dump("hace fgets2");
          //var_dump($line);
          $line = fgets($file_handle);
          //var_dump("hace fgets3");
          //var_dump($line);
          $line = fgets($file_handle);
          //var_dump("hace fgets3.1");
          //var_dump($line);
          if($jump_row) {
            $jump_row = false;
            $line = fgets($file_handle);
            //var_dump("hace fgets3.2");
            //var_dump($line);
          }
        }
      }

      if($get_info_line_2 && !$search_after_space_2) {
        //var_dump("entra if7");
        if(strlen($line) == 2) {
          //var_dump("entra if8");
          $search_after_space_2 = true;
        } else {
          //var_dump("entra if9");
          $all_data[$i]['2'] = $line;
          $get_info_line_2 = false;
          $get_info_line_1 = true;
          $line = fgets($file_handle);
          //var_dump("hace fgetsaeiou");
          //var_dump($line);
          $i++;
        }
      }

      if($get_info_line_1 && !$search_after_space_1) {
        //var_dump("entra if10");
        if(strlen($line) == 2) {
          //var_dump("entra if11");
          $search_after_space_1 = true;
        } else {
          //var_dump("entra if12");
          if(!strstr($line, "The following article belongs to set")) {
            //var_dump("entra if13");
            $all_data[$i]['1'] = $line;
            $get_info_line_1 = false;
            $get_info_line_2 = true;
          } else {
            //var_dump("entra if14");
            $line = fgets($file_handle);
            //var_dump("hace fgets4");
            //var_dump($line);
            if(strlen($line) == 2) {
              //var_dump("entra if20");
              $search_after_space_1 = true;
              $jump_row = true;
            }
          }
        }
      }

      if($search_space && strlen($line) == 2) {
        //var_dump("entra if15");
        $search_space = false;
        $get_info_line_1 = true;
      }

      if(!empty($billing_document)) {
        //var_dump("entra if16");
        if(strstr($line, "Delivery note")) {
          //var_dump("entra if17");
          $search_space = true;
        }
      }
    }
    fclose($file_handle);

    //var_dump($all_data);

    $processed_data = $this->processDataArray($all_data);

    //var_dump($processed_data);
    
    $this->guardarLineaTxt($processed_data, $cliente_archivo, $billing_document);

    return $i;
  }

  public function processDataArray($data) {
    $processed_data = array();
    foreach($data as $d) {
      $data_separated = array();
      $extracted = explode(" ", $d[1]);
      $campo = 1;
      $is_space = 0;
      $descripcion = "";
      foreach($extracted as $part) {
        switch ($campo) {
          case '1':
            $data_separated['no_parte'] = $part;
            $campo++;
            break;
          case '2':
            if($is_space < 5) {
              if($part) {
                $descripcion.= $part." ";
              } else {
                $is_space++;
              }
            } else {
              $data_separated['descripcion'] = trim($descripcion);
              $campo++;
            }
            break;
          case '3':
            if($part) {
              $data_separated['cantidad'] = intval(str_replace(",", "", $part));
              $campo++;
            }
            break;
          case '4':
            if($part) {
              $data_separated['uc'] = $part;
              $campo++;
            }
            break;
          case '5':
            if($part) {
              $campo++;
            }
            break;
          case '6':
            if($part) {
              $campo++;
            }
            break;
          case '7':
            if($part) {
              $data_separated['valor'] = floatval(str_replace(",", "", $part));
              $campo++;
            }
            break;
        }
      }
      $extracted = explode(" ", $d[2]);
      $campo = 1;
      $is_space = 0;
      foreach($extracted as $part) {
        switch ($campo) {
          case '1':
            if(strstr($part, ":")) {
              $campo++;
            }
            break;
          case '2':
            if($part){
              $data_separated['origen'] = $part;
              $campo++;
            }
            break;
          case '3':
            if(strstr($part, ":")) {
              $campo++;
            }
            break;
          case '4':
            if($part) {
              $data_separated['fraccion'] = intval($part);
            }
            break;
        }
      }
      $processed_data[] = $data_separated;
    }

    return $processed_data;
  }

  public function executeSubirArchivo($request)
  {
    $this->form = new ArchivoForm();
    
    if($request->isMethod('post'))
    {
      $this->form->bind($request->getParameter($this->form->getName()), $request->getFiles($this->form->getName()));
      
      if($this->form->isValid())
      {
        $files = $request->getFiles($this->form->getName());
        $archivo_tmp = $files['archivo']['tmp_name'];
        $archivo_nombre = $files['archivo']['name'];
        
        $cliente_archivo = new ClienteArchivo();
        $cliente_archivo->setNombreArchivo($archivo_nombre);
        $cliente_archivo->setFechaSubida(date('Y-m-d H:i:s'));
        $cliente_archivo->save();
        
        /******csv****/
        $file_handle = fopen($archivo_tmp, "r");
        $i = 0;
        $columns = array();
        while (!feof($file_handle))
        {
          $line_of_text = fgetcsv($file_handle, 2048, ";");
          if($i > 0)
          {
            $this->guardarLinea($line_of_text, $cliente_archivo, $columns);
          }
          else
          {
            $columnas_buscadas = array(1 => 'Material', 2 => 'Material Description', 3 => 'HS Code CH', 4 => 'UoM', 5 => 'Packed quantity', 6 => 'Value', 7 => 'Country of origin', 8 => 'Billing Document', 9 => 'Net wght');
            foreach($line_of_text as $pos => $columna)
            {
              if($key = array_search(trim($columna), $columnas_buscadas))
              {
                $columns[$key] = $pos;
              }
            }
            if(count($columns) < 9)
            {
              $cliente_archivo->delete();
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
          $cliente_archivo->setCantidadDatos(($i-2));
          $cliente_archivo->save();
          $this->getUser()->setFlash('notice', "El Archivo '$archivo_nombre' fue cargado con exito.", false);
          $this->redirect('@cliente_archivo');
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
  
  public function executeVerClientes($request)
  {
    $this->redirect('@cliente');
  }

  public function guardarLineaTxt($lineas, $cliente_archivo, $billing_document)
  {
    foreach($lineas as $linea) {
      $cliente_obj = new Cliente();
      $cliente_obj->setClienteArchivo($cliente_archivo);
      $cliente_obj->setNoParte($linea['no_parte']);
      $cliente_obj->setDescripcion($linea['descripcion']);
      $cliente_obj->setFraccion($linea['fraccion']);
      $cliente_obj->setUc($linea['uc']);
      $cliente_obj->setCantidad($linea['cantidad']);
      $cliente_obj->setValor($linea['valor']);
      $cliente_obj->setOrigen($linea['origen']);
      $cliente_obj->setBillingDocument($billing_document);
      $cliente_obj->save();
    }
  }
  
  public function guardarLinea($linea, $cliente_archivo, $columns)
  {
    $cliente_obj = new Cliente();
    $cliente_obj->setClienteArchivo($cliente_archivo);
    $no_parte = str_replace('.', '', str_replace(' ', '', $linea[$columns['1']]));
    $cliente_obj->setNoParte($no_parte);
    $cliente_obj->setDescripcion($linea[$columns['2']]);
    $cliente_obj->setFraccion($linea[$columns['3']]);
    $cliente_obj->setUc($linea[$columns['4']]);
    $cantidad = str_replace('.', '', str_replace(' ', '', $linea[$columns['5']]));
    $cliente_obj->setCantidad($cantidad);
    $valor = str_replace(',', '', str_replace('$', '', str_replace(' ', '', $linea[$columns['6']])));
    $cliente_obj->setValor($valor);
    $cliente_obj->setOrigen($linea[$columns['7']]);
    $cliente_obj->setBillingDocument($linea[$columns['8']]);
	$peso = str_replace(',', '', str_replace('$', '', str_replace(' ', '', $linea[$columns['9']])));
	$cliente_obj->setPeso($peso);
    $cliente_obj->save();
  }
  
  public function executeGenerarReporteNoCoincidencias($request)
  {
    //select count(*) from cliente where archivo_id = '$id' AND no_parte not in (select no_parte from base_datos);
    $cliente_archivo = $this->getRoute()->getObject();
    $id = $cliente_archivo->getId();
    
    $query = "SELECT * FROM cliente WHERE archivo_id = '$id' AND no_parte NOT IN (SELECT no_parte FROM base_datos)";
    $con = Doctrine_Manager::getInstance()->connection();
    $st = $con->execute($query);
    $lineas = $st->fetchAll();
    
    // data
    $data = "no_parte;descripcion;fraccion;uc;cantidad;valor;origen\n";
    
    foreach($lineas as $linea)
    {
      $no_parte = $linea['no_parte'];
      $descripcion = $linea['descripcion'];
      $fraccion = $linea['fraccion'];
      $uc = $linea['uc'];
      $cantidad = $linea['cantidad'];
      $valor = $linea['valor'];
      $origen = $linea['origen'];
      
      $data.= "$no_parte;$descripcion;$fraccion;$uc;$cantidad;$valor;$origen\n";
    }

    // filename
    $filename = str_replace(' ', '_', $cliente_archivo->getFechaSubida()).'_no_coincidencias.csv';
        
    // init
    $this->setLayout(false);

    // header
    $this->getResponse()->clearHttpHeaders();
    $this->getResponse()->setHttpHeader('Content-Type', 'text/csv');
    $this->getResponse()->setHttpHeader('Content-Disposition', 'attachment; filename=' . $filename);
      
    // content
    $this->getResponse()->setContent($data);
      
    // disable template
    return sfView::NONE;
  }
  
  public function executeGenerarReporteCoincidencias($request)
  {
    $cliente_archivo = $this->getRoute()->getObject();
    $id = $cliente_archivo->getId();
    
    $billings = ClienteTable::getBillings($id);
    
    $billings_txt = "";
    
    foreach($billings as $billing)
    {
      $billings_txt.=$billing['billing'].";";
    }

    //$query = "SELECT DISTINCT(A.id), A.billing_document, A.no_parte, B.descripcion, B.fraccion, B.uc, A.cantidad, A.valor, B.origen FROM cliente AS A INNER JOIN base_datos AS B ON A.no_parte = B.no_parte AND A.archivo_id = '$id' ORDER BY B.fraccion, B.uc, B.origen, A.no_parte";

    $query = "SELECT * FROM (SELECT DISTINCT(A.id), A.billing_document, A.no_parte, B.descripcion, B.fraccion, B.uc, A.cantidad, A.valor, B.origen, A.peso FROM cliente AS A INNER JOIN base_datos AS B ON A.no_parte = B.no_parte AND A.archivo_id = '$id') as D GROUP BY id ORDER BY fraccion, uc, origen, descripcion, no_parte";

    $con = Doctrine_Manager::getInstance()->connection();
    $st = $con->execute($query);
    $lineas = $st->fetchAll();
    
    
    // data
    $data = "PARTIDA;no_parte;descripcion;fraccion;uc;cantidad;valor;origen;peso;factura;$billings_txt\n";
    $v_total = 0;
    $c_total = 0;
    $p_total = 0;
    $fraccion_ant = strtoupper(trim($lineas[0]['fraccion']));
    $uc_ant = strtoupper(trim($lineas[0]['uc']));
    $origen_ant = strtoupper(trim($lineas[0]['origen']));
    $descripcion_ant = strtoupper(trim($lineas[0]['descripcion']));
    $i = 1;
    $billings_subtotales = array();
    
    $billings_totales = array();
    $cantidad_totales = 0;
    $valor_totales = 0;
    $peso_totales = 0;
    
    foreach($lineas as $linea)
    {
      $no_parte = $linea['no_parte'];
      $descripcion = $linea['descripcion'];
      $fraccion = strtoupper(trim($linea['fraccion']));
      $uc = strtoupper(trim($linea['uc']));
      $cantidad = $linea['cantidad'];
      $valor = $linea['valor'];
      $origen = strtoupper(trim($linea['origen']));
       $peso = $linea['peso'];
      $descripcion = strtoupper(trim($linea['descripcion']));
      $j = 0;
      $billings_txt = '';
      
      if($fraccion_ant == $fraccion && $uc_ant == $uc && $origen_ant == $origen && $descripcion_ant == $descripcion)
      {
        $billing_document = $linea['billing_document'];
        foreach($billings as $billing)
        {
          if($billing['billing'] == $billing_document)
          {
            $billings_txt.= $linea['valor'];
            $billings_subtotales[$j]+= $linea['valor'];
          }
          else
            $billings_subtotales[$j]+= 0;
          $billings_txt.=";";
          $j++;
        }
        $data.= "$i;$no_parte;$descripcion;$fraccion;$uc;$cantidad;$valor;$origen;$peso;$billing_document;$billings_txt\n";
        $v_total+= $valor;
        $c_total+= $cantidad;
		 $p_total+= $peso;
      }
      else
      {
        $subtotales_billings_txt = '';
        $total_definitivo = 0;
        foreach($billings_subtotales as $key => $subtotales_b)
        {
          $billings_totales[$key]+=$subtotales_b;
          $subtotales_billings_txt.=$subtotales_b.";";
          $total_definitivo+= $subtotales_b;
        }
        
        $cantidad_totales+=$c_total;
        $valor_totales+=$v_total;
		$peso_totales+=$p_total;
        
        $data.= "TOTAL;;;;;$c_total;$v_total;;$p_total;;$subtotales_billings_txt"."$total_definitivo\n";
        
        $billings_subtotales = array();
        $billing_document = $linea['billing_document'];
        foreach($billings as $billing)
        {
          if($billing['billing'] == $billing_document)
          {
            $billings_txt.= $linea['valor'];
            $billings_subtotales[$j]+= $linea['valor'];
          }
          else
            $billings_subtotales[$j]+= 0;
          $billings_txt.=";";
          $j++;
        }
        
        $fraccion_ant = $fraccion;
        $uc_ant = $uc;
        $origen_ant = $origen;
        $descripcion_ant = $descripcion;
        $c_total = $cantidad;
        $v_total = $valor;
		 $p_total = $peso;
        $i++;
        $data.= "$i;$no_parte;$descripcion;$fraccion;$uc;$cantidad;$valor;$origen;$peso;$billing_document;$billings_txt\n";
      }
    }
    $subtotales_billings_txt = '';
    $total_definitivo = 0;
    $totales_billings_txt = '';
    $totales_definitivos = 0;
    foreach($billings_subtotales as $key => $subtotales_b)
    {
      $billings_totales[$key]+=$subtotales_b;
      $subtotales_billings_txt.=$subtotales_b.";";
      $total_definitivo+= $subtotales_b;
    }
    
    foreach($billings_totales as $totales_b)
    {
      $totales_billings_txt.=$totales_b.";";
      $totales_definitivos+=$totales_b;
    }
    
    $cantidad_totales+=$c_total;
    $valor_totales+=$v_total;
	$peso_totales+=$p_total;
    
    $data.= "TOTAL;;;;;$c_total;$v_total;;$p_total;;$subtotales_billings_txt"."$total_definitivo\n";
    
    $data.= "TOTALES;;;;;$cantidad_totales;$valor_totales;;$peso_totales;;$totales_billings_txt"."$totales_definitivos\n";

    // filename
    $filename = str_replace(' ', '_', $cliente_archivo->getFechaSubida()).'_coincidencias.csv';
        
    // init
    $this->setLayout(false);

    // header
    $this->getResponse()->clearHttpHeaders();
    $this->getResponse()->setHttpHeader('Content-Type', 'text/csv');
    $this->getResponse()->setHttpHeader('Content-Disposition', 'attachment; filename=' . $filename);
      
    // content
    $this->getResponse()->setContent($data);
      
    // disable template
    return sfView::NONE;
  }

   public function executeGenerarReporteSecundario($request)
  {
    $cliente_archivo = $this->getRoute()->getObject();
    $id = $cliente_archivo->getId();

    $query = "SELECT * FROM (SELECT DISTINCT(A.id), A.billing_document, A.no_parte, B.descripcion, B.uc, A.cantidad, A.valor, B.fraccion, B.arancel FROM cliente AS A INNER JOIN base_datos AS B ON A.no_parte = B.no_parte AND A.archivo_id = '$id') as D GROUP BY id ORDER BY billing_document, no_parte";

    $con = Doctrine_Manager::getInstance()->connection();
    $st = $con->execute($query);
    $lineas = $st->fetchAll();
    
    
    // data
    $data = "FACTURA;no_parte;descripcion;cantidad;valor;uc;fraccion;arancel\n";
    $v_total = 0;
    $c_total = 0;
    $factura_ant = $lineas[0]['billing_document'];
    $no_parte_ant = $lineas[0]['no_parte'];
    $descripcion_ant = $lineas[0]['descripcion'];
    $uc_ant = strtoupper(trim($lineas[0]['uc']));
    $fraccion_ant = $lineas[0]['fraccion'];
    $arancel_ant = $lineas[0]['arancel'];
    $cantidad_totales = 0;
    $valor_totales = 0;
    
    foreach($lineas as $linea)
    {
    	$factura = $linea['billing_document'];
      $no_parte = $linea['no_parte'];
      $uc = strtoupper(trim($linea['uc']));
      $fraccion = $linea['fraccion'];
      $arancel = $linea['arancel'];
      $cantidad = $linea['cantidad'];
      $valor = $linea['valor'];
      $descripcion = strtoupper(trim($linea['descripcion']));
      
      if($factura_ant == $factura)
      {
      	if($no_parte_ant == $no_parte)
      	{
      		$v_total+= $valor;
           $c_total+= $cantidad;
      	}
		else {
			$data.= "$factura;$no_parte_ant;$descripcion_ant;$c_total;$v_total;$uc_ant;$fraccion_ant;$arancel_ant\n";
			$cantidad_totales+=$c_total;
			$valor_totales+=$v_total;
			$c_total=$cantidad;
			$v_total=$valor;
			$no_parte_ant = $no_parte;
			$descripcion_ant = $descripcion;
			$uc_ant = $uc;
      $fraccion_ant = $fraccion;
      $arancel_ant = $arancel;
		}
      }
      else
      {
      	$data.= "$factura_ant;$no_parte_ant;$descripcion_ant;$c_total;$v_total;$uc_ant;$fraccion_ant;$arancel_ant\n";
		$cantidad_totales+=$c_total;
		$valor_totales+=$v_total;
       $data.= "TOTAL;;;$cantidad_totales;$valor_totales\n\n";
       
	   $factura_ant = $factura;
	   $no_parte_ant = $no_parte;
	   $c_total = $cantidad;
	   $v_total = $valor;
	   $descripcion_ant = $descripcion;
	   $uc_ant = $uc;
     $fraccion_ant = $fraccion;
     $arancel_ant = $arancel;
	   
	   $cantidad_totales = 0;
	   $valor_totales = 0;
      }
    }
    
    $data.= "$factura_ant;$no_parte_ant;$descripcion_ant;$c_total;$v_total;$uc_ant;$fraccion_ant;$arancel_ant\n";
	$cantidad_totales+=$c_total;
	$valor_totales+=$v_total;
    $data.= "TOTAL;;;$cantidad_totales;$valor_totales\n\n";

    // filename
    $filename = str_replace(' ', '_', $cliente_archivo->getFechaSubida()).'_reporte_tres.csv';
        
    // init
    $this->setLayout(false);

    // header
    $this->getResponse()->clearHttpHeaders();
    $this->getResponse()->setHttpHeader('Content-Type', 'text/csv');
    $this->getResponse()->setHttpHeader('Content-Disposition', 'attachment; filename=' . $filename);
      
    // content
    $this->getResponse()->setContent($data);
      
    // disable template
    return sfView::NONE;
  }

}
