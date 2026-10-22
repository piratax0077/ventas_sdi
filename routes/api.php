<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use App\Producto;
use App\Cliente;
use App\Venta;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/
Route::get("/status", function () {
    return response()->json(Auth::guard('api')->check());
});
Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});
Route::group(['prefix' => 'auth'], function () {
    Route::post('login', 'AuthController@login');
    Route::post('signup', 'AuthController@signup');

    Route::group(['middleware' => 'auth:api'], function() {
        Route::get('logout', 'AuthController@logout');
        Route::get('user', 'AuthController@user');

        Route::middleware('sales')->group(function () {
        Route::get("productos", function () {
            return response()->json(Producto::all());
        });
        /*
            Si existe un dios, que me perdone por dejar todas las peticiones aquí
            en lugar de separarlas a otro archivo o invocar un controlador
        */
        Route::post("/producto", function(Request $request){
            $producto = new Producto($request->input());
            $producto->saveOrFail();
            return response()->json(["data" => "true"]);
        });
        Route::get("/producto/{id}", function($id){
            $producto = Producto::findOrFail($id);
            return response()->json($producto);
        });
        Route::put("/producto", function(Request $request){
            $producto = Producto::findOrFail($request->input("id"));
            $producto->fill($request->input());
            $producto->saveOrFail();
            return response()->json(true);
        });
        Route::delete("/producto/{id}", function($id){
            $producto = Producto::findOrFail($id);
            $producto->delete();
            return response()->json(true);
        });

        // Clientes
        
        Route::get("clientes", function () {
            return response()->json(Cliente::all());
        });
        Route::post("/cliente", function(Request $request){
            $cliente = new Cliente($request->input());
            $cliente->saveOrFail();
            return response()->json(["data" => "true"]);
        });
        Route::get("/cliente/{id}", function($id){
            $cliente = Cliente::findOrFail($id);
            return response()->json($cliente);
        });
        Route::put("/cliente", function(Request $request){
            $cliente = Cliente::findOrFail($request->input("id"));
            $cliente->fill($request->input());
            $cliente->saveOrFail();
            return response()->json(true);
        });
        Route::delete("/cliente/{id}", function($id){
            $cliente = Cliente::findOrFail($id);
            $cliente->delete();
            return response()->json(true);
        });

        // Ventas
        Route::get("ventas", function () {
            return response()->json(Venta::with(["productos", "cliente"])->where('id_responsable', auth()->id())->get());
        });
        Route::post("/venta", function(Request $request){
            $data = $request->validate(['id_cliente' => 'required|integer|exists:clientes,id']);
            $venta = new Venta();
            $venta->id_cliente = $data['id_cliente'];
            $venta->id_responsable = $request->user()->id;
            $venta->saveOrFail();
            return response()->json(["data" => "true"]);
        });
        Route::get("/venta/{id}", function($id){
            $venta = Venta::with(["productos", "cliente"])->where('id_responsable', auth()->id())->findOrFail($id);
            return response()->json($venta);
        });
        Route::delete("/venta/{id}", function($id){
            $venta = Venta::where('id_responsable', auth()->id())->findOrFail($id);
            $venta->delete();
            return response()->json(true);
        });
        });
    });
});
