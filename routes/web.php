<?php

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;


Route::get('/', function () {
    return redirect()->route("home");
});
Route::get("/acerca-de", function () {
    return view("misc.acerca_de");
})->name("acerca_de.index");

Auth::routes([
    'register' => false,
    "reset" => false,// no pueden olvidar contraseña
]);

Route::get('/home', 'HomeController@index')->name('home');
// Permitir logout con petición get
Route::get("/logout", function (\Illuminate\Http\Request $request) {
    Auth::logout();
    $request->session()->invalidate();
    $request->session()->regenerateToken();
    return redirect()->route("home");
})->name("logout.get");


Route::middleware(['auth', 'sales'])
    ->group(function () {
        Route::get('/perfil', 'UserController@perfil')->name('perfil');
        Route::put('/perfil', 'UserController@updatePerfil')->name('perfil.update');

        Route::resource("clientes", "ClientesController");
        Route::resource("usuarios", "UserController")->parameters(["usuarios" => "user"]);
        Route::resource("productos", "ProductosController");
        Route::resource("mantenimiento", "MantenimientoController")->parameters(["mantenimiento" => "mantenimiento"]);
        Route::resource("productos-software", "ProductosSoftwareController")->parameters(["productos-software" => "productoSoftware"]);
        Route::resource("planes", "PlanesController")->parameters(["planes" => "plan"]);
        Route::get("/planes-catalogo", "PlanesController@catalogo")->name("planes.catalogo");
        Route::resource("configuracion","ConfiguracionController")->parameters(["configuracion" => "configuracion"]);
        Route::resource("reportes", "ReportesController")->parameters(["reportes" => "reporte"]);
        Route::get("/ventas/ticket", "VentasController@ticket")->name("ventas.ticket");
        Route::get("/ventas/ticket-pdf/{id}", "VentasController@ticketPdf")->name("ventas.ticket.pdf");
        Route::resource("ventas", "VentasController")->only(['index', 'show', 'destroy']);
        Route::get("/vender", "VenderController@index")->name("vender.index");
        Route::post("/productoDeVenta", "VenderController@agregarProductoVenta")->name("agregarProductoVenta");
        Route::delete("/productoDeVenta", "VenderController@quitarProductoDeVenta")->name("quitarProductoDeVenta");
        Route::post("/terminarOCancelarVenta", "VenderController@terminarOCancelarVenta")->name("terminarOCancelarVenta");
        Route::get('/buscar-ciudad-region', 'HomeController@buscarCiudadRegion')->name('home.buscar_ciudad_region');
        Route::get('/buscar-especialidad', 'HomeController@buscarEspecialidad')->name('home.buscar_especialidad');
        Route::get('/buscar-sub-especialidad', 'HomeController@buscarSubEspecialidad')->name('home.buscar_sub_tipo_especialidad');
        Route::get('/buscar-subtipo-institucion', 'HomeController@buscarSubtipoInstitucion')->name('home.buscar_subtipo_institucion');

        // Rutas de Soporte
        Route::get('/soporte', 'SoporteController@index')->name('soporte.index');
        Route::post('/soporte', 'SoporteController@store')->name('soporte.store');
        Route::post('/soporte/upload', 'SoporteController@upload')->name('soporte.upload');
        Route::get('/soporte/{id}', 'SoporteController@show')->name('soporte.show');
        Route::post('/soporte/{id}/responder', 'SoporteController@responder')->name('soporte.responder');
        Route::post('/soporte/{id}/estado', 'SoporteController@cambiarEstado')->name('soporte.cambiar-estado');
        Route::get('/mis-solicitudes', 'SoporteController@misSolicitudes')->name('soporte.mis-solicitudes');
    });
