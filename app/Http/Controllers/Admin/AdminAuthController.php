<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Admin;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

/**
 * Controlador de autenticación del panel de administración central.
 *
 * Gestiona el login y logout exclusivo para administradores de IMS Global.
 * Usa la BD central (mysql) — no requiere conexión a BD de tenant.
 * Las credenciales se validan contra la tabla admins de la BD central.
 *
 * @package App\Http\Controllers\Admin
 * @see     \App\Models\Admin
 * @see     \App\Http\Middleware\AdminMiddleware
 * @see     \App\Http\Controllers\Admin\AdminDashboardController
 */
class AdminAuthController extends Controller
{
    /**
     * Muestra el formulario de login del panel admin.
     *
     * Si ya existe sesión de admin activa redirige directamente
     * al dashboard sin mostrar el formulario.
     *
     * Ruta: GET /admin/login
     * Vista: resources/views/admin/login.blade.php
     *
     * @return \Illuminate\View\View|\Illuminate\Http\RedirectResponse
     */
    public function showLogin()
    {
        if (session()->has('admin_id')) {
            return redirect()->route('admin.dashboard');
        }

        return view('admin.login');
    }

    /**
     * Procesa las credenciales del formulario de login.
     *
     * Flujo:
     * 1. Valida email y password
     * 2. Busca admin activo por email en BD central
     * 3. Verifica password con Hash::check()
     * 4. Guarda sesión: admin_id, admin_nombre, admin_email
     * 5. Redirige al dashboard
     *
     * Ruta: POST /admin/login
     *
     * @param  Request $request  Campos requeridos: email, password
     * @return \Illuminate\Http\RedirectResponse
     */
    public function login(Request $request)
    {
        $request->validate([
            'email'    => 'required|email',
            'password' => 'required|min:6',
        ], [
            'email.required'    => 'El correo es obligatorio.',
            'email.email'       => 'El correo no tiene un formato válido.',
            'password.required' => 'La contraseña es obligatoria.',
            'password.min'      => 'La contraseña debe tener al menos 6 caracteres.',
        ]);

        // Buscar admin activo en BD central — lógica encapsulada en el modelo
        $admin = Admin::buscarActivo($request->email);

        if (!$admin || !Hash::check($request->password, $admin->password)) {
            return back()->withErrors([
                'login' => 'Credenciales incorrectas.',
            ])->withInput($request->only('email'));
        }

        // Guardar sesión del administrador
        session([
            'admin_id'     => $admin->id,
            'admin_nombre' => $admin->nombre,
            'admin_email'  => $admin->email,
        ]);

        return redirect()->route('admin.dashboard');
    }

    /**
     * Cierra la sesión del administrador.
     *
     * Elimina solo las claves de sesión del admin —
     * no afecta sesiones de tenant activas en otros navegadores.
     *
     * Ruta: POST /admin/logout
     *
     * @param  Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function logout(Request $request)
    {
        session()->forget(['admin_id', 'admin_nombre', 'admin_email']);
        return redirect()->route('admin.login');
    }
}