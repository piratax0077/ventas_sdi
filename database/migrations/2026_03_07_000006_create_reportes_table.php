<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateReportesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('reportes', function (Blueprint $table) {
            $table->id();
            $table->string('nombre'); // Título del reporte
            $table->text('descripcion')->nullable(); // Descripción
            $table->enum('tipo', ['mensual', 'trimestral', 'anual', 'personalizado'])->default('mensual');
            $table->enum('frecuencia', ['diaria', 'semanal', 'mensual', 'trimestral', 'anual'])->default('mensual');
            $table->longText('destinatarios')->nullable(); // Emails separados por coma
            $table->longText('contenido')->nullable(); // Template HTML del reporte
            $table->boolean('estado')->default(true);
            $table->timestamp('ultima_ejecucion')->nullable();
            $table->timestamp('proxima_ejecucion')->nullable();
            $table->timestamps();

            $table->index('tipo');
            $table->index('frecuencia');
            $table->index('estado');
            $table->index('proxima_ejecucion');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('reportes');
    }
}
