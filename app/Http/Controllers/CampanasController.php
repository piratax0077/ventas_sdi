<?php

namespace App\Http\Controllers;

use App\Cliente;
use App\ContactoCliente;
use App\Roles;
use App\SeguimientoContacto;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;

class CampanasController extends Controller
{
    public function index(Request $request)
    {
        $canal = $request->query('canal') === 'whatsapp' ? 'whatsapp' : 'correo';
        $busqueda = trim($request->string('buscar')->limit(100)->toString());
        $rolId = $request->integer('rol');
        $roles = Roles::whereIn('id', Cliente::whereNotNull('rol_id')->select('rol_id'))->orderBy('name')->get();
        $consulta = Cliente::buscarContacto($busqueda)
            ->when($rolId > 0, fn ($query) => $query->where('rol_id', $rolId))
            ->orderBy('nombre')->orderBy('id');

        $pagina = max(1, $request->integer('page', 1));
        $porPagina = 50;
        $inicio = ($pagina - 1) * $porPagina;
        $cantidad = 0;
        $filas = [];
        foreach ($consulta->cursor() as $cliente) {
            $contacto = $this->contacto($cliente, $canal);
            if (!$contacto) {
                continue;
            }
            if ($cantidad >= $inicio && count($filas) < $porPagina) {
                $filas[] = ['cliente' => $cliente, 'contacto' => $contacto];
            }
            $cantidad++;
        }
        $destinatarios = (new LengthAwarePaginator($filas, $cantidad, $porPagina, $pagina, ['path' => $request->url()]))->withQueryString();

        $lote = (string) Str::uuid();
        return view('campanas.index', compact('canal', 'busqueda', 'rolId', 'roles', 'destinatarios', 'lote'));
    }

    public function preparar(Request $request)
    {
        $datos = $request->validate([
            'canal' => 'required|in:correo,whatsapp',
            'lote' => 'required|uuid',
            'destinatarios' => 'required|array|min:1|max:50',
            'destinatarios.*' => 'required|integer|min:1|distinct',
            'asunto' => 'required_if:canal,correo|nullable|string|max:120|not_regex:/[\r\n]/',
            'mensaje' => 'required|string|max:1000',
        ]);
        $consulta = Cliente::whereKey($datos['destinatarios'])->orderBy('nombre')->get();
        $destinatarios = [];
        foreach ($consulta as $cliente) {
            $contacto = $this->contacto($cliente, $datos['canal']);
            if (!$contacto || isset($destinatarios[$contacto])) {
                continue;
            }
            $mensaje = rawurlencode($datos['mensaje']);
            $enlace = $datos['canal'] === 'whatsapp'
                ? "https://wa.me/$contacto?text=$mensaje"
                : 'mailto:'.rawurlencode($contacto).'?subject='.rawurlencode($datos['asunto']).'&body='.$mensaje;
            $destinatarios[$contacto] = ['cliente' => $cliente, 'contacto' => $contacto, 'enlace' => $enlace];
        }
        if (!$destinatarios) {
            throw ValidationException::withMessages(['destinatarios' => 'Selecciona clientes con un contacto válido para este canal.']);
        }
        DB::transaction(function () use ($request, $datos, &$destinatarios) {
            foreach ($destinatarios as &$fila) {
                $fila['seguimiento'] = SeguimientoContacto::firstOrCreate([
                    'cliente_id' => $fila['cliente']->id,
                    'responsable_id' => $request->user()->id,
                    'lote' => $datos['lote'],
                ], [
                    'canal' => $datos['canal'],
                    'referencia' => $datos['canal'] === 'correo' ? $datos['asunto'] : 'Campaña WhatsApp',
                ]);
            }
        });
        $correoGrupal = null;
        if ($datos['canal'] === 'correo' && count($destinatarios) > 1) {
            $enlace = 'mailto:?bcc='.implode(',', array_map('rawurlencode', array_keys($destinatarios)))
                .'&subject='.rawurlencode($datos['asunto']).'&body='.rawurlencode($datos['mensaje']);
            if (strlen($enlace) <= 1800) {
                $correoGrupal = $enlace;
            }
        }

        return view('campanas.preparada', [
            'canal' => $datos['canal'],
            'destinatarios' => $destinatarios,
            'omitidos' => count($datos['destinatarios']) - count($destinatarios),
            'correoGrupal' => $correoGrupal,
        ]);
    }

    private function contacto(Cliente $cliente, string $canal): ?string
    {
        return $canal === 'whatsapp' ? ContactoCliente::whatsapp($cliente) : ContactoCliente::correo($cliente->correo);
    }
}
