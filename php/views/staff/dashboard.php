<!doctype html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width,initial-scale=1">
  <title>Social Welfare System - Staff Dashboard</title>
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
    .top { display: flex; justify-content: space-between; align-items: center; margin-bottom: 16px; }
    .top .welcome { color: #6b7280; font-size: 14px; }
    .cards { display: grid; grid-template-columns: repeat(3, minmax(0, 1fr)); gap: 14px; }
    .card-metric { border-radius: 12px; padding: 16px; color: #fff; box-shadow: 0 10px 24px rgba(0,0,0,.12); }
    .card-metric .label { font-size: 12px; text-transform: uppercase; opacity: 0.9; }
    .card-metric .value { font-size: 26px; font-weight: 700; margin-top: 6px; }
    .bg-blue { background: linear-gradient(135deg, #2563eb, #60a5fa); }
    .bg-green { background: linear-gradient(135deg, #059669, #34d399); }
    .bg-purple { background: linear-gradient(135deg, #7c3aed, #a78bfa); }
    .panel { margin-top: 16px; background: #fff; border: 1px solid #e5e7eb; border-radius: 12px; padding: 18px; }
    .quick a { display: inline-block; margin-right: 10px; margin-bottom: 10px; padding: 9px 12px; border-radius: 8px; background: #eef2ff; color: #1e3a8a; text-decoration: none; }
    .quick a:hover { background: #dbeafe; }
    @media (max-width: 980px) {
      .layout { grid-template-columns: 1fr; }
      .cards { grid-template-columns: repeat(2, minmax(0, 1fr)); }
    }
  </style>
</head>
<body>
  <div class="layout">
    <aside class="sidebar">
      <div class="brand">ENRIQUE B. MAGALONA</div>
      <div class="nav-title">Navigation</div>
      <a class="nav-link active" href="/staff-dashboard">Dashboard</a>
      <a class="nav-link" href="/Pwd-form">PWD Form</a>
      <a class="nav-link" href="/Senior-form">Senior Form</a>
      <a class="nav-link" href="/add_pwd">Add PWD</a>
      <a class="nav-link" href="/add_senior">Add Senior</a>
      <a class="nav-link" href="/logout">Logout</a>
    </aside>

    <main class="main">
      <div class="top">
        <h1 class="h4 mb-0">Staff Dashboard</h1>
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
        <a href="/add_pwd">Register New PWD</a>
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
</body>
</html>

