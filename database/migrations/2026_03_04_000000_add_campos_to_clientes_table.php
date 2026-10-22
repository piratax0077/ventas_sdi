<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddCamposToClientesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('clientes', function (Blueprint $table) {
            if (!Schema::hasColumn('clientes', 'rol_id')) {
                $table->unsignedBigInteger('rol_id')->nullable()->after('id');
                $table->foreign('rol_id')->references('id')->on('roles')->onDelete('restrict');
            }

            if (!Schema::hasColumn('clientes', 'rut')) {
                $table->string('rut', 20)->unique()->nullable()->after('rol_id');
            }

            if (!Schema::hasColumn('clientes', 'primer_apellido')) {
                $table->string('primer_apellido')->nullable()->after('nombre');
            }

            if (!Schema::hasColumn('clientes', 'segundo_apellido')) {
                $table->string('segundo_apellido')->nullable()->after('primer_apellido');
            }

            if (!Schema::hasColumn('clientes', 'fecha_nacimiento')) {
                $table->date('fecha_nacimiento')->nullable()->after('segundo_apellido');
            }

            if (!Schema::hasColumn('clientes', 'sexo')) {
                $table->char('sexo', 1)->nullable()->comment('M: Masculino, F: Femenino')->after('fecha_nacimiento');
            }

            if (!Schema::hasColumn('clientes', 'prevision_id')) {
                $table->unsignedBigInteger('prevision_id')->nullable()->after('sexo');
                $table->foreign('prevision_id')->references('id')->on('previsiones')->onDelete('restrict');
            }

            if (!Schema::hasColumn('clientes', 'direccion')) {
                $table->string('direccion')->nullable()->after('prevision_id');
            }

            if (!Schema::hasColumn('clientes', 'numero_dir')) {
                $table->string('numero_dir')->nullable()->after('direccion');
            }

            if (!Schema::hasColumn('clientes', 'region_id')) {
                $table->unsignedBigInteger('region_id')->nullable()->after('numero_dir');
                $table->foreign('region_id')->references('id')->on('regiones')->onDelete('restrict');
            }

            if (!Schema::hasColumn('clientes', 'ciudad_id')) {
                $table->unsignedBigInteger('ciudad_id')->nullable()->after('region_id');
            }

            if (!Schema::hasColumn('clientes', 'celular')) {
                $table->string('celular', 20)->nullable()->after('ciudad_id');
            }

            if (!Schema::hasColumn('clientes', 'correo')) {
                $table->string('correo', 100)->unique()->nullable()->after('celular');
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
            // Eliminar foreign keys
            $table->dropForeign(['rol_id']);
            $table->dropForeign(['prevision_id']);
            $table->dropForeign(['region_id']);

            // Eliminar columnas
            $table->dropColumn([
                'rol_id',
                'rut',
                'primer_apellido',
                'segundo_apellido',
                'fecha_nacimiento',
                'sexo',
                'prevision_id',
                'direccion',
                'numero_dir',
                'region_id',
                'ciudad_id',
                'celular',
                'correo'
            ]);
        });
    }
}
