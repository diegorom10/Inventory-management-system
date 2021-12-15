<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Support\Facades\DB; //agregada
use Illuminate\Http\Request; //agregado
use DataTables;

class InventarioController extends Controller{
    public function index(Request $request){

        if($request->ajax()){
            /**SELECT inventarioutl.id, catalogo.descripcion, inventarioutl.qtyo AS 'Cantidad original', inventarioutl.qtyf AS 'Cantidad fisica', inventarioutl.qtyc AS 'Cantidad comprometida' 
             * FROM inventarioutl INNER JOIN catalogo ON inventarioutl.herramienta = catalogo.id;  */

            $query = 'SELECT inventarioutl.id, catalogo.descripcion, inventarioutl.qtyo AS "Cantidad original", inventarioutl.qtyf AS "Cantidad fisica", inventarioutl.qtyc AS "Cantidad comprometida"'
                .' FROM inventarioutl' 
                . ' INNER JOIN catalogo'
                . ' ON inventarioutl.herramienta = catalogo.id';

            $inventario = DB::select($query);
            
            return DataTables::of($inventario)
                ->addColumn('action', function($inventario){
                    $acciones = '<a href="javascript:void(0)" onclick="editarHerramienta('. $inventario->id .')" class="btn btn-info btn-sm">Editar</a>';
                    $acciones .= '&nbsp;&nbsp;&nbsp;<button type="button" name="delete" id="'. $inventario->id .'" class="delete btn btn-danger btn-sm">Eliminar</button>';
                    return $acciones; 
                })
                ->rawColumns(['action'])
                ->make(true);
        }

        $listado_herramientas= DB::select('SELECT descripcion , codigo,numserie FROM catalogo');

       
        return view('inventario.index');

    }
    
    public function registrar(Request $request){

      

    }

    public function eliminar($id){
       

    }

    public function editar($id){
     
    }

    public function actualizar(Request $request){
        
       
    }

}