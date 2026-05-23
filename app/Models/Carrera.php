<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Carrera extends Model
{
    protected $table = 'carreras';
    protected $primaryKey = 'id';
    public $timestamps = false;

    protected $fillable = [
        'nombre', 'tronco_id', 'id_asignatura_1', 'id_asignatura_2', 'id_asignatura_3'
    ];

    public function tronco()
    {
        return $this->belongsTo(Tronco::class, 'tronco_id', 'id');
    }

    public function asignatura1()
    {
        return $this->belongsTo(Asignatura::class, 'id_asignatura_1', 'id');
    }

    public function asignatura2()
    {
        return $this->belongsTo(Asignatura::class, 'id_asignatura_2', 'id');
    }

    public function asignatura3()
    {
        return $this->belongsTo(Asignatura::class, 'id_asignatura_3', 'id');
    }

    public function universidades()
    {
        return $this->hasMany(Universidad::class, 'carrera_id', 'id');
    }
}