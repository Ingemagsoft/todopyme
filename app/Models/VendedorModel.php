<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class VendedorModel extends Model
{
    protected $connection = 'tenant';
    protected $table = 'vendedores';

    protected $fillable = [
        'codigo',
        'nombre',
        'documento',
        'telefono',
        'email',
        'comision_ventas',
        'comision_recaudo',
        'activo',
    ];

    protected $casts = [
        'activo'            => 'boolean',
        'comision_ventas'   => 'double',
        'comision_recaudo'  => 'double',
    ];

    public static function activos()
    {
        return static::where('activo', true)->orderBy('nombre');
    }

    public static function sinVendedor()
    {
        return static::where('codigo', 'SIN')->first();
    }
}