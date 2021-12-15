<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Support\Facades\DB; //agregada
use Illuminate\Http\Request; //agregado
use DataTables;

//load phpspreadsheet class using namespaces
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Worksheet\Drawing;

class InventarioController extends Controller{
    public function index(Request $request){


        if($request->ajax()){
            /**SELECT inventarioutl.id, catalogo.descripcion, inventarioutl.qtyo AS 'Cantidad original', inventarioutl.qtyf AS 'Cantidad fisica', inventarioutl.qtyc AS 'Cantidad comprometida' 
             * FROM inventarioutl INNER JOIN catalogo ON inventarioutl.herramienta = catalogo.id;  */

            $query = 'SELECT inventarioutl.id, catalogo.codigo, catalogo.numserie, catalogo.descripcion, inventarioutl.qtyo, inventarioutl.qtyf, inventarioutl.qtyc'
                . ' FROM inventarioutl' 
                . ' INNER JOIN catalogo'
                . ' ON inventarioutl.herramienta = catalogo.id'
                . ' WHERE catalogo.activo = 1';

            $inventarios = DB::select($query);

            //datatable no carga al enviar codigo y numserie juntos, solucion -> enviar un arreglo propio
            $data = array();
            foreach($inventarios as $inventario){
                $codigo = '';
                if($inventario->numserie == null){
                   $codigo = $inventario->codigo;
                }else{
                    $codigo = $inventario->numserie;
                }

                if(!empty($codigo)){
                    $data[] = array(
                        'codigo' => $codigo,
                        'descripcion' => $inventario->descripcion,
                        'Cantidad original' => $inventario->qtyo,
                        'Cantidad fisica' => $inventario->qtyf,
                        'Cantidad comprometida' => $inventario->qtyc
                    );
                }else{
                    return "Hubo un problema al obtener el codigo o el numero de serie";
                }
               
            }

            return DataTables::of($data)
                ->make(true);
        }

        return view('inventario.index');

    }
    

    public function fetchTools(){
        $query = 'SELECT catalogo.id, catalogo.descripcion, catalogo.codigo, catalogo.numserie, inventarioutl.qtyf FROM catalogo '  
            . 'INNER JOIN inventarioutl ON catalogo.id = inventarioutl.herramienta';
        
        $result = DB::select($query);

        if(!$result){
            die('Hubo un error '. mysqli_error($connect));
        }

        $json = array();

        if(count($result) > 0){
            foreach($result as $row){
                $json[] = array(
                    'id' => $row->id,
                    'descripcion' => $row->descripcion,
                    'codigo' => $row->codigo,
                    'numserie' => $row->numserie,
                    'qtyf' => $row->qtyf
                );    
            }
            $jsonstring =  json_encode($json);

            return $jsonstring;
        }else{
            die("No existen herramientas");
        } 

    }

    public function getTool($codigo){
        $codigo_serie = $codigo;
        $query = 'SELECT catalogo.id, catalogo.descripcion, inventarioutl.qtyf FROM catalogo ' 
            . 'INNER JOIN inventarioutl ON catalogo.id = inventarioutl.herramienta '
            . 'WHERE catalogo.numserie = "'.$codigo_serie.'" OR catalogo.codigo = "' . $codigo_serie . '"';
    
        // $resultado = mysqli_query($connect, $query);
        $resultado = DB::select($query);
    
        if(count($resultado) == 0){
            die('No se pudo obtener esa herramienta');
        }
    
        $json = array();
        
        foreach($resultado as $row){
            $json[] = array(
                'id' => $row->id,
                'descripcion' => $row->descripcion,
                'qtyf' => $row->qtyf
            );    
        }
    
        $jsonstring =  json_encode($json[0]);
        return $jsonstring;
    }


    public function hacerPrestamo(Request $request){
         $herramientas = $request->selected_list;
         $solicitante = $request->solicitante;
         $comentario = $request->comentario == "" ? 'Préstamo ordinario' : $request->comentario;
         $ticket = isset($request->ticket) ? $request->ticket : null; 
         

        //crear movimiento en kardex y obtener su id
         $id_mov = DB::table('kardex')->insertGetId(
            array(
                'movimiento' => 1,
                'descripcion' => $comentario,
                'solicitante' => $solicitante,
                'idticket' => $ticket,
                'estado' => 1,
            )
        );
    
        if(empty($id_mov)) abort(500);
        
        //actualizar cantidades en inventario
         foreach($herramientas as $herramienta){
            $id_kardexD = '';
            $codigo = $herramienta['codigo'];
            $query = 'SELECT id FROM catalogo WHERE numserie = "'. $codigo .'" OR codigo = "'. $codigo.'"';
            $result = DB::select($query);


             if(count($result) > 0){
                $id = $result[0]->id;
                $query = 'UPDATE inventarioutl SET qtyc = (qtyc + ' . $herramienta['cantidad'] . '), qtyf = (qtyo - qtyc) WHERE herramienta = '. $id;
                DB::select($query);
             }else{
                DB::table('kardex')
                ->where('id',$id_mov)
                ->delete();
                abort(500);
             }

             $id_kardex = DB::table('kardex_detalle')->insertGetId(
                    array(
                        'id_kardex' => $id_mov,
                        'id_herramienta' => $id,
                        'qty' => $herramienta['cantidad']
                    )
                );

            if(empty($id_kardex)) return "Fallo al agregar detalle de alguna herramienta";

         }
         
       return "Datos insertados satisfactoriamente";
    }


    public function insertFaltantes(Request $request){
        $herramientas = $request->faltantes;
        $motivo = ucfirst($request->motivo);
        $id_mov = $request->id;
        $estado = 1;


        foreach($herramientas as $herramienta){

            $codigo = $herramienta['codigo'];
            $cantidad = $herramienta['cantidad'];

            $result = DB::table('catalogo')
                        ->select('id')
                        ->where('codigo', '=', $codigo)
                        ->orWhere('numserie', '=', $codigo)
                        ->get();

             if(count($result) > 0){
                $id = $result[0]->id;

                $insert_faltante = DB::table('faltantes')->insertGetId([
                    'id_herramienta' => $id,
                    'motivo' => $motivo,
                    'cantidad' => $cantidad,
                    'id_mov' => $id_mov,
                    'estado' => 1
                ]);
                
                if(empty($insert_faltante)) abort(500);
                   
            }
        }

        return "Se guardó el registro de pérdida";
    }


    public function getTicket($id_ticket){
        
        if(isset($id_ticket) && $id_ticket != null){
            $query = 'SELECT detalle_peticion.herramienta, catalogo.codigo, catalogo.numserie, catalogo.descripcion, detalle_peticion.qty_peticion'
            . ' FROM detalle_peticion'
            . ' INNER JOIN catalogo ON detalle_peticion.herramienta = catalogo.id' 
            . ' WHERE detalle_peticion.peticion_id = (SELECT id FROM peticiones WHERE ticket_id = '. $id_ticket.')'; 
            $herramientas_ticket = DB::select($query);

            //$query = 'SELECT solicitante FROM peticiones WHERE ticket_id = '.$id_ticket;
            $query = 'SELECT osticket_db.ost_user.name'
            .' FROM osticket_db.ost_user INNER JOIN almacen_utld_prod.peticiones' 
            .' ON osticket_db.ost_user.id = almacen_utld_prod.peticiones.solicitante'
            .' WHERE osticket_db.ost_user.id = (SELECT solicitante FROM peticiones WHERE ticket_id = '.$id_ticket.')';
            $solicitante = DB::select($query);
        }
        
        //return $herramientas_ticket;

        return [$herramientas_ticket, $solicitante[0]->name];
        
        //$herramientas_ticket = array();

    //herramientas disponbles en local
        // $herramientas_ticket[] = array(
        //   'codigo'=>5067,
        //   'descripcion'=> 'martillo',
        //   'herramienta'=> 1,
        //   'numserie'=> null,
        //   'qty_peticion'=>3
        // );

        // $herramientas_ticket[] = array( 
        //     'codigo'=>345,
        //     'descripcion'=> 'Lijas',
        //     'herramienta'=> 4349,
        //     'numserie'=> null,
        //     'qty_peticion'=>5 
        // );


//herramientas disponibles en produccion
        // $herramientas_ticket[] = array(
        //     'codigo'=>878787,
        //     'descripcion'=> 'Pinzas de corte',
        //     'herramienta'=> 4355,
        //     'numserie'=> null,
        //     'qty_peticion'=>3
        //   );
  
        //   $herramientas_ticket[] = array( 
        //       'codigo'=>787878,
        //       'descripcion'=> 'Martillo',
        //       'herramienta'=> 4357,
        //       'numserie'=> null,
        //       'qty_peticion'=>5 
        //   );

            
    }

    public function getPrestamos(){
        $query = 'SELECT * FROM kardex WHERE movimiento = 1 AND estado = 1';
        $result = DB::select($query);
        
            
        $json = array();

        if(count($result) > 0){
            foreach($result as $row){
                $json[] = array(
                    'id' => $row->id,
                    'movimiento' => $row->movimiento,
                    'fecha' => $row->fecha,
                    'descripcion' => $row->descripcion,
                    'solicitante' => $row->solicitante,
                    'idticket' => $row->idticket,
                    'estado' => $row->estado
                );    
            }
            $jsonstring =  json_encode($json);

            return $jsonstring;
        }else{
            return "No hay ningun prestamo pendiente";
        } 
    }   

    public function getPrestamoDetalle($id ){

        if(isset($id) && is_numeric($id)){
            $query = 'SELECT catalogo.descripcion, catalogo.codigo, catalogo.numserie, kardex_detalle.id_herramienta, kardex_detalle.qty, kardex.descripcion AS comentario, kardex.solicitante FROM kardex_detalle' 
            .' INNER JOIN catalogo ON kardex_detalle.id_herramienta = catalogo.id'
            . ' INNER JOIN kardex ON kardex_detalle.id_kardex = kardex.id'
            .' WHERE id_kardex='.$id;

            $result = DB::select($query);

            if(!$result){
                abort(500);
            }

        }
        
        $json = array();

        if(count($result) > 0){
            foreach($result as $row){
                $codigo = "";
                if($row->codigo == null){
                    $codigo = $row->numserie;
                }else{
                    $codigo = $row->codigo;
                }

                $json[] = array(
                    'descripcion' => $row->descripcion,
                    'id_herramienta' => $codigo,
                    'qty' => $row->qty,
                    'comentario' => $row->comentario,
                    'solicitante' => $row->solicitante
                );    
            }
            $jsonstring =  json_encode($json);

            return $jsonstring;
        }else{
            die("No existen herramientas");
        } 


    }

    public function getMovimiento($ticket){
        if(isset($ticket) && is_numeric($ticket)){
            $query = 'SELECT id FROM kardex WHERE idticket='.$ticket.' AND estado = 1';
            $result = DB::select($query);

            if(count($result) > 0){
                return $result[0]->id;
            }else{
                return "Este pedido ya fue regresado";
            }
            
        }
    }

    public function regresarPrestamo(Request $request){

        $herramientas = $request->entregadas_lista;
        $solicitante = $request->solicitante;
        $id_kardex_prestamo = $request->id; //se necesita el id del prestamo para cambiar el estado a 0
        
       
       //crear movimiento en kardex y obtener su id
        $id_mov = DB::table('kardex')->insertGetId(
           array(
               'movimiento' => 2,
               'solicitante' => $solicitante,
               'descripcion' => 'Regreso ordinario'
           )
       );
   
      
    if(empty($id_mov)) abort(500);
       
       //actualizar cantidades en inventario
        foreach($herramientas as $herramienta){
           $id_kardexD = '';
           $codigo = $herramienta['codigo'];

           $result = DB::table('catalogo')
           ->select('id')
           ->where('codigo', '=', $codigo)
           ->orWhere('numserie', '=', $codigo)
           ->get();

            if(count($result) > 0){
               $id = $result[0]->id;
               $query = 'UPDATE inventarioutl SET qtyc = (qtyc - ' . $herramienta['cantidad'] . '), qtyf = (qtyo - qtyc) WHERE herramienta = '. $id;
               DB::select($query);
        
               $query = 'UPDATE kardex SET estado = 0 WHERE id ='.$id_kardex_prestamo;
                DB::select($query);
            }else{
                //si no encuentra al menos una herramienta, aborta y elimina el moviemitno ya creado
               DB::table('kardex')
               ->where('id',$id_mov)
               ->delete();
               abort(500);
            }

            $id_kardex = DB::table('kardex_detalle')->insertGetId(
                   array(
                       'id_kardex' => $id_mov,
                       'id_herramienta' => $id,
                       'qty' => $herramienta['cantidad']
                   )
               );

           if(empty($id_kardex)) return "Fallo al agregar detalle de alguna herramienta";

        }
        
      return "El regreso de herramienta se realizó satisfactoriamente";


    }

    /* Metodos de ajustes*/
    public function fetchFaltantes(){
        $query = 'SELECT faltantes.id, catalogo.codigo, catalogo.numserie, catalogo.descripcion, faltantes.motivo, faltantes.cantidad, kardex.fecha, kardex.solicitante' 
        . ' FROM faltantes INNER JOIN catalogo ON faltantes.id_herramienta = catalogo.id'
        . ' INNER JOIN kardex ON faltantes.id_mov = kardex.id'
        . ' WHERE faltantes.estado = 1';
        
        $result = DB::select($query);
        
        $json = array();
        
        if(count($result) > 0){

            foreach($result as $row){
                $codigo = '';
                if($row->numserie == null){
                   $codigo = $row->codigo;
                }else{
                    $codigo = $row->numserie;
                }

                if(!empty($codigo)){
                    $json[] = array(
                        'id' => $row->id,
                        'codigo' => $codigo,
                        'descripcion' => $row->descripcion,
                        'cantidad' => $row->cantidad,
                        'fecha' => $row->fecha,
                        'solicitante' => $row->solicitante,
                        'motivo' => $row->motivo,
                    ); 
                }else{
                    return "Hubo un problema al obtener el codigo o el numero de serie";
                }
                
            }
            $jsonstring =  json_encode($json);

            return $jsonstring;
        }else{
            return "No hay ninguna herramienta pendiente";
        } 

    }

    public function confirmarPendiente(Request $request){
        if(isset($request) && !empty($request)){
            $id = $request->id;
            $accion = $request->accion;
            $tipo_mov = '';
            $estado = '';
            $descripcion_mov = '';

            if($accion == "recuperar"){
                $estado = 3;
                $tipo_mov = 2;
                $descripcion_mov = "Regreso tardío";
            }else if($accion == "eliminar"){
                $estado = 2;
                $tipo_mov = 5;
                $descripcion_mov = "Robo o extravío";
            }
            
    
            if(!empty($id) && !empty($estado)){
               $resultUpdate = DB::table('faltantes')
                ->where('id', $id)
                ->update(['estado' => $estado]);
            }

            if($resultUpdate == true){
    
                $result = DB::table('faltantes')
                ->select('id_herramienta','id_mov','cantidad')
                ->where('id', $id)
                ->get();
    
                $id_herramienta = $result[0]->id_herramienta;
                $id_mov = $result[0]->id_mov;
                $cantidad = $result[0]->cantidad;
    
    
                if(empty($id_herramienta) || empty($id_mov) || empty($cantidad)) abort(500);
    
                $result = DB::table('kardex')
                ->select('solicitante')
                ->where('id', $id_mov)
                ->get();
                
                $solicitante = $result[0]->solicitante;
                if(empty($solicitante)) abort(500);
    
                $mov_nuevo = DB::table('kardex')->insertGetId(
                    array(
                        'movimiento' => $tipo_mov,
                        'solicitante' => $solicitante,
                        'descripcion' => $descripcion_mov,
                        'estado' => NULL
                    )
                );
    
                if(empty($mov_nuevo)) abort(500);
    
                $id_kardex = DB::table('kardex_detalle')->insertGetId(
                    array(
                        'id_kardex' => $mov_nuevo,
                        'id_herramienta' => $id_herramienta,
                        'qty' => $cantidad
                    )
                );
    
                 if(!empty($id_kardex)){
                     //si se recupera o se pierda, deja de estar prestado
                    $query_id_kardex = 'UPDATE inventarioutl SET qtyc = (qtyc - ' . $cantidad . '), qtyf = (qtyo - qtyc) WHERE herramienta = '. $id_herramienta;
                    DB::select($query_id_kardex);

                    if($accion == "recuperar"){
                        return "Se regresó la herramienta satisfactoriamente";
                    }else if($accion == "eliminar"){
                        //si se pierde, disminuye la cantidad fisica
                        $query = 'UPDATE inventarioutl SET qtyo = (qtyo - '. $cantidad .'), qtyf = (qtyo - qtyc) WHERE herramienta = '. $id_herramienta;
                        DB::select($query);
                        return "Se eliminó la herramienta para siempre";
                    }
                    
                 }else{
                    abort(500);
                 } 
    
            }else{
                abort(500);
            }
        }else{
            abort(500);
        }
       

    } 


    /*Fin de metodos de ajustes */


    public function indexTicket(Request $request){

        return view('tickets.ticketView');

    }

    public function indexRegreso(Request $request){

        return view('tickets.regresoView');

    }

    public function exportInventario(){

        //stayling arrays
        //table head style encabezados
        $tableHead = [
            'font'=>[
                'color'=>[
                    'rgb'=>'FFFFFF'
                ],
                'bold'=>true,
                'size'=>12
            ],

            'fill'=>[
                'fillType'=>Fill::FILL_SOLID,
                'startColor'=>[
                    'rgb'=>'217117'
                ],
            ],
        ];
        //end of arrays

        //table head style titulo
        $tableTitle = [
            'font'=>[
                'color'=>[
                    'rgb'=>'FFFFFF'
                ],
                'bold'=>true,
                'size'=>20
            ],

            'fill'=>[
                'fillType'=>Fill::FILL_SOLID,
                'startColor'=>[
                    'rgb'=>'891C1C'
                ],
            ],
        ];

        $styleArray = [
            'borders' => [
                'allBorders' => [
                    'borderStyle' =>Border::BORDER_THIN
                ]
            ]
        ];
        //end of arrays

        //$sql = 'SELECT herramienta, qtyo, qtyf, qtyc FROM inventarioutl';
        $sql = 'SELECT inventarioutl.id, catalogo.codigo, catalogo.numserie, catalogo.descripcion, inventarioutl.qtyo, inventarioutl.qtyf, inventarioutl.qtyc'
                . ' FROM inventarioutl' 
                . ' INNER JOIN catalogo'
                . ' ON inventarioutl.herramienta = catalogo.id';
        $result = DB::select($sql);

        $celdaFinal = count($result)+3;



        $excel = new Spreadsheet();
        $hojaActiva = $excel->getActiveSheet();
        $hojaActiva->setTitle("Inventario");

        //PONER IMAGEN------------------------------------------------------------------------------------------------------------------
        $hojaActiva->getRowDimension('1')->setRowHeight(47);
        $drawing = new Drawing();
        $drawing->setName('UTLD logo');
        $drawing->setPath('.\images\utld-logo.png');
        $drawing->setCoordinates('A1');
        $drawing->setOffsetX(100);
        $drawing->setOffsetY(10);
        $drawing->setHeight(45);
        $drawing->getShadow()->setVisible(true);
        $drawing->getShadow()->setDirection(45);
        $drawing->setWorksheet($excel->getActiveSheet());
        //$hojaActiva->getActiveSheet()->getHeaderFooter()->addImage($drawing, \PhpOffice\PhpSpreadsheet\Worksheet\HeaderFooter::IMAGE_HEADER_LEFT);
        //------------------------------------------------------------------------------------------------------------------------------------------------------------------
        //PONER FECHA
        $fecha = date("d-m-Y", time());
        $hojaActiva->setCellValue('B1', "ESTE REPORTE SE GENERÓ EL: ". $fecha);
        $hojaActiva->mergeCells('B1:D1');
        $hojaActiva->getStyle('B1:D1')->getFont()->setSize(15);

        //------------------------------------------------------------------------------------------------------------------------------------------------------------------

        //poner encabezados
        $hojaActiva->setCellValue('A2', 'Tabla del Inventario');
        $hojaActiva->mergeCells('A2:F2');
        //$hojaActiva->getStyle('A2')->getFont()->setSize(20);
        $hojaActiva->getStyle('A2')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
        $hojaActiva->getStyle('A2:F2')->applyFromArray($tableTitle);

        //esto es para ajustar el width de las columnas
        $hojaActiva->getColumnDimension('A')->setWidth(50);
        $hojaActiva->setCellValue('A3', "Herramienta");
        $hojaActiva->getStyle('A3')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);

        $hojaActiva->getColumnDimension('B')->setWidth(15);
        $hojaActiva->setCellValue('B3', "Código");

        $hojaActiva->getColumnDimension('C')->setWidth(20);
        $hojaActiva->setCellValue('C3', "Número Serie");

        $hojaActiva->getColumnDimension('D')->setWidth(25);
        $hojaActiva->setCellValue('D3', "Cantidad Total");

        $hojaActiva->getColumnDimension('E')->setWidth(25);
        $hojaActiva->setCellValue('E3', "Cantidad Disponible");

        $hojaActiva->getColumnDimension('F')->setWidth(25);
        $hojaActiva->setCellValue('F3', "Cantidad en Préstamo");
        

        //background color encabezados
        $hojaActiva->getStyle('A3:F3')->applyFromArray($tableHead);



        //traer la información

        //controlar las filas y que se vaya para abajo al terminar la columna
        //empieza en la fila 2 pq la 1 esta con los encabezados
        $fila = 4;


        foreach($result as $row){
            $hojaActiva->setCellValue('A'.$fila, $row->descripcion);
            $hojaActiva->setCellValue('B'.$fila, $row->codigo)->getStyle('B')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
            $hojaActiva->setCellValue('C'.$fila, $row->numserie)->getStyle('C')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
            $hojaActiva->setCellValue('D'.$fila, $row->qtyo)->getStyle('D')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
            $hojaActiva->setCellValue('E'.$fila, $row->qtyf)->getStyle('E')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
            $hojaActiva->setCellValue('F'.$fila, $row->qtyc)->getStyle('F')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);

            //Pase a la fila de abajo
            $fila++;
        }

        $hojaActiva ->getStyle('A2'.':F'.$celdaFinal)->applyFromArray($styleArray);

        // bajar el archivo del browser

        $fileName =  'Content-Disposition: attachment;filename="Inventario('.$fecha.').xlsx"';
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header($fileName);
        header('Cache-Control: max-age=0');

        //crear la hoja de excel
        $writer = IOFactory::createWriter($excel, 'Xlsx');
        $writer->save('php://output');

        exit;

    }

    public function exportFaltantes($accion){
        $estado = '';
        $titulo = '';
        
        if(isset($accion) && !empty($accion)){
            if($accion == 'perdidos'){
                $estado = 2;
                $titulo = 'Herramientas Perdidas';
            }else if($accion == 'recuperados'){
                $estado = 3;
                $titulo = 'Herramientas Recuperadas';
            }else{
                abort(500);
            }
        }   
   

        $tableHead = [
            'font'=>[
                'color'=>[
                    'rgb'=>'FFFFFF'
                ],
                'bold'=>true,
                'size'=>12
            ],

            'fill'=>[
                'fillType'=>Fill::FILL_SOLID,
                'startColor'=>[
                    'rgb'=>'217117'
                ],
            ],
        ];
        //end of arrays

        //table head style titulo
        $tableTitle = [
            'font'=>[
                'color'=>[
                    'rgb'=>'FFFFFF'
                ],
                'bold'=>true,
                'size'=>20
            ],

            'fill'=>[
                'fillType'=>Fill::FILL_SOLID,
                'startColor'=>[
                    'rgb'=>'891C1C'
                ],
            ],
        ];

        $styleArray = [
            'borders' => [
                'allBorders' => [
                    'borderStyle' =>Border::BORDER_THIN
                ]
            ]
        ];
        //end of arrays

        //$sql = 'SELECT herramienta, qtyo, qtyf, qtyc FROM inventarioutl';

    
            $sql = 'SELECT catalogo.codigo, catalogo.numserie, catalogo.descripcion, faltantes.id, faltantes.motivo, faltantes.cantidad, kardex.fecha, kardex.solicitante' 
            . ' FROM faltantes INNER JOIN catalogo ON faltantes.id_herramienta = catalogo.id'
            . ' INNER JOIN kardex ON faltantes.id_mov = kardex.id'
            . ' WHERE faltantes.estado = '.$estado;
            
            $result = DB::select($sql);

            $celdaFinal = count($result)+3;



        $excel = new Spreadsheet();
        $hojaActiva = $excel->getActiveSheet();
        $hojaActiva->setTitle("Faltantes");

         //PONER IMAGEN------------------------------------------------------------------------------------------------------------------
         $hojaActiva->getRowDimension('1')->setRowHeight(47);
         $drawing = new Drawing();
         $drawing->setName('UTLD logo');
         $drawing->setPath('.\images\utld-logo.png');
         $drawing->setCoordinates('A1');
         $drawing->setOffsetX(150);
         $drawing->setOffsetY(10);
         $drawing->setHeight(45);
         $drawing->getShadow()->setVisible(true);
         $drawing->getShadow()->setDirection(45);
         $drawing->setWorksheet($excel->getActiveSheet());



        //------------------------------------------------------------------------------------------------------------------------------------------------------------------
        //PONER FECHA
        $fecha = date("d-m-Y", time());
        $hojaActiva->setCellValue('B1', "ESTE REPORTE SE GENERÓ EL: ". $fecha);
        $hojaActiva->mergeCells('B1:D1');
        $hojaActiva->getStyle('B1:D1')->getFont()->setSize(15);

        //------------------------------------------------------------------------------------------------------------------------------------------------------------------

        //poner encabezados
        $hojaActiva->setCellValue('A2', $titulo);
        $hojaActiva->mergeCells('A2:G2');
        //$hojaActiva->getStyle('A2')->getFont()->setSize(20);
        $hojaActiva->getStyle('A2')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
        $hojaActiva->getStyle('A2:G2')->applyFromArray($tableTitle);

        //esto es para ajustar el width de las columnas
        $hojaActiva->getColumnDimension('A')->setWidth(50);
        $hojaActiva->setCellValue('A3', "Herramienta");
        $hojaActiva->getStyle('A3')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);

        $hojaActiva->getColumnDimension('B')->setWidth(15);
        $hojaActiva->setCellValue('B3', "Código");

        $hojaActiva->getColumnDimension('C')->setWidth(20);
        $hojaActiva->setCellValue('C3', "Número Serie");

        $hojaActiva->getColumnDimension('D')->setWidth(60);
        $hojaActiva->setCellValue('D3', "Motivo");

        $hojaActiva->getColumnDimension('E')->setWidth(20);
        $hojaActiva->setCellValue('E3', "Cantidad Faltante");

        $hojaActiva->getColumnDimension('F')->setWidth(25);
        $hojaActiva->setCellValue('F3', "Fecha");

        $hojaActiva->getColumnDimension('G')->setWidth(25);
        $hojaActiva->setCellValue('G3', "Solicitante");
        

        //background color encabezados
        $hojaActiva->getStyle('A3:G3')->applyFromArray($tableHead);



        //traer la información

        //controlar las filas y que se vaya para abajo al terminar la columna
        //empieza en la fila 2 pq la 1 esta con los encabezados
        $fila = 4;


        foreach($result as $row){
            $hojaActiva->setCellValue('A'.$fila, $row->descripcion);
            $hojaActiva->setCellValue('B'.$fila, $row->codigo)->getStyle('B')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
            $hojaActiva->setCellValue('C'.$fila, $row->numserie)->getStyle('C')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
            $hojaActiva->setCellValue('D'.$fila, $row->motivo)->getStyle('D')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
            $hojaActiva->setCellValue('E'.$fila, $row->cantidad)->getStyle('E')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
            $hojaActiva->setCellValue('F'.$fila, $row->fecha)->getStyle('F')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
            $hojaActiva->setCellValue('G'.$fila, $row->solicitante)->getStyle('G')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);

            //Pase a la fila de abajo
            $fila++;
        }

        $hojaActiva ->getStyle('A2'.':G'.$celdaFinal)->applyFromArray($styleArray);

        // bajar el archivo del browser

        $fileName =  'Content-Disposition: attachment;filename="'.$titulo.' ('.$fecha.').xlsx"';
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header($fileName);
        header('Cache-Control: max-age=0');

        //crear la hoja de excel
        $writer = IOFactory::createWriter($excel, 'Xlsx');
        $writer->save('php://output');

        exit;

    }

}