<?php
// routes/web_gastos.php
// Módulo: Movimiento de Gastos

use Illuminate\Support\Facades\Route;

Route::prefix('gastos')->name('gastos.')->group(function () {
    Route::get('/registro',             fn() => abort(404))->name('registro');
    Route::get('/inf-gastos',           fn() => abort(404))->name('inf-gastos');
    Route::get('/cuadre-caja',          fn() => abort(404))->name('cuadre-caja');
    Route::get('/consulta-cuadre',      fn() => abort(404))->name('consulta-cuadre');
    Route::get('/creacion-gastos',      fn() => abort(404))->name('creacion-gastos');
    Route::get('/clasificacion-gastos', fn() => abort(404))->name('clasificacion-gastos');
    Route::get('/asociacion-sedes',     fn() => abort(404))->name('asociacion-sedes');
});