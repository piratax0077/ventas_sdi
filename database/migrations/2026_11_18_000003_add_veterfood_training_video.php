<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration {
    public function up(): void
    {
        DB::table('videos_capacitacion')->insert([
            'linea' => 'veterchile',
            'orden' => 0,
            'titulo' => 'Veterchile y Veterfood · Tienda y pedidos mensuales',
            'descripcion' => 'Del cuidado de la mascota a la visita a la tienda: elegir productos, organizar entregas mensuales y consultar las suscripciones y pedidos.',
            'video' => 'videos/presentacion-veterchile-veterfood.mp4',
            'portada' => 'videos/presentacion-veterchile-veterfood.jpg',
            'subtitulos' => 'videos/presentacion-veterchile-veterfood.es.vtt',
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }

    public function down(): void
    {
        DB::table('videos_capacitacion')->where('video', 'videos/presentacion-veterchile-veterfood.mp4')->delete();
    }
};
