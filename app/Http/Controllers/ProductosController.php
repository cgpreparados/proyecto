<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use DateTime;

use App\Models\OrdenProduccion;
use App\Models\OrdenProduccionDetalle;
use App\Models\Materiales;
use App\Models\MaterialesStock;
use App\Models\MaterialesRelacion;
use App\Models\MaterialesOperaciones;
use App\Models\Lotes;
use App\Models\LotesOperaciones;
use App\Models\Formulas;

use App\Models\CargaInventario;

class ProductosController extends Controller
{
    public function productos(){

        $materiales = DB::connection('cg')->table('materiales')->get();

        return view('productos.productos',['materiales'=>$materiales]);
    }
    public function guardar_productos(Request $request){

        $request = $request->all();
        $codigo = $request['codigo'];
        $descripcion = $request['nombre'];
        $unidad = $request['unidad'];
        $dias = $request['dias'];
        $tipo = $request['tipo'];

        $data = array('cod_material'=>$codigo, 'desc_material'=>$descripcion,
                      'unidad_material'=>$unidad,'dias_vencimiento'=>$dias,
                      'tipo_material'=>$tipo,'activo'=>1);

        try{
            Materiales::on('cg')->insert($data);
        }catch(Exception $e){
            $texto = "Error al insertar datos";
            $response = array('code'=>1,'msg'=>$texto);
            return response()->json($response,200);
        }
        $response = array('code'=>0);
        return response()->json($response,200);
    }
    public function inhabilitar_productos(Request $request){

        $request = $request->all();
        $codigo = $request['codigo'];

        $data = array('activo'=>0);

        try{
            Materiales::on('cg')->where('cod_material',$codigo)->update($data);
        }catch(Exception $e){
            $texto = "Error al actualizar datos";
            $response = array('code'=>1,'msg'=>$texto);
            return response()->json($response,200);
        }
        $response = array('code'=>0);
        return response()->json($response,200);
    }
    public function habilitar_productos(Request $request){

        $request = $request->all();
        $codigo = $request['codigo'];

        $data = array('activo'=>1);

        try{
            Materiales::on('cg')->where('cod_material',$codigo)->update($data);
        }catch(Exception $e){
            $texto = "Error al actualizar datos";
            $response = array('code'=>1,'msg'=>$texto);
            return response()->json($response,200);
        }
        $response = array('code'=>0);
        return response()->json($response,200);
    }
    public function guardar_edicion_productos(Request $request){

        $request = $request->all();
        $codigo = $request['codigo'];
        $descripcion = $request['nombre'];
        $unidad = $request['unidad'];
        $dias = $request['dias'];
        $tipo = $request['tipo'];

        $data = array('cod_material'=>$codigo, 'desc_material'=>$descripcion,
                      'unidad_material'=>$unidad,'dias_vencimiento'=>$dias,
                      'tipo_material'=>$tipo);

        try{
            Materiales::on('cg')->where('cod_material',$codigo)->update($data);
        }catch(Exception $e){
            $texto = "Error al actualizar datos";
            $response = array('code'=>1,'msg'=>$texto);
            return response()->json($response,200);
        }
        $response = array('code'=>0);
        return response()->json($response,200);
    }
}