<?php
// Archivo de middleware para la autenticación de inquilinos,
// que se encarga de cambiar la conexión a la base de datos dependiendo del usuario que se
// logueo, esto se hace para que cada usuario tenga su propia base de datos y no
// se mezclen los datos entre usuarios, es decir, cada usuario tiene su propia base de
// datos y no se mezclan los datos entre usuarios, esto se hace para mejorar la seguridad
// y la privacidad de los datos de cada usuario.

namespace App\Http\Middleware;   

// ==== importacion de clases ====
use Closure; 
use Illuminate\Http\Request; 
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\DB;


// ==== clase middleware ====
class TenantMiddleware
{
    public function handle(Request $request, Closure $next)  // Funcion que se encarga de manejar la solicitud y cambiar la conexion a la BD
    {
        // 1. Verificar que hay una sesión de tenant activa
        if (!session()->has('tenant_db')) {
            return redirect()->route('login')
                ->withErrors(['empresa' => 'Sesión expirada. Por favor inicia sesión nuevamente.']);
        }

        // 2. Obtener el nombre de la BD del tenant desde la sesión
        $tenantDb = session('tenant_db');

        // 3. Configurar la conexión dinámica al tenant
        Config::set('database.connections.tenant.database', $tenantDb);

        // 4. Limpiar la conexión anterior y reconectar
        DB::purge('tenant');
        DB::reconnect('tenant');

        // 5. Establecer tenant como conexión por defecto para este request
        DB::setDefaultConnection('tenant');

        // 6. Todo listo -- dejar pasar al controlador
        return $next($request);
    }
}