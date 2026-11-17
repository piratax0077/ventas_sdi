<?php
/*

  ____          _____               _ _           _
 |  _ \        |  __ \             (_) |         | |
 | |_) |_   _  | |__) |_ _ _ __ _____| |__  _   _| |_ ___
 |  _ <| | | | |  ___/ _` | '__|_  / | '_ \| | | | __/ _ \
 | |_) | |_| | | |  | (_| | |   / /| | |_) | |_| | ||  __/
 |____/ \__, | |_|   \__,_|_|  /___|_|_.__/ \__, |\__\___|
         __/ |                               __/ |
        |___/                               |___/

    Blog:       https://parzibyte.me/blog
    Ayuda:      https://parzibyte.me/blog/contrataciones-ayuda/
    Contacto:   https://parzibyte.me/blog/contacto/

    Copyright (c) 2020 Luis Cabrera Benito
    Licenciado bajo la licencia MIT

    El texto de arriba debe ser incluido en cualquier redistribucion
*/ ?>
<?php

namespace App\Http\Controllers;

use App\Cliente;
use App\Plan;
use App\RegistrarSuscripciones;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;

class VenderController extends Controller
{
    public function terminarOCancelarVenta(Request $request, RegistrarSuscripciones $registrar)
    {
        $request->validate(['accion' => 'required|in:terminar,cancelar']);

        if ($request->input('accion') === 'cancelar') {
            $request->session()->forget('productos');
            return redirect()->route('vender.index')->with('mensaje', 'Venta cancelada');
        }

        $request->validate(['id_cliente' => 'required|integer|exists:clientes,id']);
        $cantidades = [];
        foreach ($this->carrito($request) as $plan) {
            $cantidades[$plan->id] = $plan->cantidad;
        }

        $registrar->registrar($request->integer('id_cliente'), $request->user()->id, $cantidades);
        $request->session()->forget('productos');

        return redirect()->route('vender.index')->with('mensaje', 'Venta de suscripción registrada');
    }

    public function quitarProductoDeVenta(Request $request)
    {
        $request->validate(['indice' => 'required|integer|min:0']);
        $indice = $request->integer('indice');
        $carrito = $this->carrito($request);
        if (!isset($carrito[$indice])) {
            throw ValidationException::withMessages(['indice' => 'El plan no está en el carrito.']);
        }

        unset($carrito[$indice]);
        $request->session()->put('productos', array_values($carrito));

        return redirect()->route('vender.index');
    }

    public function agregarProductoVenta(Request $request)
    {
        $request->validate([
            'tipo' => 'sometimes|in:membresia',
            'id' => 'required|integer',
        ]);

        $plan = Plan::disponible()->find($request->integer('id'));
        if (!$plan) {
            throw ValidationException::withMessages(['id' => 'El plan no está disponible.']);
        }

        $carrito = $this->carrito($request);
        $indice = collect($carrito)->search(fn (Plan $item) => $item->id === $plan->id);
        if ($indice === false) {
            $plan->cantidad = 1;
            $carrito[] = $plan;
        } else {
            $carrito[$indice]->cantidad++;
        }
        $request->session()->put('productos', $carrito);

        return $request->expectsJson() || $request->ajax()
            ? response()->json(['ok' => true])
            : redirect()->route('vender.index');
    }

    public function index(Request $request)
    {
        $carrito = collect($this->carrito($request));
        $planes = Plan::disponible()->findMany($carrito->pluck('id'))->keyBy('id');

        return view('vender.vender', [
            'carrito' => $carrito,
            'planes' => $planes,
            'total' => $carrito->sum(fn (Plan $item) => $item->cantidad * ($planes->get($item->id)?->precioAplicado() ?? 0)),
            'clientes' => Cliente::orderBy('nombre')->get(),
        ]);
    }

    private function carrito(Request $request): array
    {
        return array_values(array_filter(
            $request->session()->get('productos') ?? [],
            fn ($item) => $item instanceof Plan
        ));
    }
}
