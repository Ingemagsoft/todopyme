<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DocumentoModel extends Model
{
    protected $connection = 'tenant';
    protected $table = 'documentos';

    protected $fillable = [
        'codigo',
        'tipo',
        'descripcion',
        'titulo',
        'ultimo_numero',
        'bodega',
        'predeterminado',
        'obs',
        'activo',
    ];

    protected $casts = [
        'activo'         => 'boolean',
        'predeterminado' => 'boolean',
        'ultimo_numero'  => 'double',
    ];

    // ── Scopes ────────────────────────────────────────────────

    public static function activos()
    {
        return static::where('activo', true)->orderBy('descripcion');
    }

    public static function predeterminado()
    {
        return static::where('activo', true)
                     ->where('predeterminado', true)
                     ->where('tipo', 1) // tipo 1 = ventas
                     ->first();
    }

    // ── Métodos ───────────────────────────────────────────────

    public function siguienteNumero(): float
    {
        return $this->ultimo_numero + 1;
    }

    public function incrementarNumero(): void
    {
        $this->increment('ultimo_numero');
    }
}