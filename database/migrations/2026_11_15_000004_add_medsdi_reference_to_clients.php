<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('clientes', fn (Blueprint $table) => $table->unsignedBigInteger('medsdi_usuario_id')->nullable()->unique());
    }

    public function down(): void
    {
        Schema::table('clientes', function (Blueprint $table) {
            $table->dropUnique(['medsdi_usuario_id']);
            $table->dropColumn('medsdi_usuario_id');
        });
    }
};
