<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TipRetencionModel extends Model
{
    protected $connection = 'tenant';
    protected $table = 'tipretenciones';

    protected $fillable = [
        'descripcion',
        'base',
        'por',
        'cuenta',
        'tipo',
        'naturaleza',
        'activo',
    ];

    protected $casts = [
        'activo'     => 'boolean',
        'base'       => 'double',
        'por'        => 'double',
        'tipo'       => 'integer',
    ];

    public static function activas()
    {
        return static::where('activo', true)->orderBy('descripcion');
    }

    public static function buscar(string $termino)
    {
        return static::where('activo', true)
            ->where('descripcion', 'like', "%{$termino}%");
    }
}