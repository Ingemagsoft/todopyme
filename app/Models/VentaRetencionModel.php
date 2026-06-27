<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class VentaRetencionModel extends Model
{
    protected $connection = 'tenant';
    protected $table = 'cia_retenciones_doc';

    protected $fillable = [
        'iddoc',
        'idtiporete',
        'base',
        'por_rete',
        'valor',
    ];

    protected $casts = [
        'base'      => 'double',
        'por_rete'  => 'double',
        'valor'     => 'double',
        'iddoc'     => 'integer',
        'idtiporete'=> 'integer',
    ];

    // ── Relaciones ────────────────────────────────────────────

    public function venta()
    {
        return $this->belongsTo(VentaModel::class, 'iddoc');
    }

    public function tipoRetencion()
    {
        return $this->belongsTo(TipRetencionModel::class, 'idtiporete');
    }

    // ── Métodos de negocio ────────────────────────────────────

    /**
     * Calcula el valor de la retención sobre una base dada.
     * valor = base * (por_rete / 100)
     */
    public function calcularValor(float $base): float
    {
        $this->base  = $base;
        $this->valor = $base * ($this->por_rete / 100);
        return $this->valor;
    }
}