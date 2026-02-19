<?php

use Illuminate\Http\Request;

define('LARAVEL_START', microtime(true));

// Ajustar la ruta según donde esté tu proyecto Laravel
// Si está en /home/usuario/laravel_app, usa:
$base_path = dirname(__DIR__) . '/laravel_app';

// O si está un nivel arriba de public_html:
// $base_path = dirname(__DIR__);

// Determine if the application is in maintenance mode...
if (file_exists($maintenance = $base_path.'/storage/framework/maintenance.php')) {
    require $maintenance;
}

// Register the Composer autoloader...
require $base_path.'/vendor/autoload.php';

// Bootstrap Laravel and handle the request...
(require_once $base_path.'/bootstrap/app.php')
    ->handleRequest(Request::capture());
