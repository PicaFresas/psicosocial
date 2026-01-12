<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Evaluacion extends Model
{



    use HasFactory;

    protected $table = 'evaluaciones';

    protected $fillable = [
        'empresa',
        'area',
        'puesto',
        'actividad',
        'fecha',
        'observaciones',
        'encargado_id',

        // Apéndice I
        'resultado_apendice_i',

        // Apéndice II
        'resultado_apendice_ii',

        // Apéndice III
        'peso_carga',
        'frecuencia',
        'distancia_horizontal',
        'altura_inicial',
        'altura_final',
        'giro_tronco',
        'duracion_tarea',
        'resultado_apendice_iii',
    ];

    public function respuestas()
    {
        return $this->hasMany(Respuesta::class);
    }

    /*
    |--------------------------------------------------------------------------
    | RELACIÓN: ENCARGADO (USUARIO)
    |--------------------------------------------------------------------------
    */
    public function encargado()
    {
        return $this->belongsTo(\App\Models\User::class, 'encargado_id');
    }

    public function calcularPuntajeApendiceII()     
    {
    return Respuesta::where('evaluacion_id', $this->id)
        ->where('respuesta', 'si')
        ->count();
    }

}
