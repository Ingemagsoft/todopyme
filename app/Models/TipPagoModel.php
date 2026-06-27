<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TipPagoModel extends Model
{
    protected $connection = 'tenant';
    protected $table = 'tippagos';

    protected $fillable = [
        'codigo',
        'descripcion',
        'cuenta',
        'naturaleza',
        'codfe',
        'activo',
    ];

    protected $casts = [
        'activo' => 'boolean',
        'codigo' => 'integer',
    ];

    public static function activos()
    {
        return static::where('activo', true)->orderBy('descripcion');
    }

    public static function efectivo()
    {
        return static::where('activo', true)
                     ->where('codigo', 1)
                     ->first();
    }
}