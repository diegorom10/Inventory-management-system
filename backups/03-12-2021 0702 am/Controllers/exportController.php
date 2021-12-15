<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
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

class exportController extends Controller{

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
    
            $sql = 'SELECT catalogo.descripcion AS "nombre", catalogo.codigo, catalogo.numserie, kardex.descripcion, kardex.fecha, kardex.personal, kardex.solicitante, kardex_detalle.qty, movimientos.entrada FROM movimientos'
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
        $hojaActiva->setCellValue('F3', "Tipo de Movimiento");

        $hojaActiva->getColumnDimension('G')->setWidth(25);
        $hojaActiva->setCellValue('G3', "Realizado por");

        $hojaActiva->getColumnDimension('H')->setWidth(25);
        $hojaActiva->setCellValue('H3', "Solicitante");
        

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
            $hojaActiva->setCellValue('G'.$fila, $row->personal)->getStyle('G')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
            $hojaActiva->setCellValue('H'.$fila, $row->solicitante)->getStyle('H')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);


            //Pase a la fila de abajo
            $fila++;
        }

        $hojaActiva ->getStyle('A2'.':H'.$celdaFinal)->applyFromArray($styleArray);

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