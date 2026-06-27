<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProductoPrecioModel extends Model
{
    protected $connection = 'tenant';
    protected $table = 'productos_precios';

    protected $fillable = [
        'codproducto',
        'codlista',
        'precio',
        'pordecto',
        'activo',
    ];

    protected $casts = [
        'activo'   => 'boolean',
        'precio'   => 'double',
        'pordecto' => 'double',
        'codlista' => 'integer',
    ];

    public function producto()
    {
        return $this->belongsTo(ProductoModel::class, 'codproducto', 'codigo');
    }

    public static function precioProducto(string $codproducto, int $codlista)
    {
        return static::where('codproducto', $codproducto)
                     ->where('codlista', $codlista)
                     ->where('activo', true)
                     ->first();
    }
}