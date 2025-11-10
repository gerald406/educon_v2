<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        api: __DIR__.'/../routes/api.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware) {
        
        // [AÑADE ESTE BLOQUE COMPLETO]
        // Aquí registramos los "alias" para 'role' y 'permission'
        $middleware->alias([
            'role' => \Spatie\Permission\Middleware\RoleMiddleware::class,
            'permission' => \Spatie\Permission\Middleware\PermissionMiddleware::class,
        ]);
        // [FIN DEL BLOQUE NUEVO]

        // (Es posible que Jetstream haya añadido otras cosas aquí. Déjalas)
        
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        //
    })->create();
