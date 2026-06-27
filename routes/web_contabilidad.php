<?php
// routes/web_contabilidad.php
// Módulo: Contabilidad

use Illuminate\Support\Facades\Route;

Route::prefix('contabilidad')->name('contabilidad.')->group(function () {
    Route::get('/comprobantes',         fn() => abort(404))->name('comprobantes');
    Route::get('/puc',                  fn() => abort(404))->name('puc');
    Route::get('/inf-balances',         fn() => abort(404))->name('inf-balances');
    Route::get('/inf-libro-auxiliar',   fn() => abort(404))->name('inf-libro-auxiliar');
    Route::get('/inf-libros-oficiales', fn() => abort(404))->name('inf-libros-oficiales');
    Route::get('/inf-docing',           fn() => abort(404))->name('inf-docing');
    Route::get('/inf-aux-terceros',     fn() => abort(404))->name('inf-aux-terceros');
    Route::get('/inf-detalle-terceros', fn() => abort(404))->name('inf-detalle-terceros');
    Route::get('/inf-certificados',     fn() => abort(404))->name('inf-certificados');
    Route::get('/medios-magneticos',    fn() => abort(404))->name('medios-magneticos');
    Route::get('/actualiza-acumulados', fn() => abort(404))->name('actualiza-acumulados');
    Route::get('/traslado-cuentas',     fn() => abort(404))->name('traslado-cuentas');
    Route::get('/copia-comprobantes',   fn() => abort(404))->name('copia-comprobantes');
    Route::get('/numeracion-libros',    fn() => abort(404))->name('numeracion-libros');
    Route::get('/traslado-saldos',      fn() => abort(404))->name('traslado-saldos');
    Route::get('/cierre-anual',         fn() => abort(404))->name('cierre-anual');
    Route::get('/terceros',             fn() => abort(404))->name('terceros');
});