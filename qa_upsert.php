<?php
declare(strict_types=1);
require __DIR__ . '/php/bootstrap.php';
require __DIR__ . '/php/config/database.php';
try {
    $pdo = createPdoFromEnv();
    $email = 'qa.migration@example.com';
    $passwordHash = password_hash('QaPass123!', PASSWORD_BCRYPT);
    $role = 'Super Admin';
    $status = 'Active';
    $isVerified = 1;

    $stmt = $pdo->prepare('SELECT id FROM users WHERE email = ? LIMIT 1');
    $stmt->execute([$email]);
    $existing = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($existing) {
        $update = $pdo->prepare('UPDATE users SET password = ?, role = ?, status = ?, is_verified = ? WHERE id = ?');
        $update->execute([$passwordHash, $role, $status, $isVerified, (int)$existing['id']]);
        echo 'UPDATED user_id=' . (int)$existing['id'] . PHP_EOL;
    } else {
        $insert = $pdo->prepare("INSERT INTO users (name, email, password, role, status, is_verified) VALUES (?, ?, ?, ?, ?, ?)");
        $insert->execute(['QA Migration', $email, $passwordHash, $role, $status, $isVerified]);
        echo 'INSERTED user_id=' . (int)$pdo->lastInsertId() . PHP_EOL;
    }
} catch (Throwable $e) {
    fwrite(STDERR, 'DB_ERROR: ' . $e->getMessage() . PHP_EOL);
    exit(1);
}
