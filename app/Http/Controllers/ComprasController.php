<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use DateTime;

use App\Models\Materiales;
use App\Models\MaterialesStock;
use App\Models\MaterialesOperaciones;
use App\Models\Compras;
use App\Models\ComprasDetalle;
use App\Models\Proveedores;

class ComprasController extends Controller
{

    public function nuevas_compras(){
        $materiales = Materiales::on('cg')->where('activo',1)->where('tipo_material',1)->get();
        $proveedor = Proveedores::on('cg')->get();

        return view('compras.nuevas_compras',['materiales'=>$materiales,'proveedor'=>$proveedor]);
    }

    public function guardar_compra(Request $request){
        $request = $request->all();

        $listado = $request['valores'];
        $fecha = $request['fecha'];
        $factura = $request['factura'];
        $tipo = $request['tipo'];
        $ruc = $request['ruc'];
        $total = $request['total'];
        $user = $request['user'];

        if(is_null($fecha) || is_null($factura) || is_null($tipo) || is_null($ruc) || is_null($total)){
            $texto= 'Favor completar todos los campos'; 
            $response =array('code'=>1,'msg'=>$texto);
            return response()->json($response,200);
        }

        $cont = 0;
        foreach($listado as $list){
            $cont = $cont+1;
        }
        
        if($cont<2){
            $texto= 'Favor Agregar Detalles'; 
            $response =array('code'=>1,'msg'=>$texto);
            return response()->json($response,200);
        }

        $dataCompra= array('fecha_compra'=>$fecha,'usuario'=>$user,'factura_nro'=>$factura,'factura_tipo'=>$tipo,'id_proveedor'=>$ruc,'factura_total'=>$total);
        
        Compras::on('cg')->insert($dataCompra);

        $id = Compras::on('cg')->select('id_compras')->orderBy('id_compras','desc')->first();
        $id = $id['id_compras'];
        //$id = $id +1;

        
        try{
            foreach($listado as $list){
                $codigo   = $list['codigo'];
                $cantidad = $list['cantidad'];
                $precio = $list['precio'];

                if(!(is_null($codigo))){
                    $data = array('codigo_material'=>$codigo,'cantidad'=>$cantidad,'precio_unitario'=>$precio,'id_compra'=>$id);
                    ComprasDetalle::on('cg')->insert($data);

                    $stock = MaterialesStock::on('cg')->where('codigo_material',$codigo)->get();

                     
                    foreach($stock as $st){
                        $nuevo_stock = $st->cantidad;
                    }

                    if(!(is_null($nuevo_stock))){
                        $stock = $cantidad + $nuevo_stock;
                        $stock_up = array('cantidad'=>$stock);
                        MaterialesStock::on('cg')->where('codigo_material',$codigo)->update($stock_up);
                    }else{
                        $data = array('codigo_material'=>$codigo,'cantidad'=>$cantidad);
                        MaterialesStock::on('cg')->insert($data);
                    }            
                   
                    $justificacion = 'Compra Nro.'.$id;

                    $operacion=array('codigo_material'=>$codigo,'cantidad'=>$cantidad,'operacion'=>'Compra','observacion'=>$justificacion,'user'=>$user);

                    MaterialesOperaciones::on('cg')->insert($operacion);
                }

                    
                
            }

            $response =array('code'=>0);
        }catch(Exception $e){
            $texto = 'Se produjo un error';
            $response =array('code'=>1,'msg'=>$texto);
            Compras::on('cg')->where('id_compras',$id)->delete();
            ComprasDetalle::on('cg')->where('id_compra',$id)->delete();
            return response()->json($response,200);
        }

        $response = array('code'=>0,'id'=>$id);

        return response()->json($response,200);
    }

    public function imprimir_compra(Int $id){

        $compra = Compras::on('cg')->where('id_compras',$id)->get();
        foreach($compra as $comp){
            $fecha = $comp->fecha_compra;
            $user = $comp->usuario;
            $factura = $comp->factura_nro;
            $ruc = $comp->id_proveedor;
            $tipo = $comp->factura_tipo;
            $total = $comp->factura_total;
        }

       // $listado = ComprasDetalle::on('cg')->where('id_compra',$id)->get();
        //$listado = DB::select('select d.codigo_material as codigo, m.desc_material as descripcion, m.unidad_material as unidad, d.cantidad as cantidad, d.precio_unitario as precio from cg.compras_detalle as d join cg.materiales as m on m.cod_material=d.codigo_material where id_compra = '.$id);

        $listado = DB::connection('cg')->table('compras_detalle as d')
        ->selectRaw('d.codigo_material as codigo, m.desc_material as descripcion, m.unidad_material as unidad, 
        d.cantidad as cantidad, d.precio_unitario as precio')
        ->join('materiales as m','m.cod_material','d.codigo_material')
        ->where('d.id_compra',$id)
        ->get();


        return view('compras.imprimir_compra',['listado'=>$listado,'fecha'=>$fecha,'user'=>$user,'factura'=>$factura,'ruc'=>$ruc,'tipo'=>$tipo,'total'=>$total,'id'=>$id]);

    }

    public function buscar_compra(Request $request){
        $request = $request->all();
        $fecha_inicio = $request['fecha_inicio'];
        $fecha_fin = $request['fecha_fin'];

        $listado = Compras::on('cg')->where('fecha_compra','>=',$fecha_inicio)->where('fecha_compra','<=',$fecha_fin)->get();
        
        return response()->json($listado,200);
    }

    public function impresiones_compra(){
        
        return view('compras.impresiones_compras');
    }

}
