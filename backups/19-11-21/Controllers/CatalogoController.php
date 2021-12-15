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

class CatalogoController extends Controller{
    public function index(Request $request){
        if($request->ajax()){
            $query = 'SELECT catalogo.id, catalogo.descripcion, catalogo.codigo, catalogo.numserie, tipo_herramienta.tipo' 
                .' FROM catalogo' 
                . ' INNER JOIN tipo_herramienta'
                . ' ON catalogo.tipo = tipo_herramienta.id';


            $herramientas = DB::select($query);
            
            return DataTables::of($herramientas)
                ->addColumn('action', function($herramientas){
                    $acciones = '<a href="javascript:void(0)" onclick="editarHerramienta('. $herramientas->id .')" class="btn btn-info btn-sm">Editar</a>';
                    $acciones .= '&nbsp;&nbsp;&nbsp;<button type="button" name="delete" id="'. $herramientas->id .'" class="delete btn btn-danger btn-sm">Eliminar</button>';
                    // $acciones .= '&nbsp;&nbsp;&nbsp;<button type="button" value="'. $herramientas->id .'" class="btn btn-success btn-sm descargar-kardex">s</button>';
                    $acciones .= '&nbsp;&nbsp;&nbsp;<a href="'.route('catalogo.export', ['id'=> $herramientas->id]).'" class="btn btn-success btn-sm descargar-kardex">s</a>';
                     //$acciones .= '&nbsp;&nbsp;&nbsp;<a href="<?=php echo route("catalogo.export")" class="btn btn-success btn-sm descargar-kardex">s</a>';
                    return $acciones; 
                })
                ->rawColumns(['action'])
                ->make(true);
        }

        $tipos_herramienta = DB::select('SELECT * FROM tipo_herramienta');
        //return view('catalogo.index')->with('tipos', $tipos_herramienta);
        return view('Catalogo.index')->with('tipos', $tipos_herramienta);


    }
    
    public function registrar(Request $request){

        $descripcion = $request->descripcion;
        $codigo = $request->codigo;
        $numserie = $request->numserie;
        $tipo = $request->tipo;

        //codigo o serie estara vacio, por eso cambia el query
        if(empty($numserie)){
            $query= 'INSERT INTO catalogo(descripcion, codigo, tipo)'
                    .'VALUES("'.$descripcion.'","'.$codigo.'","'.$tipo.'")';
                
        }

        if(empty($codigo)){
            $query= 'INSERT INTO catalogo(descripcion, numserie, tipo)'
                    .'VALUES("'.$descripcion.'","'.$numserie.'","'.$tipo.'")';
                
        }
     
        $herramienta = DB::select($query);
        return back();

    }

    public function eliminar($id){
        $herramienta = DB::select('DELETE FROM catalogo WHERE id = '. $id);
        return back();

    }

    public function editar($id){
        //solo seleccionar la herramienta para despues actualizarla
        $herramienta = DB::select('SELECT * FROM catalogo WHERE id = '. $id);  
        return response()->json($herramienta);
    }

    public function actualizar(Request $request){
        
        $id = $request->id;
        $descripcion = $request->descripcion;
        $codigo = $request->codigo;
        $serie = $request->numserie;
        $tipo = $request->tipo;

         //ASI NO FUNCIONA -> $serie = !empty($request->numserie) ? $request->numserie : NULL; 

        if(empty($codigo)){
            $query = 'UPDATE catalogo SET '
            .'descripcion="'.$descripcion.'",'
            .'numserie="'. $serie .'",'
            .'tipo="'. $tipo . '"'
            .' WHERE id='.$id;  
        }

        if(empty($serie)){
            $query = 'UPDATE catalogo SET '
            .'descripcion="'.$descripcion.'",'
            .'codigo="'. $codigo .'",'
            .'tipo="'. $tipo . '"'
            .' WHERE id='.$id;
        }

        $herramienta = DB::select($query);
        return back();
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
    
            $sql = 'SELECT catalogo.descripcion AS "nombre", catalogo.codigo, catalogo.numserie, kardex.descripcion, kardex.fecha, kardex.solicitante, kardex_detalle.qty, movimientos.entrada FROM movimientos'
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

        //------------------------------------------------------------------------------------------------------------------------------------------------------------------
        //PONER FECHA
        $fecha = date("d-m-Y", time());
        $hojaActiva->setCellValue('A1', "ESTE REPORTE SE GENERÓ EL: ". $fecha);
        $hojaActiva->mergeCells('A1:C1');
        $hojaActiva->getStyle('A1:C1')->getFont()->setSize(15);

        //------------------------------------------------------------------------------------------------------------------------------------------------------------------

        //poner encabezados
        $hojaActiva->setCellValue('A2', $result[0]->nombre);
        $hojaActiva->mergeCells('A2:G2');
        //$hojaActiva->getStyle('A2')->getFont()->setSize(20);
        $hojaActiva->getStyle('A2')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
        $hojaActiva->getStyle('A2:G2')->applyFromArray($tableTitle);

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
        $hojaActiva->setCellValue('E3', "Cantidad en Préstamo");

        $hojaActiva->getColumnDimension('F')->setWidth(25);
        $hojaActiva->setCellValue('F3', "Tipo de Entrada");

        $hojaActiva->getColumnDimension('G')->setWidth(25);
        $hojaActiva->setCellValue('G3', "Solicitante");
        

        //background color encabezados
        $hojaActiva->getStyle('A3:G3')->applyFromArray($tableHead);



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
