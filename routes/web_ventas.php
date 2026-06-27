<?php
// routes/web_ventas.php
// Módulo: Ventas / Pedidos / Cotizaciones

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\VentaController;

Route::prefix('ventas')->name('ventas.')->group(function () {

    // ── Facturación ───────────────────────────────────────────
    Route::get ('/facturacion',                [VentaController::class, 'create'])->name('facturacion');
    Route::post('/facturacion',                [VentaController::class, 'store'])->name('facturacion.store');
    Route::get ('/facturacion/{id}',           [VentaController::class, 'show'])->name('facturacion.show');
    Route::put ('/facturacion/{id}/cerrar',    [VentaController::class, 'cerrar'])->name('facturacion.cerrar');
    Route::put ('/facturacion/{id}/anular',    [VentaController::class, 'anular'])->name('facturacion.anular');
    Route::get('/facturacion/producto/precio', [VentaController::class, 'precioProducto'])->name('facturacion.precio');

    // ── Endpoints AJAX ────────────────────────────────────────
    Route::get ('/facturacion/cliente/{codigo}',    [VentaController::class, 'buscarCliente'])->name('facturacion.cliente');
    Route::get ('/facturacion/producto/{codigo}',   [VentaController::class, 'buscarProducto'])->name('facturacion.producto');
    Route::get('/facturacion/modal/productos',      [VentaController::class, 'buscarProductosModal'])->name('facturacion.modal.productos');
    Route::get('/facturacion/modal/retenciones',    [VentaController::class, 'buscarRetencionesModal'])->name('facturacion.modal.retenciones');
    Route::get ('/facturacion/productos/buscar',    [VentaController::class, 'buscarProductos'])->name('facturacion.productos.buscar'); // Ignorar
    Route::get ('/facturacion/clientes/buscar',     [VentaController::class, 'buscarClientes'])->name('facturacion.clientes.buscar');   // Ignorar

    // ── Resto de rutas placeholder ────────────────────────────
    Route::get('/pedidos-cotizaciones',  fn() => abort(404))->name('pedidos');
    Route::get('/pos',                   fn() => abort(404))->name('pos');
    Route::get('/devoluciones',          fn() => abort(404))->name('devoluciones');
    Route::get('/informe-z',             fn() => abort(404))->name('informe-z');
    Route::get('/inf-documentos',        fn() => abort(404))->name('inf-documentos');
    Route::get('/inf-productos',         fn() => abort(404))->name('inf-productos');
    Route::get('/historial-clientes',    fn() => abort(404))->name('historial-clientes');
});