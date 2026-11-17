<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('planes', fn (Blueprint $table) => $table->string('nivel', 20)->nullable());
    }

    public function down(): void
    {
        Schema::table('planes', fn (Blueprint $table) => $table->dropColumn('nivel'));
    }
};
