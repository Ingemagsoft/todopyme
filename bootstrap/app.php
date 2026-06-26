<?php
// Archivo de configuración de la aplicación Laravel, 
// donde se registran los middlewares personalizados para inquilinos 
// (TenantMiddleware y TenantApiMiddleware) que se encargan de cambiar 
// la conexión a la base de datos dependiendo del usuario que se logueo, 
// esto se hace para que cada usuario tenga su propia base de datos y no 
// se mezclen los datos entre usuarios, es decir, cada usuario tiene su propia 
// base de datos y no se mezclan los datos entre usuarios, 
// esto se hace para mejorar la seguridad y la privacidad de los datos de cada usuario. 

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        //api: __DIR__.'/../routes/api.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    // Registrar los middlewares personalizados para inquilinos
    ->withMiddleware(function (Middleware $middleware): void {
        $middleware->alias([
            'auth.tenant'     => \App\Http\Middleware\TenantMiddleware::class,
            'auth.tenant.api' => \App\Http\Middleware\TenantApiMiddleware::class,
            'auth.admin'      => \App\Http\Middleware\AdminMiddleware::class,  
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        //
    })->create();
