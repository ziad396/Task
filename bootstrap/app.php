<?php

// use Illuminate\Auth\AuthenticationException;
// use Illuminate\Foundation\Application;
// use Illuminate\Foundation\Configuration\Exceptions;
// use Illuminate\Foundation\Configuration\Middleware;

// return Application::configure(basePath: dirname(__DIR__))
//     ->withRouting(
//         web: __DIR__.'/../routes/web.php',
//         api: __DIR__.'/../routes/api.php',
//         commands: __DIR__.'/../routes/console.php',
//         health: '/up',
//     )
//      ->withMiddleware(function ($middleware) {
//     $middleware->alias([
//         'user' => \App\Http\Middleware\TaskMiddleware::class,
//         'api' => \App\Http\Middleware\ApiMiddleware::class,
        
//     ]);
  
//     })
//     ->withMiddleware(function (Middleware $middleware): void {

//     })
//     ->withExceptions(function (Exceptions $exceptions): void {
//         //
//     })->create();
    


use Illuminate\Auth\AuthenticationException;
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
    ->withMiddleware(function ($middleware) {
        $middleware->alias([
            'user' => \App\Http\Middleware\TaskMiddleware::class,
            'api' => \App\Http\Middleware\ApiMiddleware::class,
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        $exceptions->render(function (AuthenticationException $e, \Illuminate\Http\Request $request) {
            return response()->json([
                'message' => 'Unauthorized - User must be logged in',
                'success' => false,
                'error' => 'authentication_required'
            ], 401);
        });
    })->create();