<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\TenantModel;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\DB;

/**
 * Controlador de gestión de usuarios por empresa.
 *
 * Permite al administrador de IMS Global crear y listar usuarios
 * dentro de la BD de cada empresa (tenant).
 *
 * Cada método conecta dinámicamente a la BD del tenant
 * usando el método privado conectarTenant() antes de
 * operar sobre la tabla users.
 *
 * @package App\Http\Controllers\Admin
 * @see     \App\Models\TenantModel
 * @see     \App\Models\User
 * @see     \App\Http\Middleware\AdminMiddleware
 */
class AdminUsuarioController extends Controller
{
    /**
     * Conecta dinámicamente a la BD de un tenant específico.
     *
     * Método privado reutilizado por index(), create() y store().
     * Configura la conexión 'tenant' con los datos de la empresa
     * y la reconecta para que los modelos User operen en esa BD.
     *
     * @param  int         $empresa_id  ID de la empresa en BD central
     * @return TenantModel              Tenant encontrado con sus datos de conexión
     * @throws \Illuminate\Database\Eloquent\ModelNotFoundException
     */
    private function conectarTenant(int $empresa_id): TenantModel
    {
        $tenant = TenantModel::buscarPorId($empresa_id);

        Config::set('database.connections.tenant.database', $tenant->db_name);
        Config::set('database.connections.tenant.host',     $tenant->db_host);
        Config::set('database.connections.tenant.port',     $tenant->db_port);
        Config::set('database.connections.tenant.username', $tenant->db_user);
        Config::set('database.connections.tenant.password', $tenant->db_password);

        DB::purge('tenant');
        DB::reconnect('tenant');

        return $tenant;
    }

    /**
     * Muestra el listado de usuarios de una empresa.
     *
     * Conecta a la BD del tenant y lista todos sus usuarios
     * ordenados por nombre.
     *
     * Ruta: GET /admin/empresas/{empresa_id}/usuarios
     * Vista: resources/views/admin/usuarios/index_view.blade.php
     *
     * @param  int $empresa_id  ID de la empresa cuyos usuarios se listan
     * @return \Illuminate\View\View
     */
    public function index(int $empresa_id)
    {
        $tenant   = $this->conectarTenant($empresa_id);
        $usuarios = User::listarTodos();

        return view('admin.usuarios.index_view', compact('tenant', 'usuarios'));
    }

    /**
     * Muestra el formulario de creación de usuario para una empresa.
     *
     * Conecta al tenant para tener disponible su nombre en la vista.
     *
     * Ruta: GET /admin/empresas/{empresa_id}/usuarios/create
     * Vista: resources/views/admin/usuarios/create_view.blade.php
     *
     * @param  int $empresa_id  ID de la empresa donde se creará el usuario
     * @return \Illuminate\View\View
     */
    public function create(int $empresa_id)
    {
        $tenant = $this->conectarTenant($empresa_id);

        return view('admin.usuarios.create_view', compact('tenant'));
    }

    /**
     * Guarda un nuevo usuario en la BD del tenant.
     *
     * Valida unicidad de email dentro de la BD del tenant específico.
     * El password se hashea automáticamente en User::crearEnTenant().
     *
     * Ruta: POST /admin/empresas/{empresa_id}/usuarios
     *
     * @param  Request $request     Campos: name, email, password, password_confirmation
     * @param  int     $empresa_id  ID de la empresa donde se crea el usuario
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(Request $request, int $empresa_id)
    {
        $tenant = $this->conectarTenant($empresa_id);

        $request->validate([
            'name'     => 'required|string|max:255',
            'email'    => 'required|email|unique:tenant.users,email',
            'password' => 'required|min:6|confirmed',
        ], [
            'name.required'      => 'El nombre es obligatorio.',
            'email.required'     => 'El correo es obligatorio.',
            'email.unique'       => 'Este correo ya está registrado en esta empresa.',
            'password.required'  => 'La contraseña es obligatoria.',
            'password.min'       => 'La contraseña debe tener al menos 6 caracteres.',
            'password.confirmed' => 'Las contraseñas no coinciden.',
        ]);

        User::crearEnTenant($request->only('name', 'email', 'password'));

        return redirect()->route('admin.usuarios.index', $empresa_id)
                         ->with('success', 'Usuario creado correctamente.');
    }
}