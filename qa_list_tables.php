<?php
declare(strict_types=1);
require __DIR__ . '/php/bootstrap.php';
require __DIR__ . '/php/config/database.php';
$pdo = createPdoFromEnv();
$rows = $pdo->query('SHOW TABLES')->fetchAll(PDO::FETCH_NUM);
foreach ($rows as $r) { echo $r[0], PHP_EOL; }