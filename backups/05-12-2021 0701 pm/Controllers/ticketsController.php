<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Support\Facades\DB; //agregada
use Illuminate\Http\Request; //agregado


class ticketsController extends Controller{
  
    public function movimientoAlmacen($accion, $key, $id){

            $result = DB::table('osticket_db.ost_user_account')
                        ->select('passwd')
                        ->where('user_id', '=', 26)
                        ->get();

            $hashed_pass = $result[0]->passwd;
            //escapar "/"
            $hashed_pass = str_replace("/","",$hashed_pass);

            if($hashed_pass == $key){
               Auth::loginUsingId(6, TRUE);

                if($accion == 1){
                    $url = 'ticket?ticket='.$id;
                    return redirect($url);
                }else if($accion == 2){
                    $url = 'regreso?ticket='.$id;
                    return redirect($url);
                }
                
            }else{
                return view('errors.keyError');
            }
    }

};