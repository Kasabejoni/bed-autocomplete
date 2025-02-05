<?php

require_once __DIR__.'/../vendor/autoload.php';

$app = new Laravel\Lumen\Application(
    realpath(__DIR__.'/../')
);
$app->withFacades();
$app->withEloquent();
$app->middleware([
    App\Http\Middleware\CorsMiddleware::class
]);
$app->router->group([
    'namespace' => 'App\Http\Controllers',
], function ($router) {
    require __DIR__.'/../routes/web.php';
});
$app->configure('app');
$app->register(Illuminate\Log\LogServiceProvider::class);
$app->make('config')->set('app.debug', true);
Dotenv\Dotenv::createUnsafeImmutable(__DIR__.'/../')->load();

return $app;
