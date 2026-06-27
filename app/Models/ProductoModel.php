<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProductoModel extends Model
{
    protected $connection = 'tenant';
    protected $table = 'productos';

    protected $fillable = [
        'codigo',
        'codbarras',
        'descripcion',
        'unidad',
        'tipproducto',
        'grupo',
        'subgrupo',
        'linea',
        'vrcompra',
        'poriva',
        'pordecto',
        'servicio',
        'preciomod',
        'activo',
    ];

    protected $casts = [
        'activo'    => 'boolean',
        'servicio'  => 'boolean',
        'preciomod' => 'boolean',
        'poriva'    => 'integer',
        'vrcompra'  => 'double',
        'pordecto'  => 'double',
    ];

    // ── Relaciones ────────────────────────────────────────────

    public function precios()
    {
        return $this->hasMany(ProductoPrecioModel::class, 'codproducto', 'codigo');
    }

    public function precioLista(int $codlista)
    {
        return $this->precios()->where('codlista', $codlista)->first();
    }

    // ── Scopes ────────────────────────────────────────────────

    public static function activos()
    {
        return static::where('activo', true)->orderBy('descripcion');
    }

    public static function buscar(string $termino)
    {
        return static::where('activo', true)
            ->where(function ($q) use ($termino) {
                $q->where('codigo',      'like', "%{$termino}%")
                  ->orWhere('descripcion','like', "%{$termino}%")
                  ->orWhere('codbarras', 'like', "%{$termino}%");
            });
    }
}