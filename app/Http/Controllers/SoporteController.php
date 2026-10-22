<?php

namespace App\Http\Controllers;

use App\SolicitudSoporte;
use App\ArchivoSoporte;
use App\Models\RespuestaTicket;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class SoporteController extends Controller
{
    /**
     * Mostrar el formulario de soporte junto con las solicitudes del usuario.
     */
    public function index()
    {
        $solicitudes = SolicitudSoporte::where('usuario_id', Auth::id())
            ->orderBy('created_at', 'desc')
            ->get();

        return view('misc.soporte', compact('solicitudes'));
    }

    /**
     * Guardar una nueva solicitud de soporte.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'tipo_solicitud' => 'required|string|in:' . implode(',', SolicitudSoporte::TIPOS_SOLICITUD),
            'asunto'         => 'required|string|max:200',
            'descripcion'    => 'required|string|min:10|max:5000',
            'prioridad'      => 'required|string|in:' . implode(',', SolicitudSoporte::PRIORIDADES),
        ]);

        try {
            $solicitud = SolicitudSoporte::create([
                'usuario_id'     => Auth::id(),
                'tipo_solicitud' => $validated['tipo_solicitud'],
                'asunto'         => $validated['asunto'],
                'descripcion'    => $validated['descripcion'],
                'prioridad'      => $validated['prioridad'],
                'estado'         => 'abierta',
            ]);

            // Vincular archivos subidos previamente con Dropzone
            if ($request->has('archivos')) {
                foreach ($request->input('archivos') as $archivo) {
                    if (!empty($archivo['ruta'])) {
                        ArchivoSoporte::create([
                            'solicitud_id'    => $solicitud->id,
                            'nombre_original' => $archivo['nombre_original'] ?? 'archivo',
                            'nombre_guardado' => $archivo['nombre_guardado'] ?? '',
                            'ruta'            => $archivo['ruta'],
                            'tipo_mime'       => $archivo['tipo_mime'] ?? null,
                            'tamaño'          => $archivo['tamaño'] ?? $archivo['tamano'] ?? null,
                        ]);
                    }
                }
            }

            return redirect()->route('soporte.index')
                ->with('success', 'Solicitud creada exitosamente. Número de ticket: ' . $solicitud->numero_ticket);
        } catch (\Exception $e) {
            return redirect()->back()
                ->withInput()
                ->with('error', 'Error al crear la solicitud: ' . $e->getMessage());
        }
    }

    /**
     * Ver el detalle de una solicitud.
     */
    public function show($id)
    {
        $solicitud = SolicitudSoporte::with([
            'archivos',
            'respuestas.usuario',
            'respuestas.archivos',
        ])->findOrFail($id);

        // Solo el dueño o un admin puede ver la solicitud
        if ($solicitud->usuario_id !== Auth::id() && !Auth::user()->hasAnyRole(['admin', 'Admin'])) {
            abort(403);
        }

        return view('misc.soporte-detalle', compact('solicitud'));
    }

    /**
     * Listar todas las solicitudes del usuario autenticado.
     */
    public function misSolicitudes()
    {
        $solicitudes = SolicitudSoporte::with('archivos')
            ->where('usuario_id', Auth::id())
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        return view('misc.mis-solicitudes', compact('solicitudes'));
    }

    /**
     * Subir archivos con Dropzone (respuesta JSON).
     */
    public function upload(Request $request)
    {
        $request->validate([
            'file' => 'required|file|max:5120|mimes:jpg,jpeg,png,pdf,doc,docx',
        ]);
        try {
            $request->validate([
                'file' => 'required|file|max:5120|mimes:jpg,jpeg,png,pdf,doc,docx',
            ]);

            if ($request->hasFile('file')) {
                $file           = $request->file('file');
                $nombreOriginal = $file->getClientOriginalName();
                $nombreGuardado = time() . '_' . uniqid() . '.' . $file->getClientOriginalExtension();
                $ruta           = $file->storeAs('soporte', $nombreGuardado, 'public');

                return response()->json([
                    'success' => true,
                    'message' => 'Archivo cargado exitosamente',
                    'file'    => [
                        'nombre_original' => $nombreOriginal,
                        'nombre_guardado' => $nombreGuardado,
                        'ruta'            => $ruta,
                        'tipo_mime'       => $file->getMimeType(),
                        'tamaño'          => $file->getSize(),
                    ],
                ], 200);
            }

            return response()->json(['success' => false, 'message' => 'No se proporcionó archivo'], 400);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error al cargar el archivo: ' . $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Registrar una respuesta al ticket (admin o usuario).
     * Si se indica estado_nuevo, actualiza el estado del ticket.
     */
    public function responder(Request $request, $id)
    {
        if (!Auth::user()->hasAnyRole(['admin', 'Admin'])) {
            abort(403);
        }

        $ticket = SolicitudSoporte::findOrFail($id);

        $request->validate([
            'contenido'    => 'required|string|min:5',
            'estado_nuevo' => 'nullable|in:' . implode(',', SolicitudSoporte::ESTADOS),
            'archivos.*'   => 'nullable|file|max:5120|mimes:pdf,jpg,jpeg,png,doc,docx,xls,xlsx',
        ]);

        $respuesta = RespuestaTicket::create([
            'solicitud_id' => $ticket->id,
            'usuario_id'   => Auth::id(),
            'contenido'    => $request->contenido,
            'estado_nuevo' => $request->filled('estado_nuevo') ? $request->estado_nuevo : null,
        ]);

        if ($request->filled('estado_nuevo')) {
            $ticket->estado = $request->estado_nuevo;
            $ticket->save();
        }

        if ($request->hasFile('archivos')) {
            foreach ($request->file('archivos') as $archivo) {
                $nombreOriginal = $archivo->getClientOriginalName();
                $nombreGuardado = time() . '_' . uniqid() . '.' . $archivo->getClientOriginalExtension();
                $ruta           = $archivo->storeAs('soporte', $nombreGuardado, 'public');

                ArchivoSoporte::create([
                    'solicitud_id'    => $ticket->id,
                    'respuesta_id'    => $respuesta->id,
                    'nombre_original' => $nombreOriginal,
                    'nombre_guardado' => $nombreGuardado,
                    'ruta'            => $ruta,
                    'tipo_mime'       => $archivo->getMimeType(),
                    'tamaño'          => $archivo->getSize(),
                ]);
            }
        }

        return redirect()->route('soporte.show', $ticket->id)
            ->with('success', 'Respuesta registrada correctamente.');
    }

    /**
     * Cambiar únicamente el estado de una solicitud.
     */
    public function cambiarEstado(Request $request, $id)
    {
        if (!Auth::user()->hasAnyRole(['admin', 'Admin'])) {
            abort(403);
        }

        $solicitud = SolicitudSoporte::findOrFail($id);

        $request->validate([
            'estado' => 'required|string|in:' . implode(',', SolicitudSoporte::ESTADOS),
        ]);

        $solicitud->update(['estado' => $request->input('estado')]);

        return redirect()->back()->with('success', 'Estado actualizado correctamente.');
    }
}
