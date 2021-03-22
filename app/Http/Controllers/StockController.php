<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use DateTime;

use App\Models\Materiales;
use App\Models\MaterialesStock;
use App\Models\MaterialesRelacion;
use App\Models\MaterialesOperaciones;
use App\Models\MaterialesOperacionesHead;
use App\Models\Lotes;
use App\Models\LotesOperaciones;
use App\Models\CargaInventario;
use App\Models\CargaInventarioLotes;

class StockController extends Controller
{
    public function stock_materiales(){
        $listado = DB::on('cg')->select('SELECT m.cod_material as codigo,m.desc_material as descripcion,s.cantidad as cantidad, m.unidad_material as unidad FROM materiales_stock as s JOIN materiales as m on m.cod_material = s.codigo_material');

        return view('stock.stock_materiales',['listado'=>$listado]);
    }

    public function stock_lotes(){
        $listado = DB::select('SELECT m.cod_material as codigo,m.desc_material as descripcion, (SELECT SUM(l.cantidad) from cg.lotes as l where l.cod_material=s.cod_material and l.en_stock=1) as cantidad FROM cg.lotes as s JOIN cg.materiales as m on m.cod_material = s.cod_material  GROUP BY m.cod_material, m.desc_material,s.cod_material;');

        return view('stock.stock_lotes',['listado'=>$listado]);
    }

    public function detalle_lotes_stock(Request $request){

        $request = $request->all();
        $codigo = $request['codigo'];

        $listado= DB::select('Select l.nro as contenedor, l.lote_nro as produccion, 
        l.fecha_lote as fecha_elab, l.fecha_vencimiento as vencimiento,
        l.cantidad as cantidad, m.unidad_material as unidad
        FROM cg.lotes as l JOIN cg.materiales as m on m.cod_material=l.cod_material AND l.cod_material="'.$codigo.'" AND l.en_stock=1 ORDER BY l.id_lote DESC LIMIT 50');

        return response()->json($listado,200);

    }

    public function inventario_materiales(){

        $listado = Materiales::on('cg')->where('tipo_material',1)->get();

        return view('stock.inventario_materiales',['listado'=>$listado]);
    }

    public function guardar_inventario_materiales(Request $request){

        $request = $request->all();
        $listado = $request['valores'];
        $usuario = $request['usuario'];

        $now = date('Y-m-d');

        $cantidad = DB::select('select COUNT(*) as num from cg.carga_inventario where CAST(fecha_carga as DATE)= "'. $now . '"');
        foreach($cantidad as $cont){
            $conta = $cont->num;
        }
       if($conta > 0){
        $texto = 'Ya se ha realizado carga de inventario en esta fecha';
        $response = array('code'=>1, 'msg'=>$texto);

       }else{
           foreach($listado as $list){
            $codigo   = $list['codigo'];
            $cantidad = $list['cantidad'];
            
            
            if(!(is_null($codigo))){   

                $diferencia = MaterialesStock::on('cg')->where('codigo_material',$codigo)->get();
                foreach($diferencia as $dif){
                    $anterior = $dif->cantidad;
                }

                $diferencia = $cantidad - $anterior;

                $data = array('codigo_material'=>$codigo,'cantidad'=>$cantidad,'diferencia'=>$diferencia,'user'=>$usuario);
                $dataStock = array('cantidad'=>$cantidad);

                MaterialesStock::on('cg')->where('codigo_material',$codigo)->update($dataStock);

                CargaInventario::on('cg')->insert($data);

            }
            
        }
        $response =array('code'=>0,'fecha'=>$now);
       }
        
        return response()->json($response,200);
    }

    public function imprimir_inventario_materiales(String $fecha){

        $listado = DB::select('SELECT s.codigo_material as codigo, m.desc_material as descripcion, s.cantidad as cantidad, s.diferencia as diferencia, m.unidad_material as unidad, s.user as user FROM cg.carga_inventario as s JOIN cg.materiales as m on m.cod_material = s.codigo_material WHERE CAST(s.fecha_carga AS DATE)="'.$fecha.'"');
        foreach ($listado as $list){
            $user = $list->user;
        }

        return view('stock.imprimir_inventario',['listado'=>$listado,'fecha'=>$fecha,'user'=>$user]);
    }
    public function inventario_lotes(){

        $listado = Materiales::on('cg')->where('tipo_material',3)->get();

        return view('stock.inventario_lotes',['listado'=>$listado]);
    }
    public function guardar_inventario_lotes(Request $request){

        $request = $request->all();
        $listado = $request['valores'];
        $usuario = $request['usuario'];

        $now = date('Y-m-d');

        $cantidad = DB::select('select COUNT(*) as num from cg.carga_inventario_lotes where CAST(fecha_carga as DATE)= "'. $now . '"');
        foreach($cantidad as $cont){
            $conta = $cont->num;
        }
       if($conta > 0){
        $texto = 'Ya se ha realizado carga de inventario en esta fecha';
        $response = array('code'=>1, 'msg'=>$texto);

       }else{
           
           foreach($listado as $list){
            $codigo   = $list['codigo'];
            $cantidad = $list['cantidad'];
            
            
            if(!(is_null($codigo))){   

                $diferencia = DB::select('SELECT COUNT(l.cantidad) as cantidad from cg.lotes as l WHERE l.cod_material= "'. $codigo . '"');
                
                foreach($diferencia as $dif){
                    $anterior = $dif->cantidad;
                }

                $diferencia = $cantidad - $anterior;

                $data = array('codigo_material'=>$codigo,'cantidad'=>$cantidad,'diferencia'=>$diferencia,'user'=>$usuario);

                CargaInventarioLotes::on('cg')->insert($data);

            }
            
        }
        $response =array('code'=>0,'fecha'=>$now);
       }
        
        return response()->json($response,200);
        
    }

    public function imprimir_inventario_lotes(String $fecha){

        $listado = DB::select('SELECT s.codigo_material as codigo, m.desc_material as descripcion, s.cantidad as cantidad, s.diferencia as diferencia, m.unidad_material as unidad, s.user as user FROM cg.carga_inventario_lotes as s JOIN cg.materiales as m on m.cod_material = s.codigo_material WHERE CAST(s.fecha_carga AS DATE)="'.$fecha.'"');
        foreach ($listado as $list){
            $user = $list->user;
        }

        return view('stock.imprimir_inventario_lotes',['listado'=>$listado,'fecha'=>$fecha,'user'=>$user]);
    }

    public function inventario_impresiones(Request $request){

        return view('stock.inventario_impresiones');

    }

    public function buscar_inventario_impresiones(Request $request){
        $request = $request->all();
        $fecha_inicio = $request['fecha_inicio'];
        $fecha_fin = $request['fecha_fin'];
        $tipo = $request['estado'];

        if($tipo == 'Lotes'){
            $listado =  DB::select('SELECT fecha_carga as fecha ,user as user FROM cg.carga_inventario_lotes  WHERE CAST(fecha_carga AS DATE) BETWEEN "'.$fecha_inicio.'" AND "'.$fecha_fin.'"GROUP BY fecha_carga,user');
        }else if($tipo == 'Materiales'){
            $listado =  DB::select('SELECT fecha_carga as fecha, user as user FROM cg.carga_inventario  WHERE CAST(fecha_carga AS DATE) BETWEEN "'.$fecha_inicio.'" AND "'.$fecha_fin.'" GROUP BY fecha_carga,user');
        }else{
            $listado = DB::select('SELECT l.fecha_operacion as fecha, l.user as user, l.id_operacion as id from cg.materiales_operaciones_head as l  where l.tipo_operacion = "'.$tipo.'" AND CAST(fecha_operacion AS DATE) BETWEEN "'.$fecha_inicio.'" AND "'.$fecha_fin.'" GROUP BY fecha_operacion,user,id_operacion');
        }

        return response()->json($listado,200);

    }

    public function movimiento_materiales(){

        $listado = Materiales::on('cg')->where('tipo_material',1)->get();

        return view('stock.movimiento_materiales',['elegir'=>$listado]);

    }

    public function guardar_movimiento(Request $request){

        $request = $request->all();
        $listado = $request['valores'];
        $user    = $request['user'];
        $tipo    = $request['estado'];

        $data = array('tipo_operacion'=>$tipo,'user'=>$user);

        MaterialesOperacionesHead::on('cg')->insert($data);

        $id = MaterialesOperacionesHead::on('cg')->select('id_operacion')->orderBy('id_operacion','desc')->first();     
        $id = $id['id_operacion'];

        foreach($listado as $list){

            $codigo = $list['codigo'];
            $cantidad = $list['cantidad'];
            $observacion = $list['observacion'];

            if(!(is_null($codigo))){ 

                $data = array('codigo_material'=>$codigo,'cantidad'=>$cantidad, 'operacion'=>$tipo,'observacion'=>$observacion,'user'=>$user,'id_operacion_head'=>$id);

                try{
                    MaterialesOperaciones::on('cg')->insert($data);
                }catch(Exception $e){
                    $texto = 'Error al insertar datos';
                    $response =array('code'=>1,'msg'=>$texto);
                    return response()->json($response,200);
                }

                $stock = MaterialesStock::on('cg')->where('codigo_material',$codigo)->get();
                foreach($stock as $st){
                    $en_stock = $st->cantidad;
                }
                if($tipo == "BAJA"){
                    $stock = $en_stock - $cantidad;
                }else{
                    $stock = $en_stock + $cantidad;
                }

                $data=array('codigo_material'=>$codigo,'cantidad'=>$stock);

                try{
                    MaterialesStock::on("cg")->where('codigo_material',$codigo)->update($data);
                }catch(Exception $e){
                    $texto = 'Error al insertar datos';
                    $response =array('code'=>1,'msg'=>$texto);
                    return response()->json($response,200);
                }
                
            }

        }

        $response =array('code'=>0,'id'=>$id);
        return response()->json($response,200);

    }

    public function imprimir_inventario_movimiento(Int $id){

        $listado = DB::select('SELECT l.codigo_material as codigo, m.desc_material as descripcion, l.cantidad as cantidad,m.unidad_material as unidad, l.observacion as observacion,l.fecha as fecha, l.operacion as operacion, l.user as user from cg.materiales_operaciones as l join cg.materiales as m on m.cod_material=l.codigo_material where l.id_operacion_head='.$id);
        foreach($listado as $list){
            $user = $list->user;
            $fecha = $list->fecha;
            $operacion = $list->operacion;
        }

        $fecha = strtotime($fecha);
        $fecha = date('d-m-Y',$fecha);
    
        return view('stock.imprimir_inventario_movimiento',['listado'=>$listado,'fecha'=>$fecha,'user'=>$user,'operacion'=>$operacion,'id'=>$id]);
         

    }
    
}