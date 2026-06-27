<?php
// routes/web_compras.php
// Módulo: Compras

use Illuminate\Support\Facades\Route;

Route::prefix('compras')->name('compras.')->group(function () {
    Route::get('/ordenes-compra',         fn() => abort(404))->name('ordenes');
    Route::get('/compras',                fn() => abort(404))->name('compras');
    Route::get('/devoluciones',           fn() => abort(404))->name('devoluciones');
    Route::get('/inf-documentos',         fn() => abort(404))->name('inf-documentos');
    Route::get('/creacion-proveedores',    fn() => abort(404))->name('creacion-proveedores');
    Route::get('/historial-proveedores',   fn() => abort(404))->name('historial-proveedores');
});