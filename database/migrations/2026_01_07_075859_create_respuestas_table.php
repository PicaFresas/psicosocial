<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('respuestas', function (Blueprint $table) {
            $table->id();

            // SIN foreign keys
            $table->unsignedBigInteger('evaluacion_id');
            $table->unsignedBigInteger('pregunta_id');
            $table->unsignedBigInteger('user_id');

            $table->text('respuesta')->nullable();

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('respuestas');
    }
};
