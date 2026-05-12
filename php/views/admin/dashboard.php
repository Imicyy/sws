<!doctype html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width,initial-scale=1">
  <link rel="icon" type="image/png" href="<?= htmlspecialchars(asset_url('images/SilayLogo.png'), ENT_QUOTES) ?>">
  <title>Social Welfare System - Admin Dashboard</title>
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/css/bootstrap.min.css">
  <link rel="stylesheet" href="<?= htmlspecialchars(asset_url('css/light-theme.css?v=20260424'), ENT_QUOTES) ?>">
  <style>
    :root {
      --bg-page: linear-gradient(180deg, #fffdf0 0%, #f5f9ff 55%, #eef5ff 100%);
      --panel-bg: #ffffff;
      --panel-border: #dbe5f3;
      --accent-blue: #3b82f6;
      --accent-blue-strong: #2563eb;
      --accent-yellow: #facc15;
      --text-main: #1f2937;
      --text-muted: #6b7280;
    }
    body { font-family: Open Sans, Segoe UI, Arial, sans-serif; margin: 0; background: var(--bg-page); color: var(--text-main); }
    .layout { display: grid; grid-template-columns: 260px 1fr; min-height: 100vh; }
    .sidebar { background: #fffef7; border-right: 1px solid var(--panel-border); padding: 20px 14px; position: sticky; top: 0; height: 100vh; overflow-y: auto; align-self: start; box-shadow: 10px 0 24px rgba(59, 130, 246, 0.08); }
    .brand { font-weight: 800; font-size: 13px; letter-spacing: 0.4px; margin-bottom: 18px; }
    .nav-title { font-size: 12px; color: #6b7280; text-transform: uppercase; margin: 8px 10px; }
    .nav-link { display: block; padding: 10px 12px; margin-bottom: 6px; border-radius: 8px; color: #1f2937; text-decoration: none; }
    .nav-link.active { background: linear-gradient(135deg, #60a5fa, #3b82f6); color: #fff; box-shadow: 0 8px 18px rgba(59, 130, 246, 0.24); }
    .nav-link:hover { background: #eaf3ff; }
    .main { padding: 20px; }
    .top { display: flex; justify-content: space-between; align-items: center; margin-bottom: 16px; padding: 14px 16px; border-radius: 14px; border: 1px solid var(--panel-border); background: rgba(255,255,255,0.86); box-shadow: 0 10px 22px rgba(59, 130, 246, 0.08); }
    .top .welcome { color: #6b7280; font-size: 14px; }
    .cards { display: grid; grid-template-columns: repeat(4, minmax(0, 1fr)); gap: 14px; }
    .card-metric { border-radius: 14px; padding: 16px; color: #1e3a8a; border: 1px solid #dbe5f3; box-shadow: 0 14px 26px rgba(59, 130, 246, 0.10); }
    .card-metric .label { font-size: 12px; text-transform: uppercase; opacity: 0.9; }
    .card-metric .value { font-size: 26px; font-weight: 700; margin-top: 6px; }
    .bg-yellow { background: linear-gradient(135deg, #fffbeb, #fde68a); color: #713f12; }
    .bg-blue { background: linear-gradient(135deg, #eff6ff, #93c5fd); color: #1e3a8a; }
    .bg-green { background: linear-gradient(135deg, #f0f9ff, #bfdbfe); color: #1e3a8a; }
    .bg-red { background: linear-gradient(135deg, #fff7ed, #fed7aa); color: #9a3412; }
    .panel { margin-top: 16px; background: linear-gradient(180deg, #ffffff 0%, #fffcf3 100%); border: 1px solid var(--panel-border); border-radius: 14px; padding: 18px; box-shadow: 0 14px 26px rgba(59, 130, 246, 0.08); }
    .quick a { display: inline-block; margin-right: 10px; margin-bottom: 10px; padding: 9px 12px; border-radius: 10px; background: #eaf3ff; color: #1e40af; text-decoration: none; }
    .quick a:hover { background: #dbeafe; }
      .layout { grid-template-columns: 1fr; }
      .sidebar { position: static; height: auto; max-height: none; }
      .cards { grid-template-columns: repeat(2, minmax(0, 1fr)); }
    }

    .user-profile { 
      padding: 14px; 
      margin-bottom: 20px; 
      background: #f8fafc; 
      border-radius: 12px; 
      display: flex; 
      flex-direction: column; 
      align-items: center;
      text-align: center;
      gap: 6px; 
      border: 1px solid #e2e8f0; 
    }
    .user-name { font-size: 16px; font-weight: 700; color: #fff; display: flex; align-items: center; gap: 8px; justify-content: center; }
    .user-email { font-size: 13px; color: #94a3b8; word-break: break-all; font-weight: 500; }
    
    .profile-icon-wrapper {
      width: 56px;
      height: 56px;
      background: rgba(255, 255, 255, 0.15);
      border-radius: 50%;
      display: flex;
      align-items: center;
      justify-content: center;
      margin-bottom: 10px;
      border: 1px solid rgba(255, 255, 255, 0.2);
    }
    
    .nav-title { font-size: 13px; color: #6b7280; text-transform: uppercase; margin: 12px 10px 8px; font-weight: 700; }
    .nav-link { display: block; padding: 12px 14px; margin-bottom: 6px; border-radius: 8px; color: #1f2937; text-decoration: none; font-size: 16px; font-weight: 600; }
  </style>
</head>
<body>
  <div class="layout">
    <aside class="sidebar">
      <div class="brand">ENRIQUE B. MAGALONA</div>
      
      <div class="user-profile">
        <div class="profile-icon-wrapper">
          <svg width="32" height="32" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="color: #fff;">
            <path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"></path>
            <circle cx="12" cy="7" r="4"></circle>
          </svg>
        </div>
        <div class="user-name">
          <span><?= htmlspecialchars($_SESSION['user']['name'] ?? 'Admin User', ENT_QUOTES) ?></span>
        </div>
        <div class="user-email">
          <?= htmlspecialchars($_SESSION['user']['email'] ?? 'admin@example.com', ENT_QUOTES) ?>
        </div>
      </div>

      <div class="nav-title">Navigation</div>
      <a class="nav-link active" href="/Index">Dashboard</a>
      <a class="nav-link" href="/Analytics">Senior Citizen Table</a>
      
      <a class="nav-link" href="/admin-alert">Alerts</a>
      <a class="nav-link" href="/logout">Logout</a>
    </aside>

    <main class="main">
      <div class="top">
        <h1 class="h4 mb-0">Admin Dashboard</h1>
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
