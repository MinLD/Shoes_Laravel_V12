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
        // ĐĂNG KÝ MIDDLEWARE ALIAS CỦA BẠN TẠI ĐÂY
        
        // Thêm dòng này để đăng ký alias 'role.gate'
        $middleware->alias([
            'role.gate' => \App\Http\Middleware\RedirectBasedOnRole::class,
        ]);

        $middleware->web(append: [
            \App\Http\Middleware\RedirectAdminFromHome::class,
        ]);

        // Laravel Breeze cũng đăng ký alias của nó ở đây,
        // bạn sẽ thấy các dòng tương tự như:
        // $middleware->alias([
        //     'guest' => \App\Http\Middleware\RedirectIfAuthenticated::class,
        //     'auth' => \App\Http\Middleware\Authenticate::class,
        //     'verified' => \Illuminate\Auth\Middleware\EnsureEmailIsVerified::class,
        // ]);
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        //
    })->create();
