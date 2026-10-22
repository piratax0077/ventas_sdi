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
use App\Producto;
use App\ProductoVendido;
use App\VentaPlan;
use App\Venta;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

class VenderController extends Controller
{

    public function terminarOCancelarVenta(Request $request)
    {
        $request->validate(['accion' => 'required|in:terminar,cancelar']);
        if ($request->input("accion") == "terminar") {
            return $this->terminarVenta($request);
        } else {
            return $this->cancelarVenta();
        }
    }

    public function terminarVenta(Request $request)
    {
        $request->validate(['id_cliente' => 'required|integer|exists:clientes,id']);
        $productos = $this->obtenerProductos();
        if (!$productos) {
            throw ValidationException::withMessages(['productos' => 'Agrega al menos un artículo antes de terminar la venta.']);
        }
        DB::transaction(function () use ($request, $productos) {
            $venta = new Venta();
            $venta->id_cliente = $request->integer('id_cliente');
            $venta->id_responsable = auth()->id();
            $venta->saveOrFail();
            foreach ($productos as $producto) {
                $esMembresia = $producto->tipo === 'membresia';
                $actual = $esMembresia
                    ? Plan::lockForUpdate()->find($producto->id)
                    : Producto::lockForUpdate()->find($producto->id);
                $cantidad = filter_var($producto->cantidad, FILTER_VALIDATE_INT);
                if (!$actual || !$cantidad || $cantidad < 1 || ($esMembresia && !$actual->estado)) {
                    throw ValidationException::withMessages(['productos' => 'Hay artículos no disponibles en el carrito.']);
                }
                if (!$esMembresia && $actual->existencia < $cantidad) {
                    throw ValidationException::withMessages(['productos' => 'No hay existencias suficientes de '.$actual->descripcion.'.']);
                }
                $precio = $esMembresia ? $actual->precio : $actual->precio_venta;
                ProductoVendido::create([
                    'id_venta' => $venta->id,
                    'descripcion' => $esMembresia ? $actual->nombre : $actual->descripcion,
                    'codigo_barras' => $esMembresia ? 'PLAN-'.$actual->id : $actual->codigo_barras,
                    'precio' => $precio,
                    'cantidad' => $cantidad,
                ]);
                if ($esMembresia) {
                    VentaPlan::create([
                        'id_venta' => $venta->id, 'id_plan' => $actual->id,
                        'precio_unitario' => $precio, 'cantidad' => $cantidad,
                        'subtotal' => $precio * $cantidad, 'estado' => 'pendiente',
                    ]);
                } else {
                    $actual->decrement('existencia', $cantidad);
                }
            }
        });
        $this->vaciarProductos();
        return redirect()
            ->route("vender.index")
            ->with("mensaje", "Venta terminada");
    }

    private function obtenerProductos()
    {
        $productos = session("productos");
        if (!$productos) {
            $productos = [];
        }
        return $productos;
    }

    private function vaciarProductos()
    {
        $this->guardarProductos(null);
    }

    private function guardarProductos($productos)
    {
        session(["productos" => $productos,
        ]);
    }

    public function cancelarVenta()
    {
        $this->vaciarProductos();
        return redirect()
            ->route("vender.index")
            ->with("mensaje", "Venta cancelada");
    }

    public function quitarProductoDeVenta(Request $request)
    {
        $request->validate(['indice' => 'required|integer|min:0']);
        $indice = $request->integer('indice');
        $productos = $this->obtenerProductos();
        if (!array_key_exists($indice, $productos)) {
            throw ValidationException::withMessages(['indice' => 'El artículo no está en el carrito.']);
        }
        array_splice($productos, $indice, 1);
        $this->guardarProductos($productos);
        return redirect()
            ->route("vender.index");
    }

    public function agregarProductoVenta(Request $request)
    {

        $request->validate([
            'tipo' => 'sometimes|in:producto,membresia',
            'id' => 'required_if:tipo,membresia|nullable|integer',
            'codigo' => 'required_unless:tipo,membresia|nullable|string',
        ]);
        $tipo = $request->post("tipo", "producto");

        if ($tipo === "membresia") {
            $id = $request->post("id");
            $plan = Plan::where('estado', true)->find($id);
            if (!$plan) {
                if ($request->ajax() || $request->wantsJson()) {
                    return response()->json(['ok' => false, 'mensaje' => 'Plan no encontrado'], 404);
                }
                return redirect()
                    ->route("vender.index")
                    ->with("mensaje", "Plan no encontrado");
            }
            $this->agregarPlanACarrito($plan);
        } else {
            $codigo = $request->post("codigo");
            $producto = Producto::where("codigo_barras", "=", $codigo)->first();
            if (!$producto) {
                if ($request->expectsJson()) {
                    return response()->json(['ok' => false, 'mensaje' => 'Producto no encontrado'], 404);
                }
                return redirect()
                    ->route("vender.index")
                    ->with("mensaje", "Producto no encontrado");
            }
            $this->agregarProductoACarrito($producto);
        }

        if ($request->ajax() || $request->wantsJson()) {
            return response()->json(['ok' => true]);
        }

        return redirect()
            ->route("vender.index");
    }

    private function agregarPlanACarrito($plan)
    {
        $productos = $this->obtenerProductos();
        // Buscar si ya existe el plan en el carrito
        $posibleIndice = -1;
        foreach ($productos as $indice => $item) {
            if (isset($item->tipo) && $item->tipo === 'membresia' && $item->id === $plan->id) {
                $posibleIndice = $indice;
                break;
            }
        }
        if ($posibleIndice === -1) {
            $plan->tipo = 'membresia';
            $plan->cantidad = 1;
            array_push($productos, $plan);
        } else {
            $productos[$posibleIndice]->cantidad++;
        }
        $this->guardarProductos($productos);
    }

    private function agregarProductoACarrito($producto)
    {
        if ($producto->existencia <= 0) {
            throw ValidationException::withMessages(['codigo' => 'No hay existencias del producto.']);
        }
        $productos = $this->obtenerProductos();
        $posibleIndice = $this->buscarIndiceDeProducto($producto->codigo_barras, $productos);
        // Es decir, producto no fue encontrado
        if ($posibleIndice === -1) {
            $producto->tipo = 'producto';
            $producto->cantidad = 1;
            array_push($productos, $producto);
        } else {
            if ($productos[$posibleIndice]->cantidad + 1 > $producto->existencia) {
                throw ValidationException::withMessages(['codigo' => 'No se pueden agregar más unidades: stock insuficiente.']);
            }
            $productos[$posibleIndice]->cantidad++;
        }
        $this->guardarProductos($productos);
    }

    private function buscarIndiceDeProducto(string $codigo, array &$productos)
    {
        foreach ($productos as $indice => $producto) {
            if ($producto->tipo !== 'membresia' && $producto->codigo_barras === $codigo) {
                return $indice;
            }
        }
        return -1;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $total = 0;
        foreach ($this->obtenerProductos() as $producto) {
            $esMembresia = isset($producto->tipo) && $producto->tipo === 'membresia';
            $precio = $esMembresia ? $producto->precio : $producto->precio_venta;
            $total += $producto->cantidad * $precio;
        }
        return view("vender.vender",
            [
                "total" => $total,
                "clientes" => Cliente::all(),
            ]);
    }
}
