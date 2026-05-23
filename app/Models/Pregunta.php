<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Pregunta extends Model
{
    protected $table = 'preguntas';
    protected $primaryKey = 'id';
    public $timestamps = false;

    protected $fillable = [
        'id_area', 'pregunta', 'respuesta_correcta',
        'respuesta1', 'respuesta2'
    ];

    // Relación con áreas
    public function area()
    {
        return $this->belongsTo(AreaPregunta::class, 'id_area', 'id');
    }

    // Relación con apoyos
    public function apoyos()
    {
        return $this->hasMany(ApoyoPregunta::class, 'pregunta', 'id');
    }
    
    // ✅ CORREGIDO: Relación con exámenes usando la tabla correcta
    public function examenesGenerados()
    {
        return $this->belongsToMany(ExamenGenerado::class, 'apoyo_preguntas', 'pregunta', 'examen');
    }
}