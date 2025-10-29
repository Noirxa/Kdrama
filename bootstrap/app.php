<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware): void {

        // Dit is de 'alias' (bijnaam) voor je admin-routes (die had je al)
        $middleware->alias([
            'admin' => \App\Http\Middleware\CheckIsAdmin::class,
        ]);

        // --- HIER TOEGEVOEGD ---
        // We voegen de 'CheckUserIsActive' middleware toe aan de 'web'-groep.
        // 'append' betekent dat het AAN HET EINDE van de lijst met
        // standaard web-middleware wordt toegevoegd.
        $middleware->web(append: [
            \App\Http\Middleware\CheckUserIsActive::class,
        ]);
        // --- EINDE TOEVOEGING ---

    })
    ->withExceptions(function (Exceptions $exceptions): void {
        //
    })->create();
