<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CiudadModel extends Model
{
    protected $connection = 'tenant';
    protected $table = 'ciudades';

    protected $fillable = [
        'codciudad',
        'nombre',
        'coddepto',
        'departamento',
        'pais',
        'nompais',
        'municipio',
        'activo',
    ];

    protected $casts = [
        'activo' => 'boolean',
    ];

    // ── Relaciones ────────────────────────────────────────────

    public function clientes()
    {
        return $this->hasMany(ClienteModel::class, 'ciudad_id');
    }

    // ── Scopes ────────────────────────────────────────────────

    public static function activas()
    {
        return static::where('activo', true)->orderBy('nombre');
    }

    public static function buscar(string $termino)
    {
        return static::where('activo', true)
            ->where(function ($q) use ($termino) {
                $q->where('nombre',      'like', "%{$termino}%")
                  ->orWhere('codciudad', 'like', "%{$termino}%")
                  ->orWhere('municipio', 'like', "%{$termino}%");
            });
    }

    // ── Accessors ─────────────────────────────────────────────

    public function getNombreCompletoAttribute(): string
    {
        $parts = array_filter([$this->nombre, $this->departamento]);
        return implode(' — ', $parts);
    }
}