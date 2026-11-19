<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('videos_capacitacion', fn (Blueprint $table) => $table->integer('orden')->default(0));
        DB::table('videos_capacitacion')->insert([
            'linea' => 'veterchile',
            'orden' => -1,
            'titulo' => 'Veterchile: el cuidado de la mascota, en un solo lugar',
            'descripcion' => 'Veterchile es un sistema que centraliza la salud de la mascota y promueve la tenencia responsable. Conecta a tutores, médicos veterinarios y centros de atención en una misma plataforma. Así, todos trabajan con la misma información, de forma simple, ordenada y siempre disponible.',
            'video' => 'videos/presentacion-veterchile-introduccion.mp4',
            'portada' => 'videos/presentacion-veterchile-introduccion.jpg',
            'subtitulos' => 'videos/presentacion-veterchile-introduccion.es.vtt',
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }

    public function down(): void
    {
        DB::table('videos_capacitacion')->where('video', 'videos/presentacion-veterchile-introduccion.mp4')->delete();
        Schema::table('videos_capacitacion', fn (Blueprint $table) => $table->dropColumn('orden'));
    }
};
