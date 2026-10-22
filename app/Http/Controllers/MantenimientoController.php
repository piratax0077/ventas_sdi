<?php

namespace App\Http\Controllers;

use App\Mantenimiento;
use Illuminate\Http\Request;

class MantenimientoController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $mantenimientos = Mantenimiento::latest()->paginate(15);
        return view('mantenimiento.index', compact('mantenimientos'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $tipos = ['servidor', 'sistema', 'software', 'hardware', 'red', 'otro'];
        $estados = ['pendiente', 'activo', 'completado', 'cancelado'];
        return view('mantenimiento.create', compact('tipos', 'estados'));
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
            'descripcion' => 'required|string|min:5|max:1000',
            'tipo' => 'required|in:servidor,sistema,software,hardware,red,otro',
            'fecha_inicio' => 'required|date',
            'fecha_fin' => 'nullable|date|after_or_equal:fecha_inicio',
            'estado' => 'required|in:pendiente,activo,completado,cancelado',
            'responsable' => 'nullable|string|max:255',
            'notas' => 'nullable|string|max:5000'
        ]);

        Mantenimiento::create($validated);

        return redirect()->route('mantenimiento.index')
            ->with('mensaje', 'Mantenimiento registrado correctamente')
            ->with('tipo', 'success');
    }

    /**
     * Display the specified resource.
     *
     * @param  Mantenimiento  $mantenimiento
     * @return \Illuminate\Http\Response
     */
    public function show(Mantenimiento $mantenimiento)
    {
        return view('mantenimiento.show', compact('mantenimiento'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  Mantenimiento  $mantenimiento
     * @return \Illuminate\Http\Response
     */
    public function edit(Mantenimiento $mantenimiento)
    {
        $tipos = ['servidor', 'sistema', 'software', 'hardware', 'red', 'otro'];
        $estados = ['pendiente', 'activo', 'completado', 'cancelado'];
        return view('mantenimiento.edit', compact('mantenimiento', 'tipos', 'estados'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  Mantenimiento  $mantenimiento
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Mantenimiento $mantenimiento)
    {
        $validated = $request->validate([
            'descripcion' => 'required|string|min:5|max:1000',
            'tipo' => 'required|in:servidor,sistema,software,hardware,red,otro',
            'fecha_inicio' => 'required|date',
            'fecha_fin' => 'nullable|date|after_or_equal:fecha_inicio',
            'estado' => 'required|in:pendiente,activo,completado,cancelado',
            'responsable' => 'nullable|string|max:255',
            'notas' => 'nullable|string|max:5000'
        ]);

        $mantenimiento->update($validated);

        return redirect()->route('mantenimiento.show', $mantenimiento->id)
            ->with('mensaje', 'Mantenimiento actualizado correctamente')
            ->with('tipo', 'success');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  Mantenimiento  $mantenimiento
     * @return \Illuminate\Http\Response
     */
    public function destroy(Mantenimiento $mantenimiento)
    {
        $mantenimiento->delete();

        return redirect()->route('mantenimiento.index')
            ->with('mensaje', 'Mantenimiento eliminado correctamente')
            ->with('tipo', 'success');
    }

    /**
     * Estadísticas
     */
    public function estadisticas()
    {
        return [
            'pendientes' => Mantenimiento::pendientes()->count(),
            'activos' => Mantenimiento::activos()->count(),
            'completados' => Mantenimiento::where('estado', 'completado')->count(),
            'por_tipo' => Mantenimiento::select('tipo')
                ->selectRaw('count(*) as cantidad')
                ->groupBy('tipo')
                ->get()
        ];
    }
}
