<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Clase extends Model
{
    protected $table = 'clases';
    public $timestamps = true;
    
    protected $fillable = [
        'id_asignatura',
        'num_clase',
        'nombre_clase',
        'link',  
        'url'    
    ];
    
    public function asignatura()
    {
        return $this->belongsTo(Asignatura::class, 'id_asignatura');
    }
}