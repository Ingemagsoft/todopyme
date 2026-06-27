<?php
// routes/web_productos.php
// Módulo: Parámetros Productos

use Illuminate\Support\Facades\Route;

Route::prefix('productos')->name('productos.')->group(function () {
    Route::get('/creacion',             fn() => abort(404))->name('creacion');
    Route::get('/tipo-productos',       fn() => abort(404))->name('tipo-productos');
    Route::get('/grupos',               fn() => abort(404))->name('grupos');
    Route::get('/subgrupos',            fn() => abort(404))->name('subgrupos');
    Route::get('/lineas',               fn() => abort(404))->name('lineas');
    Route::get('/tipos-listas',         fn() => abort(404))->name('tipos-listas');
    Route::get('/consulta-precios',     fn() => abort(404))->name('consulta-precios');
    Route::get('/asignar-precios',      fn() => abort(404))->name('asignar-precios');
    Route::get('/asignar-costos',       fn() => abort(404))->name('asignar-costos');
    Route::get('/recodificacion',       fn() => abort(404))->name('recodificacion');
    Route::get('/exportar',             fn() => abort(404))->name('exportar');
});