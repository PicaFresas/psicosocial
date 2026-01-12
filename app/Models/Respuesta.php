<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Respuesta extends Model
{
    use HasFactory;

    protected $table = 'respuestas';

    protected $fillable = [
        'evaluacion_id',
        'pregunta_id',
        'respuesta',
        'valor',
    ];

    public function pregunta()
    {
        return $this->belongsTo(Pregunta::class);
    }

    public function evaluacion()
    {
        return $this->belongsTo(Evaluacion::class);
    }
}
