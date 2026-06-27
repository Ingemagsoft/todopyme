<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\ClienteController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PdfController;
use App\Http\Controllers\Admin\AdminAuthController;
use App\Http\Controllers\Admin\AdminDashboardController;
use App\Http\Controllers\Admin\AdminEmpresaController;
use App\Http\Controllers\Admin\AdminUsuarioController;
use App\Models\ClienteModel;
use App\Http\Controllers\DashboardController; // --- IGNORE ---

// ─── Rutas públicas (sin autenticación) ─────────────────────
Route::get('/',      [AuthController::class, 'showLogin'])->name('login');
Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login',[AuthController::class, 'login'])->name('login.post');

// ─── Rutas protegidas (requieren sesión de tenant) ───────────
Route::middleware(['auth.tenant'])->group(function () {

    // ─── Dashboard ───────────────────────────────────────────────
    Route::get('/dashboard', function () {
        $totalClientes = \App\Models\ClienteModel::where('activo', true)->count();
        return view('dashboard', compact('totalClientes'));
    })->name('dashboard');

    Route::post('/logout', [AuthController::class, 'logout'])->name('logout'); 
    

    // ─── Módulo Clientes ─────────────────────────────────────────
    require __DIR__.'/web_clientes.php';
    require __DIR__.'/web_ventas.php';
    require __DIR__.'/web_compras.php';
    require __DIR__.'/web_inventarios.php';
    require __DIR__.'/web_productos.php';
    require __DIR__.'/web_cxc.php';
    require __DIR__.'/web_cxp.php';
    require __DIR__.'/web_gastos.php';
    require __DIR__.'/web_contabilidad.php';
    require __DIR__.'/web_nomina.php';
    require __DIR__.'/web_radian.php';

});

// ═══════════════════════════════════════════════════════════════
//  PANEL DE ADMINISTRACIÓN CENTRAL — Solo equipo IMS Global
//  Rutas prefijadas con /admin — nombre con prefijo admin.
//  Protegidas por AdminMiddleware (auth.admin)
// ═══════════════════════════════════════════════════════════════
Route::prefix('admin')->name('admin.')->group(function () {

    // ── Rutas públicas — sin middleware ──────────────────────────
    Route::get ('/login', [AdminAuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [AdminAuthController::class, 'login'])    ->name('login.post');

    // ── Rutas protegidas — requieren sesión admin ────────────────
    Route::middleware('auth.admin')->group(function () {

        // Dashboard
        Route::get ('/dashboard', [AdminDashboardController::class, 'index'])->name('dashboard');
        Route::post('/logout',    [AdminAuthController::class,      'logout'])->name('logout');

        // ── CRUD de empresas ─────────────────────────────────────
        // Genera: index, create, store, edit, update (sin show ni destroy)
        Route::resource('empresas', AdminEmpresaController::class)
             ->except(['show', 'destroy']);

        // Activar / desactivar empresa
        Route::patch('empresas/{id}/toggle', [AdminEmpresaController::class, 'toggle'])
             ->name('empresas.toggle');

        // ── Usuarios por empresa ─────────────────────────────────
        // URL: /admin/empresas/{empresa_id}/usuarios
        Route::prefix('empresas/{empresa_id}/usuarios')
             ->name('usuarios.')
             ->group(function () {
                 Route::get ('/',       [AdminUsuarioController::class, 'index']) ->name('index');
                 Route::get ('/create', [AdminUsuarioController::class, 'create'])->name('create');
                 Route::post('/',       [AdminUsuarioController::class, 'store']) ->name('store');
             });
    });
});