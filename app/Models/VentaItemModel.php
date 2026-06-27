<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class VentaItemModel extends Model
{
    protected $connection = 'tenant';
    protected $table = 'cia_mtoproductos';

    protected $fillable = [
        'iddoc',
        'doc',
        'tipo',
        'numero',
        'bodega',
        'codproducto',
        'detalle',
        'fecha',
        'cant',
        'vr_venta',
        'por_iva',
        'vr_iva',
        'por_decto',
        'vr_decto',
        'vr_total',
        'vendedor',
        'feccreacion',
        'hora',
    ];

    protected $casts = [
        'fecha'       => 'date',
        'feccreacion' => 'date',
        'cant'        => 'double',
        'vr_venta'    => 'double',
        'por_iva'     => 'double',
        'vr_iva'      => 'double',
        'por_decto'   => 'double',
        'vr_decto'    => 'double',
        'vr_total'    => 'double',
        'tipo'        => 'integer',
        'numero'      => 'double',
    ];

    // ── Relaciones ────────────────────────────────────────────

    public function venta()
    {
        return $this->belongsTo(VentaModel::class, 'iddoc');
    }

    public function producto()
    {
        return $this->belongsTo(ProductoModel::class, 'codproducto', 'codigo');
    }

    // ── Métodos de negocio ────────────────────────────────────

    /**
     * Calcula el total del ítem:
     * (cant * vr_venta) - vr_decto + vr_iva
     */
    public function calcularTotal(): float
    {
        $subtotal       = $this->cant * $this->vr_venta;
        $this->vr_decto = $subtotal * ($this->por_decto / 100);
        $baseIva        = $subtotal - $this->vr_decto;
        $this->vr_iva   = $baseIva * ($this->por_iva / 100);
        $this->vr_total = $baseIva + $this->vr_iva;
        return $this->vr_total;
    }

    // ── Accessors ─────────────────────────────────────────────

    public function getSubtotalAttribute(): float
    {
        return $this->cant * $this->vr_venta;
    }
}