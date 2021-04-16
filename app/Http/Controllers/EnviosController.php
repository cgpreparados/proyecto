<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use DateTime;

use App\Models\Materiales;
use App\Models\Lotes;
use App\Models\LotesOperaciones;
use App\Models\LotesEnvios;
use App\Models\Envios;
use App\Models\EnviosDetalle;
use App\Models\Clientes;
use App\Models\Factura;
use App\Models\FacturaDetalle;
use App\Models\DatosFactura;
use App\Models\ProductosPrecio;


class EnviosController extends Controller
{
    public function nuevo_envio(){

        $materiales = Materiales::on('cg')->where('activo',1)->where('tipo_material',3)->get();
        $clientes = Clientes::on('cg')->get();

        return view('envios.nuevo_envio',['materiales'=>$materiales,'cliente'=>$clientes]);
    }

    public function guardar_envio(Request $request){
        $request = $request->all();

        $user = $request['user'];
        $cliente = $request['cliente'];
        $listado = $request['valores'];
        $fecha = $request['fecha'];

        if(is_null($fecha) || is_null($cliente)){
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

        foreach($listado as $list){
            $codigo = $list['codigo'];
            $cantidad = $list['cantidad'];
            if(!(is_null($codigo))){
               // $stock = DB::select('SELECT COUNT(*) as stock FROM cg.lotes AS l WHERE l.en_stock=1 AND l.cod_material="'. $codigo.'"');

                $listado = DB::connection('cg')->table('lotes as l')
                ->selectRaw('COUNT(*) as stock')
                ->where('l.en_stock',1)
                ->where('l.cod_material',$codigo)
                ->get();

                foreach($stock as $st){
                    $en_stock = $st->stock;
                }

                $nombre = Materiales::on('cg')->select('desc_material')->where('cod_material',$codigo)->first();
                $nombre = $nombre['desc_material'];

                if($en_stock < $cantidad){
                    $texto = 'No hay suficiente stock de '.$nombre." Cantidad Disponible: ".$en_stock;
                    $response =array('code'=>1,'msg'=>$texto);
                    return response()->json($response,200);
                }
            }
        }

        $datos = array('fecha_envio'=>$fecha,'destino'=>$cliente,'usuario'=>$user);

        Envios::on('cg')->insert($datos);

        $id = Envios::on('cg')->select('id_envio')->orderBy('id_envio','desc')->first();
        $id = $id['id_envio'];

        //$factura = DB::select('SELECT ultima_factura as factura, id_timbrado as timbrado from cg.datos_factura order by id_timbrado desc limit 1');

        $listado = DB::connection('cg')->table('datos_factura')
        ->selectRaw('ultima_factura as factura, id_timbrado as timbrado')
        ->orderBy('id_timbrado','desc')
        ->limit(1)
        ->get();

        foreach($factura as $fac){
            $nro_factura = $fac->factura;
            $timbrado = $fac->timbrado;
        }
        

        $id_factura = Factura::on('cg')->select('id_factura')->orderBy('id_factura','desc')->first();
        $id_factura = $id_factura['id_factura'];
        $id_factura=$id_factura+1;
        
        $totalFactura = 0;
        foreach($listado as $list){
            $codigo = $list['codigo'];
            $cantidad = $list['cantidad'];

            if(!(is_null($codigo))){
                $data = array('id_envio'=>$id,'codigo_material'=>$codigo,'cantidad'=>$cantidad);
                try{
                    EnviosDetalle::on('cg')->insert($data);
                }catch(Exception $e){
                    EnviosDetalle::on('cg')->where('id_envio',$id)->delete();
                    Envios::on('cg')->where('id_envio',$id)->delete();
                    $texto = 'Error al ingresar datos';
                    $response =array('code'=>1,'msg'=>$texto);
                    return response()->json($response,200);
                }
                
                for($i=1; $i<= $cantidad; $i++){

                    $lote = Lotes::on('cg')->select('id_lote')->where('en_stock',1)->where('cod_material',$codigo)->orderBy('id_lote','asc')->first();
                    $id_lote = $lote['id_lote'];
                    $lote = Lotes::on('cg')->select('lote_nro')->where('en_stock',1)->where('cod_material',$codigo)->orderBy('id_lote','asc')->first();
                    $lote= $lote['lote_nro'];

                    $justificacion = "ENVIO DE LOTES - NOTA DE ENVIO NRO. ".$id." - ".$user;

                    $datos_lotes = array('lote'=>$id_lote,'lote_operacion'=>$justificacion);

                    LotesOperaciones::on('cg')->insert($datos_lotes);
                    
                    //CAMBIO DE ESTADO LOTE

                    $lote_update = array('en_stock'=>0);

                    Lotes::on("cg")->where('id_lote',$id_lote)->update($lote_update);

                    $lotes_envios = array('id_envio'=>$id,'id_lote'=>$id_lote,'nro_lote'=>$lote);

                    LotesEnvios::on('cg')->insert($lotes_envios);

                }

                $precio = ProductosPrecio::on('cg')->select('precio_unitario')->where('codigo_material',$codigo)->first();
                $precio= $precio['precio_unitario'];
                $totalFactura = $totalFactura + ($cantidad * $precio);
                $detalle_factura=array('id_factura'=>$id_factura,'codigo_material'=>$codigo,'cantidad'=>$cantidad,'precio'=>$precio);
                FacturaDetalle::on('cg')->insert($detalle_factura);
            }
            
        }
        $nro_factura = $nro_factura+1;
        $update_datos = array('ultima_factura'=>$nro_factura);
        DatosFactura::on("cg")->where('id_timbrado',$timbrado)->update($update_datos);
        $datos_factura = array('fecha'=>$fecha,'cliente'=>$cliente,'nota_envio'=>$id,'nro_factura'=>$nro_factura,'timbrado'=>$timbrado,'total_factura'=>$totalFactura);
        Factura::on('cg')->insert($datos_factura);
        $response =array('code'=>0,'id'=>$id,'id_factura'=>$id_factura);
        return response()->json($response,200);

    }

    public function imprimir_envio(Int $id){

        $datos = Envios::on('cg')->where('id_envio',$id)->get();
        foreach($datos as $dat){
            $cliente = $dat ->destino;
            $fecha = $dat->fecha_envio;
            $user = $dat->usuario;
        }

        $destino = Clientes::on('cg')->select('nombre_cliente')->where('id_cliente',$cliente)->first();
        $destino = $destino['nombre_cliente']; 

        $direccion = Clientes::on('cg')->select('direccion_cliente')->where('id_cliente',$cliente)->first();
        $direccion = $direccion['direccion_cliente']; 

        // $detalle = DB::select( "SELECT m.cod_material as codigo, l.nro as nro , m.desc_material as descripcion,l.cantidad as cantidad,m.unidad_material as unidad,
        // l.fecha_lote as fecha,l.orden_produccion as op FROM cg.envios as e 
        // JOIN cg.lotes_envios as le on le.id_envio=e.id_envio 
        // JOIN cg.lotes as l on le.id_lote=l.id_lote 
        // JOIN cg.materiales as m on l.cod_material=m.cod_material 
        // WHERE e.id_envio= ".$id);

        $detalle = DB::connection('cg')->table('envios as e')
        ->selectRaw('m.cod_material as codigo, l.nro as nro , m.desc_material as descripcion,l.cantidad as cantidad,m.unidad_material as unidad,
        l.fecha_lote as fecha,l.orden_produccion as op')
        ->join('lotes_envios as le','le.id_envio','e.id_envio')
        ->join('lotes as l','le.id_lote','l.id_lote')
        ->join('materiales as m','m.cod_material','l.cod_material')
        ->where('e.id_envio',$id)
        ->get();

        // $total = DB::select("SELECT m.cod_material as codigo, m.desc_material as descripcion, e.cantidad as cantidad, 
        // m.unidad_material as unidad
        // FROM cg.envios_detalle as e
        // JOIN cg.materiales as m on e.codigo_material=m.cod_material
        // WHERE e.id_envio=".$id);

        $total = DB::connection('cg')->table('envios_detalle as e')
        ->selectRaw('m.cod_material as codigo, m.desc_material as descripcion, e.cantidad as cantidad, 
        m.unidad_material as unidad')
        ->join('materiales as m','m.cod_material','e.codigo_material')
        ->where('e.id_envio',$id)
        ->get();


        return view('envios.imprimir_envios',['detalle'=>$detalle,'destino'=>$destino,'total'=>$total,'direccion'=>$direccion,'id'=>$id,'fecha'=>$fecha,'user'=>$user]);

    }

    public function envios_impresiones(){
        $clientes = Clientes::on('cg')->get();

        return view('envios.impresiones_envios',['cliente'=>$clientes]);
    }
    public function buscar_envios(Request $request){
        $request = $request->all();
        $fecha_inicio = $request['fecha_inicio'];
        $fecha_fin = $request['fecha_fin'];
        $cliente = $request['cliente'];

        if($cliente == 0){
            $listado = Envios::on('cg')->where('fecha_envio','>=',$fecha_inicio)->where('fecha_envio','<=',$fecha_fin)->where('cliente',$cliente)->get();
        }else{
            $listado = Envios::on('cg')->where('fecha_envio','>=',$fecha_inicio)->where('fecha_envio','<=',$fecha_fin)->get();
        }
        
        return response()->json($listado,200);
    }
}
