<?php
// php/views/admin/pdao_dashboard.php
// Basic PDAO Admin Dashboard placeholder
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>PDAO Admin Dashboard</title>
    <link rel="stylesheet" href="/files/assets/css/admin.css">
</head>
<body>
    <div class="container">
        <h1>PDAO Admin Dashboard</h1>
        <p>Welcome, <?= htmlspecialchars($user['name'] ?? 'Admin', ENT_QUOTES, 'UTF-8') ?> (PDAO Admin)</p>
        <!-- Add PDAO-specific content here -->
    </div>
</body>
</html>
