<!doctype html>
<html lang="en">
<head><meta charset="utf-8"><meta name="viewport" content="width=device-width,initial-scale=1"><title>Barangay Senior List</title>
<style>body{font-family:Segoe UI,Arial,sans-serif;background:#f4f7fb;margin:0}.wrap{max-width:1200px;margin:24px auto;padding:0 16px}.card{background:#fff;border-radius:12px;box-shadow:0 10px 24px rgba(0,0,0,.08);padding:16px}table{width:100%;border-collapse:collapse}th,td{padding:8px;border-bottom:1px solid #e8edf3;text-align:left;font-size:13px}</style>
</head>
<body>
  <div class="wrap">
    <div class="card">
      <h1>Barangay Senior List</h1>
      <p>Assigned barangay: <?= htmlspecialchars((string)($assignedBarangayName ?? ''), ENT_QUOTES, 'UTF-8') ?></p>
      <table>
        <thead><tr><th>ID</th><th>Name</th><th>Purok</th><th>Gender</th><th>Age</th><th>Status</th></tr></thead>
        <tbody>
          <?php foreach (($seniors ?? []) as $s): ?>
            <tr>
              <td><?= (int)($s['id'] ?? 0) ?></td>
              <td><?= htmlspecialchars(trim((string)(($s['last_name'] ?? '') . ' ' . ($s['first_name'] ?? '') . ' ' . ($s['middle_name'] ?? '') . ' ' . ($s['extension'] ?? ''))), ENT_QUOTES, 'UTF-8') ?></td>
              <td><?= htmlspecialchars((string)($s['purok'] ?? ''), ENT_QUOTES, 'UTF-8') ?></td>
              <td><?= htmlspecialchars((string)($s['gender'] ?? ''), ENT_QUOTES, 'UTF-8') ?></td>
              <td><?= htmlspecialchars((string)($s['age'] ?? ''), ENT_QUOTES, 'UTF-8') ?></td>
              <td><?= htmlspecialchars((string)($s['status'] ?? ''), ENT_QUOTES, 'UTF-8') ?></td>
            </tr>
          <?php endforeach; ?>
        </tbody>
      </table>
    </div>
  </div>
</body>
</html>
