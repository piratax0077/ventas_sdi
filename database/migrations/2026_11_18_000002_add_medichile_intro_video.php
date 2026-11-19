<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration {
    public function up(): void
    {
        DB::table('videos_capacitacion')->insert([
            'linea' => 'medichile',
            'orden' => -1,
            'titulo' => 'Medichile: conectados para cuidar la salud',
            'descripcion' => 'Medichile conecta a pacientes, profesionales y centros de atención que participan en dar, recibir o colaborar en el cuidado de la salud. Cada persona conserva la propiedad y el control de sus datos. La información se comparte y los participantes interactúan con la autorización de sus titulares, para facilitar una atención coordinada y respetuosa de sus decisiones.',
            'video' => 'videos/presentacion-medichile-introduccion.mp4',
            'portada' => 'videos/presentacion-medichile-introduccion.jpg',
            'subtitulos' => 'videos/presentacion-medichile-introduccion.es.vtt',
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }

    public function down(): void
    {
        DB::table('videos_capacitacion')->where('video', 'videos/presentacion-medichile-introduccion.mp4')->delete();
    }
};
