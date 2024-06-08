<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use DateTime;

use App\Models\Informes;
use App\Models\AccesoInformes;
use App\Models\Materiales;

class InformesController extends Controller
{
    public function lista_informes(Int $usuario){

        $listado = DB::connection('cg')->table('acceso_informes as a')
        ->selectRaw('i.descripcion as descripcion, i.ruta as ruta')
        ->join('cg.informes as i','i.id_informe','a.id_informe')
        ->where('a.id_usuario',$usuario)->get();


        return view('Informes.lista_informes',['listado'=>$listado]);

    }

    public function movimiento_materiales_informe(){

        $materiales = Materiales::on('cg')->where('tipo_material',1)->get();

        return view('Informes.movimiento_materiales',['materiales'=>$materiales]);

    }

    public function buscar_movimientos(Request $request){

        $request= $request->all();
        $codigo = $request['material'];
        $fecha_inicio = $request['fecha_inicio'];
        $fecha_fin = $request['fecha_fin'];

        $compras = DB::connection('cg')->table('compras_detalle as cd')
        ->selectRaw('c.fecha_compra as fecha , cd.cantidad as cantidad,cd.precio_unitario as precio, p.nombre_proveedor as proveedor, c.usuario as usuario, m.unidad_material as unidad')
        ->join('cg.compras as c','cd.id_compra','c.id_compras')
        ->join('cg.materiales as m','m.cod_material','cd.codigo_material')
        ->join('cg.proveedor as p','p.id_proveedor','c.id_proveedor')
        ->where('c.fecha_compra','>=',$fecha_inicio)
        ->where('c.fecha_compra','<=',$fecha_fin)
        ->where('cd.codigo_material',$codigo)
        ->orderBy('c.fecha_compra','ASC')
        ->get();

        $ordenes = DB::connection('cg')->table('materiales_operaciones')
        ->selectRaw('id_operacion,fecha,cantidad,observacion,user')
        ->where('fecha','>=',$fecha_inicio)
        ->where('fecha','<=',$fecha_fin)
        ->where('codigo_material',$codigo)
        ->where('operacion','Ruta')
        ->orderBy('fecha','ASC')
        ->get();

        $alta = DB::connection('cg')->table('materiales_operaciones')
        ->selectRaw('id_operacion,fecha,cantidad,observacion,user')
        ->where('fecha','>=',$fecha_inicio)
        ->where('fecha','<=',$fecha_fin)
        ->where('codigo_material',$codigo)
        ->where('operacion','Alta')
        ->orderBy('fecha','ASC')
        ->get();

        $baja = DB::connection('cg')->table('materiales_operaciones')
        ->selectRaw('id_operacion,fecha,cantidad,observacion,user')
        ->where('fecha','>=',$fecha_inicio)
        ->where('fecha','<=',$fecha_fin)
        ->where('codigo_material',$codigo)
        ->where('operacion','Baja')
        ->orderBy('fecha','ASC')
        ->get();
    

        $response = array('compras'=>$compras,'ordenes'=>$ordenes,'alta'=>$alta,'baja'=>$baja);
        return response()->json($response,200);

    }
}
