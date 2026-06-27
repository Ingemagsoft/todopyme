<?php
// routes/web_inventarios.php
// Módulo: Inventarios

use Illuminate\Support\Facades\Route;

Route::prefix('inventarios')->name('inventarios.')->group(function () {
    Route::get('/entrada-salida',       fn() => abort(404))->name('entrada-salida');
    Route::get('/traslados',            fn() => abort(404))->name('traslados');
    Route::get('/inf-documentos',       fn() => abort(404))->name('inf-documentos');
    Route::get('/inf-rotativo',         fn() => abort(404))->name('inf-rotativo');
    Route::get('/inf-inventarios',      fn() => abort(404))->name('inf-inventarios');
    Route::get('/inf-kardex',           fn() => abort(404))->name('inf-kardex');
});