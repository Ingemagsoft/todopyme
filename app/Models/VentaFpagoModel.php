<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class VentaFpagoModel extends Model
{
    protected $connection = 'tenant';
    protected $table = 'cia_fpagos_factura';

    protected $fillable = [
        'iddoc',
        'codtippago',
        'valor',
        'recibido',
        'doc',
    ];

    protected $casts = [
        'iddoc'      => 'integer',
        'codtippago' => 'integer',
        'valor'      => 'double',
        'recibido'   => 'double',
    ];

    // ── Relaciones ────────────────────────────────────────────

    public function venta()
    {
        return $this->belongsTo(VentaModel::class, 'iddoc');
    }

    public function tipoPago()
    {
        return $this->belongsTo(TipPagoModel::class, 'codtippago');
    }
}