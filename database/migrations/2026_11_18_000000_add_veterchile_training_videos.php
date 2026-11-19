<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    private const VIDEOS = [
        'veterchile-tutores' => ['Veterchile para Tutores', 'Ficha Veterinaria Única, documentos clínicos y reserva de horas.'],
        'veterchile-profesionales' => ['Veterchile para Profesionales', 'Agenda, fichas por especialidad y recetas u órdenes electrónicas.'],
        'veterchile-asistente-online' => ['Veterchile para Asistente Online', 'Coordinación de horas y manejo de las agendas vinculadas.'],
        'veterchile-centros-veterinarios' => ['Veterchile para Centros Veterinarios', 'Agendas centralizadas, recepción y coordinación de profesionales.'],
        'veterchile-clinicas' => ['Veterchile para Clínicas', 'Agenda, fichas por especialidad y continuidad de los controles.'],
        'veterchile-hospitales' => ['Veterchile para Hospitales', 'Historial, documentos y coordinación entre equipos en atenciones complejas.'],
        'veterchile-laboratorios' => ['Veterchile para Laboratorios', 'Órdenes de exámenes, carga de resultados y consulta de documentos.'],
    ];

    public function up(): void
    {
        if (!Schema::hasColumn('videos_capacitacion', 'linea')) {
            Schema::table('videos_capacitacion', fn (Blueprint $table) => $table->string('linea', 20)->default('medichile')->index());
        }

        $fecha = now();
        $registros = [];
        foreach (self::VIDEOS as $slug => [$titulo, $descripcion]) {
            $base = 'videos/presentacion-'.$slug;
            $registros[] = [
                'linea' => 'veterchile',
                'titulo' => $titulo,
                'descripcion' => $descripcion,
                'video' => $base.'.mp4',
                'portada' => $base.'.jpg',
                'subtitulos' => $base.'.es.vtt',
                'created_at' => $fecha,
                'updated_at' => $fecha,
            ];
        }
        DB::table('videos_capacitacion')->insert($registros);
    }

    public function down(): void
    {
        $rutas = array_map(fn (string $slug) => 'videos/presentacion-'.$slug.'.mp4', array_keys(self::VIDEOS));
        DB::table('videos_capacitacion')->whereIn('video', $rutas)->delete();
        Schema::table('videos_capacitacion', fn (Blueprint $table) => $table->dropColumn('linea'));
    }
};
