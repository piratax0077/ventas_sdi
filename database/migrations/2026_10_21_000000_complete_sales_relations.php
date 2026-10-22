<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('ventas', function (Blueprint $table) {
            $table->foreignId('id_responsable')->nullable()->constrained('users')->nullOnDelete();
        });
        Schema::table('clientes', function (Blueprint $table) {
            $table->foreignId('id_usuario')->nullable()->constrained('users')->nullOnDelete();
        });
        Schema::table('archivos_soporte', function (Blueprint $table) {
            $table->foreignId('respuesta_id')->nullable()->constrained('respuestas_tickets')->cascadeOnDelete();
        });
        Schema::table('laboratorios', function (Blueprint $table) {
            $table->foreignId('id_usuario')->nullable()->constrained('users')->nullOnDelete();
        });
    }

    public function down(): void
    {
        foreach (['ventas' => 'id_responsable', 'clientes' => 'id_usuario', 'archivos_soporte' => 'respuesta_id', 'laboratorios' => 'id_usuario'] as $table => $column) {
            Schema::table($table, fn (Blueprint $blueprint) => $blueprint->dropConstrainedForeignId($column));
        }
    }
};
