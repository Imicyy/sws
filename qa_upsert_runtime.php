<?php
declare(strict_types=1);
require __DIR__ . '/php/bootstrap.php';
require __DIR__ . '/php/config/database.php';

try {
    $pdo = createPdoFromEnv();
    $email = 'qa.migration@example.com';
    $name = 'QA Migration';
    $passwordHash = password_hash('QaPass123!', PASSWORD_BCRYPT);
    $role = 'Super Admin';
    $status = 'Active';
    $isVerified = 1;

    $colStmt = $pdo->prepare('SELECT COLUMN_NAME FROM INFORMATION_SCHEMA.COLUMNS WHERE TABLE_SCHEMA = DATABASE() AND TABLE_NAME = ?');
    $colStmt->execute(['users']);
    $cols = array_map(static fn($r) => (string)$r['COLUMN_NAME'], $colStmt->fetchAll(PDO::FETCH_ASSOC));
    $hasIsVerified = in_array('is_verified', $cols, true);

    $stmt = $pdo->prepare('SELECT id FROM users WHERE email = ? LIMIT 1');
    $stmt->execute([$email]);
    $existing = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($existing) {
        if ($hasIsVerified) {
            $update = $pdo->prepare('UPDATE users SET name = ?, password = ?, role = ?, status = ?, is_verified = ? WHERE id = ?');
            $update->execute([$name, $passwordHash, $role, $status, $isVerified, (int)$existing['id']]);
        } else {
            $update = $pdo->prepare('UPDATE users SET name = ?, password = ?, role = ?, status = ? WHERE id = ?');
            $update->execute([$name, $passwordHash, $role, $status, (int)$existing['id']]);
            echo "MISSING_COLUMN users.is_verified (continued without it)\n";
        }
        echo 'UPSERT_RESULT=UPDATED user_id=' . (int)$existing['id'] . PHP_EOL;
    } else {
        if ($hasIsVerified) {
            $insert = $pdo->prepare('INSERT INTO users (name, email, password, role, status, is_verified) VALUES (?, ?, ?, ?, ?, ?)');
            $insert->execute([$name, $email, $passwordHash, $role, $status, $isVerified]);
        } else {
            $insert = $pdo->prepare('INSERT INTO users (name, email, password, role, status) VALUES (?, ?, ?, ?, ?)');
            $insert->execute([$name, $email, $passwordHash, $role, $status]);
            echo "MISSING_COLUMN users.is_verified (continued without it)\n";
        }
        echo 'UPSERT_RESULT=INSERTED user_id=' . (int)$pdo->lastInsertId() . PHP_EOL;
    }
} catch (Throwable $e) {
    fwrite(STDERR, 'DB_ERROR: ' . $e->getMessage() . PHP_EOL);
    exit(1);
}