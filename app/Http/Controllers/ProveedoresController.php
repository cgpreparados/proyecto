<?php


namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use DateTime;

use App\Models\Proveedores;

class ProveedoresController extends Controller
{
    public function proveedores(){

        $proveedor = Proveedores::on('cg')->get();

        return view('proveedores.proveedores',['proveedores'=>$proveedor]);

    }
    public function guardar_proveedor(Request $request){

        $request = $request->all();

        $nombre = $request['nombre_proveedor'];
        $ruc = $request['ruc_proveedor'];
        $direccion = $request['direccion_proveedor'];

        $data = array('nombre_proveedor'=>$nombre, 'ruc_proveedor'=>$ruc, 'direccion_proveedor'=>$direccion);

        try {
            Proveedores::on('cg')->insert($data);
        } catch (Exception $e) {
            $texto = 'Error al ingresar datos';
            $response =array('code'=>1,'msg'=>$texto);
            return response()->json($response,200);
        }

        $texto='Datos guardados correctamente';
        $response =array('code'=>0,'msg'=>$texto);

        return response()->json($response,200);

    }
    public function guardar_edicion_Proveedor(Request $request){

        $request = $request->all();

        $codigo = $request['codigo'];
        $nombre = $request['nombre'];
        $ruc = $request['ruc'];
        $direccion = $request['direccion'];

        $data = array('nombre_proveedor'=>$nombre, 'ruc_proveedor'=>$ruc, 'direccion_proveedor'=>$direccion);

        try {
            Proveedores::on('cg')->where('id_proveedor',$codigo)->update($data);
        } catch (Exception $e) {
            $texto = 'Error al actualizar datos';
            $response =array('code'=>1,'msg'=>$texto);
            return response()->json($response,200);
        }

        $texto='Datos guardados correctamente';
        $response =array('code'=>0,'msg'=>$texto);

        return response()->json($response,200);

    }
    public function eliminar_proveedor(Request $request){

        $request = $request->all();

        $codigo = $request['codigo'];

    

        try {
            Proveedores::on('cg')->where('id_proveedor',$codigo)->delete();
        } catch (Exception $e) {
            $texto = 'Error al actualizar datos';
            $response =array('code'=>1,'msg'=>$texto);
            return response()->json($response,200);
        }

        $texto='Datos eliminados correctamente';
        $response =array('code'=>0,'msg'=>$texto);

        return response()->json($response,200);

    }

}
