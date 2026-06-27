<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;

/**
 * Modelo de administradores del panel central de IMS Global.
 *
 * Opera exclusivamente en la BD central (mysql) — tabla admins.
 * Los administradores son el equipo interno de IMS Global
 * y tienen acceso al panel de gestión de empresas y usuarios.
 *
 * Diferencia con User: User vive en la BD de cada tenant.
 * Admin vive en la BD central y no pertenece a ningún tenant.
 *
 * @package App\Models
 * @see     \App\Http\Controllers\Admin\AdminAuthController
 * @see     \App\Http\Middleware\AdminMiddleware
 *
 * @property int    $id
 * @property string $nombre
 * @property string $email
 * @property string $password
 * @property bool   $activo
 */
class Admin extends Authenticatable
{
    /** @var string Conexión a la BD central — nunca al tenant */
    protected $connection = 'mysql';

    /** @var string Nombre explícito de la tabla */
    protected $table = 'admins';

    /** @var array Campos asignables masivamente */
    protected $fillable = [
        'nombre',
        'email',
        'password',
        'activo',
    ];

    /** @var array Campos ocultos en serialización */
    protected $hidden = [
        'password',
    ];

    /** @var array Casteos automáticos */
    protected $casts = [
        'activo'   => 'boolean',
        'password' => 'hashed',
    ];

    // ── Métodos estáticos ─────────────────────────────────────────

    /**
     * Busca un administrador activo por email.
     *
     * Usado en AdminAuthController::login() para validar credenciales.
     * Solo devuelve admins con activo = true — los inactivos no pueden ingresar.
     *
     * @param  string     $email  Correo del administrador
     * @return static|null        Admin encontrado o null si no existe/inactivo
     */
    public static function buscarActivo(string $email): ?self
    {
        return self::where('email', $email)
                   ->where('activo', true)
                   ->first();
    }
}