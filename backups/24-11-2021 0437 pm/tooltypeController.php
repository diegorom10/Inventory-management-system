<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Support\Facades\DB; //agregada
use Illuminate\Http\Request; //agregado
use DataTables;

class tooltypeController extends Controller{
    public function index(Request $request){

        if($request->ajax()){
            $query = 'SELECT * FROM tipo_herramienta';
        
            $herramientas = DB::select($query);
            
            return DataTables::of($herramientas)
                ->addColumn('action', function($herramientas){
                    //$acciones = '<a href="javascript:void(0)" onclick="editarHerramienta('. $herramientas->id .')" class="btn btn-info btn-sm">Editar</a>';
                    $acciones = 
                    '<button type="button" name="edit" id="'. $herramientas->id .'"  tipo="' . $herramientas->tipo .'" class="edit btn btn-info btn-sm"> Editar</button> 
                      <button type="button" name="delete" id="'. $herramientas->id .'" class="delete btn btn-danger btn-sm">Eliminar</button>';
                    
                    return $acciones; 
                })
                ->rawColumns(['action'])
                ->make(true);
        }

        //$tipos_herramienta = DB::select('SELECT * FROM tipo_herramienta');
        return view('tool_type.index');//->with('tipos', $tipos_herramienta);
    }

    public function registrar(Request $request){

        $tipo = ucfirst($request->tipo);

        $id_tipo = DB::table('tipo_herramienta')->insertGetId(
            array(
                'tipo' => $tipo,
            )
        );
     
        if(empty($id_tipo)) abort(500);

        return back();

    }

    public function eliminar($id){
        $herramienta = DB::select('DELETE FROM tipo_herramienta WHERE id = '. $id);
        return back();
    }

    public function datos($id){
        $herramienta = DB::select('SELECT * FROM tipo_herramienta WHERE id = '. $id);  
        return response()->json($herramienta);  
    }

    public function editartipo(Request $request){
        $nuevotipo = $request->updatetipo;
        $id = $request->id;
        $tipos_herramienta = DB::select('UPDATE tipo_herramienta SET tipo ='."'$nuevotipo'".'WHERE tipo_herramienta.id = ' . $id);
        return back();
    }
}