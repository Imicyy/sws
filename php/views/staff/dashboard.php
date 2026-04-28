<!doctype html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width,initial-scale=1">
  <link rel="icon" type="image/jpeg" href="<?= htmlspecialchars(asset_url('images/SilayLogo.jpg'), ENT_QUOTES) ?>">
  <title>Social Welfare System - Staff Dashboard</title>
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/css/bootstrap.min.css">
  <link rel="stylesheet" href="<?= htmlspecialchars(asset_url('css/light-theme.css?v=20260424'), ENT_QUOTES) ?>">
  <style>
    :root {
      --bg-page: linear-gradient(180deg, #fffdf0 0%, #f5f9ff 55%, #eef5ff 100%);
      --panel-border: #dbe5f3;
    }
    body { font-family: Open Sans, Segoe UI, Arial, sans-serif; margin: 0; background: var(--bg-page); color: #1f2937; }
    .layout { display: grid; grid-template-columns: 260px 1fr; min-height: 100vh; }
    .sidebar { background: #fffef7; border-right: 1px solid var(--panel-border); padding: 20px 14px; position: sticky; top: 0; height: 100vh; overflow-y: auto; align-self: start; box-shadow: 10px 0 24px rgba(59, 130, 246, 0.08); display: flex; flex-direction: column; }
    .brand { font-weight: 800; font-size: 13px; letter-spacing: 0.4px; margin-bottom: 18px; }
    .nav-title { font-size: 12px; color: #6b7280; text-transform: uppercase; margin: 8px 10px; }
    .nav-link { display: flex; align-items: center; justify-content: center; text-align: center; padding: 10px 12px; margin-bottom: 6px; border-radius: 8px; color: #1f2937; text-decoration: none; }
    .nav-link.active { background: linear-gradient(135deg, #60a5fa, #3b82f6); color: #fff; box-shadow: 0 8px 18px rgba(59, 130, 246, 0.24); }
    .nav-link:hover { background: #eaf3ff; }
    .sidebar .logout-link { margin-top: auto; background: #fee2e2; color: #991b1b; font-weight: 700; }
    .sidebar .logout-link:hover { background: #ef4444; color: #fff; }
    .main { padding: 20px; }
    .top { display: flex; justify-content: space-between; align-items: center; margin-bottom: 16px; padding: 14px 16px; border-radius: 14px; border: 1px solid var(--panel-border); background: rgba(255,255,255,0.88); box-shadow: 0 10px 22px rgba(59, 130, 246, 0.08); }
    .top .welcome { color: #6b7280; font-size: 14px; }
    .top-left { display: flex; align-items: center; gap: 12px; }
    .add-pwd-btn {
      display: inline-block;
      padding: 9px 14px;
      border-radius: 8px;
      background: linear-gradient(135deg, #93c5fd, #60a5fa);
      color: #1e3a8a;
      color: #fff;
      font-weight: 700;
      text-decoration: none;
    }
    .add-pwd-btn:hover { background: linear-gradient(135deg, #60a5fa, #3b82f6); color: #fff; text-decoration: none; }
    .cards { display: grid; grid-template-columns: repeat(3, minmax(0, 1fr)); gap: 14px; }
    .card-metric { border-radius: 14px; padding: 16px; color: #1e3a8a; box-shadow: 0 12px 24px rgba(15, 23, 42,.12); }
    .card-metric .label { font-size: 12px; text-transform: uppercase; opacity: 0.9; }
    .card-metric .value { font-size: 26px; font-weight: 700; margin-top: 6px; }
    .bg-blue { background: linear-gradient(135deg, #bfdbfe, #60a5fa); }
    .bg-green { background: linear-gradient(135deg, #fef3c7, #fde68a); color: #713f12; }
    .bg-purple { background: linear-gradient(135deg, #dbeafe, #93c5fd); }
    .panel { margin-top: 16px; background: #fff; border: 1px solid var(--panel-border); border-radius: 14px; padding: 18px; box-shadow: 0 14px 26px rgba(59, 130, 246, 0.08); }
    .quick a { display: inline-block; margin-right: 10px; margin-bottom: 10px; padding: 9px 12px; border-radius: 10px; background: #eaf3ff; color: #1e3a8a; text-decoration: none; }
    .quick a:hover { background: #dbeafe; }
    @media (max-width: 980px) {
      .layout { grid-template-columns: 1fr; }
      .sidebar { position: static; height: auto; max-height: none; }
      .cards { grid-template-columns: repeat(2, minmax(0, 1fr)); }
    }
  </style>
</head>
<body>
  <script>
    window.__APP_BASE__ = <?= json_encode(app_base_path(), JSON_UNESCAPED_UNICODE) ?>;
    function appPath(p) {
      var b = window.__APP_BASE__ || '';
      return b + (p.charAt(0) === '/' ? p : '/' + p);
    }
  </script>
  <div class="layout">
    <aside class="sidebar">
      <div class="brand">ENRIQUE B. MAGALONA</div>
      <div class="nav-title">Navigation</div>
      <a class="nav-link active" href="/staff-dashboard">Dashboard</a>
      <a class="nav-link" href="/Pwd-form">PWD Form</a>
      <a class="nav-link" href="/Senior-form">Senior Form</a>
      <a class="nav-link" href="/add_pwd">Add PWD</a>
      <a class="nav-link" href="/add_senior">Add Senior</a>
      <a class="nav-link logout-link" href="/logout">Logout</a>
    </aside>

    <main class="main">
      <div class="top">
        <div class="top-left">
          <a class="add-pwd-btn" href="/add_pwd">Add PWD</a>
          <h1 class="h4 mb-0">Staff Dashboard</h1>
        </div>
        <div class="welcome">Signed in as <?= htmlspecialchars((string) ($user['email'] ?? 'staff'), ENT_QUOTES, 'UTF-8') ?></div>
      </div>

      <section class="cards">
        <div class="card-metric bg-blue">
          <div class="label">PWD Entries Registered</div>
          <div class="value">342</div>
        </div>
        <div class="card-metric bg-green">
          <div class="label">Senior Entries Registered</div>
          <div class="value">287</div>
        </div>
        <div class="card-metric bg-purple">
          <div class="label">Total Registrations</div>
          <div class="value">629</div>
        </div>
      </section>

      <section class="panel quick">
        <h2 class="h6">Quick Entry Actions</h2>
        <a href="/add_pwd">Add PWD</a>
        <a href="/add_senior">Register New Senior</a>
        <a href="/Pwd-form">View PWD Form</a>
        <a href="/Senior-form">View Senior Form</a>
      </section>

      <section class="panel">
        <h2 class="h6">Staff Workflow</h2>
        <p class="mb-1">Primary responsibilities:</p>
        <ol class="mb-0 pl-3">
          <li>Register Persons with Disabilities (PWD)</li>
          <li>Register Senior Citizens</li>
          <li>Complete intake forms with full beneficiary information</li>
          <li>Submit for approval and archival</li>
        </ol>
      </section>
    </main>
  </div>

  <script>
    (function monitorSessionReplacement() {
      const expectedUserId = <?= json_encode((int) ($user['_id'] ?? 0), JSON_UNESCAPED_UNICODE) ?>;
      if (!expectedUserId) return;
      const storageKey = 'swsActiveUserId';

      try { localStorage.setItem(storageKey, String(expectedUserId)); } catch (_) {}

      function forceLogout() {
        window.location.replace(appPath('/?session_replaced=1'));
      }

      function checkLocalActiveUser() {
        try {
          const active = Number(localStorage.getItem(storageKey) || 0);
          if (active && active !== expectedUserId) forceLogout();
        } catch (_) {}
      }

      window.addEventListener('storage', function (event) {
        if (event.key !== storageKey) return;
        const active = Number(event.newValue || 0);
        if (active && active !== expectedUserId) forceLogout();
      });

      async function checkSession() {
        try {
          const res = await fetch(appPath('/api/session-state'), { credentials: 'same-origin', cache: 'no-store' });
          if (!res.ok) {
            forceLogout();
            return;
          }
          const data = await res.json();
          const activeUserId = Number((data && data.user && data.user._id) || 0);
          if (!data || data.success !== true || activeUserId !== expectedUserId) {
            forceLogout();
          }
        } catch (_) {
          forceLogout();
        }
      }

      checkLocalActiveUser();
      setInterval(checkLocalActiveUser, 1000);
      setInterval(checkSession, 3000);
      document.addEventListener('visibilitychange', function () {
        if (!document.hidden) {
          checkLocalActiveUser();
          checkSession();
        }
      });
    })();
  </script>
</body>
</html>

