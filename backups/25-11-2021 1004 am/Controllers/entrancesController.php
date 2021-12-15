<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Support\Facades\DB; //agregada
use Illuminate\Http\Request; //agregado
use DataTables;

class entrancesController extends Controller{
    
    public function index(Request $request){

        if($request->ajax()){
            $query = 'SELECT * FROM movimientos';
        
            $entrada = DB::select($query);
            
            
            return DataTables::of($entrada)
            ->addColumn('action', function($entrada){
                $acciones = '<a href="javascript:void(0)" onclick="editarHerramienta('. $entrada->id .')" class="btn btn-info btn-sm">Editar</a>';
                $acciones = '<button type="button" name="delete" id="'. $entrada->id .'" class="delete btn btn-danger btn-sm">Eliminar</button>';
                return $acciones; 
            })
            ->rawColumns(['action'])
                ->make(true);
            
        }

        //$tipos_herramienta = DB::select('SELECT * FROM tipo_herramienta');
        return view('entrances.index');//->with('tipos', $tipos_herramienta);
    }

    public function registrar(Request $request){

        $entrada = $request->entrada;
     
        $entradas = DB::select('INSERT INTO movimientos(entrada) VALUES("'.$entrada.'")');
        return back();

    }

    public function eliminar($id){
        $entrada = DB::select('DELETE FROM movimientos WHERE id = '. $id);
        return back();

    }
}