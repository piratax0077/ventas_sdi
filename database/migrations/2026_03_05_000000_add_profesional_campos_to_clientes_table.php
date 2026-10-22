<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddProfesionalCamposToClientesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('clientes', function (Blueprint $table) {
            // ID de profesión (FK a tabla especialidades)
            if (!Schema::hasColumn('clientes', 'profesion')) {
                $table->unsignedBigInteger('profesion')->nullable();
            }

            // ID de especialidad (sub-tipo de profesión)
            if (!Schema::hasColumn('clientes', 'especialidad')) {
                $table->unsignedBigInteger('especialidad')->nullable();
            }

            // ID de sub-especialidad
            if (!Schema::hasColumn('clientes', 'sub_tipo_especialidad')) {
                $table->unsignedBigInteger('sub_tipo_especialidad')->nullable();
            }
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('clientes', function (Blueprint $table) {
            $columns = [];
            if (Schema::hasColumn('clientes', 'profesion'))            $columns[] = 'profesion';
            if (Schema::hasColumn('clientes', 'especialidad'))         $columns[] = 'especialidad';
            if (Schema::hasColumn('clientes', 'sub_tipo_especialidad')) $columns[] = 'sub_tipo_especialidad';

            if (!empty($columns)) {
                $table->dropColumn($columns);
            }
        });
    }
}
