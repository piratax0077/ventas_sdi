<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    private const TABLES = ['productos', 'productos_software', 'planes'];

    public function up(): void
    {
        foreach (self::TABLES as $tableName) {
            if (!Schema::hasColumn($tableName, 'segmento_catalogo')) {
                Schema::table($tableName, fn (Blueprint $table) =>
                    $table->string('segmento_catalogo', 64)->nullable()
                );
            }
        }
    }

    public function down(): void
    {
        foreach (self::TABLES as $tableName) {
            if (Schema::hasColumn($tableName, 'segmento_catalogo')) {
                Schema::table($tableName, fn (Blueprint $table) => $table->dropColumn('segmento_catalogo'));
            }
        }
    }
};
