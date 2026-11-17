<?php

namespace App;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;

class RegistrarSuscripciones
{
    public function registrar(int $clienteId, int $responsableId, array $cantidades): Venta
    {
        if (!$cantidades) {
            throw ValidationException::withMessages(['planes' => 'Agrega un plan antes de registrar la venta.']);
        }

        return DB::transaction(function () use ($clienteId, $responsableId, $cantidades) {
            $planes = Plan::disponible()->whereKey(array_keys($cantidades))->orderBy('id')->lockForUpdate()->get();
            if ($planes->count() !== count($cantidades)) {
                throw ValidationException::withMessages(['planes' => 'Revisa el carrito. Hay planes que ya no están disponibles.']);
            }

            $venta = new Venta();
            $venta->id_cliente = $clienteId;
            $venta->id_responsable = $responsableId;
            $venta->saveOrFail();

            foreach ($planes as $plan) {
                $cantidad = filter_var($cantidades[$plan->id], FILTER_VALIDATE_INT, ['options' => ['min_range' => 1]]);
                $precio = $plan->precioAplicado();
                if ($precio > 9999999.99) {
                    throw ValidationException::withMessages(['planes' => 'El precio de un plan supera el límite admitido.']);
                }
                if ($cantidad === false || $precio * $cantidad > 99999999.99) {
                    throw ValidationException::withMessages(['planes' => 'La cantidad supera el límite admitido para el plan.']);
                }

                VentaPlan::create([
                    'id_venta' => $venta->id,
                    'id_plan' => $plan->id,
                    'precio_unitario' => $precio,
                    'cantidad' => $cantidad,
                    'subtotal' => $precio * $cantidad,
                    'estado' => 'pendiente',
                ]);

                // El detalle histórico también alimenta los comprobantes y conserva el ciclo vendido.
                $venta->productos()->create([
                    'descripcion' => Str::limit($plan->nombre, 240, '').' ('.$plan->frecuencia.')',
                    'codigo_barras' => 'PLAN-'.$plan->id,
                    'precio' => $precio,
                    'cantidad' => $cantidad,
                ]);
            }

            return $venta;
        });
    }
}
