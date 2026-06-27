<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ClienteModel extends Model
{
    protected $connection = 'tenant';
    protected $table = 'clientes';   // Especificar el nombre de la tabla si no sigue la convención

    protected $fillable = [
        // ── Grupo 1 — Identificación ─────────────────────────
        'codigo',
        'tipo_cliente',
        'tipo_documento',
        'numero_documento',
        'digito_verificacion',

        // ── Grupo 2 — Nombre ──────────────────────────────────
        'razon_social',
        'primer_apellido',
        'segundo_apellido',
        'primer_nombre',
        'segundo_nombre',

        // ── Grupo 3 — Rep. legal ──────────────────────────────
        'rep_legal_primer_nombre',
        'rep_legal_segundo_nombre',
        'rep_legal_primer_apellido',
        'rep_legal_segundo_apellido',
        'rep_legal_documento',

        // ── Grupo 4 — Contacto ────────────────────────────────
        'email',
        'telefono',
        'celular',
        'direccion',
        'ciudad_id',
        'codigo_postal',

        // ── Grupo 5 — Comercial ───────────────────────────────
        'cod_lista_precios',
        'cod_vendedor',
        'id_forma_pago',
        'descuento',
        'observaciones',

        // ── Grupo 6 — Control ─────────────────────────────────
        'activo',
    ];

    protected $casts = [
        'activo'        => 'boolean',
        'descuento'     => 'double',
        'cod_lista_precios' => 'integer',
        'id_forma_pago' => 'integer',
    ];

    // ── Relaciones ────────────────────────────────────────────

    public function ciudad()
    {
        return $this->belongsTo(CiudadModel::class, 'ciudad_id');
    }

    // ── Scopes ────────────────────────────────────────────────

    public static function activos()
    {
        return static::where('activo', true)->orderBy('razon_social')->orderBy('primer_apellido');
    }

    public static function buscar(string $termino)
    {
        return static::where('activo', true)
            ->where(function ($q) use ($termino) {
                $q->where('codigo',           'like', "%{$termino}%")
                  ->orWhere('razon_social',   'like', "%{$termino}%")
                  ->orWhere('primer_nombre',  'like', "%{$termino}%")
                  ->orWhere('primer_apellido','like', "%{$termino}%")
                  ->orWhere('numero_documento','like', "%{$termino}%");
            });
    }

    // ── Accessors-nombre completo para mostrar en listados ──

    public function getNombreCompletoAttribute(): string
    {
        if ($this->razon_social) {
            return $this->razon_social;
        }

        return trim(preg_replace('/\s+/', ' ',
            "{$this->primer_nombre} {$this->segundo_nombre} " .
            "{$this->primer_apellido} {$this->segundo_apellido}"
        ));
    }

    // Accessor — documento formateado para mostrar
    
    public function getDocumentoFormateadoAttribute(): string
    {
        if ($this->digito_verificacion) {
            return "{$this->numero_documento}-{$this->digito_verificacion}";
        }
        return $this->numero_documento ?? '';
    }
}