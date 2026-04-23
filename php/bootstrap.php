<?php

declare(strict_types=1);

if (session_status() !== PHP_SESSION_ACTIVE) {
    session_start();
}

$projectRoot = dirname(__DIR__);
$envPath = $projectRoot . DIRECTORY_SEPARATOR . '.env';
$autoloadPath = $projectRoot . DIRECTORY_SEPARATOR . 'vendor' . DIRECTORY_SEPARATOR . 'autoload.php';
if (file_exists($autoloadPath)) {
    require_once $autoloadPath;
}

$dotenvLoaded = false;
$envPath = $projectRoot . DIRECTORY_SEPARATOR . '.env';
if (class_exists('Dotenv\\Dotenv') && file_exists($envPath)) {
    try {
        Dotenv\Dotenv::createImmutable($projectRoot)->safeLoad();
        $dotenvLoaded = true;
    } catch (Throwable $e) {
        $dotenvLoaded = false;
    }
}

if (!$dotenvLoaded && file_exists($envPath)) {
    $lines = file($envPath, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
    if (is_array($lines)) {
        foreach ($lines as $line) {
            $line = trim($line);
            if ($line === '' || str_starts_with($line, '#')) {
                continue;
            }

            $parts = explode('=', $line, 2);
            if (count($parts) !== 2) {
                continue;
            }

            $key = trim($parts[0]);
            $value = trim($parts[1]);

            if (str_starts_with($value, '"') && str_ends_with($value, '"')) {
                $value = substr($value, 1, -1);
            }

            if (getenv($key) === false) {
                putenv($key . '=' . $value);
                $_ENV[$key] = $value;
                $_SERVER[$key] = $value;
            }
        }
    }
}

/**
 * Base URL path to this app entrypoint directory (e.g. "/sws/Final_Caps/php").
 * Useful when the project is hosted under a subfolder in XAMPP.
 */
function app_base_path(): string
{
    $scriptName = (string) ($_SERVER['SCRIPT_NAME'] ?? '/');
    $dir = rtrim(str_replace('\\', '/', dirname($scriptName)), '/');
    return $dir === '' ? '' : $dir;
}

/** Build a URL path to `php/assets/...` */
function asset_url(string $relativePath): string
{
    return app_base_path() . '/assets/' . ltrim($relativePath, '/');
}
