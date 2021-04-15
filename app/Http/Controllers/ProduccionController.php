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

class ProduccionController extends Controller
{
    public function index()
    {
        return view('pages.table');
    }
    public function nueva_orden(Request $request)
    {
        if($request->isMethod('get')){

            $materiales = DB::connection('cg')->table('materiales')->where('materiales.activo',1)->where('materiales.tipo_material',2)->get();

            return view('produccion.nueva_orden',['materiales'=>$materiales]);
        }if($request->isMethod('post')){
            $request= $request->all();
            $id=$request['id_material'];

            $datos = DB::connection('cg')->table('materiales')->where('materiales.cod_material', $id)->get();
        }
        
    }
    public function nueva_orden_datos(Request $request){
        $request= $request->all();
        $id=$request['id_material'];

        $datos = DB::connection('cg')->table('materiales')->where('materiales.cod_material', $id)->get();

        return response()->json(['datos'=>$datos],200);
    }

    public function detalle_orden(Request $request){
        $request= $request->all();

        $codigo=$request['cod'];

        $mat_saliente= DB::connection('cg')->table('materiales_relacion')->where('materiales_relacion.codigo_material_entrante', $codigo)->get();
        foreach($mat_saliente as $mat){
            $material = $mat->codigo_material_saliente;
        }

        $detalles = DB::connection('cg')->table('cg.formulas')->join('cg.materiales','cg.materiales.cod_material','=','cg.formulas.codigo_material')->where('cg.formulas.codigo_material_saliente','=',$material)->get();
        $listado= array();

        foreach($detalles as $detalle){
            $codigo      = $detalle->codigo_material;
            $descripcion = $detalle->desc_material;
            $cantidad    = $detalle->cantidad;
            $unidad      = $detalle->unidad_material;

            array_push($listado, array('codigo'=>$codigo,'descripcion'=>$descripcion,'cantidad'=>$cantidad,'unidad'=>$unidad));
        }
        
        return response()->json($listado, 200);
    }

    Public function guardar_orden(Request $request){

        $request = $request->all();
        $listado = $request['valores'];
        $fecha   = $request['fecha'];
        $usuario = $request['usuario'];

       
        if(is_null($fecha)){
            $texto= 'Favor indicar Fecha de Orden de Produccion'; 
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
            $codigo   = $list['codigo'];
            $cantidad = $list['cantidad'];

            if(!(is_null($codigo))){
                $mat_saliente= DB::connection('cg')->table('materiales_relacion')->where('materiales_relacion.codigo_material_entrante', $codigo)->get();
            
                foreach($mat_saliente as $mat){
                    $material = $mat->codigo_material_saliente;
                }
        
                $detalles = DB::connection('cg')->table('cg.formulas')->join('cg.materiales','cg.materiales.cod_material','=','cg.formulas.codigo_material')->where('cg.formulas.codigo_material_saliente','=',$material)->get();
    
                foreach($detalles as $detalle){
                    $codigo      = $detalle->codigo_material;
                    $uso    = $detalle->cantidad;
                    $descripcion = $detalle->desc_material;
    
                    $uso =(floatval($uso) * floatval($cantidad)) ;
    
                    $en_stock = DB::connection('cg')->table('cg.materiales_stock')->where('materiales_stock.codigo_material','=',$codigo)->get();

                    foreach($en_stock as $stock){
                        $stock_final= $stock->cantidad;
                        $stock_final = floatval($stock_final);
                    }

                    if($stock_final < $uso){
                        $texto= 'Stock insuficiente de '.($descripcion). '. Cantidad Disponible: '.($stock_final); 
                        $response =array('code'=>1,'msg'=>$texto);
                        return response()->json($response,200);
                    }
        
                }
            }
            
        }
  
        $estado='PROCESO';
        $datos = array('fecha_inicio'=>$fecha,'usuario'=>$usuario,'estado'=> $estado);
 
		try{
            $result = OrdenProduccion::on('cg')->insert($datos);
		}
		catch(Exception $e){
            $texto = 'Error al crear orden de produccion';
            $response =array('code'=>1,'msg'=>$texto);
            return response()->json($response,200);
        }
        
        $id_orden = OrdenProduccion::on('cg')->select('id_orden')->orderBy('id_orden','desc')->first();     
        $id_orden = $id_orden['id_orden'];


        foreach($listado as $list){
            $codigoP   = $list['codigo'];
            $cantidadP = $list['cantidad'];

            if(!(is_null($codigoP))){
                $mat_saliente= DB::connection('cg')->table('materiales_relacion')->where('materiales_relacion.codigo_material_entrante', $codigoP)->get();
            
                foreach($mat_saliente as $mat){
                    $material = $mat->codigo_material_saliente;
                }
        
                $detalles = DB::connection('cg')->table('cg.formulas')->join('cg.materiales','cg.materiales.cod_material','=','cg.formulas.codigo_material')->where('cg.formulas.codigo_material_saliente','=',$material)->get();
    
                foreach($detalles as $detalle){
                    $codigo = $detalle->codigo_material;
                    $uso    = $detalle->cantidad;

                    $uso =(floatval($uso) * floatval($cantidadP)) ;

                    $obs= "Orden de Produccion Nr. ".$id_orden;
                    $op='Ruta';

                    $datos_operacion = array('codigo_material'=>$codigo, 'cantidad'=>$uso,'operacion'=>$op, 'user'=> $usuario,'observacion'=>$obs );

                    $stock = MaterialesStock::on('cg')->where('codigo_material','=',$codigo)->get();

                    foreach($stock as $st){
                        $cant_stock = $st->cantidad;
                    }

                    $uso = $cant_stock-$uso;
                    $data = array('cantidad'=>$uso);

                    try{
                        $stock = MaterialesStock::on('cg')->where('codigo_material','=',$codigo)->update($data);
                    }catch(Exception $e){
                        $texto = 'Error al actualizar stock';
                        $response =array('code'=>1,'msg'=>$texto);
                        return response()->json($response,200);
                    }
                    
                    try{
                        $datos_operacion = MaterialesOperaciones::on('cg')->insert($datos_operacion);
                    }catch(Exception $e){
                        $texto = 'Error al cargar movimiento de materiales';
                        $response =array('code'=>1,'msg'=>$texto);
                        return response()->json($response,200);
                    }
                    
                }

                $detalle_orden = array('cantidad'=> $cantidadP,'codigo_material'=>$codigoP,'id_orden'=>$id_orden);
                try{
                    $detalle_orden = OrdenProduccionDetalle::on('cg')->insert($detalle_orden);
                    $id = $id_orden;
                }catch(Exception $e){
                    $texto = 'Error al cargar detalle de orden';
                    $limpiar = OrdenProduccionDetalle::on('cg')->where('id_orden','=',$id_orden)->delete();
                    $limpiar_orden = OrdenProduccion::on('cg')->where('id_orden','=',$id_orden)->delete();
                    $response =array('code'=>1,'msg'=>$texto);
                    return response()->json($response,200);
                }
               
            }
        }

        $response =array('code'=>0,'id'=>$id_orden);
        return response()->json($response,200);

    }

    Public function orden_print(Int $id){

        $datos_orden = OrdenProduccion::on('cg')->where('id_orden','=',$id)->get();

        $datos_gral = array();

        foreach($datos_orden as $orden){
            $user_orden = $orden->usuario;
            $fecha_orden = $orden->fecha_inicio;
            $fecha_orden = strtotime(DateTime::createFromFormat('Y-m-d', $fecha_orden)->format('d-m-Y'));
            $fecha_orden = date('d-m-Y',$fecha_orden);


            array_push($datos_gral,array('user'=>$user_orden,'fecha'=>$fecha_orden));
        }

        $detalle_orden = OrdenProduccionDetalle::on('cg')->where('id_orden','=',$id)->get();

        $lista_detalle = array();
        $acum= array();

        foreach($detalle_orden as $detalle){
            $codigo = $detalle->codigo_material;
            $descripcion = Materiales::on('cg')->where('cod_material','=',$codigo)->get();
            foreach($descripcion as $desc){
                $descripcion = $desc->desc_material;
                $unidad = $desc->unidad_material;
            }
            $cantidad = $detalle->cantidad;

            $mat_saliente= DB::connection('cg')->table('materiales_relacion')->where('materiales_relacion.codigo_material_entrante', $codigo)->get();
            foreach($mat_saliente as $mat){
                $material = $mat->codigo_material_saliente;
            }
            
            $detalles = DB::connection('cg')->table('cg.formulas')->join('cg.materiales','cg.materiales.cod_material','=','cg.formulas.codigo_material')->where('cg.formulas.codigo_material_saliente','=',$material)->get();
            $listado= array();
            

            foreach($detalles as $detalle){

                $codigoD      = $detalle->codigo_material;
                $descripcionD = $detalle->desc_material;
                $cantidadD    = $detalle->cantidad;
                $unidadD      = $detalle->unidad_material;

                $cantidadD = ($cantidad * $cantidadD);


                array_push($listado, array('codigo'=>$codigoD,'descripcion'=>$descripcionD,'cantidad'=>$cantidadD,'unidad'=>$unidadD));
                array_push($acum, array('codigo'=>$codigoD,'descripcion'=>$descripcionD,'cantidad'=>$cantidadD,'unidad'=>$unidadD));
            }
            
            array_push($lista_detalle,array('codigo'=>$codigo,'descripcion'=>$descripcion,'cantidad'=>$cantidad,'unidad'=>$unidad,'listado'=>$listado));
        
        }

        $final=array();

        foreach ($acum as $list) {

            $codigo= $list['codigo'];
            $cantidad=$list['cantidad'];
            $descripcion=$list['descripcion'];
            $unidad = $list['unidad'];

            $suma= 0;
            foreach ($acum as $li) {
              if($li['codigo'] == $codigo){
                $suma = $suma+ $li['cantidad'];
              }
            }
           
            $key = array_search($codigo, array_column($final, 'codigo'));

            if($key == 0 ){
                array_push($final, array('codigo'=>$codigo,'total'=>$suma, 'descripcion'=>$descripcion, 'unidad'=>$unidad));
            }
            
        }

        function unique_multidim_array($array, $key) {
            $temp_array = array();
            $i = 0;
            $key_array = array();
             
            foreach($array as $val) {
                if (!in_array($val[$key], $key_array)) {
                    $key_array[$i] = $val[$key];
                    $temp_array[$i] = $val;
                }
                $i++;
            }
            return $temp_array;
        }

        $details = unique_multidim_array($final,'codigo');

        return view('produccion.orden_print',['datos'=>$datos_gral,'id'=>$id,'detalle'=>$lista_detalle, 'totales'=>$details]);

    }
    public function orden_proceso()
    {
        $orden = OrdenProduccion::on('cg')->where('estado','PROCESO')->get();
        return view('produccion.orden_proceso',['listado'=>$orden]);
    }
    public function terminar_orden(Request $request){
        $request = $request->all();

       
        $fecha = $request['fecha'];
        $id    = $request['id'];
        $user  = $request['usuario'];
        

        if(is_null($fecha)){
            $texto= 'Favor indicar Fecha de Termino de Orden'; 
            $response =array('code'=>1,'msg'=>$texto);
            return response()->json($response,200);
        }

        $data = array('estado'=>'TERMINADO', 'fecha_fin'=>$fecha);

        try{
            OrdenProduccion::on('cg')->where('id_orden','=',$id)->update($data);
            $response =array('code'=>0);
        }catch(Exception $e){
            $texto = 'Error al actualizar orden de produccion';
            $response =array('code'=>1,'msg'=>$texto);
            return response()->json($response,200);
        }

        $detalle = OrdenProduccionDetalle::on('cg')->where('id_orden',$id)->get();


         foreach($detalle as $deta){

            $codigo   = $deta->codigo_material;
            $cantidad = $deta->cantidad;

            $cant=$cantidad;
            $contador = intval($cant);
            
            $mat_saliente= DB::connection('cg')->table('materiales_relacion')->where('materiales_relacion.codigo_material_entrante', $codigo)->get();

            foreach($mat_saliente as $mat){
                $material = $mat->codigo_material_saliente;
            }

            $deposito = 1;

         	for($i=0;$i<$contador;$i++){

                $fechas = strtotime($fecha);
                $month = date('m',$fechas);

                $contenedor = DB::select('select COUNT(*) as num from cg.lotes where MONTH(CAST(fecha_lote as DATE)) = '.$month.' and cod_material="'. $material.'"');
                foreach($contenedor as $cont){
                    $conta = $cont->num;
                }
                
                if(is_null($conta)){
                    $contenedor = 1;
                }else{
                    $contenedor = $conta +1;
                }

                $cant 		= 1;
                $en_stock	= 1;
                $justificacion = "INGRESO AL SISTEMA - ".$user;

                $dias = Materiales::on('cg')->select('dias_vencimiento')->where('cod_material',$material)->first();
                $dias = $dias['dias_vencimiento'];

                $fecha_vencimiento = date("Y-m-d",strtotime($fecha."+ ".$dias." days"));

                $data = array('usuario'=>$user, 'fecha_lote'=>$fecha, 
                'deposito'=>$deposito,'cod_material'=>$material, 
                'nro'=>$contenedor,'orden_produccion'=>$id, 
                'cantidad'=>$cant,'lote_nro'=>$id,'en_stock'=>$en_stock,'fecha_vencimiento'=>$fecha_vencimiento);

                try{
                    Lotes::on('cg')->insert($data); 
                }catch(Exception $e){
                    $texto = 'Error al ingresar lote';
                    $response =array('code'=>1,'msg'=>$texto);
                    return response()->json($response,200);
                }

                $lote = Lotes::on('cg')->select('id_lote')->orderBy('id_lote','desc')->first();
                $lote = $lote['id_lote'];

               
                $data = array('lote'=> $lote,'lote_operacion'=>$justificacion);

                try{
                    $operacion = LotesOperaciones::on('cg')->insert($data);
                    $response =array('code'=>0);
                }catch(Exception $e){
                    $texto = 'Error al ingresar detalle de lotes';
                    $response =array('code'=>1,'msg'=>$texto);
                    return response()->json($response,200);
                }

         	}
        }
         
        return response()->json($response,200);
    }

    public function anular_orden(Request $request){
        $request = $request->all();
        $id = $request['id'];
        $usuario = $request['usuario'];

        $data = array('estado'=>'ANULADO');

        try{
            OrdenProduccion::on('cg')->where('id_orden','=',$id)->update($data);
            $response =array('code'=>0);
        }catch(Exception $e){
            $texto = 'Error al actualizar orden de produccion';
            $response =array('code'=>1,'msg'=>$texto);
            return response()->json($response,200);
        }

        $detalle_orden = OrdenProduccionDetalle::on('cg')->where('id_orden','=',$id)->get();


        foreach($detalle_orden as $detalle){

            $codigo = $detalle->codigo_material;
            $cantidad = $detalle->cantidad;

            $mat_saliente= DB::connection('cg')->table('materiales_relacion')->where('materiales_relacion.codigo_material_entrante', $codigo)->get();
            foreach($mat_saliente as $mat){
                $material = $mat->codigo_material_saliente;
            }
            
            $detalles = DB::connection('cg')->table('cg.formulas')->where('cg.formulas.codigo_material_saliente','=',$material)->get();
            
            foreach($detalles as $detalle){

                $codigoD      = $detalle->codigo_material;
                $cantidadD    = $detalle->cantidad;

                $cantidadD = ($cantidad * $cantidadD);

                $obs= 'Alta de material por anulacion de OP nro.'.$id;

                $datos_operacion = array('codigo_material'=>$codigoD, 'cantidad'=>$cantidadD,'operacion'=>$id, 'user'=> $usuario,'observacion'=>$obs );

                $stock = MaterialesStock::on('cg')->where('codigo_material','=',$codigo)->get();

                foreach($stock as $st){
                    $cant_stock = $st->cantidad;
                }

                $uso = $cant_stock+$cantidadD;
                $data = array('cantidad'=>$uso);

                try{
                    $stock = MaterialesStock::on('cg')->where('codigo_material','=',$codigo)->update($data);
                    $response =array('code'=>0);
                }catch(Exception $e){
                    $texto = 'Error al actualizar stock';
                    $response =array('code'=>1,'msg'=>$texto);
                    return response()->json($response,200);
                }
                    
                try{
                    $datos_operacion = MaterialesOperaciones::on('cg')->insert($datos_operacion);
                    $response =array('code'=>0);
                }catch(Exception $e){
                    $texto = 'Error al cargar movimiento de materiales';
                    $response =array('code'=>1,'msg'=>$texto);
                    return response()->json($response,200);
                }
            }
        }
        return response()->json($response, 200);
    }

    public function buscar_orden(Request $request){

        $request = $request->all();
        $id = $request['id'];

        $orden = OrdenProduccion::on('cg')->where('estado','PROCESO')->where('id_orden',$id)->get();

        return response()->json($orden, 200);
    }

    public function imprimir_etiquetas(Int $id){
        $sql =DB::select( "SELECT l.fecha_lote as fecha_elab, l.cod_material as codigo, l.nro as lote, 
        l.orden_produccion as orden, l.cantidad as cantidad, m.desc_material as descri, l.fecha_vencimiento as fecha_vto
        FROM cg.lotes as l JOIN cg.materiales as m on m.cod_material = l.cod_material WHERE l.orden_produccion = $id");

        $listado = array();

        foreach($sql as $resp){

            $fecha_elab = $resp->fecha_elab;
            $codigo = $resp->codigo;
            $lote = $resp->lote;
            $orden = $resp->orden;
            $cantidad = $resp->cantidad;
            $descripcion = $resp->descri;
            $fecha_vencimiento = $resp->fecha_vto;

            array_push($listado,array('fecha_elab'=>$fecha_elab,'codigo'=>$codigo,'lote'=>$lote,'orden'=>$orden,'cantidad'=>$cantidad,'descripcion'=>$descripcion,'fecha_vto'=>$fecha_vencimiento));
        }

        return view('produccion.imprimir_etiquetas',['listado'=>$listado]);
    }

    public function orden_impresiones(){
        return view('produccion.orden_impresiones');
    }

    public function buscar_orden_impresion(Request $request){

        $request = $request->all();
        $action = $request['action'];
 
        if($action == 1){
            $fecha_inicio = $request['fecha_inicio'];
            $fecha_fin = $request['fecha_fin'];
            $estado = $request['estado'];

            if(is_null($fecha_inicio) || is_null($fecha_fin) || is_null($estado)){
                $texto = 'Complete todos los campos';
                $response =array('code'=>1,'msg'=>$texto);
                return response()->json($response,200);
            }
            if($estado == 'TODAS'){
                $orden = OrdenProduccion::on('cg')->where('fecha_inicio','>=',$fecha_inicio)->where('fecha_inicio','<=',$fecha_fin)->get();
            }else{
                $orden = OrdenProduccion::on('cg')->where('fecha_inicio','>=',$fecha_inicio)->where('fecha_inicio','<=',$fecha_fin)->where('estado',$estado)->get();
            }
        }else{
            $id = $request['id'];
            $orden = OrdenProduccion::on('cg')->where('id_orden',$id)->get();
        }

        return response()->json($orden, 200);
    }

    public function rutas(){

        $materiales = DB::connection('cg')->table('materiales')->where('materiales.activo',1)->where('materiales.tipo_material',2)->get();
        $mat_elegir = Materiales::on('cg')->where('tipo_material',1)->get();
        $mat_resultado = Materiales::on('cg')->where('tipo_material',3)->get();

        return view('produccion.rutas',['materiales'=>$materiales, 'elegir'=>$mat_elegir,'resultado'=>$mat_resultado]);
    }

    public function detalle_rutas(Request $request){
        $request = $request->all();
        $codigo = $request['codigo'];
        $material = '-';

        $mat_saliente= DB::connection('cg')->table('materiales_relacion')->where('materiales_relacion.codigo_material_entrante', $codigo)->get();
        foreach($mat_saliente as $mat){
            $material = $mat->codigo_material_saliente;
        }

        $mat_resultado = Materiales::on('cg')->where('cod_material',$material)->get();
        
        if($material == '-'){
            $mat_resultado = '-';
        }
        
            
        $detalles = DB::connection('cg')->table('cg.formulas')->join('cg.materiales','cg.materiales.cod_material','=','cg.formulas.codigo_material')->where('cg.formulas.codigo_material_saliente','=',$material)->get();

        return response()->json(['detalles'=>$detalles,'material'=>$mat_resultado], 200);

    }
    public function detalle_rutas_add(Request $request){
        $request = $request->all();
        $codigo = $request['codigo'];

        $mat_resultado = Materiales::on('cg')->where('cod_material',$codigo)->get();
        foreach($mat_resultado as $mat){
            $unidad = $mat->unidad_material;
        }
        
        return response()->json($unidad, 200);

    }

    public function guardar_resultado(Request $request){
        $request = $request->all();
        $codigo = $request['codigo'];
        $codigo_terminado = $request['codigo_resultado'];

        $data = array('codigo_material_entrante'=>$codigo,'codigo_material_saliente'=>$codigo_terminado);
        $material = Materiales::on('cg')->where('cod_material',$codigo_terminado)->get();

        try{
            MaterialesRelacion::on('cg')->insert($data);
            return response()->json($material,200);
        }catch(Exception $e){
            $texto = 'Se produjo un error';
            $response =array('code'=>1,'msg'=>$texto);
            return response()->json($response,-999);
        }
    }
    
    public function guardar_formula(Request $request){
        $request=$request->all();
        $codigo = $request['codigo'];
        $listado = $request['valores'];

        Formulas::on('cg')->where('codigo_material_saliente','=',$codigo)->delete();

        try{
            foreach($listado as $list){
                $codigo_material   = $list['codigo'];
                $cantidad = $list['cantidad'];
                if(!(is_null($codigo_material))){
                    $data = array('codigo_material'=>$codigo_material,'cantidad'=>$cantidad,'codigo_material_saliente'=>$codigo);
                    Formulas::on('cg')->insert($data);
                }
                
            }

            $response =array('code'=>0);
        }catch(Exception $e){
            $texto = 'Se produjo un error';
            $response =array('code'=>1,'msg'=>$texto);
            return response()->json($response,-999);
        }
        return response()->json($response,200);
    }

    public function lotes(){
        return view('produccion.lotes');
    }

    public function buscar_lote(Request $request){
        $request=$request->all();
        $lote = $request['lote'];

        $listado= DB::select('Select l.lote_nro as lote, l.id_lote as id, l.fecha_lote as fecha_elab,m.desc_material as descripcion,l.cantidad as cantidad FROM cg.lotes as l JOIN cg.materiales as m on m.cod_material=l.cod_material WHERE l.lote_nro='.$lote.' ORDER BY l.id_lote DESC LIMIT 50');
        return response()->json($listado,200);
    }

    public function detalle_lotes(Request $request){

        $request=$request->all();
        $lote = $request['id'];

        $listado = LotesOperaciones::on('cg')->where('lote',$lote)->get();

        return response()->json($listado,200);
    }

    public function modificar_lotes(Request $request){

        $request = $request->all();
        $id = $request['lote'];
        $action = $request['action'];
        $user = $request['user'];

        $data = array('en_stock'=>$action);
       

        $estado = Lotes::on('cg')->where('id_lote',$id)->get();
        foreach($estado as $est){
            $stock = $est->en_stock;
        }

        if($action == 1 && $stock == 0){
            Lotes::on('cg')->where('id_lote',$id)->update($data);
            $lote_operacion = 'ALTA DEL SISTEMA - '.$user;
            $data_operacion = array('lote'=>$id,'lote_operacion'=>$lote_operacion);
            LotesOperaciones::on('cg')->insert($data_operacion);
        }else if($action == 0 && $stock == 1){
            Lotes::on('cg')->where('id_lote',$id)->update($data);
            $lote_operacion = 'BAJA DEL SISTEMA - '.$user;
            $data_operacion = array('lote'=>$id,'lote_operacion'=>$lote_operacion);
            LotesOperaciones::on('cg')->insert($data_operacion);
        }else{
            if($stock == 0){
                $estado = 'BAJA';
            }else{
                $estado = 'ALTA';
            }

            $texto = 'Lote ya se encuentra de '.$estado;
            $response =array('code'=>1,'msg'=>$texto);
            return response()->json($response);
        }

        $response=array('code'=>0);
        return response()->json($response,200);
    }

    public function prueba(){

        $año=2021;
        $mes=05;
        $codigo = '01-01-01001';

        // $producido = DB::select("SELECT SUM(opd.cantidad) as sumatoria
        // FROM cg.orden_produccion_detalle as opd 
        // JOIN cg.orden_produccion as op on op.id_orden=opd.id_orden
        // WHERE MONTH(op.fecha_carga)=$mes AND YEAR(op.fecha_carga)=$año AND op.estado='TERMINADO'
        // GROUP BY opd.codigo_material");

        $producido = DB::connection('cg')->table('orden_produccion_detalle as opd')
        ->selectRaw('SUM(opd.cantidad) as sumatoria')
        ->join('orden_produccion as op','op.id_orden','opd.id_orden')
        ->whereMonth('fecha_carga',$mes)
        ->whereYear('fecha_carga',$año)
        ->where('estado','TERMINADO')
        ->groupBy('opd.codigo_material')->get();
        
        print_r($producido);
        //$response = array('listado'=>$listado,'total'=>$total,'code'=>0);
       
    //    $producciones= DB::connection('cgdb')->table('materiales_relacion as mr')->selectRaw('opd.codigo_material as codigo, SUM(opd.cantidad) as sumatoria ')
    //    ->join('orden_produccion_detalle as opd','opd.codigo_material','=','mr.codigo_material_entrante')
    //    ->join('orden_produccion as op','op.id_orden','=','opd.id_orden')
    //    ->join('materiales as m','m.cod_material','=','mr.codigo_material_saliente')
    //    ->whereMonth('op.fecha_carga',2)
    //    ->whereYear('op.fecha_carga',2021)
    //    ->where('op.estado','=','TERMINADO')
    //    ->groupBy('opd.codigo_material')->get();

    //     $sumatoria = array();
    //     print_r($producciones);

       
         
    }
}