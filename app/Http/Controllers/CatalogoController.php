<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Support\Facades\DB; 
use Illuminate\Http\Request; 
use DataTables;


use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Worksheet\Drawing;

class CatalogoController extends Controller{
    public function __construct()
    {
        $this->middleware('auth');
    }



    public function index(Request $request){
        if($request->ajax()){
            $query = 'SELECT catalogo.id, catalogo.descripcion, catalogo.codigo, catalogo.numserie, tipo_herramienta.tipo' 
                .' FROM catalogo' 
                . ' LEFT OUTER JOIN tipo_herramienta'
                . ' ON catalogo.tipo = tipo_herramienta.id'
                . ' WHERE catalogo.activo = 1';


            $herramientas = DB::select($query);
            
            return DataTables::of($herramientas)
                ->addColumn('action', function($herramientas){
                    $acciones = '<a href="javascript:void(0)" onclick="editarHerramienta('. $herramientas->id .')" class="btn btn-info btn-sm">Editar</a>';
                    $acciones .= '&nbsp;&nbsp;&nbsp;<button type="button" name="delete" id="'. $herramientas->id .'" class="delete btn btn-danger btn-sm">Eliminar</button>';
                    $acciones .= '&nbsp;&nbsp;&nbsp;<a href="'.route('export.movimientos', ['id'=> $herramientas->id]).'" 
                    class="btn btn-success btn-sm descargar-kardex" title="Descargar todos los movimientos de esta herramienta">
                    <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-file-download" 
                        width="16" height="16" viewBox="2 0 21 21" stroke-width="1.5" stroke="#ffffff" fill="none" stroke-linecap="round" stroke-linejoin="round">
                    <path stroke="none" d="M0 0h24v24H0z" fill="none"/>
                    <path d="M14 3v4a1 1 0 0 0 1 1h4" />
                    <path d="M17 21h-10a2 2 0 0 1 -2 -2v-14a2 2 0 0 1 2 -2h7l5 5v11a2 2 0 0 1 -2 2z" />
                    <line x1="12" y1="11" x2="12" y2="17" />
                    <polyline points="9 14 12 17 15 14" />
                  </svg></a>';
                    
                    return $acciones; 
                })
                ->rawColumns(['action'])
                ->make(true);
        }

        $tipos_herramienta = DB::select('SELECT * FROM tipo_herramienta');
        return view('Catalogo.index')->with('tipos', $tipos_herramienta);

    }
    
    public function registrar(Request $request){

        $descripcion = ucfirst(trim($request->descripcion));
        $codigo = trim($request->codigo);
        $numserie = trim($request->numserie);
        $consumible = $request->consumible;
        $tipo = $request->tipo == 'null' ? null : $request->tipo;
        $personal = Auth()->user()->name;
     

        //codigo o serie estara vacio, por eso cambia el query
        if(empty($numserie)){

             //solo comparar si activo=1, activo=false equivalen a herramienta eliminada

            $repetidos = DB::table('catalogo')
                        ->where('activo','=',1)
                        ->where('codigo','=', $codigo)
                        ->get();

            if(count($repetidos) > 0){
                return response()->json(['error' => 'codigo']);
            }

           
            $consumible = $consumible == 'true' ? 1 : 0;
           

            $id_catalogo = DB::table('catalogo')->insertGetId(
                array(
                    'descripcion' => $descripcion,
                    'codigo' => $codigo,
                    'tipo' => $tipo,
                    'consumible' => $consumible
                )
            );
        
            if(empty($id_catalogo)) abort(500);  


            $id_kardex = DB::table('kardex')->insertGetId(
                array('movimiento' => 6, 'descripcion' => 'Registro de herramienta', 'personal'=>$personal)
            );

            if(empty($id_kardex)){
                //no agregar herramienta a catalogo si no se puede crear el movimiento
                DB::table("catalogo")->orderBy("id", "desc")->take(1)->delete();
                abort(500);
            }


            $id_kardex_detalle = DB::table('kardex_detalle')->insertGetId(
                array(
                    'id_kardex' => $id_kardex,
                    'id_herramienta' => $id_catalogo,
                )
            );

            if(empty($id_kardex_detalle)){
                DB::table("catalogo")->orderBy("id", "desc")->take(1)->delete();
                DB::table("kardex")->orderBy("id", "desc")->take(1)->delete();
                abort(500);    
            };


            $id_inventario = DB::table('inventarioutl')->insertGetId(
                array(
                    'herramienta' => $id_catalogo,
                    'qtyo' => 0,
                    'qtyf' => 0,
                    'qtyc' => 0
                )
            );

            if(empty($id_inventario)){
                //si no se puede crear el registro en el inventario, borrar movimiento y borrar herramienta
                DB::table("catalogo")->orderBy("id", "desc")->take(1)->delete();
                DB::table("kardex")->orderBy("id", "desc")->take(1)->delete();
                abort(500);
            } 
        }

        if(empty($codigo)){

            //solo comparar si activo=1, activo=false equivalen a herramienta eliminada
            $repetidos = DB::table('catalogo')
                        ->where('activo','=',1)
                        ->where('numserie', '=', $numserie)
                        ->get();

            if(count($repetidos) > 0){
                return response()->json(['error' => 'serie']);
            }


              $id_catalogo = DB::table('catalogo')->insertGetId(
                array(
                    'descripcion' => $descripcion,
                    'numserie' => $numserie,
                    'tipo' => $tipo,
                )
            );
        
            if(empty($id_catalogo)) abort(500);


            $id_kardex = DB::table('kardex')->insertGetId(
                array(
                    'movimiento' => 6,
                    'descripcion' => 'Herramienta nueva',
                    'personal'=>$personal
                )
            );

            if(empty($id_kardex)){
                //no agregar herramienta a catalogo si no se puede crear el movimiento
                DB::table("catalogo")->orderBy("id", "desc")->take(1)->delete();
                abort(500);
            }

            $id_kardex_detalle = DB::table('kardex_detalle')->insertGetId(
                array(
                    'id_kardex' => $id_kardex,
                    'id_herramienta' => $id_catalogo,
                )
            );

            if(empty($id_kardex_detalle)){
                DB::table("catalogo")->orderBy("id", "desc")->take(1)->delete();
                DB::table("kardex")->orderBy("id", "desc")->take(1)->delete();
                abort(500);    
            };


            $id_inventario = DB::table('inventarioutl')->insertGetId(
                array(
                    'herramienta' => $id_catalogo,
                    'qtyo' => 0,
                    'qtyf' => 0,
                    'qtyc' => 0
                )
            );

            if(empty($id_inventario)){
                //si no se puede crear el registro en el inventario, borrar la herramienta y el moviemiento
                DB::table("catalogo")->orderBy("id", "desc")->take(1)->delete();
                DB::table("kardex")->orderBy("id", "desc")->take(1)->delete();
                abort(500);
            } 

            //si es un articulo unico (numserie), la herramienta se añade automaticamente con 1 elementos
            $id_kardex2 = DB::table('kardex')->insertGetId(
                array(
                    'movimiento' => 3,
                    'descripcion' => 'Llegada de herramienta unica'
                )
            );

            if(empty($id_kardex2)) abort(500);

            $id_kardex_detalle2 = DB::table('kardex_detalle')->insertGetId(
                array(
                    'id_kardex' => $id_kardex2,
                    'id_herramienta' => $id_catalogo,
                    'qty' => 1
                )
            );

            if(empty($id_kardex_detalle2)) abort(500);

            //en where puede ser herramienta=id o el ultimo registro insertado
            $inventario_update = DB::table('inventarioutl')
                                ->where('herramienta', $id_catalogo)
                                ->update(['qtyo' => 1, 'qtyf' => 1]);

            if($inventario_update == false){
                 //si no se puede actualizar el registro en el inventario, borrar la herramienta y el moviemiento
                 DB::table("catalogo")->orderBy("id", "desc")->take(1)->delete();
                 DB::table("kardex")->orderBy("id", "desc")->take(1)->delete();
                 abort(500);
            }


        }

        return back();

    }

    public function eliminar(Request $request){
        $id = $request->id;
        $password_input = trim($request->password);
        $motivo = ucfirst($request->motivo);
        $id_active_user = Auth::id();

        $result_info_user = DB::table('users')->select('password','name')->where('id', '=', 6)->get();
        $password_hash = $result_info_user[0]->password;
        $user_name = $result_info_user[0]->name;
        
        if (Hash::check($password_input, $password_hash) == false) {
            return response()->json(['success'=> false,'error' => 'contraseña']);
        }
        

        $result_inventario = DB::table('inventarioutl')
                    ->select('qtyc')
                    ->where('herramienta', '=', $id)
                    ->get();
                    
        if(count($result_inventario)  > 0) $prestadas = $result_inventario[0]->qtyc;
        
        if($prestadas > 0){
        return response()->json(['success'=> false,'error' => 'pendientes', 'cantidad'=>$prestadas]);
        }


        //$herramienta = DB::select('DELETE FROM catalogo WHERE id = '. $id);
        $result1 = DB::table('catalogo')
             ->where('id', $id)
             ->update(['activo' => 0]);

         if($result1 == false) abort(500);       

        $id_mov = DB::table('kardex')->insertGetId(
                array(
                    'movimiento' => 4,
                    'descripcion' => $motivo,
                    'solicitante' => $user_name.'(admin)'
                )
            );

        if(empty($id_mov)){
            //no se pudo crear el registro de baja en kardex, entonces regresa la herramienta a activa
            DB::table('catalogo')
            ->where('id', $id)
            ->update(['activo' => 1]);

            abort(500);
        } 

        $result2 = DB::table('kardex_detalle')->insertGetId(
            array(
                'id_kardex' => $id_mov,
                'id_herramienta' => $id,
            )
        );
         
        if($result2 == false){
            //si no se pudo hacer el detalle del movimiento, elimina el movimiento y regresa el estado de la herramienta a activo
            DB::table("kardex")
                ->orderBy("id", "desc")
                ->take(1)
                ->delete();

            DB::table('catalogo')
                ->where('id', $id)
                ->update(['activo' => 1]);

            abort(500);
        } 

        return back();

    }

    public function editar($id){
        //solo seleccionar la herramienta para despues actualizarla
        $herramienta = DB::select('SELECT * FROM catalogo WHERE id = '. $id);  
        return response()->json($herramienta);
    }

    public function actualizar(Request $request){
        
        $id = $request->id;
        $descripcion = ucfirst($request->descripcion);
        $tipo = $request->tipo == 'null' ? null : $request->tipo;

         if(!empty($id) && !empty($descripcion)){
            $resultUpdate = DB::table('catalogo')
             ->where('id', $id)
             ->update(['descripcion' => $descripcion,'tipo' => $tipo]);
         }

         if($resultUpdate == true){
            return back();
        }else{
            abort(500);
        }

    }

    public function fetchTool($id){
        //funcion: recibir id de herramienta y regresar toda la infromacion
        if(isset($id)){
            $herramienta = DB::table('catalogo')
            ->where('id', '=', $id)
            ->where('activo','=', true)
            ->get();
        
            if(!empty($herramienta)){
                return $herramienta[0];
            }else{
                abort(500);
            }
        }
    }

    public function fetchCategorias(){

        //$query = DB::select('SELECT * FROM tipo_herramienta');
        $result = DB::table('tipo_herramienta')
                ->select('*')
                ->get();

        return $result;

    }

    public function exportKardex($id){

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
    
            $sql = 'SELECT catalogo.descripcion AS "nombre", catalogo.codigo, catalogo.numserie, kardex.descripcion, kardex.fecha, kardex.solicitante, kardex.personal, kardex_detalle.qty, movimientos.entrada FROM movimientos'
            .' INNER JOIN kardex ON kardex.movimiento = movimientos.id'
            .' INNER JOIN kardex_detalle ON kardex_detalle.id_kardex = kardex.id'
            .' INNER JOIN catalogo ON catalogo.id = kardex_detalle.id_herramienta'
            .' WHERE catalogo.id = '.$id;

            $result = DB::select($sql);
            $celdaFinal = count($result)+3;
        

        // $sql = 'SELECT inventarioutl.id, catalogo.codigo, catalogo.numserie, catalogo.descripcion, inventarioutl.qtyo, inventarioutl.qtyf, inventarioutl.qtyc'
        //         . ' FROM inventarioutl' 
        //         . ' INNER JOIN catalogo'
        //         . ' ON inventarioutl.herramienta = catalogo.id';
        // $result = DB::select($sql);



        $excel = new Spreadsheet();
        $hojaActiva = $excel->getActiveSheet();
        $hojaActiva->setTitle("Kardex");

        //PONER IMAGEN------------------------------------------------------------------------------------------------------------------
        $hojaActiva->getRowDimension('1')->setRowHeight(47);
        $drawing = new Drawing();
        $drawing->setName('UTLD logo');
        $drawing->setPath('.\images\utld-logo.png');
        $drawing->setCoordinates('A1');
        $drawing->setOffsetX(20);
        $drawing->setOffsetY(10);
        $drawing->setHeight(45);
        $drawing->getShadow()->setVisible(true);
        $drawing->getShadow()->setDirection(45);
        $drawing->setWorksheet($excel->getActiveSheet());
        //------------------------------------------------------------------------------------------------------------------------------------------------------------------
        //------------------------------------------------------------------------------------------------------------------------------------------------------------------
        //PONER FECHA
        $fecha = date("d-m-Y", time());
        $hojaActiva->setCellValue('B1', "ESTE REPORTE SE GENERÓ EL: ". $fecha);
        $hojaActiva->mergeCells('B1:D1');
        $hojaActiva->getStyle('B1:D1')->getFont()->setSize(15);

        //------------------------------------------------------------------------------------------------------------------------------------------------------------------

        //poner encabezados
        $hojaActiva->setCellValue('A2', $result[0]->nombre);
        $hojaActiva->mergeCells('A2:H2');
        //$hojaActiva->getStyle('A2')->getFont()->setSize(20);
        $hojaActiva->getStyle('A2')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
        $hojaActiva->getStyle('A2:H2')->applyFromArray($tableTitle);

        //esto es para ajustar el width de las columnas
        // $hojaActiva->getColumnDimension('A')->setWidth(50);
        // $hojaActiva->setCellValue('A3', "Herramienta");
        // $hojaActiva->getStyle('A3')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);

        $hojaActiva->getColumnDimension('A')->setWidth(15);
        $hojaActiva->setCellValue('A3', "Código");

        $hojaActiva->getColumnDimension('B')->setWidth(20);
        $hojaActiva->setCellValue('B3', "Número Serie");

        $hojaActiva->getColumnDimension('C')->setWidth(25);
        $hojaActiva->setCellValue('C3', "Movimiento Descripción");

        $hojaActiva->getColumnDimension('D')->setWidth(25);
        $hojaActiva->setCellValue('D3', "Fecha del Movimiento");

        $hojaActiva->getColumnDimension('E')->setWidth(25);
        $hojaActiva->setCellValue('E3', "Cantidad");

        $hojaActiva->getColumnDimension('F')->setWidth(25);
        $hojaActiva->setCellValue('F3', "Tipo de Entrada");

        $hojaActiva->getColumnDimension('G')->setWidth(25);
        $hojaActiva->setCellValue('G3', "Solicitante");

        $hojaActiva->getColumnDimension('H')->setWidth(25);
        $hojaActiva->setCellValue('H3', "Personal");
        

        //background color encabezados
        $hojaActiva->getStyle('A3:H3')->applyFromArray($tableHead);



        //traer la información

        //controlar las filas y que se vaya para abajo al terminar la columna
        //empieza en la fila 2 pq la 1 esta con los encabezados
        $fila = 4;


        foreach($result as $row){
            //$hojaActiva->setCellValue('A'.$fila, $row->nombre);
            $hojaActiva->setCellValue('A'.$fila, $row->codigo)->getStyle('A')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
            $hojaActiva->setCellValue('B'.$fila, $row->numserie)->getStyle('B')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
            $hojaActiva->setCellValue('C'.$fila, $row->descripcion)->getStyle('C')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
            $hojaActiva->setCellValue('D'.$fila, $row->fecha)->getStyle('D')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
            $hojaActiva->setCellValue('E'.$fila, $row->qty)->getStyle('E')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
            $hojaActiva->setCellValue('F'.$fila, $row->entrada)->getStyle('F')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
            $hojaActiva->setCellValue('G'.$fila, $row->solicitante)->getStyle('G')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
            $hojaActiva->setCellValue('H'.$fila, $row->personal)->getStyle('H')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);


            //Pase a la fila de abajo
            $fila++;
        }

        $hojaActiva ->getStyle('A2'.':G'.$celdaFinal)->applyFromArray($styleArray);

        // bajar el archivo del browser

        $fileName =  'Content-Disposition: attachment;filename="'.$result[0]->nombre.'('.$fecha.')'.'.xlsx"';
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header($fileName);
        header('Cache-Control: max-age=0');

        //crear la hoja de excel
        $writer = IOFactory::createWriter($excel, 'Xlsx');
        return $writer->save('php://output');

        //exit;

    }
    

}
