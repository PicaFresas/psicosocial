<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Encuesta;
use App\Models\Pregunta;

class EncuestasNom035Seeder extends Seeder
{
    public function run(): void
    {
        /*
        |----------------------------------------------------------
        | GUÍA DE REFERENCIA I
        |----------------------------------------------------------
        */
        $guiaI = Encuesta::create([
            'titulo' => 'NOM-035 - Guía de Referencia I',
            'descripcion' => 'Acontecimiento traumático severo'
        ]);

        $preguntasGuiaI = [
            '¿Ha presenciado o sufrido algún accidente grave?',
            '¿Ha sido víctima de violencia laboral?',
            '¿Ha presenciado la muerte de un compañero?',
            '¿Ha sufrido amenazas en su trabajo?',
            '¿Ha vivido algún evento traumático en el trabajo?'
        ];

        foreach ($preguntasGuiaI as $i => $texto) {
            Pregunta::create([
                'encuesta_id' => $guiaI->id,
                'texto' => $texto,
                'tipo_respuesta' => 'si_no',
                'orden' => $i + 1
            ]);
        }

        /*
        |----------------------------------------------------------
        | GUÍA DE REFERENCIA II
        |----------------------------------------------------------
        */
        $guiaII = Encuesta::create([
            'titulo' => 'NOM-035 - Guía de Referencia II',
            'descripcion' => 'Factores de riesgo psicosocial'
        ]);

        $preguntasGuiaII = [
            'Mi trabajo me exige hacer mucho esfuerzo físico',
            'Trabajo bajo mucha presión',
            'Tengo jornadas laborales prolongadas',
            'Mi trabajo afecta mi vida familiar',
            'No tengo control sobre mi trabajo'
        ];

        foreach ($preguntasGuiaII as $i => $texto) {
            Pregunta::create([
                'encuesta_id' => $guiaII->id,
                'texto' => $texto,
                'tipo_respuesta' => 'likert',
                'orden' => $i + 1
            ]);
        }

        /*
        |----------------------------------------------------------
        | GUÍA DE REFERENCIA III
        |----------------------------------------------------------
        */
        $guiaIII = Encuesta::create([
            'titulo' => 'NOM-035 - Guía de Referencia III',
            'descripcion' => 'Entorno organizacional'
        ]);

        $preguntasGuiaIII = [
            'Recibo apoyo de mis superiores',
            'Existe buena comunicación en mi área',
            'Mi trabajo es reconocido',
            'Me siento motivado en mi trabajo',
            'Hay buen ambiente laboral'
        ];

        foreach ($preguntasGuiaIII as $i => $texto) {
            Pregunta::create([
                'encuesta_id' => $guiaIII->id,
                'texto' => $texto,
                'tipo_respuesta' => 'likert',
                'orden' => $i + 1
            ]);
        }
    }
}
