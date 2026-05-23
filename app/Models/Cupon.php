<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Cupon extends Model
{
    protected $table = 'cupones';
    protected $primaryKey = 'id';
    public $timestamps = false;

    protected $fillable = [
        'codigo',
        'usado',
        'usuario_uso',
        'usuario_genero',
        'estatus',
        'fecha_genero',
        'fecha_uso',
        'fecha_expiracion',  // ← NUEVO CAMPO
        'tipo_descuento',
        'valor_descuento'
    ];

    protected $casts = [
        'usado' => 'boolean',
        'fecha_genero' => 'datetime',
        'fecha_uso' => 'datetime',
        'fecha_expiracion' => 'datetime',  // ← NUEVO CAST
        'valor_descuento' => 'decimal:2'
    ];

    // Relación con el usuario que GENERÓ el cupón
    public function usuarioGenero()
    {
        return $this->belongsTo(User::class, 'usuario_genero', 'id');
    }

    // Relación con el usuario que USÓ el cupón
    public function usuarioUso()
    {
        return $this->belongsTo(User::class, 'usuario_uso', 'id');
    }

    // Método para verificar si el cupón ha expirado
    public function isExpired()
    {
        if (!$this->fecha_expiracion) {
            return false;
        }
        return now()->greaterThan($this->fecha_expiracion);
    }

    // Método para verificar si el cupón es válido (no usado y no expirado)
    public function isValid()
    {
        return !$this->usado && !$this->isExpired();
    }
}