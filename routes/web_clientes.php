<?php
// routes/web_clientes.php
// Módulo: Clientes
// Middleware: auth.tenant (aplicado en web.php al hacer require)

use App\Http\Controllers\ClienteController;
use App\Http\Controllers\PdfController;
use Illuminate\Support\Facades\Route;

Route::prefix('clientes')->name('clientes.')->group(function () {

    Route::get ('/',                     [ClienteController::class, 'index'])    ->name('index');
    Route::get ('/nuevo',                [ClienteController::class, 'create'])   ->name('create');
    Route::post('/',                     [ClienteController::class, 'store'])    ->name('store');
    Route::get ('/{id}/editar',          [ClienteController::class, 'edit'])     ->name('edit');
    Route::put ('/{id}',                 [ClienteController::class, 'update'])   ->name('update');
    Route::delete('/{id}',               [ClienteController::class, 'destroy'])  ->name('destroy');
    Route::post('/{id}/reactivar',       [ClienteController::class, 'reactivar'])->name('reactivar');
    Route::get ('/pdf',                  [PdfController::class,     'clientes']) ->name('pdf');
    
    // ── Modal dinámico ────────────────────────────────────────────
    Route::get ('/modal/buscar',  [ClienteController::class, 'buscarModal']) ->name('modal.buscar');
    Route::get ('/modal/crear',   [ClienteController::class, 'createModal'])->name('modal.crear');
    Route::post('/modal/crear',   [ClienteController::class, 'storeModal']) ->name('modal.store');

});