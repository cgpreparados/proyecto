<?php


namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use DateTime;

use App\Models\Clientes;

class ClientesController extends Controller
{
    public function clientes(){

        $clientes = Clientes::on('cg')->get();

        return view('clientes.clientes',['cliente'=>$clientes]);

    }
    public function guardar_cliente(Request $request){

        $request = $request->all();

        $nombre = $request['nombre_cliente'];
        $ruc = $request['ruc_cliente'];
        $direccion = $request['direccion_cliente'];

        $data = array('nombre_cliente'=>$nombre, 'ruc_cliente'=>$ruc, 'direccion_cliente'=>$direccion);

        try {
            Clientes::on('cg')->insert($data);
        } catch (Exception $e) {
            $texto = 'Error al ingresar datos';
            $response =array('code'=>1,'msg'=>$texto);
            return response()->json($response,200);
        }

        $texto='Datos guardados correctamente';
        $response =array('code'=>0,'msg'=>$texto);
        
        return response()->json($response,200);

    }
}
