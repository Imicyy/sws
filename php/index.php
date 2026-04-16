<?php

declare(strict_types=1);

require __DIR__ . DIRECTORY_SEPARATOR . 'bootstrap.php';
require __DIR__ . DIRECTORY_SEPARATOR . 'config' . DIRECTORY_SEPARATOR . 'database.php';
require __DIR__ . DIRECTORY_SEPARATOR . 'core' . DIRECTORY_SEPARATOR . 'Router.php';
require __DIR__ . DIRECTORY_SEPARATOR . 'core' . DIRECTORY_SEPARATOR . 'View.php';
require __DIR__ . DIRECTORY_SEPARATOR . 'controller' . DIRECTORY_SEPARATOR . 'controller.php';

$projectRoot = dirname(__DIR__);
$pdo = null;
try {
    $pdo = createPdoFromEnv();
} catch (Throwable $e) {
    $_SERVER['PHP_MIGRATION_DB_ERROR'] = $e->getMessage();
}

$controller = new Controller($pdo, $projectRoot);
$router = new Router();

require __DIR__ . DIRECTORY_SEPARATOR . 'routes.php';

$method = $_SERVER['REQUEST_METHOD'] ?? 'GET';

$uri = $_SERVER['REQUEST_URI'] ?? '/';
$path = parse_url($uri, PHP_URL_PATH);
if (!is_string($path) || $path === '') {
    $path = '/';
}

$scriptDir = rtrim(str_replace('\\', '/', dirname($_SERVER['SCRIPT_NAME'] ?? '/')), '/');
if ($scriptDir !== '' && $scriptDir !== '/' && str_starts_with($path, $scriptDir)) {
    $path = substr($path, strlen($scriptDir));
    if ($path === false || $path === '') {
        $path = '/';
    }
}

$router->dispatch($method, $path);