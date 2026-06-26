<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * Middleware de autenticación para el panel de administración central.
 *
 * Protege todas las rutas del panel admin verificando que existe
 * una sesión de administrador IMS Global activa.
 * Es completamente independiente del sistema multitenant —
 * no interactúa con BDs de tenants.
 *
 * @package App\Http\Middleware
 * @see     \App\Http\Controllers\Admin\AdminAuthController
 * @see     \App\Models\Admin
 */
class AdminMiddleware
{
    /**
     * Verifica que existe una sesión de administrador activa.
     *
     * Si no hay sesión redirige al login del panel admin con
     * un mensaje de acceso restringido.
     *
     * @param  Request  $request  Petición HTTP entrante
     * @param  Closure  $next     Siguiente middleware en la cadena
     * @return Response
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (!session()->has('admin_id')) {
            return redirect()->route('admin.login')
                             ->withErrors([
                                 'session' => 'Acceso restringido. Inicia sesión como administrador.'
                             ]);
        }

        return $next($request);
    }
}