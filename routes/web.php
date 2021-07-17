<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', 'App\Http\Controllers\HomeController@index')->name('home')->middleware('auth');

Route::get('/home', 'App\Http\Controllers\HomeController@index')->name('home')->middleware('auth');
Auth::routes();

//Route::view('/home', 'App\Http\Controllers\HomeController@index')->name('home');
Route::get('/prueba', 'App\Http\Controllers\ProduccionController@prueba')->name('prueba');
//------------------------------PRODUCCION----------------------------//
//******************************NUEVA ORDEN***************************//
Route::get('/nueva_orden', 'App\Http\Controllers\ProduccionController@nueva_orden')->name('nueva_orden')->middleware('auth');
Route::post( '/nueva_orden', 'App\Http\Controllers\ProduccionController@nueva_orden_datos')->name('nueva_orden_datos')->middleware('auth');
Route::post( '/detalle_orden', 'App\Http\Controllers\ProduccionController@detalle_orden')->name('detalle_orden')->middleware('auth');
Route::post( '/guardar_orden', 'App\Http\Controllers\ProduccionController@guardar_orden')->name('guardar_orden')->middleware('auth');
Route::get('/orden_print/{id}', 'App\Http\Controllers\ProduccionController@orden_print')->name('orden_print')->middleware('auth');

//******************************ORDEN EN PROCESO***************************//
Route::get('/orden_proceso', 'App\Http\Controllers\ProduccionController@orden_proceso')->name('orden_proceso')->middleware('auth');
Route::post('/terminar_orden', 'App\Http\Controllers\ProduccionController@terminar_orden')->name('terminar_orden')->middleware('auth');
Route::get('/imprimir_etiquetas/{id}', 'App\Http\Controllers\ProduccionController@imprimir_etiquetas')->name('imprimir_etiquetas')->middleware('auth');
Route::post('/buscar_orden', 'App\Http\Controllers\ProduccionController@buscar_orden')->name('buscar_orden')->middleware('auth');
Route::post('/anular_orden', 'App\Http\Controllers\ProduccionController@anular_orden')->name('anular_orden')->middleware('auth');
//******************************IMPRESION***************************//
Route::get('/orden_impresiones', 'App\Http\Controllers\ProduccionController@orden_impresiones')->name('orden_impresiones')->middleware('auth');
Route::post('/buscar_orden_impresion', 'App\Http\Controllers\ProduccionController@buscar_orden_impresion')->name('buscar_orden_impresion')->middleware('auth');
//******************************RUTAS***************************//
Route::get('/rutas', 'App\Http\Controllers\ProduccionController@rutas')->name('rutas')->middleware('auth');
Route::post('/detalle_rutas', 'App\Http\Controllers\ProduccionController@detalle_rutas')->name('detalle_rutas')->middleware('auth');
Route::post('/detalle_rutas_add', 'App\Http\Controllers\ProduccionController@detalle_rutas_add')->name('detalle_rutas_add')->middleware('auth');
Route::post('/guardar_resultado', 'App\Http\Controllers\ProduccionController@guardar_resultado')->name('guardar_resultado')->middleware('auth');
Route::post('/guardar_formula', 'App\Http\Controllers\ProduccionController@guardar_formula')->name('guardar_formula')->middleware('auth');
//******************************CONTROL DE LOTES***************************//
Route::get('/lotes', 'App\Http\Controllers\ProduccionController@lotes')->name('lotes')->middleware('auth');
Route::post('/buscar_lote', 'App\Http\Controllers\ProduccionController@buscar_lote')->name('buscar_lote')->middleware('auth');
Route::post('/detalle_lotes', 'App\Http\Controllers\ProduccionController@detalle_lotes')->name('detalle_lotes')->middleware('auth');
Route::post('/modificar_lotes', 'App\Http\Controllers\ProduccionController@modificar_lotes')->name('modificar_lotes')->middleware('auth');

//------------------------------STOCK----------------------------//
//******************************STOCK MATERIALES***************************//
Route::get('/stock_materiales', 'App\Http\Controllers\StockController@stock_materiales')->name('stock_materiales')->middleware('auth');
//******************************STOCK LOTES***************************//
Route::get('/stock_lotes', 'App\Http\Controllers\StockController@stock_lotes')->name('stock_lotes')->middleware('auth');
Route::post('/detalle_lotes_stock', 'App\Http\Controllers\StockController@detalle_lotes_stock')->name('detalle_lotes_stock')->middleware('auth');
//******************************INVENTARIO MATERIALES***************************//
Route::get('/inventario_materiales', 'App\Http\Controllers\StockController@inventario_materiales')->name('inventario_materiales')->middleware('auth');
Route::post('/guardar_inventario_materiales', 'App\Http\Controllers\StockController@guardar_inventario_materiales')->name('guardar_inventario_materiales')->middleware('auth');
Route::get('/imprimir_inventario_materiales/{fecha}', 'App\Http\Controllers\StockController@imprimir_inventario_materiales')->name('imprimir_inventario_materiales')->middleware('auth');
//******************************INVENTARIO LOTES***************************//
Route::get('/inventario_lotes', 'App\Http\Controllers\StockController@inventario_lotes')->name('inventario_lotes')->middleware('auth');
Route::post('/guardar_inventario_lotes', 'App\Http\Controllers\StockController@guardar_inventario_lotes')->name('guardar_inventario_lotes')->middleware('auth');
Route::get('/imprimir_inventario_lotes/{fecha}', 'App\Http\Controllers\StockController@imprimir_inventario_lotes')->name('imprimir_inventario_lotes')->middleware('auth');
//******************************INVENTARIO IMPRESIONES***************************//
Route::get('/inventario_impresiones', 'App\Http\Controllers\StockController@inventario_impresiones')->name('inventario_impresiones')->middleware('auth');
Route::post('/buscar_inventario_impresiones', 'App\Http\Controllers\StockController@buscar_inventario_impresiones')->name('buscar_inventario_impresiones')->middleware('auth');
//******************************MOVIMIENTO MATERIALES***************************//
Route::get('/movimiento_materiales', 'App\Http\Controllers\StockController@movimiento_materiales')->name('movimiento_materiales')->middleware('auth');
Route::post('/guardar_movimiento', 'App\Http\Controllers\StockController@guardar_movimiento')->name('guardar_movimiento')->middleware('auth');
Route::get('/imprimir_inventario_movimiento/{id}', 'App\Http\Controllers\StockController@imprimir_inventario_movimiento')->name('imprimir_inventario_movimiento')->middleware('auth');

//------------------------------PRODUCTOS----------------------------//
Route::get('/productos', 'App\Http\Controllers\ProductosController@productos')->name('productos')->middleware('auth');
Route::post('/guardar_productos', 'App\Http\Controllers\ProductosController@guardar_productos')->name('guardar_productos')->middleware('auth');
Route::post('/inhabilitar_productos', 'App\Http\Controllers\ProductosController@inhabilitar_productos')->name('inhabilitar_productos')->middleware('auth');
Route::post('/habilitar_productos', 'App\Http\Controllers\ProductosController@habilitar_productos')->name('habilitar_productos')->middleware('auth');
Route::post('/guardar_edicion_productos', 'App\Http\Controllers\ProductosController@guardar_edicion_productos')->name('guardar_edicion_productos')->middleware('auth');

//------------------------------COMPRAS----------------------------//
//******************************NUEVA COMPRA***************************//
Route::get('/nueva_compra', 'App\Http\Controllers\ComprasController@nueva_compra')->name('nueva_compra')->middleware('auth');
Route::post('/guardar_compra', 'App\Http\Controllers\ComprasController@guardar_compra')->name('guardar_compra')->middleware('auth');
Route::get('/imprimir_compra/{id}', 'App\Http\Controllers\ComprasController@imprimir_compra')->name('imprimir_compra')->middleware('auth');
Route::get('/impresiones_compra', 'App\Http\Controllers\ComprasController@impresiones_compra')->name('impresiones_compra')->middleware('auth');
Route::post('/buscar_compra', 'App\Http\Controllers\ComprasController@buscar_compra')->name('buscar_compra')->middleware('auth');


//------------------------------ENVIOS----------------------------//
//******************************NUEVO ENVIO***************************//
Route::get('/nuevo_envio', 'App\Http\Controllers\EnviosController@nuevo_envio')->name('nuevo_envio')->middleware('auth');
Route::post('/guardar_envio', 'App\Http\Controllers\EnviosController@guardar_envio')->name('guardar_envio')->middleware('auth');
Route::get('/imprimir_envio/{id}', 'App\Http\Controllers\EnviosController@imprimir_envio')->name('imprimir_envio')->middleware('auth');
Route::get('/envios_impresiones', 'App\Http\Controllers\EnviosController@envios_impresiones')->name('envios_impresiones')->middleware('auth');
Route::post('/buscar_envios', 'App\Http\Controllers\EnviosController@buscar_envios')->name('buscar_envios')->middleware('auth');

//------------------------------FACTURAS----------------------------//
Route::get('/factura/{id}/{tipo}', 'App\Http\Controllers\FacturaController@factura')->name('factura')->middleware('auth');
Route::get('/lista_facturas', 'App\Http\Controllers\FacturaController@lista_facturas')->name('lista_facturas')->middleware('auth');
Route::post('/buscar_facturas', 'App\Http\Controllers\FacturaController@buscar_facturas')->name('buscar_facturas')->middleware('auth');

//------------------------------COSTOS----------------------------//
//******************************CARGAR COSTOS***************************//
Route::get('/cargar_costos', 'App\Http\Controllers\CostosController@cargar_costos')->name('cargar_costos')->middleware('auth');
Route::post('/guardar_costos', 'App\Http\Controllers\CostosController@guardar_costos')->name('guardar_costos')->middleware('auth');
//******************************CONSULTAR COSTOS***************************//
Route::get('/consultar_costos', 'App\Http\Controllers\CostosController@consultar_costos')->name('consultar_costos')->middleware('auth');
Route::post('/buscar_costos', 'App\Http\Controllers\CostosController@buscar_costos')->name('buscar_costos')->middleware('auth');
//******************************CALCULAR COSTOS***************************//
Route::get('/productos_costos', 'App\Http\Controllers\CostosController@productos_costos')->name('productos_costos')->middleware('auth');
Route::post('/calcular_costos', 'App\Http\Controllers\CostosController@calcular_costos')->name('calcular_costos')->middleware('auth');

//------------------------------CLIENTES----------------------------//
Route::get('/clientes', 'App\Http\Controllers\ClientesController@clientes')->name('clientes');
Route::post('/guardar_cliente', 'App\Http\Controllers\ClientesController@guardar_cliente')->name('guardar_cliente');
Route::post('/guardar_edicion_cliente', 'App\Http\Controllers\ClientesController@guardar_edicion_cliente')->name('guardar_edicion_cliente');
Route::post('/eliminar_cliente', 'App\Http\Controllers\ClientesController@eliminar_cliente')->name('eliminar_cliente');


Route::group(['middleware' => 'auth'], function () {
	Route::resource('user', 'App\Http\Controllers\UserController', ['except' => ['show']]);
	Route::get('profile', ['as' => 'profile.edit', 'uses' => 'App\Http\Controllers\ProfileController@edit']);
	Route::put('profile', ['as' => 'profile.update', 'uses' => 'App\Http\Controllers\ProfileController@update']);
	Route::put('profile/password', ['as' => 'profile.password', 'uses' => 'App\Http\Controllers\ProfileController@password']);
	Route::get('{page}', ['as' => 'page.index', 'uses' => 'App\Http\Controllers\PageController@index']);
	
});
	

