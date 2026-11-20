<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

// Descripción ampliada del video "Veterchile y Veterfood": qué es Veterfood, la idea clave
// (sitios distintos que se complementan) y lo que une a ambos. Los párrafos se separan con una línea en blanco.
return new class extends Migration {
    private const VIDEO = 'videos/presentacion-veterchile-veterfood.mp4';

    public function up(): void
    {
        DB::table('videos_capacitacion')->where('video', self::VIDEO)->update([
            'descripcion' => "Veterfood funciona como un espacio de compras y servicios para el cuidado de la mascota: allí se eligen productos, se organizan entregas mensuales y se consultan las suscripciones y pedidos. En él se conectan tutores y vendedores (comercios y proveedores de servicios).\n\n"
                . "Idea clave: Veterchile cuida la salud de la mascota y Veterfood resuelve lo que la mascota necesita todos los meses, como alimento, productos y servicios. Se complementan, pero son sitios distintos. Explícalo así para que el cliente no piense que Veterfood está dentro de Veterchile.\n\n"
                . "Lo que une a ambos: si la persona tiene cuenta en Veterchile, Veterfood le ofrece cupones de descuento o acceso a convenios. Es un buen argumento para sumar clientes a Veterchile desde Veterfood, y al revés.",
            'updated_at' => now(),
        ]);
    }

    public function down(): void
    {
        DB::table('videos_capacitacion')->where('video', self::VIDEO)->update([
            'descripcion' => 'Del cuidado de la mascota a la visita a la tienda: elegir productos, organizar entregas mensuales y consultar las suscripciones y pedidos.',
            'updated_at' => now(),
        ]);
    }
};
