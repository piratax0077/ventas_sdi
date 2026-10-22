<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMantenimientosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('mantenimientos', function (Blueprint $table) {
            $table->id();
            $table->text('descripcion');
            $table->enum('tipo', ['servidor', 'sistema', 'software', 'hardware', 'red', 'otro'])->default('otro');
            $table->dateTime('fecha_inicio');
            $table->dateTime('fecha_fin')->nullable();
            $table->enum('estado', ['pendiente', 'activo', 'completado', 'cancelado'])->default('pendiente');
            $table->string('responsable')->nullable();
            $table->longText('notas')->nullable();
            $table->timestamps();

            $table->index('estado');
            $table->index('tipo');
            $table->index('fecha_inicio');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('mantenimientos');
    }
}
