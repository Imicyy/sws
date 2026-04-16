<!doctype html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width,initial-scale=1">
  <title>Social Welfare System - Admin Analytics</title>
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
    .cards { display: grid; grid-template-columns: repeat(4, minmax(0, 1fr)); gap: 14px; margin-bottom: 20px; }
    .card-stat { border-radius: 12px; padding: 16px; color: #fff; box-shadow: 0 10px 24px rgba(0,0,0,.12); }
    .card-stat .label { font-size: 12px; text-transform: uppercase; opacity: 0.9; }
    .card-stat .value { font-size: 26px; font-weight: 700; margin-top: 6px; }
    .bg-blue { background: linear-gradient(135deg, #2563eb, #60a5fa); }
    .bg-green { background: linear-gradient(135deg, #059669, #34d399); }
    .bg-orange { background: linear-gradient(135deg, #f97316, #fb923c); }
    .bg-red { background: linear-gradient(135deg, #dc2626, #f87171); }
    .panel { background: #fff; border: 1px solid #e5e7eb; border-radius: 12px; padding: 16px; margin-bottom: 20px; }
    .panel-title { font-size: 14px; font-weight: 600; margin-bottom: 12px; }
    table { width: 100%; border-collapse: collapse; font-size: 13px; }
    table th { background: #f9fafb; padding: 10px; text-align: left; font-weight: 600; border-bottom: 2px solid #e5e7eb; }
    table td { padding: 10px; border-bottom: 1px solid #e5e7eb; }
    table tr:hover { background: #f9fafb; }
    .search-box { margin-bottom: 12px; padding: 8px 12px; border: 1px solid #e5e7eb; border-radius: 6px; width: 100%; max-width: 300px; }
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
      <a class="nav-link" href="/Index">Dashboard</a>
      <a class="nav-link active" href="/Analytics">Senior Citizen Table</a>
      <a class="nav-link" href="/Map">PWD Map</a>
      <a class="nav-link" href="/Maps">Senior Map</a>
      <a class="nav-link" href="/admin-alert">Alerts</a>
      <a class="nav-link" href="/User">User Management</a>
      <a class="nav-link" href="/logout">Logout</a>
    </aside>

    <main class="main">
      <div class="top">
        <h1 class="h4 mb-0">Admin Analytics</h1>
        <div class="welcome">Senior Citizen Management</div>
      </div>

      <section class="cards">
        <div class="card-stat bg-blue">
          <div class="label">Total Senior Citizens</div>
          <div class="value">1,425</div>
        </div>
        <div class="card-stat bg-green">
          <div class="label">Active Records</div>
          <div class="value">1,298</div>
        </div>
        <div class="card-stat bg-orange">
          <div class="label">Pending Review</div>
          <div class="value">127</div>
        </div>
        <div class="card-stat bg-red">
          <div class="label">Archived Records</div>
          <div class="value">45</div>
        </div>
      </section>

      <div class="panel">
        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 14px;">
          <h2 class="panel-title mb-0">Senior Citizens Table</h2>
          <div>
            <input type="text" class="search-box" id="searchInput" placeholder="🔍 Search by name or ID...">
          </div>
        </div>

        <table>
          <thead>
            <tr>
              <th>Name</th>
              <th>Age</th>
              <th>Barangay</th>
              <th>Contact</th>
              <th>Status</th>
              <th>Action</th>
            </tr>
          </thead>
          <tbody id="tableBody">
            <tr>
              <td colspan="6" style="text-align: center; color: #999; padding: 20px;">Loading senior citizen records...</td>
            </tr>
          </tbody>
        </table>

        <div id="noResults" class="text-center" style="display: none; padding: 20px; color: #6b7280;">
          <h4>🔍 No records found</h4>
          <p>Try adjusting your search terms</p>
        </div>

        <div style="margin-top: 12px; display: flex; justify-content: space-between; align-items: center; font-size: 13px; color: #6b7280;">
          <span id="paginationInfo">Showing 0-0 of 0 entries</span>
          <div id="paginationControls" style="display: flex; gap: 6px;"></div>
        </div>
      </div>

      <div class="panel">
        <h2 class="panel-title">API Data Preview</h2>
        <div style="background: #f9fafb; border: 1px solid #e5e7eb; border-radius: 6px; padding: 12px; font-size: 12px; font-family: monospace; overflow-x: auto;">
          <strong>OSCA (Senior Citizens) API:</strong> <code>/api/analytics/osca</code>
          <br>
          <strong>PDAO (PWD) API:</strong> <code>/api/analytics/pdao</code>
          <br><br>
          Analytics data is dynamically loaded from these endpoints and displayed above.
        </div>
      </div>
    </main>
  </div>

  <script>
    document.getElementById('searchInput').addEventListener('keyup', function() {
      const query = this.value.toLowerCase();
      // Search functionality would be implemented here
      console.log('Searching for:', query);
    });
  </script>
</body>
</html>

