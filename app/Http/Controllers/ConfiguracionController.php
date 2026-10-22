<?php

namespace App\Http\Controllers;

use App\Configuracion;
use Illuminate\Http\Request;

class ConfiguracionController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $configuraciones = Configuracion::orderBy('categoria')->orderBy('clave')->get();
        $categorias = Configuracion::distinct('categoria')->pluck('categoria')->toArray();

        return view('configuracion.index', [
            'configuraciones' => $configuraciones,
            'categorias' => $categorias
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('configuracion.create');
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
            'clave' => 'required|string|max:255|unique:configuraciones',
            'valor' => 'nullable|string',
            'descripcion' => 'nullable|string',
            'tipo' => 'required|in:text,email,url,boolean,number,textarea',
            'categoria' => 'required|in:general,empresa,email,sitio,sistema,otro',
            'estado' => 'nullable|boolean'
        ]);

        $validated['estado'] = $request->boolean('estado');

        Configuracion::create($validated);

        return redirect()->route('configuracion.index')
            ->with('mensaje', 'Configuración creada exitosamente');
    }

    /**
     * Display the specified resource.
     *
     * @param  Configuracion  $configuracion
     * @return \Illuminate\Http\Response
     */
    public function show(Configuracion $configuracion)
    {
        return view('configuracion.show', ['configuracion' => $configuracion]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  Configuracion  $configuracion
     * @return \Illuminate\Http\Response
     */
    public function edit(Configuracion $configuracion)
    {
        return view('configuracion.edit', ['configuracion' => $configuracion]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  Configuracion  $configuracion
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Configuracion $configuracion)
    {
        $validated = $request->validate([
            'clave' => 'required|string|max:255|unique:configuraciones,clave,' . $configuracion->id,
            'valor' => 'nullable|string',
            'descripcion' => 'nullable|string',
            'tipo' => 'required|in:text,email,url,boolean,number,textarea',
            'categoria' => 'required|in:general,empresa,email,sitio,sistema,otro',
            'estado' => 'nullable|boolean'
        ]);

        $validated['estado'] = $request->boolean('estado');

        $configuracion->update($validated);

        return redirect()->route('configuracion.index')
            ->with('mensaje', 'Configuración actualizada exitosamente');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  Configuracion  $configuracion
     * @return \Illuminate\Http\Response
     */
    public function destroy(Configuracion $configuracion)
    {
        $configuracion->delete();

        return redirect()->route('configuracion.index')
            ->with('mensaje', 'Configuración eliminada exitosamente');
    }
}

