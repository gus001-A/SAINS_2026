<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Pregunta extends Model
{
    protected $table = 'preguntas';
    protected $primaryKey = 'id';
    public $timestamps = false;

    protected $fillable = [
        'id_area', 
        'pregunta', 
        'respuesta_correcta',
        'respuesta1', 
        'respuesta2',
        'justificacion'  
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
    
    public function examenesGenerados()
    {
        return $this->belongsToMany(ExamenGenerado::class, 'apoyo_preguntas', 'pregunta', 'examen');
    }
    
    // Método para obtener la justificación formateada (opcional pero útil)
    public function getJustificacionFormateadaAttribute()
    {
        if (empty($this->justificacion)) {
            return "La respuesta correcta es: {$this->respuesta_correcta}";
        }
        return $this->justificacion;
    }
}