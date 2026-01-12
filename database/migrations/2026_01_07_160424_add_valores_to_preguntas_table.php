<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('preguntas', function (Blueprint $table) {
            if (!Schema::hasColumn('preguntas', 'valor_si')) {
                $table->integer('valor_si')->default(0);
            }

            if (!Schema::hasColumn('preguntas', 'valor_no')) {
                $table->integer('valor_no')->default(0);
            }
        });
    }

    public function down(): void
    {
        Schema::table('preguntas', function (Blueprint $table) {
            $table->dropColumn(['valor_si', 'valor_no']);
        });
    }
};
