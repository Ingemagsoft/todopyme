<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class FormaPagoModel extends Model
{
    protected $connection = 'tenant';
    protected $table = 'formaspago';

    protected $fillable = [
        'descripcion',
        'dias',
        'cartera',
        'activo',
    ];

    protected $casts = [
        'activo'  => 'boolean',
        'dias'    => 'integer',
        'cartera' => 'integer',
    ];

    // ── Scopes ────────────────────────────────────────────────

    public static function activas()
    {
        return static::where('activo', true)->orderBy('descripcion');
    }

    public static function contado()
    {
        return static::where('activo', true)
                     ->where('cartera', 0)
                     ->first();
    }
}