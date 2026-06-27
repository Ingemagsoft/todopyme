<?php
// routes/web_cxp.php
// Módulo: Cuentas por Pagar

use Illuminate\Support\Facades\Route;

Route::prefix('cxp')->name('cxp.')->group(function () {
    Route::get('/comprobante-egreso',   fn() => abort(404))->name('comprobante-egreso');
    Route::get('/notas-debito',         fn() => abort(404))->name('notas-debito');
    Route::get('/notas-credito',        fn() => abort(404))->name('notas-credito');
    Route::get('/conceptos',            fn() => abort(404))->name('conceptos');
    Route::get('/inf-documentos',       fn() => abort(404))->name('inf-documentos');
    Route::get('/inf-cuentas-pagar',    fn() => abort(404))->name('inf-cuentas-pagar');
});