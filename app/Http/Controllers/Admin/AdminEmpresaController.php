<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\TenantModel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

/**
 * Controlador CRUD de empresas (tenants) del panel de administración central.
 *
 * Gestiona el ciclo de vida completo de una empresa:
 * registro, edición, activación/desactivación y creación
 * automática de su BD con migraciones.
 *
 * IMPORTANTE: Al crear una empresa se ejecutan dos operaciones:
 * 1. INSERT en tabla tenants (transaccional)
 * 2. CREATE DATABASE + migraciones (no transaccional — DDL)
 * Si el paso 2 falla se elimina el registro del paso 1.
 *
 * @package App\Http\Controllers\Admin
 * @see     \App\Models\TenantModel
 * @see     \App\Http\Middleware\AdminMiddleware
 */
class AdminEmpresaController extends Controller
{
    /**
     * Muestra el listado de todas las empresas registradas.
     *
     * Ruta: GET /admin/empresas
     * Vista: resources/views/admin/empresas/index_view.blade.php
     *
     * @return \Illuminate\View\View
     */
    public function index()
    {
        $empresas = TenantModel::listarTodas();
        return view('admin.empresas.index_view', compact('empresas'));
    }

    /**
     * Muestra el formulario de creación de nueva empresa.
     *
     * Ruta: GET /admin/empresas/create
     * Vista: resources/views/admin/empresas/create_view.blade.php
     *
     * @return \Illuminate\View\View
     */
    public function create()
    {
        return view('admin.empresas.create_view');
    }

    /**
     * Guarda una nueva empresa y crea su BD con migraciones.
     *
     * Flujo:
     * 1. Valida campos únicos (codigo, nit, db_name)
     * 2. INSERT en tenants dentro de DB::transaction()
     * 3. CREATE DATABASE + migraciones fuera de transacción
     * 4. Si paso 3 falla → elimina el registro huérfano
     *
     * Ruta: POST /admin/empresas
     *
     * @param  Request $request  Campos: codigo, nombre, nit, db_name
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(Request $request)
    {
        $request->validate([
            'codigo'  => 'required|string|max:20|unique:tenants,codigo',
            'nombre'  => 'required|string|max:255',
            'nit'     => 'required|string|max:20|unique:tenants,nit',
            'db_name' => 'required|string|max:100|unique:tenants,db_name',
        ], [
            'codigo.required'  => 'El código de empresa es obligatorio.',
            'codigo.unique'    => 'Este código ya está registrado.',
            'nombre.required'  => 'La razón social es obligatoria.',
            'nit.required'     => 'El NIT es obligatorio.',
            'nit.unique'       => 'Este NIT ya está registrado.',
            'db_name.required' => 'El nombre de la base de datos es obligatorio.',
            'db_name.unique'   => 'Este nombre de BD ya está en uso.',
        ]);

        try {
            // Paso 1 — Registrar en tenants (protegido con transacción)
            /** @var TenantModel|null $tenant */
            $tenant = null;

            DB::transaction(function () use ($request, &$tenant) {
                $tenant = TenantModel::registrarNueva(
                    $request->only('codigo', 'nombre', 'nit', 'db_name')
                );
            });

            // Paso 2 — Crear BD y migrar (fuera de transacción — DDL no es transaccional)
            TenantModel::crearBaseDeDatos($tenant);

        } catch (\Exception $e) {
            // Si algo falló después del INSERT eliminar el registro huérfano
            if (isset($tenant)) {
                $tenant->delete();
            }

            return redirect()->route('admin.empresas.create')
                             ->withErrors(['error' => 'Error al crear la empresa: ' . $e->getMessage()])
                             ->withInput();
        }

        return redirect()->route('admin.empresas.index')
                         ->with('success', 'Empresa registrada, BD creada y migraciones ejecutadas correctamente.');
    }

    /**
     * Muestra el formulario de edición de una empresa.
     *
     * Solo permite editar nombre y NIT —
     * el código y la BD no se pueden cambiar una vez creados.
     *
     * Ruta: GET /admin/empresas/{id}/edit
     * Vista: resources/views/admin/empresas/edit_view.blade.php
     *
     * @param  int $id  ID de la empresa a editar
     * @return \Illuminate\View\View
     */
    public function edit($id)
    {
        $empresa = TenantModel::buscarPorId($id);
        return view('admin.empresas.edit_view', compact('empresa'));
    }

    /**
     * Guarda los cambios de una empresa existente.
     *
     * Campos editables: nombre, nit.
     * Campos no editables: codigo, db_name.
     *
     * Ruta: PUT /admin/empresas/{id}
     *
     * @param  Request $request  Campos: nombre, nit
     * @param  int     $id       ID de la empresa a actualizar
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(Request $request, $id)
    {
        $request->validate([
            'nombre' => 'required|string|max:255',
            'nit'    => 'required|string|max:20|unique:tenants,nit,' . $id,
        ], [
            'nombre.required' => 'La razón social es obligatoria.',
            'nit.required'    => 'El NIT es obligatorio.',
            'nit.unique'      => 'Este NIT ya está registrado en otra empresa.',
        ]);

        TenantModel::actualizarDatos($id, $request->only('nombre', 'nit'));

        return redirect()->route('admin.empresas.index')
                         ->with('success', 'Empresa actualizada correctamente.');
    }

    /**
     * Activa o desactiva una empresa (toggle).
     *
     * Una empresa inactiva no puede autenticarse en el sistema.
     * Sus datos permanecen intactos — no es eliminación lógica
     * sino cambio de estado operativo.
     *
     * Ruta: PATCH /admin/empresas/{id}/toggle
     *
     * @param  int $id  ID de la empresa
     * @return \Illuminate\Http\RedirectResponse
     */
    public function toggle($id)
    {
        TenantModel::toggleActivo($id);

        return redirect()->route('admin.empresas.index')
                         ->with('success', 'Estado de la empresa actualizado.');
    }
}