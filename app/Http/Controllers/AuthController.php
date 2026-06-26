<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use App\Models\TenantModel;

/**
 * Controlador de autenticación multitenant.
 *
 * Coordina el flujo de login: identifica la empresa (tenant),
 * conecta dinámicamente a su base de datos, y valida las
 * credenciales del usuario dentro de esa BD específica.
 *
 * Login por nomusuario (no por email) — decisión de negocio:
 * el nombre de usuario lo define el administrador de cada
 * empresa, sin depender de un correo electrónico válido.
 */
class AuthController extends Controller
{
    /**
     * Muestra el formulario de login.
     * Si ya existe una sesión activa, redirige directo al dashboard
     * en vez de mostrar el formulario de nuevo.
     */
    public function showLogin()
    {
        if (session()->has('user_id')) {
            return redirect()->route('dashboard');
        }

        return view('auth.login');
    }

    /**
     * Procesa el intento de login.
     *
     * Flujo:
     * 1. Validar los campos del formulario (codigo, nomusuario, password)
     * 2. Buscar la empresa (tenant) activa en la BD central
     * 3. Conectar dinámicamente a la BD de esa empresa
     * 4. Buscar el usuario por nomusuario dentro de esa BD
     * 5. Verificar la contraseña con Hash::check()
     * 6. Guardar en sesión los datos del tenant y del usuario
     */
    public function login(Request $request)
    {
        // 1. Validar los datos del formulario
        $request->validate([
            'codigo'     => 'required|string|max:20',
            'nomusuario' => 'required|string',
            'password'   => 'required|min:6',
        ], [
            'codigo.required'     => 'El código de empresa es obligatorio.',
            'codigo.string'       => 'El código de empresa no es válido.',
            'nomusuario.required' => 'El nombre de usuario es obligatorio.',
            'password.required'   => 'La contraseña es obligatoria.',
            'password.min'        => 'La contraseña debe tener al menos 6 caracteres.',
        ]);

        // 2. Buscar la empresa en la BD central
        $tenant = TenantModel::where('codigo', strtoupper(trim($request->codigo)))
                        ->where('activo', true)
                        ->first();

        if (!$tenant) {
            return back()->withErrors([
                'login' => 'Empresa no registrada'
            ])->withInput();
        }

        // 3. Conectar dinámicamente a la BD del tenant
        Config::set('database.connections.tenant.database', $tenant->db_name);
        Config::set('database.connections.tenant.host',     $tenant->db_host);
        Config::set('database.connections.tenant.port',     $tenant->db_port);
        Config::set('database.connections.tenant.username', $tenant->db_user);
        Config::set('database.connections.tenant.password', $tenant->db_password);

        DB::purge('tenant');
        DB::reconnect('tenant');

        // 4. Buscar el usuario en la BD del tenant, por nomusuario
        $usuario = DB::connection('tenant')
                     ->table('users')
                     ->where('nomusuario', $request->nomusuario)
                     ->first();

        if (!$usuario || !Hash::check($request->password, $usuario->password)) {
            return back()->withErrors([
                'nomusuario' => 'Usuario o contraseña incorrectos.'
            ])->withInput($request->only('nomusuario', 'codigo'));
        }

        // 5. Guardar sesión del tenant y usuario
        session([
            'tenant_id'      => $tenant->id,
            'tenant_codigo'  => $tenant->codigo,
            'tenant_db'      => $tenant->db_name,
            'tenant_nombre'  => $tenant->nombre,
            'user_id'        => $usuario->id,
            'user_nombre'    => $usuario->name,
            'user_nomusuario'=> $usuario->nomusuario,
        ]);

        return redirect()->route('dashboard');
    }

    /**
     * Cierra la sesión activa y redirige al login.
     */
    public function logout(Request $request)
    {
        session()->flush();
        return redirect()->route('login');
    }
}