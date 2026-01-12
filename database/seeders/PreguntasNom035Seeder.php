<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Pregunta;

class PreguntasNom035Seeder extends Seeder
{
    public function run(): void
    {
        Pregunta::truncate();

        Pregunta::create([
            'seccion' => 'Apendice I',
            'texto' => '¿Ha presenciado o sufrido algún acontecimiento traumático severo?',
            'orden' => 1,
            'valor_si' => 1,
            'valor_no' => 0,
        ]);

        Pregunta::create([
            'seccion' => 'Apendice I',
            'texto' => '¿Tiene recuerdos recurrentes sobre dicho acontecimiento?',
            'orden' => 2,
            'valor_si' => 1,
            'valor_no' => 0,
        ]);

        Pregunta::create([
            'seccion' => 'Apendice I',
            'texto' => '¿Evita lugares o situaciones que le recuerdan el evento?',
            'orden' => 3,
            'valor_si' => 1,
            'valor_no' => 0,
        ]);
    }
}
