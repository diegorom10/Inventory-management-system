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
        //solo las herramientas que tengan su registro en inventario
        $query = 'SELECT catalogo.id, catalogo.descripcion, catalogo.codigo, catalogo.numserie, inventarioutl.qtyf FROM catalogo '  
            . 'INNER JOIN inventarioutl ON catalogo.id = inventarioutl.herramienta'
            . ' WHERE catalogo.activo = true'
            . ' ORDER BY catalogo.numserie';
        
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
        //funcion: recibir codigo o numero de serie y regresar toda la información de esa herramienta
        $codigo_serie = $codigo;
        $query = 'SELECT catalogo.id, catalogo.descripcion, inventarioutl.qtyf, inventarioutl.qtyo FROM catalogo ' 
            . 'INNER JOIN inventarioutl ON catalogo.id = inventarioutl.herramienta '
            . 'WHERE (catalogo.numserie = "'.$codigo_serie.'" OR catalogo.codigo = "' . $codigo_serie . '")'
            . 'AND catalogo.activo = true';
    
        $resultado = DB::select($query);
    
        if(count($resultado) == 0){
            die('No se pudo obtener esa herramienta');
        }
    
        $json = array();
        
        foreach($resultado as $row){
            $json[] = array(
                'id' => $row->id,
                'descripcion' => $row->descripcion,
                'qtyf' => $row->qtyf,
                'qtyo' => $row->qtyo
            );    
        }
    
        $jsonstring =  json_encode($json[0]);
        return $json[0];
    }

    public function addArticulos(Request $request){
        if(isset($request)){
            $herramienta_id = trim($request->herramienta);
            $cantidad = trim($request->cantidad);
            $personal = Auth()->user()->name;
        }
         

        if(!empty($herramienta_id) && !empty($cantidad) && !empty($personal)){
            //primero se hace el cambio y luego se registra el movimiento

            // 'UPDATE inventarioutl SET qtyo = (qtyo + ' . $cantidad . '), qtyf = (qtyo - qtyc) WHERE herramienta = '. $herramienta_id;
            $resultUpdate = DB::table('inventarioutl')
             ->where('herramienta', $herramienta_id)
             ->update(['qtyo' => DB::raw('qtyo +'.$cantidad),
                        'qtyf' => DB::raw('qtyo-qtyc')]);

            if(!$resultUpdate) abort(500);

            $id_kardex = DB::table('kardex')->insertGetId(
                array(
                    'movimiento' => 3,
                    'descripcion' => 'Entrada de material',
                    'personal' => $personal
                )
            );
        
            if(empty($id_kardex)){
                DB::table('inventarioutl')
                ->where('herramienta', $herramienta_id)
                ->update(['qtyo' => DB::raw('qtyo-'.$cantidad),
                           'qtyf' => DB::raw('qtyo-qtyc')]);
                abort(500);
            } 

            $id_kardex_detalle = DB::table('kardex_detalle')->insertGetId(
                array(
                    'id_kardex' => $id_kardex,
                    'id_herramienta' => $herramienta_id,
                    'qty' => $cantidad
                )
            );

            if(empty($id_kardex_detalle)){
                //si no se puede hacer el registro de kardex detalle, regresa y elimina todo
                DB::table('inventarioutl')
                ->where('herramienta', $herramienta_id)
                ->update(['qtyo' => DB::raw('qtyo-'.$cantidad),
                           'qtyf' => DB::raw('qtyo-qtyc')]);
                DB::table("kardex")->orderBy("id", "desc")->take(1)->delete();
                abort(500);    
            };

         }

         if($resultUpdate){
            return back();
        }else{
            abort(500);
        }
    }

    public function ajustarArticulos(Request $request){
        if(isset($request)){
            $herramienta_id = trim($request->herramienta);
            $cantidad = trim($request->cantidad);
            $personal = Auth()->user()->name;
            $motivo = ucfirst(trim($request->motivo));
        }
         

        if(!empty($herramienta_id) && !empty($cantidad) && !empty($personal) && !empty($motivo)){
            //primero se hace el cambio y luego se registra el movimiento

            // 'UPDATE inventarioutl SET qtyo = (qtyo - ' . $cantidad . '), qtyf = (qtyo - qtyc) WHERE herramienta = '. $herramienta_id;
            $resultUpdate = DB::table('inventarioutl')
             ->where('herramienta', $herramienta_id)
             ->update(['qtyo' => DB::raw('qtyo -'.$cantidad),
                        'qtyf' => DB::raw('qtyo-qtyc')]);

            if(!$resultUpdate) abort(500);

            $id_kardex = DB::table('kardex')->insertGetId(
                array(
                    'movimiento' => 5,
                    'descripcion' => $motivo,
                    'personal' => $personal
                )
            );
        
            if(empty($id_kardex)){
                DB::table('inventarioutl')
                ->where('herramienta', $herramienta_id)
                ->update(['qtyo' => DB::raw('qtyo+'.$cantidad),
                           'qtyf' => DB::raw('qtyo-qtyc')]);
                abort(500);
            } 

            $id_kardex_detalle = DB::table('kardex_detalle')->insertGetId(
                array(
                    'id_kardex' => $id_kardex,
                    'id_herramienta' => $herramienta_id,
                    'qty' => $cantidad
                )
            );

            if(empty($id_kardex_detalle)){
                //si no se puede hacer el registro de kardex detalle, regresa y elimina todo
                DB::table('inventarioutl')
                ->where('herramienta', $herramienta_id)
                ->update(['qtyo' => DB::raw('qtyo+'.$cantidad),
                           'qtyf' => DB::raw('qtyo-qtyc')]);
                DB::table("kardex")->orderBy("id", "desc")->take(1)->delete();
                abort(500);    
            };

         }

         if($resultUpdate){
            return back();
        }else{
            abort(500);
        }
    }



    public function hacerPrestamo(Request $request){
         $herramientas = $request->selected_list;
         $solicitante = $request->solicitante;
         $personal = Auth()->user()->name;
         $comentario = $request->comentario == "" ? 'Préstamo ordinario' : $request->comentario;
         $ticket = isset($request->ticket) ? $request->ticket : null; 
         

        //crear movimiento en kardex y obtener su id
         $id_mov = DB::table('kardex')->insertGetId(
            array(
                'movimiento' => 1,
                'descripcion' => $comentario,
                'solicitante' => $solicitante,
                'personal' => $personal,
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
        $personal = Auth()->user()->name;
        $id_kardex_prestamo = $request->id; //se necesita el id del prestamo para cambiar el estado a 0
   
        
       
       //crear movimiento en kardex y obtener su id
        $id_mov = DB::table('kardex')->insertGetId(
           array(
               'movimiento' => 2,
               'solicitante' => $solicitante,
               'personal' => $personal,
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
            $personal = Auth()->user()->name;

            if($accion == "recuperar"){
                $estado = 3;
                $tipo_mov = 2;
                $descripcion_mov = "Regreso tardío";
            }else if($accion == "eliminar"){
                $estado = 2;
                $tipo_mov = 5;
                $descripcion_mov = "Perdido en préstamo";
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
                        'personal' => $personal,
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


    /*inicio de metodos de corte */
    public function fetchEstado(){

        $result = DB::table('inventarioutl')
        ->join('catalogo', 'catalogo.id', '=', 'inventarioutl.herramienta')
        ->select('inventarioutl.id', 'catalogo.codigo', 'catalogo.numserie', 'catalogo.descripcion', 'inventarioutl.qtyo', 'inventarioutl.qtyf', 'inventarioutl.qtyc')
        ->where('catalogo.activo','=',1)
        ->get();

        //para ahorrarnos consultas se obtienen las cantidades por programación
        if(count($result) > 0){
         
            $registradas = count($result);
            $total_unidades = 0;
            $total_disponibles = 0;
            $total_comprometidas = 0;
            $personal = Auth()->user()->name;

            foreach($result as $row){
                 $total_unidades += $row->qtyo;
                 $total_disponibles += $row->qtyf;
                 $total_comprometidas += $row->qtyc;    
            }

            //asi se castea en php
            $faltantes = 0;
            $faltantes += DB::table('faltantes')
            ->where('estado', '=', 1)
            ->sum('cantidad');
            
            $en_prestamo = $total_comprometidas - $faltantes;

            $info = [
                    'inventario_estado' => $result,
                    'numero_herramientas' => count($result),
                    'total_unidades' => $total_unidades, 
                    'total_disponibles' => $total_disponibles,
                    'total_comprometidas' => $total_comprometidas,
                    'en_prestamo' => $en_prestamo,
                    'faltantes' => $faltantes,
                    'personal' => $personal];

            return $info;

        }else{
            return "No hay nada en el inventario";
        } 

    }

    public function hacerCorte(Request $request){
       

        if(isset($request) && !empty($request)){
            $data = $request->data;
            $herramientas = $data['inventario_estado'];
            $numero_herramientas = $data['numero_herramientas'];
            $total_unidades = $data['total_unidades'];
            $total_disponibles = $data['total_disponibles'];
            $total_comprometidas = $data['total_comprometidas'];
            $personal = Auth()->user()->name;

           $id_cortes = DB::table('cortes')->insertGetId(
                    array(
                        'registradas' => $numero_herramientas,
                        'totalArticulos' => $total_unidades,
                        'totalDisponibles' => $total_disponibles,
                        'totalComprometidas' => $total_comprometidas,
                        'personal' => $personal
                    )
                );
    
                if(empty($id_cortes)) abort(500);

            foreach($herramientas as $herramienta){
                $id_corte_detalle =  DB::table('cortes_detalle')->insertGetId(
                        array(
                            'id_corte' => $id_cortes,
                            'id_herramienta' => $herramienta['id'],
                            'qtyo' => $herramienta['qtyo'],
                            'qtyf' => $herramienta['qtyf'],
                            'qtyc' => $herramienta['qtyc'],
                        )
                    );

                    if(empty($id_cortes)){
                        DB::table('cortes_detalle')
                        ->where('id_corte',$id_cortes)
                        ->delete();
                      
                        DB::table('cortes')
                        ->where('id', $id_cortes)
                        ->delete();

                        abort(500);
                    } 
            }
            return back();
        }
        
    }

    public function fetchCortes(Request $request){
        

        if($request->ajax()){

            $cortes = DB::table('cortes')
            ->where('estado', '=', 1)
            ->get();

            return DataTables::of($cortes)
                ->addColumn('action', function($cortes){
                    $acciones = '<a href="#" class="btn btn-success btn-sm" title="Descargar este corte">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-file-download" 
                                    width="16" height="16" viewBox="2 0 21 21" stroke-width="1.5" stroke="#ffffff" fill="none" stroke-linecap="round" stroke-linejoin="round">
                                    <path stroke="none" d="M0 0h24v24H0z" fill="none"/>
                                    <path d="M14 3v4a1 1 0 0 0 1 1h4" />
                                    <path d="M17 21h-10a2 2 0 0 1 -2 -2v-14a2 2 0 0 1 2 -2h7l5 5v11a2 2 0 0 1 -2 2z" />
                                    <line x1="12" y1="11" x2="12" y2="17" />
                                    <polyline points="9 14 12 17 15 14" />
                                    </svg>
                                </a>';
                    $acciones .= '&nbsp;&nbsp;&nbsp;<button name="deleteCorte" id="'. $cortes->id .'" class="delete-corte btn btn-danger btn-sm" title="Eliminar este corte">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-trash" width="16" height="16" viewBox="0 0 24 24" stroke-width="1.5" stroke="#ffffff" fill="none" stroke-linecap="round" stroke-linejoin="round">
                                        <path stroke="none" d="M0 0h24v24H0z" fill="none"/>
                                        <line x1="4" y1="7" x2="20" y2="7" />
                                        <line x1="10" y1="11" x2="10" y2="17" />
                                        <line x1="14" y1="11" x2="14" y2="17" />
                                        <path d="M5 7l1 12a2 2 0 0 0 2 2h8a2 2 0 0 0 2 -2l1 -12" />
                                        <path d="M9 7v-3a1 1 0 0 1 1 -1h4a1 1 0 0 1 1 1v3" />
                                        </svg>
                                    </button>';
                    ;
                    
                    return $acciones; 
                })
                ->rawColumns(['action'])
                ->make(true);
        }

    }
    /*fin de metodos de corte*/ 


 

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