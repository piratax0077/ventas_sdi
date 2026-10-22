<?php

namespace App\Http\Controllers;

use App\Reporte;
use Illuminate\Http\Request;

class ReportesController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $reportes = Reporte::all();
        $tipos = ['mensual', 'trimestral', 'anual', 'personalizado'];

        return view('reportes.index', compact('reportes', 'tipos'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('reportes.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'nombre' => 'required|string|max:255',
            'descripcion' => 'nullable|string',
            'tipo' => 'required|in:mensual,trimestral,anual,personalizado',
            'frecuencia' => 'required|in:diaria,semanal,mensual,trimestral,anual',
            'destinatarios' => 'nullable|string',
            'contenido' => 'nullable|string',
            'estado' => 'boolean'
        ]);

        // Validar que destinatarios sean emails válidos si se proporcionan
        if (!empty($validated['destinatarios'])) {
            $emails = array_map('trim', explode(',', $validated['destinatarios']));
            foreach ($emails as $email) {
                if ($email && !filter_var($email, FILTER_VALIDATE_EMAIL)) {
                    return redirect()->back()
                        ->withErrors(['destinatarios' => "El email '$email' no es válido"])
                        ->withInput();
                }
            }
        }

        $validated['estado'] = $request->boolean('estado'); Reporte::create($validated);

        return redirect()->route('reportes.index')
            ->with('success', 'Reporte creado exitosamente');
    }

    /**
     * Display the specified resource.
     */
    public function show(Reporte $reporte)
    {
        return view('reportes.show', compact('reporte'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Reporte $reporte)
    {
        return view('reportes.edit', compact('reporte'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Reporte $reporte)
    {
        $validated = $request->validate([
            'nombre' => 'required|string|max:255',
            'descripcion' => 'nullable|string',
            'tipo' => 'required|in:mensual,trimestral,anual,personalizado',
            'frecuencia' => 'required|in:diaria,semanal,mensual,trimestral,anual',
            'destinatarios' => 'nullable|string',
            'contenido' => 'nullable|string',
            'estado' => 'boolean'
        ]);

        // Validar que destinatarios sean emails válidos si se proporcionan
        if (!empty($validated['destinatarios'])) {
            $emails = array_map('trim', explode(',', $validated['destinatarios']));
            foreach ($emails as $email) {
                if ($email && !filter_var($email, FILTER_VALIDATE_EMAIL)) {
                    return redirect()->back()
                        ->withErrors(['destinatarios' => "El email '$email' no es válido"])
                        ->withInput();
                }
            }
        }

        $validated['estado'] = $request->boolean('estado'); $reporte->update($validated);

        return redirect()->route('reportes.show', $reporte)
            ->with('success', 'Reporte actualizado exitosamente');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Reporte $reporte)
    {
        $reporte->delete();

        return redirect()->route('reportes.index')
            ->with('success', 'Reporte eliminado exitosamente');
    }
}
