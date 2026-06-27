<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class VentaModel extends Model
{
    protected $connection = 'tenant';
    protected $table = 'cia_documentos';

    protected $fillable = [
        // ── Identificación ────────────────────────────────────
        'doc',
        'bodega',
        'tipo',
        'numero',
        'fecreg',
        'fecvto',

        // ── Cliente y condiciones ─────────────────────────────
        'cliente',
        'vendedor',
        'id_forma_pago',
        'dias',
        'cartera',
        'cod_lista_precios',

        // ── Valores ───────────────────────────────────────────
        'vr_bruto',
        'vr_decto',
        'vr_pordecto',
        'vr_iva',
        'vr_retenciones',
        'vr_total',
        'recibido',
        'items',

        // ── Estado ────────────────────────────────────────────
        'anulado',
        'estado',
        'impreso',
        'cufe',

        // ── Extra ─────────────────────────────────────────────
        'obs',
        'codus',
        'feccreacion',
        'hora',
    ];

    protected $casts = [
        'fecreg'          => 'date',
        'fecvto'          => 'date',
        'feccreacion'     => 'date',
        'numero'          => 'double',
        'vr_bruto'        => 'double',
        'vr_decto'        => 'double',
        'vr_pordecto'     => 'double',
        'vr_iva'          => 'double',
        'vr_retenciones'  => 'double',
        'vr_total'        => 'double',
        'recibido'        => 'double',
        'items'           => 'double',
        'anulado'         => 'integer',
        'estado'          => 'integer',
        'id_forma_pago'   => 'integer',
        'cod_lista_precios' => 'integer',
    ];

    // ── Relaciones ────────────────────────────────────────────

    public function itemsFactura()
    {
        return $this->hasMany(VentaItemModel::class, 'iddoc');
    }

    public function retenciones()
    {
        return $this->hasMany(VentaRetencionModel::class, 'iddoc');
    }

    public function formasPago()
    {
        return $this->hasMany(VentaFpagoModel::class, 'iddoc');
    }

    public function clienteRel()
    {
        return $this->belongsTo(ClienteModel::class, 'cliente', 'codigo');
    }

    // ── Scopes ────────────────────────────────────────────────

    public static function activas()
    {
        return static::where('anulado', 0)->orderBy('numero', 'desc');
    }

    public static function porDocumento(string $doc)
    {
        return static::where('doc', $doc)
                     ->where('anulado', 0)
                     ->orderBy('numero', 'desc');
    }

    // ── Métodos de negocio ────────────────────────────────────

    /**
     * Calcula el siguiente número consecutivo para un tipo de documento.
     * Toma el mayor número existente en cia_documentos + 1.
     * Si no hay registros, parte de 1.
     */
    public static function siguienteNumero(string $doc): float
    {
        $ultimo = static::where('doc', $doc)->max('numero');
        return ($ultimo ?? 0) + 1;
    }

    /**
     * Recalcula y actualiza los totales de la factura
     * sumando los ítems registrados en cia_mtoproductos.
     */
    public function recalcularTotales(): void
    {
        $items = $this->itemsFactura;

        $vrBruto    = $items->sum(fn($i) => $i->cant * $i->vr_venta);
        $vrDecto    = $items->sum('vr_decto');
        $vrIva      = $items->sum('vr_iva');
        $vrRet      = $this->retenciones->sum('valor');
        $vrTotal    = $vrBruto - $vrDecto + $vrIva - $vrRet;
        $recibido   = $this->formasPago->sum('valor');

        $this->update([
            'vr_bruto'       => $vrBruto,
            'vr_decto'       => $vrDecto,
            'vr_iva'         => $vrIva,
            'vr_retenciones' => $vrRet,
            'vr_total'       => $vrTotal,
            'recibido'       => $recibido,
            'items'          => $items->count(),
        ]);
    }

    /**
     * Anula la factura — eliminación lógica.
     * Nunca se borra físicamente.
     */
    public function anular(): void
    {
        $this->update([
            'anulado' => 1,
            'estado'  => 0,
        ]);
    }

    /**
     * Cierra la factura — cambia estado a cerrado.
     * Después del cierre se habilitan: imprimir, correo, salir.
     */
    public function cerrar(): void
    {
        $this->update([
            'estado'  => 1,
            'impreso' => 'N',
        ]);
    }

    // ── Accessors ─────────────────────────────────────────────

    public function getEstadoLabelAttribute(): string
    {
        if ($this->anulado) return 'Anulada';
        return match($this->estado) {
            0 => 'Grabada',
            1 => 'Cerrada',
            default => 'Desconocido',
        };
    }

    public function getCambioAttribute(): float
    {
        return max(0, $this->recibido - $this->vr_total);
    }
}