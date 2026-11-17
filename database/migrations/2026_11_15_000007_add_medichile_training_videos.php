<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration {
    private const VIDEOS = [
        'profesionales' => ['Medichile para Profesionales', 'Agenda, ficha clínica, recetas firmadas y cómo presentar licencias, talonarios y bonos según la habilitación.'],
        'centros-medicos' => ['Medichile para Centros Médicos', 'Agendas del equipo, recepción, atención online, caja y convenios según el plan.'],
        'asistente-online' => ['Medichile para Asistente Online', 'Coordinación de solicitudes, manejo de agendas y videoconsulta según el plan.'],
        'clinicas' => ['Medichile para Clínicas', 'Atención ambulatoria, ficha clínica y módulos de Atención Online u Hospitalización.'],
        'hospitales' => ['Medichile para Hospitales', 'Hospitalización, evoluciones, tareas clínicas y coordinación de urgencias.'],
        'laboratorios' => ['Medichile para Laboratorios', 'Operación del laboratorio, carga de exámenes y administración técnica.'],
    ];

    public function up(): void
    {
        $fecha = now();
        $registros = [];
        foreach (self::VIDEOS as $slug => [$titulo, $descripcion]) {
            $base = 'videos/presentacion-'.$slug;
            $registros[] = [
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
    }
};
