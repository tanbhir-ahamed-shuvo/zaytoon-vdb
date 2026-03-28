<?php

use Illuminate\Foundation\Application;
use Illuminate\Http\Request;

define('LARAVEL_START', microtime(true));

// Determine if the application is in maintenance mode...
if (file_exists($maintenance = __DIR__.'/../storage/framework/maintenance.php')) {
    require $maintenance;
}

// Register the Composer autoloader...
require __DIR__.'/../vendor/autoload.php';

// Some hosting probes may send malformed Host headers. Normalize them before
// Symfony/Laravel captures the request to avoid Invalid URI host exceptions.
$httpHost = $_SERVER['HTTP_HOST'] ?? '';
if ($httpHost !== '' && ! preg_match('/^[A-Za-z0-9.-]+(?::[0-9]+)?$/', $httpHost)) {
    $fallbackHost = $_SERVER['RENDER_EXTERNAL_HOSTNAME'] ?? parse_url((string) ($_SERVER['APP_URL'] ?? getenv('APP_URL') ?: 'http://localhost'), PHP_URL_HOST) ?: 'localhost';
    $_SERVER['HTTP_HOST'] = $fallbackHost;
    $_SERVER['SERVER_NAME'] = $fallbackHost;
}

// Bootstrap Laravel and handle the request...
/** @var Application $app */
$app = require_once __DIR__.'/../bootstrap/app.php';

$app->handleRequest(Request::capture());
