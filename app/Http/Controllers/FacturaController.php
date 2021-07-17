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

class FacturaController extends Controller
{
    public function factura(Int $id, Int $tipo){

        // $datos = DB::select('SELECT f.fecha as fecha , c.nombre_cliente as cliente, c.ruc_cliente as ruc, c.direccion_cliente as direccion,
        // t.timbrado as timbrado, t.fecha_vigencia as fecha_inicio, t.fecha_fin as fin,f.nota_envio as envio, f.nro_factura as factura, 
        // total_factura as total, f.id_factura as idf FROM cg.factura as f
        // JOIN cg.clientes as c on c.id_cliente = f.cliente
        // JOIN cg.datos_factura as t on t.id_timbrado = f.timbrado where id_factura='.$id);

        $datos = DB::connection('cg')->table('factura as f')
        ->selectRaw('f.fecha as fecha , c.nombre_cliente as cliente, c.ruc_cliente as ruc, c.direccion_cliente as direccion,
        t.timbrado as timbrado, t.fecha_vigencia as fecha_inicio, t.fecha_fin as fin,f.nota_envio as envio, f.nro_factura as factura, 
        total_factura as total, f.id_factura as idf')
        ->join('clientes as c','c.id_cliente','f.cliente')
        ->join('datos_factura as t','t.id_timbrado','f.timbrado')
        ->where('f.id_factura',$id)
        ->get();

        foreach($datos as $data){
            $fecha = $data->fecha;
            $cliente = $data->cliente;
            $ruc = $data->ruc;
            $direccion= $data->direccion;
            $timbrado = $data->timbrado;
            $fecha_inicio = $data->fecha_inicio;
            $fecha_fin = $data->fin;
            $envio = $data->envio;
            $subtotal = $data->total;
            $factura = $data->factura;
            $idf = $data->idf;
        }

        if($factura < 10){
            $factura = '000000'.$factura;
        }elseif($factura > 10){
            $factura = '00000'.$factura;
        }
        elseif($factura > 99){
            $factura = '0000'.$factura; 
        }elseif($factura > 999){
            $factura = '000'.$factura;
        }elseif($factura > 9999){
            $factura = '00'.$factura;
        }elseif($factura > 99999){
            $factura = '0'.$factura;
        }elseif($factura > 999999){
            $factura = $factura;
        }


        // $detalle = DB::select('SELECT m.cod_material as codigo, m.desc_material as descripcion, f.cantidad as cantidad,
        // m.unidad_material as unidad, f.precio as precio, (f.precio * f.cantidad) as total
        // FROM cg.factura_detalles as f
        // JOIN cg.materiales as m on m.cod_material=f.codigo_material
        // WHERE f.id_factura='.$idf);

        $detalle = DB::connection('cg')->table('factura_detalles as f')
        ->selectRaw('m.cod_material as codigo, m.desc_material as descripcion, f.cantidad as cantidad,
        m.unidad_material as unidad, f.precio as precio, (f.precio * f.cantidad) as total')
        ->join('materiales as m','m.cod_material','f.codigo_material')
        ->where('f.id_factura',$id)
        ->get();

        return view('factura.factura',['total'=>$detalle, 'subtotal'=>$subtotal,
        'tipo'=>$tipo,'fecha'=>$fecha,'cliente'=>$cliente,'ruc'=>$ruc,'direccion'=>$direccion,
        'timbrado'=>$timbrado,'fecha_inicio'=>$fecha_inicio,'fecha_fin'=>$fecha_fin,'envio'=>$envio, 'factura'=>$factura
        ]);
    }

    public function lista_facturas(){
        $clientes = Clientes::on('cg')->get();
        return view('factura.lista_facturas',['cliente'=>$clientes]);
    }

    public function buscar_facturas(Request $request){
        $request = $request->all();
        $fecha_inicio = $request['fecha_inicio'];
        $fecha_fin = $request['fecha_fin'];
        $cliente = $request['cliente'];

        if($cliente == 0){
            // $listado = DB::select('SELECT f.fecha as fecha , c.nombre_cliente as cliente, f.nro_factura as factura, 
            // total_factura as total, f.id_factura as idf 
            // FROM cg.factura as f
            // JOIN cg.clientes as c on c.id_cliente = f.cliente
            // where f.fecha BETWEEN "'.$fecha_inicio.'" and "'.$fecha_fin.'"');

            $listado = DB::connection('cg')->table('factura as f')
            ->selectRaw('f.fecha as fecha , c.nombre_cliente as cliente, f.nro_factura as factura, 
            total_factura as total, f.id_factura as idf')
            ->join('clientes as c','c.id_cliente','f.cliente')
            ->where('f.fecha','>=',$fecha_inicio)
            ->where('f.fecha','<=',$fecha_fin)
            ->get();

            // $total = DB::select('SELECT SUM( f.total_factura) as total
            // FROM cg.factura as f
            // where f.fecha BETWEEN "'.$fecha_inicio.'" and "'.$fecha_fin.'"');

            $total = DB::connection('cg')->table('factura as f')
            ->selectRaw('SUM( f.total_factura) as total')
            ->where('f.fecha','>=',$fecha_inicio)
            ->where('f.fecha','<=',$fecha_fin)
            ->get();

            foreach($total as $st){
                $gs_total = $st->total;
            }
            $total = number_format($gs_total, 0,',','.');
        }else{
            // $listado = DB::select('SELECT f.fecha as fecha , c.nombre_cliente as cliente, f.nro_factura as factura, 
            // total_factura as total, f.id_factura as idf 
            // FROM cg.factura as f
            // JOIN cg.clientes as c on c.id_cliente = f.cliente
            // where f.fecha BETWEEN "'.$fecha_inicio.'" and "'.$fecha_fin.'" and f.cliente='.$cliente);

            $listado = DB::connection('cg')->table('factura as f')
            ->selectRaw('f.fecha as fecha , c.nombre_cliente as cliente, f.nro_factura as factura, 
            total_factura as total, f.id_factura as idf')
            ->join('clientes as c','c.id_cliente','f.cliente')
            ->where('f.fecha','>=',$fecha_inicio)
            ->where('f.fecha','<=',$fecha_fin)
            ->where('f.cliente',$cliente)
            ->get();

            // $total = DB::select('SELECT SUM( f.total_factura) as total
            // FROM cg.factura as f
            // where f.fecha BETWEEN "'.$fecha_inicio.'" and "'.$fecha_fin.'"and f.cliente='.$cliente);

            $total = DB::connection('cg')->table('factura as f')
            ->selectRaw('SUM( f.total_factura) as total')
            ->where('f.fecha','>=',$fecha_inicio)
            ->where('f.fecha','<=',$fecha_fin)
            ->where('f.cliente',$cliente)
            ->get();

            foreach($total as $st){
                $gs_total = $st->total;
            }
            $total = number_format($gs_total, 0,',','.');
        }
        $response = array('listado'=>$listado,'total'=>$total);
        return response()->json($response,200);
    }

}
