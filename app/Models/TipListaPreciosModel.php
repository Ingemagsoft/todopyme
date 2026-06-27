<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TipListaPreciosModel extends Model
{
    protected $connection = 'tenant';
    protected $table = 'tiplistaprecios';

    protected $fillable = [
        'codigo',
        'descripcion',
        'activo',
    ];

    protected $casts = [
        'activo' => 'boolean',
        'codigo' => 'integer',
    ];

    public static function activas()
    {
        return static::where('activo', true)->orderBy('codigo');
    }

    public static function principal()
    {
        return static::where('activo', true)
                     ->where('codigo', 1)
                     ->first();
    }
}