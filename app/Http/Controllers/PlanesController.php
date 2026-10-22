<?php

namespace App\Http\Controllers;

use App\Plan;
use App\Cliente;
use Illuminate\Http\Request;

class PlanesController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $planes = Plan::all();
        return view('planes.index', ['planes' => $planes]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('planes.planes_create');
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
            'nombre' => 'required|string|max:255',
            'descripcion' => 'required|string',
            'precio' => 'required|numeric|min:0',
            'frecuencia' => 'required|in:mensual,trimestral,semestral,anual',
            'cantidad_usuarios' => 'required|integer|min:1',
            'cantidad_dispositivos' => 'required|integer|min:1',
            'features' => 'nullable|string',
            'estado' => 'nullable|boolean'
        ]);

        // Convertir features de texto a array
        if (!empty($validated['features'])) {
            $validated['features'] = array_filter(
                array_map('trim', explode("\n", $validated['features']))
            );
        } else {
            $validated['features'] = [];
        }

        // Establecer estado por defecto
        $validated['estado'] = $request->boolean('estado');

        Plan::create($validated);

        return redirect()->route('planes.index')
            ->with('mensaje', 'Plan creado exitosamente');
    }

    /**
     * Display the specified resource.
     *
     * @param  Plan  $plan
     * @return \Illuminate\Http\Response
     */
    public function show(Plan $plan)
    {
        return view('planes.planes_show', ['plan' => $plan]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  Plan  $plan
     * @return \Illuminate\Http\Response
     */
    public function edit(Plan $plan)
    {
        return view('planes.planes_edit', ['plan' => $plan]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  Plan  $plan
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Plan $plan)
    {
        $validated = $request->validate([
            'nombre' => 'required|string|max:255',
            'descripcion' => 'required|string',
            'precio' => 'required|numeric|min:0',
            'frecuencia' => 'required|in:mensual,trimestral,semestral,anual',
            'cantidad_usuarios' => 'required|integer|min:1',
            'cantidad_dispositivos' => 'required|integer|min:1',
            'features' => 'nullable|string',
            'estado' => 'nullable|boolean'
        ]);

        // Convertir features de texto a array
        if (!empty($validated['features'])) {
            $validated['features'] = array_filter(
                array_map('trim', explode("\n", $validated['features']))
            );
        } else {
            $validated['features'] = [];
        }

        // Establecer estado
        $validated['estado'] = $request->boolean('estado');

        $plan->update($validated);

        return redirect()->route('planes.index')
            ->with('mensaje', 'Plan actualizado exitosamente');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  Plan  $plan
     * @return \Illuminate\Http\Response
     */
    public function destroy(Plan $plan)
    {
        $plan->delete();
        return redirect()->route('planes.index')
            ->with('mensaje', 'Plan eliminado exitosamente');
    }

    /**
     * Show the sales catalog for customers
     *
     * @return \Illuminate\Http\Response
     */
    public function catalogo()
    {
        $planes = Plan::where('estado', 1)->get();
        $clientes = Cliente::orderBy('nombre')->get();
        return view('vender.planes_catalogo', compact('planes', 'clientes'));
    }
}
