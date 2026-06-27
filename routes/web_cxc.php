<?php
// routes/web_cxc.php
// Módulo: Cuentas por Cobrar

use Illuminate\Support\Facades\Route;

Route::prefix('cxc')->name('cxc.')->group(function () {
    Route::get('/recibos-caja',         fn() => abort(404))->name('recibos-caja');
    Route::get('/notas-debito',         fn() => abort(404))->name('notas-debito');
    Route::get('/notas-credito',        fn() => abort(404))->name('notas-credito');
    Route::get('/conceptos',            fn() => abort(404))->name('conceptos');
    Route::get('/inf-documentos',       fn() => abort(404))->name('inf-documentos');
    Route::get('/inf-cartera',          fn() => abort(404))->name('inf-cartera');
});