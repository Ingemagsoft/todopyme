<?php
// routes/web_nomina.php
// Módulo: Nómina

use Illuminate\Support\Facades\Route;

Route::prefix('nomina')->name('nomina.')->group(function () {
    Route::get('/ficha-empleados',      fn() => abort(404))->name('ficha-empleados');
    Route::get('/contratos',            fn() => abort(404))->name('contratos');
    Route::get('/comprobantes-pago',    fn() => abort(404))->name('comprobantes-pago');
});