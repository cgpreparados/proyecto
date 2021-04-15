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
use App\Models\CostosIndirectos;
use App\Models\CostosTotales;


class CostosController extends Controller
{
    public function cargar_costos(){
        return view('Costos.cargar_costos');
    }
    public function guardar_costos(Request $request){

        $request = $request->all();
        $fecha = $request['fecha'];
        $tipo_costo = $request['tipo_costo'];
        $precio = $request['precio'];

        if(is_null($fecha) || is_null($precio)){
            $texto= 'Favor completar todos los campos'; 
            $response =array('code'=>1,'msg'=>$texto);
            return response()->json($response,200);
        }

        $año= substr($fecha, 0, 4);
		$mes= substr($fecha, 5, 6);

		$año= intval($año);
		$mes= intval($mes);
        $datos = array ('tipo_costo'=> $tipo_costo,'costo'=>$precio,'mes'=>$mes,'anho'=>$año);

        try{
            CostosIndirectos::on('cg')->insert($datos);
        }catch(Exception $e){
            $texto="Error al insertar datos";
            $response=array('code'=>1,'msg'=>$texto);
            return response()->json($response,200);
        } 

        $response=array('code'=>0);
        return response()->json($response,200);
    }

    public function consultar_costos(){
        return view('Costos.consultar_costos');
    }

    public function buscar_costos(Request $request){

        $request = $request->all();
        $periodo = $request['periodo'];

        if(is_null($periodo)){
            $texto= 'Favor completar todos los campos'; 
            $response =array('code'=>1,'msg'=>$texto);
            return response()->json($response,200);
        }

        $año= substr($periodo, 0, 4);
		$mes= substr($periodo, 5, 6);

		$anho= intval($año);
		$mes= intval($mes);

        $listado = CostosIndirectos::on('cg')->where('mes',$mes)->where('anho',$anho)->get();

        $suma = DB::connection('cg')->table('costos_indirectos')
        ->selectRaw('SUM(costo) as suma')
        ->where('mes','=',$mes)
        ->where('anho','=',$anho)
        ->get();

        foreach($suma as $sum){
            $total = $sum->suma;
        }

        $response = array('listado'=>$listado,'total'=>$total,'code'=>0);
		return response()->json($response,200);

    }

    public function productos_costos(){
        return view('Costos.productos_costos');
    }

    public function calcular_costos(Request $request){

        $request = $request->all();
        $periodo = $request['periodo'];

        if(is_null($periodo)){
            $texto= 'Favor completar todos los campos'; 
            $response =array('code'=>1,'msg'=>$texto);
            return response()->json($response,200);
        }

        $año= substr($periodo, 0, 4);
		$mes= substr($periodo, 5, 6);

		$anho= intval($año);
		$mes= intval($mes);

        //--------------------SELECCIONAR CANT PRODUCIDA X MATERIAL EN EL MES ----------------------//

		// $producciones = DB::select("SELECT opd.codigo_material as codigo, SUM(opd.cantidad) as sumatoria
        // FROM cg.materiales_relacion as mr 
        // JOIN cg.orden_produccion_detalle as opd on opd.codigo_material=mr.codigo_material_entrante
        // JOIN cg.orden_produccion as op on op.id_orden=opd.id_orden
        // JOIN cg.materiales as m on mr.codigo_material_saliente=m.cod_material
        // WHERE MONTH(op.fecha_carga)=".$mes." AND YEAR(op.fecha_carga)=".$anho." AND op.estado='TERMINADO'
        // GROUP BY opd.codigo_material");

        $producciones= DB::connection('cg')->table('materiales_relacion as mr')
        ->selectRaw('opd.codigo_material as codigo, SUM(opd.cantidad) as sumatoria ')
        ->join('orden_produccion_detalle as opd','opd.codigo_material','=','mr.codigo_material_entrante')
        ->join('orden_produccion as op','op.id_orden','=','opd.id_orden')
        ->join('materiales as m','m.cod_material','=','mr.codigo_material_saliente')
        ->whereMonth('op.fecha_carga',$mes)
        ->whereYear('op.fecha_carga',$anho)
        ->where('op.estado','=','TERMINADO')
        ->groupBy('opd.codigo_material')->get();

        $sumatoria = array();


        //----POR CADA PRODUCTO RECORRER SU RUTA PARA CALCULAR COSTOS DE MP SEGUN PRECIO DE COMPRA ----//
        foreach($producciones as $produccion)
        {
            
            $cantidad= $produccion->sumatoria;
            $codigo =  $produccion->codigo;            
            
            $mat_saliente= DB::connection('cg')->table('materiales_relacion')->where('materiales_relacion.codigo_material_entrante', $codigo)->get();
            foreach($mat_saliente as $mat){
                $material = $mat->codigo_material_saliente;
            }

            $descripcion= Materiales::on('cg')->select('desc_material')->where('cod_material',$material)->first();     
            $descripcion= $descripcion['desc_material'];

            // $precios=DB::select("SELECT ( (SELECT cd.precio_unitario 
            // FROM cg.compras_detalle as cd JOIN cg.compras as c on cd.id_compra=c.id_compras 
            // WHERE cd.codigo_material= f.codigo_material 
            // ORDER BY c.id_compras DESC LIMIT 1 ) * f.cantidad) as costo 
            // FROM cg.formulas as f WHERE f.codigo_material_saliente='". $material."'");
            // $sum = 0;
            // foreach($precios as $precio){
            //     $costo = $precio->costo;

            //     $costo = $costo* $cantidad;

            //     $sum = $sum + $costo;

            // } 
            $ruta = DB::connection('cg')->table('formulas as f')
        ->selectRaw('f.cantidad as cantidad, f.codigo_material as codigo')
        ->where('f.codigo_material_saliente','=',$material)->get();

        $sum = 0;
        foreach($ruta as $rut){
            $codigo = $rut->codigo;
            $cantidad = $rut->cantidad;

            $precios=DB::connection('cg')->table('compras_detalle as cd')
            ->selectRaw('cd.precio_unitario as precio')
            ->join('compras as c','c.id_compras','=','cd.id_compra')
            ->where('cd.codigo_material','=',$codigo)->get();

            foreach($precios as $prec){
                $total = $prec->precio;
            }

            $costo = $total * $cantidad;
            $sum = $sum+$costo;
        }

            $costo_mp_unitario = $sum / $cantidad;

            //------------------------INGRESAR COSTOS DE MATERIA PRIMA ------------------------------//

            // $contador = DB::select(" SELECT COUNT(*) AS cont FROM cg.costos_totales 
            // WHERE anho=$año AND mes = $mes AND cod_material= '".$codigo."' AND tipo_costo='Materia Prima'");

            $contador = DB::connection('cg')
            ->table('costos_totales')
            ->selectRaw('COUNT(*) as cont')
            ->where('anho',$año)
            ->where('mes',$mes)
            ->where('cod_material',$codigo)
            ->where('tipo_costo', 'Materia Prima')
            ->get();

            foreach($contador as $conta){
                $cont = $conta->cont;
            }
            

            if ($cont == 0) {
                $datos = array('tipo_costo'=>'Materia Prima','cod_material'=>$codigo,'mes'=>$mes,'anho'=>$anho,'precio_costo'=>$costo_mp_unitario);
                CostosTotales::on('cg')->insert($datos);
                
            }else{
                $datos = array('tipo_costo'=>'Materia Prima','cod_material'=>$codigo,'mes'=>$mes,'anho'=>$anho,'precio_costo'=>$costo_mp_unitario);
                CostosTotales::on('cg')->where('mes',$mes)->where('anho',$anho)->where('tipo_costo','Materia Prima')->update($datos);
            }


            //------------------------ SELECCIONAR OTROS COSTOS ------------------------------//


           // $costo_indirecto = DB::select("SELECT SUM(costo) as suma FROM cg.costos_indirectos WHERE anho=".$año." AND mes= ".$mes);
           
           $costo_indirecto = DB::connection('cg')->table('costos_indirectos')
           ->selectRaw('SUM(costo) as suma')
           ->where('anho',$año)
           ->where('mes',$mes)
           ->get();
           
           foreach($costo_indirecto as $costo){
                $costo_ind= $costo->suma;
            }
            
            // $producido = DB::select("SELECT SUM(opd.cantidad) as sumatoria
            //     FROM cg.orden_produccion_detalle as opd 
            //     JOIN cg.orden_produccion as op on op.id_orden=opd.id_orden
            //     WHERE MONTH(op.fecha_carga)=$mes AND YEAR(op.fecha_carga)=$año AND op.estado='TERMINADO'
            //     GROUP BY opd.codigo_material");

            $producido = DB::connection('cg')->table('orden_produccion_detalle as opd')
            ->selectRaw('SUM(opd.cantidad) as sumatoria')
            ->join('orden_produccion as op','op.id_orden','opd.id_orden')
            ->whereMonth('fecha_carga',$mes)
            ->whereYear('fecha_carga',$año)
            ->where('estado','TERMINADO')
            ->groupBy('opd.codigo_material')->get();

            foreach($producido as $prod){
                $cant_prod = $prod->sumatoria;
            }
            
            $total_ind = $costo_ind/$cant_prod;


            //------------------------INGRESAR OTROS COSTOS ------------------------------//

            // $contador = DB::select(" SELECT COUNT(*) AS cont FROM cg.costos_totales 
            // WHERE anho=$año AND mes = $mes AND cod_material= '$codigo'AND tipo_costo='Costos Indirectos'");

            $contador = DB::connection('cg')
            ->table('costos_totales')
            ->selectRaw('COUNT(*) as cont')
            ->where('anho',$año)
            ->where('mes',$mes)
            ->where('cod_material',$codigo)
            ->where('tipo_costo', 'Costos Indirectos')
            ->get();

            foreach($contador as $con){
                $cont = $con->cont;
            }
            
            if ($cont == 0) {
                $datos = array('tipo_costo'=>'Costos Indirectos','cod_material'=>$codigo,'mes'=>$mes,'anho'=>$anho,'precio_costo'=>$total_ind);
                CostosTotales::on('cg')->insert($datos);        
            }else{
                $datos = array('tipo_costo'=>'Costos Indirectos','cod_material'=>$codigo,'mes'=>$mes,'anho'=>$anho,'precio_costo'=>$total_ind);
                CostosTotales::on('cg')->where('mes',$mes)->where('anho',$anho)->where('tipo_costo','Costos Indirectos')->update($datos);
            }

            $total= $costo_mp_unitario+$total_ind;

            array_push($sumatoria, array(
                    'descripcion'=>$descripcion,
                    'codigo'=> $codigo,
                    'costo_mp'=>number_format($costo_mp_unitario,2,',','.'),
                    'costo_indirecto'=>number_format($total_ind,2,',','.'),
                    'costo_total'=> number_format($total,2,',','.')
                )
            );
        }

        return response()->json($sumatoria,200);

    }
}
