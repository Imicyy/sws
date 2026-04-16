<!doctype html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width,initial-scale=1">
  <title>Super Admin - System Logs</title>
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/css/bootstrap.min.css">
  <style>
    body { font-family: Open Sans, Segoe UI, Arial, sans-serif; margin: 0; background: #f3f6fb; color: #1f2937; }
    .layout { display: grid; grid-template-columns: 260px 1fr; min-height: 100vh; }
    .sidebar { background: #fff; border-right: 1px solid #e5e7eb; padding: 20px 14px; }
    .brand { font-weight: 800; font-size: 13px; letter-spacing: 0.4px; margin-bottom: 18px; }
    .nav-title { font-size: 12px; color: #6b7280; text-transform: uppercase; margin: 8px 10px; }
    .nav-link { display: block; padding: 10px 12px; margin-bottom: 6px; border-radius: 8px; color: #1f2937; text-decoration: none; }
    .nav-link.active { background: #0f766e; color: #fff; }
    .nav-link:hover { background: #edf2f7; }
    .main { padding: 20px; }
    .panel { background: #fff; border: 1px solid #e5e7eb; border-radius: 12px; padding: 16px; margin-bottom: 20px; }
    .panel-title { font-size: 14px; font-weight: 600; margin-bottom: 12px; }
    table { width: 100%; border-collapse: collapse; font-size: 13px; }
    table th { background: #f9fafb; padding: 10px; text-align: left; font-weight: 600; border-bottom: 2px solid #e5e7eb; }
    table td { padding: 10px; border-bottom: 1px solid #e5e7eb; }
    table tr:hover { background: #f9fafb; }
    .filter-bar { display: flex; gap: 12px; margin-bottom: 14px; }
    input { padding: 8px 12px; border: 1px solid #e5e7eb; border-radius: 6px; }
    button { padding: 8px 14px; border-radius: 6px; border: 1px solid #e5e7eb; background: #fff; cursor: pointer; }
    button.btn-primary { background: #0f766e; color: #fff; border: none; }
    @media (max-width: 980px) {
      .layout { grid-template-columns: 1fr; }
      .filter-bar { flex-wrap: wrap; }
    }
  </style>
</head>
<body>
  <div class="layout">
    <aside class="sidebar">
      <div class="brand">ENRIQUE B. MAGALONA</div>
      <div class="nav-title">Navigation</div>
      <a class="nav-link" href="/index-superadmin">Dashboard</a>
      <a class="nav-link" href="/superadmin-users">User Management</a>
      <a class="nav-link active" href="/superadmin-logs">System Logs</a>
      <a class="nav-link" href="/logout">Logout</a>
    </aside>

    <main class="main">
      <h1 class="h4 mb-4">System Logs</h1>

      <div class="panel">
        <h2 class="panel-title">Login Activity Logs</h2>
        <div class="filter-bar">
          <input type="text" placeholder="Search by email..." id="loginSearch">
          <input type="date" id="loginDate">
          <button class="btn btn-primary" onclick="filterLoginLogs()">Filter</button>
        </div>
        <table>
          <thead>
            <tr>
              <th>ID</th>
              <th>User Email</th>
              <th>User Name</th>
              <th>Role</th>
              <th>Login Result</th>
              <th>Timestamp</th>
            </tr>
          </thead>
          <tbody>
            <?php foreach (($loginLogs ?? []) as $l): ?>
            <tr>
              <td><?= (int)($l['id'] ?? 0) ?></td>
              <td><?= htmlspecialchars((string)($l['user_email'] ?? ''), ENT_QUOTES, 'UTF-8') ?></td>
              <td><?= htmlspecialchars((string)($l['user_name'] ?? ''), ENT_QUOTES, 'UTF-8') ?></td>
              <td><?= htmlspecialchars((string)($l['user_role'] ?? ''), ENT_QUOTES, 'UTF-8') ?></td>
              <td><span style="padding: 4px 8px; border-radius: 4px; <?= strpos($l['login_result'] ?? 'failed', 'success') !== false ? 'background: #d1fae5; color: #065f46;' : 'background: #fee2e2; color: #991b1b;' ?>"><?= htmlspecialchars((string)($l['login_result'] ?? ''), ENT_QUOTES, 'UTF-8') ?></span></td>
              <td><?= htmlspecialchars((string)($l['created_at'] ?? ''), ENT_QUOTES, 'UTF-8') ?></td>
            </tr>
            <?php endforeach; ?>
          </tbody>
        </table>
        <div style="margin-top: 12px; font-size: 12px; color: #6b7280;">
          Total Records: <?= count($loginLogs ?? []) ?>
        </div>
      </div>

      <div class="panel">
        <h2 class="panel-title">PWD Record Edit Logs</h2>
        <div class="filter-bar">
          <input type="text" placeholder="Search by record name..." id="pwdSearch">
          <button class="btn btn-primary" onclick="filterPwdLogs()">Filter</button>
        </div>
        <table>
          <thead>
            <tr>
              <th>ID</th>
              <th>Record Name</th>
              <th>Field</th>
              <th>Old Value</th>
              <th>New Value</th>
              <th>Edited By</th>
              <th>Edited At</th>
            </tr>
          </thead>
          <tbody>
            <?php foreach (($pwdLogs ?? []) as $l): ?>
            <tr>
              <td><?= (int)($l['id'] ?? 0) ?></td>
              <td><?= htmlspecialchars((string)($l['record_name'] ?? ''), ENT_QUOTES, 'UTF-8') ?></td>
              <td><?= htmlspecialchars((string)($l['field'] ?? ''), ENT_QUOTES, 'UTF-8') ?></td>
              <td><?= htmlspecialchars((string)($l['old_value'] ?? ''), ENT_QUOTES, 'UTF-8') ?></td>
              <td><?= htmlspecialchars((string)($l['new_value'] ?? ''), ENT_QUOTES, 'UTF-8') ?></td>
              <td><?= htmlspecialchars((string)($l['edited_by'] ?? ''), ENT_QUOTES, 'UTF-8') ?></td>
              <td><?= htmlspecialchars((string)($l['edited_at'] ?? ''), ENT_QUOTES, 'UTF-8') ?></td>
            </tr>
            <?php endforeach; ?>
          </tbody>
        </table>
        <div style="margin-top: 12px; font-size: 12px; color: #6b7280;">
          Total Records: <?= count($pwdLogs ?? []) ?>
        </div>
      </div>

      <div class="panel">
        <h2 class="panel-title">Senior Citizen Record Edit Logs</h2>
        <div class="filter-bar">
          <input type="text" placeholder="Search by record name..." id="seniorSearch">
          <button class="btn btn-primary" onclick="filterSeniorLogs()">Filter</button>
        </div>
        <table>
          <thead>
            <tr>
              <th>ID</th>
              <th>Record Name</th>
              <th>Field</th>
              <th>Old Value</th>
              <th>New Value</th>
              <th>Edited By</th>
              <th>Edited At</th>
            </tr>
          </thead>
          <tbody>
            <?php foreach (($seniorLogs ?? []) as $l): ?>
            <tr>
              <td><?= (int)($l['id'] ?? 0) ?></td>
              <td><?= htmlspecialchars((string)($l['record_name'] ?? ''), ENT_QUOTES, 'UTF-8') ?></td>
              <td><?= htmlspecialchars((string)($l['field'] ?? ''), ENT_QUOTES, 'UTF-8') ?></td>
              <td><?= htmlspecialchars((string)($l['old_value'] ?? ''), ENT_QUOTES, 'UTF-8') ?></td>
              <td><?= htmlspecialchars((string)($l['new_value'] ?? ''), ENT_QUOTES, 'UTF-8') ?></td>
              <td><?= htmlspecialchars((string)($l['edited_by'] ?? ''), ENT_QUOTES, 'UTF-8') ?></td>
              <td><?= htmlspecialchars((string)($l['edited_at'] ?? ''), ENT_QUOTES, 'UTF-8') ?></td>
            </tr>
            <?php endforeach; ?>
          </tbody>
        </table>
        <div style="margin-top: 12px; font-size: 12px; color: #6b7280;">
          Total Records: <?= count($seniorLogs ?? []) ?>
        </div>
      </div>
    </main>
  </div>

  <script>
    function filterLoginLogs() {
      const email = document.getElementById('loginSearch').value;
      const date = document.getElementById('loginDate').value;
      console.log('Filter login logs:', { email, date });
      // Filter implementation would be here
    }
    function filterPwdLogs() {
      const search = document.getElementById('pwdSearch').value;
      console.log('Filter PWD logs:', { search });
    }
    function filterSeniorLogs() {
      const search = document.getElementById('seniorSearch').value;
      console.log('Filter Senior logs:', { search });
    }
  </script>
</body>
</html>

  </div>
</body>
</html>
