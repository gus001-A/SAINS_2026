<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $table = 'usuario';
    protected $primaryKey = 'id';
    public $timestamps = false;

    protected $fillable = [
        'correo',
        'contraseña',
        'rol',
        'google_id',
    ];

    protected $hidden = [
        'contraseña',
    ];

    // Relaciones existentes
    public function administrador()
    {
        return $this->hasOne(Administrador::class, 'usuario_id', 'id');
    }

    public function estudiante()
    {
        return $this->hasOne(Estudiante::class, 'usuario', 'id');
    }

    public function pagosRevisados()
    {
        return $this->hasMany(Pago::class, 'usuario_revision', 'id');
    }

    public function cuponesGenerados()
    {
        return $this->hasMany(Cupon::class, 'usuario_genero', 'id');
    }

    // Verificar si es administrador
    public function isAdmin()
    {
        return $this->rol === 'Administrador';
    }

    // Verificar si es estudiante
    public function isEstudiante()
    {
        return $this->rol === 'estudiante';
    }
}