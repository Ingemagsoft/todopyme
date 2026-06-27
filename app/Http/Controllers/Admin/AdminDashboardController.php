<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\TenantModel;

/**
 * Controlador del dashboard del panel de administración central.
 *
 * Muestra estadísticas globales del sistema — total de empresas,
 * activas e inactivas. Lee únicamente de la BD central (mysql).
 *
 * @package App\Http\Controllers\Admin
 * @see     \App\Models\TenantModel
 * @see     \App\Http\Middleware\AdminMiddleware
 */
class AdminDashboardController extends Controller
{
    /**
     * Muestra el dashboard del panel admin con estadísticas globales.
     *
     * Estadísticas disponibles en $stats:
     * - total:     total de empresas registradas
     * - activas:   empresas con activo = true
     * - inactivas: empresas con activo = false
     *
     * Ruta: GET /admin/dashboard
     * Vista: resources/views/admin/dashboard.blade.php
     *
     * @return \Illuminate\View\View
     */
    public function index()
    {
        $stats = TenantModel::estadisticasDashboard();

        return view('admin.dashboard', compact('stats'));
    }
}