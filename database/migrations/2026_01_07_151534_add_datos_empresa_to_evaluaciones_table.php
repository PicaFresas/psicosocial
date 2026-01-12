<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('evaluaciones', function (Blueprint $table) {
            $table->string('empresa')->nullable();
            $table->string('area')->nullable();
            $table->string('puesto')->nullable();
            $table->string('actividad')->nullable();
            $table->date('fecha')->nullable();
            $table->text('observaciones')->nullable();
        });
    }

    public function down(): void
    {
        Schema::table('evaluaciones', function (Blueprint $table) {
            $table->dropColumn([
                'empresa',
                'area',
                'puesto',
                'actividad',
                'fecha',
                'observaciones',
            ]);
        });
    }
};
