<?php

namespace App\Http\Controllers;

use App\Models\VentaModel;
use App\Models\VentaItemModel;
use App\Models\VentaRetencionModel;
use App\Models\VentaFpagoModel;
use App\Models\ClienteModel;
use App\Models\ProductoModel;
use App\Models\ProductoPrecioModel;
use App\Models\DocumentoModel;
use App\Models\FormaPagoModel;
use App\Models\VendedorModel;
use App\Models\TipListaPreciosModel;
use App\Models\TipRetencionModel;
use App\Models\TipPagoModel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class VentaController extends Controller
{
    // ─── Pantalla de facturación ─────────────────────────────────
    public function create()
    {
        $documento    = DocumentoModel::predeterminado();
        $numero       = VentaModel::siguienteNumero($documento->codigo ?? 'FV');
        $formasPago   = FormaPagoModel::activas()->get();
        $vendedores   = VendedorModel::activos()->get();
        $listas       = TipListaPreciosModel::activas()->get();
        $tippagos     = TipPagoModel::activos()->get();

        return view('ventas.facturacion_view', compact(
            'documento',
            'numero',
            'formasPago',
            'vendedores',
            'listas',
            'tippagos',
        ));
    }

    // ─── Cargar factura existente ────────────────────────────────
    public function show($id)
    {
        $venta      = VentaModel::with([
                            'itemsFactura',
                            'retenciones.tipoRetencion',
                            'formasPago.tipoPago',
                            'clienteRel',
                        ])->findOrFail($id);

        $documento  = DocumentoModel::where('codigo', $venta->doc)->first();
        $formasPago = FormaPagoModel::activas()->get();
        $vendedores = VendedorModel::activos()->get();
        $listas     = TipListaPreciosModel::activas()->get();
        $tippagos   = TipPagoModel::activos()->get();

        return view('ventas.facturacion_view', compact(
            'venta',
            'documento',
            'formasPago',
            'vendedores',
            'listas',
            'tippagos',
        ));
    }

    // ─── Grabar factura ──────────────────────────────────────────
    public function store(Request $request)
    {
        $request->validate([
            'doc'             => 'required',
            'cliente'         => 'required',
            'id_forma_pago'   => 'required',
            'fecreg'          => 'required|date',
            'items'           => 'required|array|min:1',
            'items.*.codproducto' => 'required',
            'items.*.cant'        => 'required|numeric|min:0.01',
            'items.*.vr_venta'    => 'required|numeric|min:0',
        ], [
            'doc.required'              => 'El tipo de documento es obligatorio.',
            'cliente.required'          => 'Debe seleccionar un cliente.',
            'id_forma_pago.required'    => 'La forma de pago es obligatoria.',
            'fecreg.required'           => 'La fecha es obligatoria.',
            'items.required'            => 'Debe agregar al menos un producto.',
            'items.min'                 => 'Debe agregar al menos un producto.',
            'items.*.codproducto.required' => 'El código de producto es obligatorio.',
            'items.*.cant.required'        => 'La cantidad es obligatoria.',
            'items.*.cant.min'             => 'La cantidad debe ser mayor a 0.',
            'items.*.vr_venta.required'    => 'El precio es obligatorio.',
        ]);

        /** @var VentaModel $venta */
        $venta = null;

        try {
            DB::connection('tenant')->transaction(function () use ($request, &$venta) {

                $formaPago = FormaPagoModel::find($request->id_forma_pago);
                $numero    = VentaModel::siguienteNumero($request->doc);
                $documento = DocumentoModel::where('codigo', $request->doc)
                                ->lockForUpdate()  // bloquea el registro hasta que termine la transacción
                                ->first();

                $numero = $documento->ultimo_numero + 1;

                $now       = now();

                // ── 1. Grabar cabecera ────────────────────────
                $venta = VentaModel::create([
                    'doc'              => $request->doc,
                    'bodega'           => $request->bodega ?? '01',
                    'tipo'             => 1,
                    'numero'           => $numero,
                    'fecreg'           => $request->fecreg,
                    'fecvto'           => $formaPago
                                            ? now()->addDays($formaPago->dias)->toDateString()
                                            : $request->fecreg,
                    'cliente'          => $request->cliente,
                    'vendedor'         => $request->vendedor ?? 'SIN',
                    'id_forma_pago'    => $request->id_forma_pago,
                    'dias'             => $formaPago->dias ?? 0,
                    'cartera'          => $formaPago->cartera ?? 0,
                    'cod_lista_precios'=> $request->cod_lista_precios ?? 1,
                    'obs'              => $request->obs,
                    'codus'            => session('usuario_codigo', 'ADM'),
                    'feccreacion'      => $now->toDateString(),
                    'hora'             => $now->toTimeString(),
                    'anulado'          => 0,
                    'estado'           => 0,
                    // Totales en 0 — se recalculan al final
                    'vr_bruto'         => 0,
                    'vr_decto'         => 0,
                    'vr_pordecto'      => 0,
                    'vr_iva'           => 0,
                    'vr_retenciones'   => 0,
                    'vr_total'         => 0,
                    'recibido'         => 0,
                    'items'            => 0,
                ]);

                // ── 2. Grabar ítems ───────────────────────────
                foreach ($request->items as $item) {
                    $subtotal = $item['cant'] * $item['vr_venta'];
                    $vrDecto  = $subtotal * (($item['por_decto'] ?? 0) / 100);
                    $baseIva  = $subtotal - $vrDecto;
                    $vrIva    = $baseIva * (($item['por_iva'] ?? 0) / 100);
                    $vrTotal  = $baseIva + $vrIva;

                    VentaItemModel::create([
                        'iddoc'       => $venta->id,
                        'doc'         => $request->doc,
                        'tipo'        => 1,
                        'numero'      => $numero,
                        'bodega'      => $request->bodega ?? '01',
                        'codproducto' => $item['codproducto'],
                        'detalle'     => $item['detalle'] ?? null,
                        'fecha'       => $request->fecreg,
                        'cant'        => $item['cant'],
                        'vr_venta'    => $item['vr_venta'],
                        'por_iva'     => $item['por_iva'] ?? 0,
                        'vr_iva'      => $vrIva,
                        'por_decto'   => $item['por_decto'] ?? 0,
                        'vr_decto'    => $vrDecto,
                        'vr_total'    => $vrTotal,
                        'vendedor'    => $request->vendedor ?? 'SIN',
                        'feccreacion' => $now->toDateString(),
                        'hora'        => $now->toTimeString(),
                    ]);
                }

                // ── 3. Grabar retenciones ─────────────────────
                if ($request->filled('retenciones')) {
                    foreach ($request->retenciones as $ret) {
                        VentaRetencionModel::create([
                            'iddoc'      => $venta->id,
                            'idtiporete' => $ret['idtiporete'],
                            'base'       => $ret['base'],
                            'por_rete'   => $ret['por_rete'],
                            'valor'      => $ret['valor'],
                        ]);
                    }
                }

                // ── 4. Grabar medios de pago ──────────────────
                if ($request->filled('pagos')) {
                    foreach ($request->pagos as $pago) {
                        VentaFpagoModel::create([
                            'iddoc'      => $venta->id,
                            'codtippago' => $pago['codtippago'],
                            'valor'      => $pago['valor'],
                            'recibido'   => $pago['valor'],
                            'doc'        => $pago['doc'] ?? null,
                        ]);
                    }
                }

                // ── 5. Recalcular totales ─────────────────────
                $venta->recalcularTotales();

                // ── 6. Incrementar consecutivo ────────────────
                $documento->update(['ultimo_numero' => $numero]);

            });

            return response()->json([
                'success' => true,
                'id'      => $venta->id,
                'numero'  => $venta->numero,
                'mensaje' => "Factura #{$venta->numero} grabada correctamente.",
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'mensaje' => 'Error al grabar la factura: ' . $e->getMessage(),
            ], 500);
        }
    }

    // ─── Cerrar factura ──────────────────────────────────────────
    public function cerrar($id)
    {
        $venta = VentaModel::where('id', $id)
                           ->where('anulado', 0)
                           ->where('estado', 0)
                           ->firstOrFail();

        $venta->cerrar();

        // ── Disparar Job PDF en segundo plano ─────────────────
        // App\Jobs\GenerarPdfFactura::dispatch($venta->id);
        // Se activa en la Fase 5

        return response()->json([
            'success' => true,
            'mensaje' => "Factura #{$venta->numero} cerrada correctamente.",
        ]);
    }

    // ─── Anular factura ──────────────────────────────────────────
    public function anular($id)
    {
        $venta = VentaModel::where('id', $id)
                           ->where('anulado', 0)
                           ->firstOrFail();

        $venta->anular();

        return response()->json([
            'success' => true,
            'mensaje' => "Factura #{$venta->numero} anulada correctamente.",
        ]);
    }

    // ─── AJAX — buscar cliente por código ────────────────────────
    public function buscarCliente($codigo)
    {
        $cliente = ClienteModel::where('codigo', $codigo)
                               ->where('activo', true)
                               ->first();

        if (!$cliente) {
            return response()->json(['found' => false]);
        }

        $formaPago = FormaPagoModel::find($cliente->id_forma_pago);

        return response()->json([
            'found'             => true,
            'id'                => $cliente->id,
            'codigo'            => $cliente->codigo,
            'nombre'            => $cliente->nombre_completo,
            'documento'         => $cliente->documento_formateado,
            'tipo_documento'    => $cliente->tipo_documento,
            'numero_documento'  => $cliente->numero_documento,
            'dv'                => $cliente->digito_verificacion,
            'razon_social'      => $cliente->razon_social,
            'primer_nombre'     => $cliente->primer_nombre,
            'segundo_nombre'    => $cliente->segundo_nombre,
            'primer_apellido'   => $cliente->primer_apellido,
            'segundo_apellido'  => $cliente->segundo_apellido,
            'rep_legal_primer_nombre'    => $cliente->rep_legal_primer_nombre,
            'rep_legal_segundo_nombre'   => $cliente->rep_legal_segundo_nombre,
            'rep_legal_primer_apellido'  => $cliente->rep_legal_primer_apellido,
            'rep_legal_segundo_apellido' => $cliente->rep_legal_segundo_apellido,
            'rep_legal_documento'        => $cliente->rep_legal_documento,
            'direccion'         => $cliente->direccion,
            'telefono'          => $cliente->telefono,
            'celular'           => $cliente->celular,
            'email'             => $cliente->email,
            'ciudad_id'         => $cliente->ciudad_id,
            'cod_lista_precios' => $cliente->cod_lista_precios,
            'id_forma_pago'     => $cliente->id_forma_pago,
            'forma_pago_nombre' => $formaPago->descripcion ?? 'Contado',
            'dias'              => $formaPago->dias ?? 0,
            'cartera'           => $formaPago->cartera ?? 0,
            'descuento'         => $cliente->descuento,
            'cod_vendedor'      => $cliente->cod_vendedor,
        ]);
    }

    // ─── Guardar cliente desde modal ─────────────────────────────
    public function storeModal(Request $request)
    {
        $esNit = $request->filled('digito_verificacion');

        $rules = [
            'codigo'           => 'required|alpha_num|unique:tenant.clientes,codigo',
            'tipo_documento'   => 'required',
            'numero_documento' => 'required',
            'direccion'        => 'required',
        ];

        if ($esNit) {
            $rules['razon_social'] = 'required';
        } else {
            $rules['primer_apellido'] = 'required';
            $rules['primer_nombre']   = 'required';
        }

        try {
            $request->validate($rules);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),
            ], 422);
        }

        $cliente = ClienteModel::create([
            'codigo'                    => strtoupper($request->codigo),
            'tipo_cliente'              => 'Cliente',
            'tipo_documento'            => $request->tipo_documento,
            'numero_documento'          => $request->numero_documento,
            'digito_verificacion'       => $request->digito_verificacion ?: null,
            'razon_social'              => $esNit ? $request->razon_social               : null,
            'rep_legal_primer_nombre'   => $esNit ? $request->rep_legal_primer_nombre    : null,
            'rep_legal_segundo_nombre'  => $esNit ? $request->rep_legal_segundo_nombre   : null,
            'rep_legal_primer_apellido' => $esNit ? $request->rep_legal_primer_apellido  : null,
            'rep_legal_segundo_apellido'=> $esNit ? $request->rep_legal_segundo_apellido : null,
            'rep_legal_documento'       => $esNit ? $request->rep_legal_documento        : null,
            'primer_nombre'             => !$esNit ? $request->primer_nombre    : null,
            'segundo_nombre'            => !$esNit ? $request->segundo_nombre   : null,
            'primer_apellido'           => !$esNit ? $request->primer_apellido  : null,
            'segundo_apellido'          => !$esNit ? $request->segundo_apellido : null,
            'email'                     => $request->email,
            'telefono'                  => $request->telefono,
            'celular'                   => $request->celular,
            'direccion'                 => $request->direccion,
            'ciudad_id'                 => $request->ciudad_id ?: null,
            'activo'                    => true,
            'cod_lista_precios'         => 1,
            'cod_vendedor'              => '0',
            'id_forma_pago'             => 0,
            'descuento'                 => 0,
        ]);

        return response()->json([
            'success' => true,
            'id'      => $cliente->id,
            'codigo'  => $cliente->codigo,
            'nombre'  => $cliente->nombre_completo,
        ]);
    }

    // ─── AJAX — buscar producto por código ───────────────────────
    public function buscarProducto($codigo)
    {
        $producto = ProductoModel::where('codigo', $codigo)
                                 ->where('activo', true)
                                 ->first();

        if (!$producto) {
            return response()->json(['found' => false]);
        }

        return response()->json([
            'found'       => true,
            'codigo'      => $producto->codigo,
            'descripcion' => $producto->descripcion,
            'unidad'      => $producto->unidad,
            'poriva'      => $producto->poriva,
            'pordecto'    => $producto->pordecto,
            'preciomod'   => $producto->preciomod,
        ]);
    }

    // ─── AJAX — precio del producto según lista ───────────────────
    public function precioProducto(Request $request)
    {
        $precio = ProductoPrecioModel::precioProducto(
            $request->codproducto,
            $request->codlista ?? 1
        );

        return response()->json([
            'precio'   => $precio->precio ?? 0,
            'pordecto' => $precio->pordecto ?? 0,
        ]);
    }

    // ─── AJAX — búsqueda progresiva clientes (modal) ─────────────
    public function buscarClientes(Request $request)
    {
        $termino  = trim($request->input('q', ''));

        if (strlen($termino) < 2) {
            return response()->json([]);
        }

        $clientes = ClienteModel::buscar($termino)
                        ->select('codigo', 'razon_social', 'primer_nombre',
                                 'primer_apellido', 'numero_documento', 'telefono')
                        ->limit(15)
                        ->get()
                        ->map(fn($c) => [
                            'codigo'    => $c->codigo,
                            'nombre'    => $c->nombre_completo,
                            'documento' => $c->documento_formateado,
                            'telefono'  => $c->telefono,
                        ]);

        return response()->json($clientes);
    }

    // ─── AJAX — búsqueda progresiva productos (modal) ────────────
    public function buscarProductos(Request $request)
    {
        $termino  = trim($request->input('q', ''));
        $codlista = $request->input('codlista', 1);

        if (strlen($termino) < 2) {
            return response()->json([]);
        }

        $productos = ProductoModel::buscar($termino)
                        ->select('codigo', 'descripcion', 'unidad', 'poriva', 'pordecto')
                        ->limit(15)
                        ->get()
                        ->map(function ($p) use ($codlista) {
                            $precio = ProductoPrecioModel::precioProducto($p->codigo, $codlista);
                            return [
                                'codigo'      => $p->codigo,
                                'descripcion' => $p->descripcion,
                                'unidad'      => $p->unidad,
                                'poriva'      => $p->poriva,
                                'pordecto'    => $p->pordecto,
                                'precio'      => $precio->precio ?? 0,
                            ];
                        });

        return response()->json($productos);
    }

    // ─── Modal buscar productos ──────────────────────────────────
    public function buscarProductosModal()
    {
        return view('partials.modals.modal_buscar_productos');
    }

    // ___ Modal buscar Retenciones ________________________________
        public function buscarRetencionesModal()
    {
        $retenciones = TipRetencionModel::activas()->get();
        return view('partials.modals.modal_buscar_retenciones', compact('retenciones'));
    }
}
