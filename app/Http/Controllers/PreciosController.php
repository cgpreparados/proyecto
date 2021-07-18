<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\ProductosPrecio;
use App\Models\Materiales;
use Illuminate\Support\Facades\DB;

class PreciosController extends Controller
{
    public function productos_precio(){

        $productos = Materiales::on('cg')->where('tipo_material',3)
                    ->join('productos_precio as pp','pp.codigo_material','=','cod_material')
                    ->get();
        $materiales = Materiales::on('cg')->where('tipo_material',3)->get();

         return view('precios.productos_precios',['productos'=>$productos,'materiales'=>$materiales]);           
    }
    public function guardar_precio(Request $request){

        $request = $request->all();

        $codigo= $request['cod_material'];
        $precio = $request['precio'];

        $contador = DB::connection('cg')
                ->table('productos_precio')
                ->selectRaw('COUNT(*) as num')
                ->where('codigo_material',$codigo)
                ->get();

        foreach($contador as $cont){
            $conta = $cont->num;
        }

        if($conta == 0){
            try {
                $data=array('codigo_material'=>$codigo,'precio_unitario'=>$precio);
                ProductosPrecio::on('cg')->insert($data);
            }catch(Exception $e){
                $texto = 'Error al ingresar datos';
                $response =array('code'=>1,'msg'=>$texto);
                return response()->json($response,200);
            }
        }else{
            try {
                $data=array('precio_unitario'=>$precio);
                ProductosPrecio::on('cg')->where('codigo_material',$codigo)->update($data);
            }catch(Exception $e){
                $texto = 'Error al ingresar datos';
                $response =array('code'=>1,'msg'=>$texto);
                return response()->json($response,200);
            }
        }

        $texto = 'Exito';
        $response =array('code'=>0,'msg'=>$texto);
        return response()->json($response,200);

        

    }
    public function guardar_edicion_precio(Request $request){

        $request = $request->all();

        $codigo= $request['codigo'];
        $precio = $request['precio'];
        
            try {
                $data=array('precio_unitario'=>$precio);
                ProductosPrecio::on('cg')->where('codigo_material',$codigo)->update($data);
            }catch(Exception $e){
                $texto = 'Error al ingresar datos';
                $response =array('code'=>1,'msg'=>$texto);
                return response()->json($response,200);
            }

        $texto = 'Exito';
        $response =array('code'=>0,'msg'=>$texto);
        return response()->json($response,200);

    }

    public function eliminar_precio(Request $request){

        $request = $request->all();

        $codigo= $request['codigo'];
        
            try {
                ProductosPrecio::on('cg')->where('codigo_material',$codigo)->delete();
            }catch(Exception $e){
                $texto = 'Error al ingresar datos';
                $response =array('code'=>1,'msg'=>$texto);
                return response()->json($response,200);
            }

        $texto = 'Exito';
        $response =array('code'=>0,'msg'=>$texto);
        return response()->json($response,200);

    }
}
