<!doctype html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width,initial-scale=1">
  <title><?= htmlspecialchars((string)($title ?? 'Page'), ENT_QUOTES, 'UTF-8') ?></title>
  <style>
    body { font-family: Segoe UI, Arial, sans-serif; background: #eef2f7; margin: 0; }
    main { max-width: 900px; margin: 56px auto; background: #fff; border-radius: 12px; box-shadow: 0 10px 24px rgba(0,0,0,.08); padding: 24px; }
    .meta { color: #5b6470; }
  </style>
</head>
<body>
  <main>
    <h1><?= htmlspecialchars((string)($title ?? 'Page'), ENT_QUOTES, 'UTF-8') ?></h1>
    <p class="meta">This route is now running on PHP. The detailed page migration is in progress.</p>
    <?php if (!empty($user) && is_array($user)): ?>
      <p class="meta">Signed in as <?= htmlspecialchars((string)($user['email'] ?? 'unknown'), ENT_QUOTES, 'UTF-8') ?> (<?= htmlspecialchars((string)($user['role'] ?? 'unknown'), ENT_QUOTES, 'UTF-8') ?>)</p>
    <?php endif; ?>
  </main>
</body>
</html>
