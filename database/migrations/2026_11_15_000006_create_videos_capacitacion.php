<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('videos_capacitacion', function (Blueprint $table) {
            $table->id();
            $table->string('titulo', 160);
            $table->text('descripcion')->nullable();
            $table->string('video');
            $table->string('portada')->nullable();
            $table->string('subtitulos')->nullable();
            $table->timestamps();
        });

        DB::table('videos_capacitacion')->insert([
            'titulo' => 'Ficha Médica Única · Cómo presentar la suscripción',
            'descripcion' => 'Compara los planes de FMU y muestra cómo consultar la ficha, pedir horas y ver exámenes desde la app cuando está incluida.',
            'video' => 'videos/presentacion-fmu.mp4',
            'portada' => 'videos/presentacion-fmu.jpg',
            'subtitulos' => 'videos/presentacion-fmu.es.vtt',
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }

    public function down(): void
    {
        Schema::dropIfExists('videos_capacitacion');
    }
};
