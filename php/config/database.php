<?php

declare(strict_types=1);

function createPdoFromEnv(): PDO
{
    $host = getenv('DB_HOST') ?: '127.0.0.1';
    $port = getenv('DB_PORT') ?: '3306';
    $dbName = getenv('MYSQL_DATABASE') ?: 'ebmag';
    $user = getenv('DB_USER') ?: 'root';
    $passwordCandidates = array_values(array_unique(array_filter([
        getenv('MYSQL_ROOT_PASSWORD') ?: null,
        getenv('MYSQL_PASSWORD') ?: null,
        getenv('DB_PASSWORD') ?: null,
        '',
    ], static fn($v): bool => $v !== null)));

    $hosts = array_values(array_unique(array_filter([
        $host,
        '127.0.0.1',
        'localhost',
    ])));

    $errors = [];
    foreach ($hosts as $candidateHost) {
        foreach ($passwordCandidates as $password) {
            $dsn = "mysql:host={$candidateHost};port={$port};dbname={$dbName};charset=utf8mb4";
            try {
                return new PDO($dsn, $user, (string) $password, [
                    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                    PDO::ATTR_EMULATE_PREPARES => false,
                ]);
            } catch (Throwable $e) {
                $errors[] = $candidateHost . ': ' . $e->getMessage();
            }
        }
    }

    throw new RuntimeException('Unable to connect to MySQL. Attempts: ' . implode(' | ', $errors));
}
