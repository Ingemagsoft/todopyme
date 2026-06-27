<?php
// routes/web_radian.php
// Módulo: Recepción de Facturas (RADIAN)

use Illuminate\Support\Facades\Route;

Route::prefix('radian')->name('radian.')->group(function () {
    Route::get('/recepcion',            fn() => abort(404))->name('recepcion');
});