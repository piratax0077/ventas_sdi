<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('seguimientos_contacto', function (Blueprint $table) {
            $table->id();
            $table->foreignId('cliente_id')->constrained('clientes')->cascadeOnDelete();
            $table->foreignId('responsable_id')->nullable()->constrained('users')->nullOnDelete();
            $table->uuid('lote');
            $table->string('canal', 12);
            $table->string('referencia', 120)->nullable();
            $table->string('estado', 20)->default('pendiente');
            $table->text('nota')->nullable();
            $table->timestamp('respondio_en')->nullable();
            $table->timestamps();
            $table->unique(['responsable_id', 'lote', 'cliente_id'], 'seguimiento_responsable_lote_cliente');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('seguimientos_contacto');
    }
};
