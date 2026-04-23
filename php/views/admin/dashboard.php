<!doctype html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width,initial-scale=1">
  <link rel="icon" type="image/png" href="<?= htmlspecialchars(asset_url('images/logo-ebmag.png'), ENT_QUOTES) ?>">
  <title>Social Welfare System - Admin Dashboard</title>
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/css/bootstrap.min.css">
  <style>
    body { font-family: Open Sans, Segoe UI, Arial, sans-serif; margin: 0; background: #f3f6fb; color: #1f2937; }
    .layout { display: grid; grid-template-columns: 260px 1fr; min-height: 100vh; }
    .sidebar { background: #fff; border-right: 1px solid #e5e7eb; padding: 20px 14px; position: sticky; top: 0; height: 100vh; overflow-y: auto; align-self: start; }
    .brand { font-weight: 800; font-size: 13px; letter-spacing: 0.4px; margin-bottom: 18px; }
    .nav-title { font-size: 12px; color: #6b7280; text-transform: uppercase; margin: 8px 10px; }
    .nav-link { display: block; padding: 10px 12px; margin-bottom: 6px; border-radius: 8px; color: #1f2937; text-decoration: none; }
    .nav-link.active { background: #0f766e; color: #fff; }
    .nav-link:hover { background: #edf2f7; }
    .main { padding: 20px; }
    .top { display: flex; justify-content: space-between; align-items: center; margin-bottom: 16px; }
    .top .welcome { color: #6b7280; font-size: 14px; }
    .cards { display: grid; grid-template-columns: repeat(4, minmax(0, 1fr)); gap: 14px; }
    .card-metric { border-radius: 12px; padding: 16px; color: #fff; box-shadow: 0 10px 24px rgba(0,0,0,.12); }
    .card-metric .label { font-size: 12px; text-transform: uppercase; opacity: 0.9; }
    .card-metric .value { font-size: 26px; font-weight: 700; margin-top: 6px; }
    .bg-yellow { background: linear-gradient(135deg, #f59e0b, #fbbf24); }
    .bg-blue { background: linear-gradient(135deg, #2563eb, #60a5fa); }
    .bg-green { background: linear-gradient(135deg, #059669, #34d399); }
    .bg-red { background: linear-gradient(135deg, #dc2626, #f87171); }
    .panel { margin-top: 16px; background: #fff; border: 1px solid #e5e7eb; border-radius: 12px; padding: 18px; }
    .quick a { display: inline-block; margin-right: 10px; margin-bottom: 10px; padding: 9px 12px; border-radius: 8px; background: #eef2ff; color: #1e3a8a; text-decoration: none; }
    .quick a:hover { background: #dbeafe; }
    @media (max-width: 980px) {
      .layout { grid-template-columns: 1fr; }
      .sidebar { position: static; height: auto; max-height: none; }
      .cards { grid-template-columns: repeat(2, minmax(0, 1fr)); }
    }
  </style>
</head>
<body>
  <div class="layout">
    <aside class="sidebar">
      <div class="brand">ENRIQUE B. MAGALONA</div>
      <div class="nav-title">Navigation</div>
      <a class="nav-link active" href="/Index">Dashboard</a>
      <a class="nav-link" href="/Analytics">Senior Citizen Table</a>
      
      <a class="nav-link" href="/admin-alert">Alerts</a>
      <a class="nav-link" href="/logout">Logout</a>
    </aside>

    <main class="main">
      <div class="top">
        <h1 class="h4 mb-0">Admin Dashboard</h1>
        <div class="welcome">Signed in as <?= htmlspecialchars((string) ($user['email'] ?? 'admin'), ENT_QUOTES, 'UTF-8') ?></div>
      </div>

      <section class="cards">
        <div class="card-metric bg-yellow">
          <div class="label">New Senior Citizen</div>
          <div class="value">852</div>
        </div>
        <div class="card-metric bg-blue">
          <div class="label">New PWD Registrations</div>
          <div class="value">640</div>
        </div>
        <div class="card-metric bg-green">
          <div class="label">Total Active Users</div>
          <div class="value">298</div>
        </div>
        <div class="card-metric bg-red">
          <div class="label">Alerts Triggered</div>
          <div class="value">14</div>
        </div>
      </section>

      

      <section class="panel">
        <h2 class="h6">Legacy Flow Notes</h2>
        <p class="mb-1">This page mirrors the legacy Admin entry flow:</p>
        <ol class="mb-0 pl-3">
          <li>Dashboard landing</li>
          <li>Navigate to analytics and map pages</li>
          <li>Go to forms for data entry and updates</li>
          <li>Send alerts and manage users</li>
        </ol>
      </section>
    </main>
  </div>
</body>
</html>
