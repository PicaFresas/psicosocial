<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('evaluaciones', function (Blueprint $table) {
            $table->id();

            $table->unsignedBigInteger('encargado_id')->nullable();

            $table->string('empresa')->nullable();
            $table->string('area')->nullable();
            $table->string('puesto')->nullable();
            $table->string('actividad')->nullable();
            $table->date('fecha')->nullable();

            $table->boolean('resultado_apendice_i')->nullable();
            $table->boolean('resultado_apendice_ii')->nullable();
            $table->boolean('resultado_apendice_iii')->nullable();

            $table->text('observaciones')->nullable();

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('evaluaciones');
    }
};
