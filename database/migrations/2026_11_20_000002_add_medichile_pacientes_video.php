<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

// Agrega "Medichile para Pacientes" justo después de "Ficha Médica Única" en el menú de videos.
// Aún no hay video: la vista muestra "Video en preparación" hasta que exista public/videos/presentacion-pacientes.mp4.
return new class extends Migration {
    private const VIDEO = 'videos/presentacion-pacientes.mp4';
    private const SIGUIENTES = [
        'videos/presentacion-profesionales.mp4',
        'videos/presentacion-centros-medicos.mp4',
        'videos/presentacion-asistente-online.mp4',
        'videos/presentacion-clinicas.mp4',
        'videos/presentacion-hospitales.mp4',
        'videos/presentacion-laboratorios.mp4',
    ];

    public function up(): void
    {
        // Los videos por público pasan a orden 1 para quedar después de Pacientes (orden 0)
        DB::table('videos_capacitacion')->whereIn('video', self::SIGUIENTES)->update(['orden' => 1]);

        if (!DB::table('videos_capacitacion')->where('video', self::VIDEO)->exists()) {
            DB::table('videos_capacitacion')->insert([
                'linea' => 'medichile',
                'orden' => 0,
                'titulo' => 'Medichile para Pacientes',
                'descripcion' => null,
                'video' => self::VIDEO,
                'portada' => null,
                'subtitulos' => null,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }

    public function down(): void
    {
        DB::table('videos_capacitacion')->where('video', self::VIDEO)->delete();
        DB::table('videos_capacitacion')->whereIn('video', self::SIGUIENTES)->update(['orden' => 0]);
    }
};
