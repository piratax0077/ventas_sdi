<?php

namespace App\Http\Controllers;

use App\ProductoSoftware;
use Illuminate\Http\Request;

class ProductosSoftwareController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $productosSoftware = ProductoSoftware::all();
        return view('productos_software.index', ['productosSoftware' => $productosSoftware]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('productos_software.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'nombre' => 'required|string|max:255|unique:productos_software',
            'descripcion' => 'required|string',
            'versión' => 'nullable|string|max:50',
            'tipo' => 'required|in:web,desktop,mobile,api,otro',
            'url_descarga' => 'nullable|url',
            'estado' => 'nullable|boolean'
        ]);

        $validated['estado'] = $request->boolean('estado');

        ProductoSoftware::create($validated);

        return redirect()->route('productos-software.index')
            ->with('mensaje', 'Producto software creado exitosamente');
    }

    /**
     * Display the specified resource.
     *
     * @param  ProductoSoftware  $productoSoftware
     * @return \Illuminate\Http\Response
     */
    public function show(ProductoSoftware $productoSoftware)
    {
        return view('productos_software.show', ['productoSoftware' => $productoSoftware]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  ProductoSoftware  $productoSoftware
     * @return \Illuminate\Http\Response
     */
    public function edit(ProductoSoftware $productoSoftware)
    {
        return view('productos_software.edit', ['productoSoftware' => $productoSoftware]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  ProductoSoftware  $productoSoftware
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, ProductoSoftware $productoSoftware)
    {
        $validated = $request->validate([
            'nombre' => 'required|string|max:255|unique:productos_software,nombre,' . $productoSoftware->id,
            'descripcion' => 'required|string',
            'versión' => 'nullable|string|max:50',
            'tipo' => 'required|in:web,desktop,mobile,api,otro',
            'url_descarga' => 'nullable|url',
            'estado' => 'nullable|boolean'
        ]);

        $validated['estado'] = $request->boolean('estado');

        $productoSoftware->update($validated);

        return redirect()->route('productos-software.index')
            ->with('mensaje', 'Producto software actualizado exitosamente');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  ProductoSoftware  $productoSoftware
     * @return \Illuminate\Http\Response
     */
    public function destroy(ProductoSoftware $productoSoftware)
    {
        $productoSoftware->delete();

        return redirect()->route('productos-software.index')
            ->with('mensaje', 'Producto software eliminado exitosamente');
    }
}
