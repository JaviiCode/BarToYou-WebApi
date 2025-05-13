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
        // Registrar middlewares globales (opcional)
        // $middleware->append(\App\Http\Middleware\ExampleMiddleware::class);

        // Registrar middlewares de ruta
        $middleware->alias([
            'authMiddleware' => \App\Http\Middleware\authMiddleware::class,
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions) {
        // Configuración de manejo de excepciones (opcional)
    })->create();
