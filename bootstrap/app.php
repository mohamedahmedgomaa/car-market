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
    ->withMiddleware(function (Middleware $middleware): void {
        // Register route middleware alias
        $middleware->alias([
            'force.seller' => \App\Http\Middleware\ForceSellerId::class,
            'force.filter.seller' => \App\Http\Middleware\ForceFilterSellerId::class,
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        $exceptions->render(function (\Illuminate\Http\Exceptions\PostTooLargeException $e, $request) {
            return response()->json([
                'status' => 413,
                'message' => 'حجم ملف الفيديو كبير جداً ويتجاوز الحد المسموح به في إعدادات PHP على خادم الاستضافة (post_max_size). يرجى زيادة الحد في cPanel أو رفع فيديو بحجم أقل.',
            ], 413);
        });
    })->create();
