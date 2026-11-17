<?php

namespace App\Http\Controllers;

use App\Cliente;
use App\ContactoCliente;
use App\SeguimientoContacto;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
use Illuminate\Validation\ValidationException;

class SeguimientosContactoController extends Controller
{
    public function index(Request $request)
    {
        $estado = $request->query('estado');
        $consulta = SeguimientoContacto::visiblePara($request->user())->with(['cliente', 'responsable']);
        if (is_string($estado) && isset(SeguimientoContacto::ESTADOS[$estado])) {
            $consulta->where('estado', $estado);
        }
        $seguimientos = $consulta->latest('id')->paginate(30)->withQueryString();
        return view('seguimientos.index', compact('seguimientos', 'estado'));
    }

    public function show(Request $request, Cliente $cliente)
    {
        $correo = ContactoCliente::correo($cliente->correo);
        $whatsapp = ContactoCliente::whatsapp($cliente);
        $seguimientos = SeguimientoContacto::visiblePara($request->user())->where('cliente_id', $cliente->id)
            ->with(['cliente', 'responsable'])->latest('id')->paginate(20);
        $lote = (string) Str::uuid();

        return view('seguimientos.cliente', compact('cliente', 'correo', 'whatsapp', 'seguimientos', 'lote'));
    }

    public function store(Request $request, Cliente $cliente)
    {
        $datos = $request->validate([
            'canal' => 'required|in:correo,whatsapp',
            'lote' => 'required|uuid',
        ]);
        $contacto = $datos['canal'] === 'correo'
            ? ContactoCliente::correo($cliente->correo)
            : ContactoCliente::whatsapp($cliente);
        if (!$contacto) {
            throw ValidationException::withMessages(['canal' => 'El cliente no tiene un contacto válido en este canal.']);
        }
        SeguimientoContacto::firstOrCreate([
            'cliente_id' => $cliente->id,
            'responsable_id' => $request->user()->id,
            'lote' => $datos['lote'],
        ], ['canal' => $datos['canal']]);

        return redirect()->route('clientes.contacto', $cliente)->with('mensaje', 'Seguimiento pendiente registrado.');
    }

    public function update(Request $request, int $seguimiento)
    {
        $registro = SeguimientoContacto::visiblePara($request->user())->findOrFail($seguimiento);
        $datos = $request->validate([
            'estado' => ['required', Rule::in(array_keys(SeguimientoContacto::ESTADOS))],
            'nota' => 'nullable|string|max:1000',
        ]);
        $datos['respondio_en'] = $datos['estado'] === 'respondio' ? ($registro->respondio_en ?? now()) : null;
        $registro->update($datos);

        return back()->with('mensaje', 'Seguimiento actualizado.');
    }
}
