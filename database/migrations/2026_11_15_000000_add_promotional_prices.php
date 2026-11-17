<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('productos', function (Blueprint $table) {
            $table->decimal('precio_promocional', 12, 2)->nullable();
        });
        Schema::table('planes', function (Blueprint $table) {
            $table->decimal('precio_promocional', 10, 2)->nullable();
        });
    }

    public function down(): void
    {
        Schema::table('productos', fn (Blueprint $table) => $table->dropColumn('precio_promocional'));
        Schema::table('planes', fn (Blueprint $table) => $table->dropColumn('precio_promocional'));
    }
};
