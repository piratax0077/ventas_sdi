<?php

namespace App\Http\Controllers;

use App\Plan;
use App\CatalogoSegmentos;
use App\ProductoSoftware;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;

class PlanesController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $planes = CatalogoSegmentos::filtrar(Plan::with('productosSoftware'), $request->query('segmento'))->get();
        return view('planes.index', ['planes' => $planes]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('planes.planes_create', ['software' => ProductoSoftware::orderBy('nombre')->get()]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $this->guardarPlan($request, new Plan());

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
        return view('planes.planes_edit', [
            'plan' => $plan,
            'software' => ProductoSoftware::orderBy('nombre')->get(),
        ]);
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
        $this->guardarPlan($request, $plan);

        return redirect()->route('planes.index')
            ->with('mensaje', 'Plan actualizado exitosamente');
    }

    private function guardarPlan(Request $request, Plan $plan): void
    {
        $validated = $request->validate([
            'nombre' => 'required|string|max:255',
            'nivel' => ['nullable', Rule::in(array_keys(Plan::NIVELES))],
            'descripcion' => 'required|string',
            'precio' => 'required|numeric|decimal:0,2|min:0|max:9999999.99',
            'precio_promocional' => 'nullable|numeric|decimal:0,2|min:0|lt:precio',
            'segmento_catalogo' => CatalogoSegmentos::reglas(),
            'frecuencia' => ['required', Rule::in(Plan::FRECUENCIAS)],
            'software_ids' => 'required|array|min:1',
            'software_ids.*' => 'required|integer|distinct|exists:productos_software,id',
            'cantidad_usuarios' => 'required|integer|min:1',
            'cantidad_dispositivos' => 'required|integer|min:1',
            'features' => 'nullable|string',
            'estado' => 'nullable|boolean'
        ]);

        $softwareIds = $validated['software_ids'];
        unset($validated['software_ids']);
        $validated['features'] = array_values(array_filter(array_map('trim', explode("\n", $validated['features'] ?? ''))));
        $validated['estado'] = $request->boolean('estado');

        DB::transaction(function () use ($plan, $validated, $softwareIds) {
            $plan->fill($validated)->saveOrFail();
            $plan->productosSoftware()->sync($softwareIds);
        });
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
    public function catalogo(Request $request)
    {
        $consulta = CatalogoSegmentos::filtrar(Plan::disponible()->with('productosSoftware'), $request->query('segmento'));
        $productoSeleccionado = null;
        if ($request->filled('software_id')) {
            $productoSeleccionado = ProductoSoftware::where('estado', true)->findOrFail($request->integer('software_id'));
            $consulta->whereHas('productosSoftware', fn ($software) => $software->whereKey($productoSeleccionado->id));
        }
        $frecuencia = $request->query('frecuencia');
        if (in_array($frecuencia, Plan::FRECUENCIAS, true)) {
            $consulta->where('frecuencia', $frecuencia);
        }
        $nivel = $request->query('nivel');
        if (is_string($nivel) && isset(Plan::NIVELES[$nivel])) {
            $consulta->where('nivel', $nivel);
        }
        $planes = $consulta->orderBy('nombre')->get();
        return view('vender.planes_catalogo', compact('planes', 'productoSeleccionado'));
    }
}
