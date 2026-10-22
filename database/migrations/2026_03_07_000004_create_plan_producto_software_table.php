<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePlanProductoSoftwareTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('plan_producto_software', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('id_plan');
            $table->unsignedBigInteger('id_producto_software');
            $table->timestamps();

            // Claves foráneas
            $table->foreign('id_plan')->references('id')->on('planes')->onDelete('cascade');
            $table->foreign('id_producto_software')->references('id')->on('productos_software')->onDelete('cascade');

            // Índice único para evitar duplicados
            $table->unique(['id_plan', 'id_producto_software']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('plan_producto_software');
    }
}
