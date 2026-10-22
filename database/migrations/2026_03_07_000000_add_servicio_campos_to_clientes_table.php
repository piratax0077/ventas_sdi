<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddServicioCamposToClientesTable extends Migration
{
    public function up()
    {
        Schema::table('clientes', function (Blueprint $table) {
            if (!Schema::hasColumn('clientes', 'serv_rut')) {
                $table->string('serv_rut', 20)->nullable()->after('inst_telefono');
            }
            if (!Schema::hasColumn('clientes', 'nombre_servicio')) {
                $table->string('nombre_servicio')->nullable()->after('serv_rut');
            }
            if (!Schema::hasColumn('clientes', 'tipo_servicio')) {
                $table->string('tipo_servicio')->nullable()->after('nombre_servicio');
            }
            if (!Schema::hasColumn('clientes', 'serv_direccion')) {
                $table->string('serv_direccion')->nullable()->after('tipo_servicio');
            }
            if (!Schema::hasColumn('clientes', 'serv_numero_dir')) {
                $table->string('serv_numero_dir', 20)->nullable()->after('serv_direccion');
            }
            if (!Schema::hasColumn('clientes', 'serv_region_id')) {
                $table->unsignedBigInteger('serv_region_id')->nullable()->after('serv_numero_dir');
            }
            if (!Schema::hasColumn('clientes', 'serv_ciudad_id')) {
                $table->unsignedBigInteger('serv_ciudad_id')->nullable()->after('serv_region_id');
            }
            if (!Schema::hasColumn('clientes', 'serv_telefono')) {
                $table->string('serv_telefono', 20)->nullable()->after('serv_ciudad_id');
            }
        });
    }

    public function down()
    {
        Schema::table('clientes', function (Blueprint $table) {
            $columns = [
                'serv_rut', 'nombre_servicio', 'tipo_servicio',
                'serv_direccion', 'serv_numero_dir',
                'serv_region_id', 'serv_ciudad_id', 'serv_telefono',
            ];
            foreach ($columns as $col) {
                if (Schema::hasColumn('clientes', $col)) {
                    $table->dropColumn($col);
                }
            }
        });
    }
}
