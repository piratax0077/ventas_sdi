<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddInstitucionCamposToClientesTable extends Migration
{
    public function up()
    {
        Schema::table('clientes', function (Blueprint $table) {
            // Datos de la institución
            if (!Schema::hasColumn('clientes', 'inst_rut')) {
                $table->string('inst_rut', 20)->nullable();
            }
            if (!Schema::hasColumn('clientes', 'nombre_institucion')) {
                $table->string('nombre_institucion')->nullable();
            }
            if (!Schema::hasColumn('clientes', 'tipo_institucion')) {
                $table->string('tipo_institucion')->nullable();
            }
            if (!Schema::hasColumn('clientes', 'inst_direccion')) {
                $table->string('inst_direccion')->nullable();
            }
            if (!Schema::hasColumn('clientes', 'inst_numero_dir')) {
                $table->string('inst_numero_dir')->nullable();
            }
            if (!Schema::hasColumn('clientes', 'inst_region_id')) {
                $table->unsignedBigInteger('inst_region_id')->nullable();
            }
            if (!Schema::hasColumn('clientes', 'inst_ciudad_id')) {
                $table->unsignedBigInteger('inst_ciudad_id')->nullable();
            }
            if (!Schema::hasColumn('clientes', 'inst_telefono')) {
                $table->string('inst_telefono', 20)->nullable();
            }

            // Datos del responsable
            if (!Schema::hasColumn('clientes', 'responsable_rut')) {
                $table->string('responsable_rut', 20)->nullable();
            }
            if (!Schema::hasColumn('clientes', 'responsable_nombre')) {
                $table->string('responsable_nombre')->nullable();
            }
            if (!Schema::hasColumn('clientes', 'responsable_apellido_uno')) {
                $table->string('responsable_apellido_uno')->nullable();
            }
            if (!Schema::hasColumn('clientes', 'responsable_apellido_dos')) {
                $table->string('responsable_apellido_dos')->nullable();
            }
            if (!Schema::hasColumn('clientes', 'responsable_celular')) {
                $table->string('responsable_celular', 20)->nullable();
            }
            if (!Schema::hasColumn('clientes', 'responsable_telefono')) {
                $table->string('responsable_telefono', 20)->nullable();
            }
        });
    }

    public function down()
    {
        Schema::table('clientes', function (Blueprint $table) {
            $columns = [
                'inst_rut', 'nombre_institucion', 'tipo_institucion',
                'inst_direccion', 'inst_numero_dir', 'inst_region_id',
                'inst_ciudad_id', 'inst_telefono',
                'responsable_rut', 'responsable_nombre',
                'responsable_apellido_uno', 'responsable_apellido_dos',
                'responsable_celular', 'responsable_telefono',
            ];
            $existing = array_filter($columns, fn($c) => Schema::hasColumn('clientes', $c));
            if (!empty($existing)) {
                $table->dropColumn(array_values($existing));
            }
        });
    }
}
