<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Reference structures only; the original database's personal data is not imported.
        if (DB::getDriverName() !== 'mysql') {
            throw new RuntimeException('La instalación completa requiere MySQL o MariaDB.');
        }
        $schemas = json_decode(file_get_contents(database_path('source-schema.json')), true, flags: JSON_THROW_ON_ERROR);
        $create = function (string $table) use (&$create, $schemas): void {
            if (Schema::hasTable($table)) {
                return;
            }
            preg_match_all('/REFERENCES `([^`]+)`/', $schemas[$table], $matches);
            foreach ($matches[1] as $dependency) {
                $create($dependency);
            }
            DB::unprepared($schemas[$table]);
        };
        foreach (array_keys($schemas) as $table) {
            $create($table);
        }
        Schema::create('productos', function (Blueprint $table) {
            $table->id();
            $table->string('codigo_barras')->unique();
            $table->string('descripcion');
            $table->decimal('precio_compra', 12, 2)->default(0);
            $table->decimal('precio_venta', 12, 2);
            $table->decimal('existencia', 12, 2)->default(0);
            $table->timestamps();
        });
        Schema::create('permissions', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('guard_name');
            $table->timestamps();
            $table->unique(['name', 'guard_name']);
        });
        foreach (['model_has_permissions' => 'permission_id', 'model_has_roles' => 'role_id'] as $name => $key) {
            Schema::create($name, function (Blueprint $table) use ($key) {
                $table->unsignedBigInteger($key);
                $table->string('model_type');
                $table->unsignedBigInteger('model_id');
                $table->index(['model_id', 'model_type']);
                $table->primary([$key, 'model_id', 'model_type']);
                $table->foreign($key)->references('id')->on($key === 'role_id' ? 'roles' : 'permissions')->cascadeOnDelete();
            });
        }
        Schema::create('role_has_permissions', function (Blueprint $table) {
            $table->unsignedBigInteger('permission_id');
            $table->unsignedBigInteger('role_id');
            $table->primary(['permission_id', 'role_id']);
            $table->foreign('permission_id')->references('id')->on('permissions')->cascadeOnDelete();
            $table->foreign('role_id')->references('id')->on('roles')->cascadeOnDelete();
        });
        Schema::create('configuraciones', function (Blueprint $table) {
            $table->id();
            $table->string('clave')->unique();
            $table->text('valor')->nullable();
            $table->text('descripcion')->nullable();
            $table->string('tipo');
            $table->string('categoria');
            $table->boolean('estado')->default(true);
            $table->timestamps();
        });
        Schema::create('respuestas_tickets', function (Blueprint $table) {
            $table->id();
            $table->foreignId('solicitud_id')->constrained('solicitudes_soporte')->cascadeOnDelete();
            $table->foreignId('usuario_id')->constrained('users');
            $table->text('contenido');
            $table->string('estado_nuevo')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::disableForeignKeyConstraints();
        try {
            foreach (['respuestas_tickets', 'configuraciones', 'role_has_permissions', 'model_has_roles', 'model_has_permissions', 'permissions', 'productos'] as $table) {
                Schema::dropIfExists($table);
            }
            foreach (array_reverse(array_keys(json_decode(file_get_contents(database_path('source-schema.json')), true))) as $table) {
                Schema::dropIfExists($table);
            }
        } finally {
            Schema::enableForeignKeyConstraints();
        }
    }
};
