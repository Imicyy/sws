<!doctype html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width,initial-scale=1">
  <title>Barangay — Senior Citizen Analytics</title>
  <link rel="stylesheet" href="/files/bower_components/bootstrap/css/bootstrap.min.css">
  <link rel="stylesheet" href="<?= htmlspecialchars(asset_url('css/light-theme.css?v=20260424'), ENT_QUOTES) ?>">
  <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
  <style>
    :root {
      --bg-page: linear-gradient(180deg, #fffdf0 0%, #f5f9ff 55%, #eef5ff 100%);
      --panel-border: #dbe5f3;
      --blue-main: #3b82f6;
      --blue-dark: #1d4ed8;
      --yellow-soft: #fef3c7;
      --yellow-main: #facc15;
    }
    body { font-family: Open Sans, Segoe UI, Arial, sans-serif; margin: 0; background: var(--bg-page); color: #1f2937; line-height: 1.45; position: relative; }
    body::before {
      content: "";
      position: fixed;
      top: 0;
      left: 260px;
      width: calc(100% - 260px);
      height: 100%;
      background-image: url('<?= htmlspecialchars(asset_url("images/SilayLogo.jpg"), ENT_QUOTES) ?>');
      background-repeat: no-repeat;
      background-position: center;
      background-size: 600px;
      opacity: 0.05;
      z-index: 0;
      pointer-events: none;
    }
    .layout { display: block; min-height: 100vh; position: relative; z-index: 1; }
    .sidebar { background: #fffef7; border-right: 1px solid var(--panel-border); padding: 20px 14px; height: 100vh; width: 260px; box-sizing: border-box; overflow-y: auto; position: fixed; top: 0; left: 0; box-shadow: 10px 0 30px rgba(59, 130, 246, 0.08); display: flex; flex-direction: column; z-index: 100; }
    .brand { font-weight: 800; font-size: 13px; letter-spacing: 0.7px; margin-bottom: 18px; color: #0f172a !important; opacity: 1 !important; visibility: visible !important; }
    .nav-title { font-size: 12px; color: #6b7280 !important; text-transform: uppercase; margin: 8px 10px; opacity: 1 !important; visibility: visible !important; }
    .sidebar .nav-link { display: flex !important; align-items: center !important; justify-content: center !important; text-align: center !important; min-height: 42px !important; padding: 12px 14px; margin-bottom: 8px; border-radius: 12px; color: #334155 !important; text-decoration: none !important; font-weight: 600; font-size: 14px !important; line-height: 1.4 !important; letter-spacing: .2px !important; text-indent: 0 !important; opacity: 1 !important; visibility: visible !important; transition: all .3s cubic-bezier(0.4, 0, 0.2, 1); border: 1px solid transparent; }
    .sidebar .nav-link.active { background: linear-gradient(135deg, #60a5fa, var(--blue-main)); color: #fff !important; box-shadow: 0 8px 20px rgba(59, 130, 246, 0.3); border-color: transparent; }
    .sidebar .nav-link:hover:not(.active) { background: #f1f5f9; color: #0f172a !important; transform: translateX(4px); border-color: #e2e8f0; }
    .sidebar .logout-link { background: #fee2e2 !important; color: #991b1b !important; font-weight: 700 !important; }
    .sidebar .logout-link:hover { background: #ef4444 !important; color: #fff !important; transform: none !important; }
    .nav-fallback-item { padding: 8px 10px; margin-bottom: 4px; border-radius: 8px; font-size: 13px; font-weight: 600; color: #1f2937; cursor: pointer; }
    .nav-fallback-item.active { background: #3b82f6; color: #fff; }
    .main { padding: 24px; margin-left: 260px; }
    .top-header { margin-bottom: 20px; }
    .stats { display: grid; grid-template-columns: repeat(3, minmax(0, 1fr)); gap: 14px; margin-bottom: 20px; }
    .stat-card { background: #fff; border: 1px solid var(--panel-border); border-radius: 16px; padding: 20px; text-align: center; box-shadow: 0 10px 20px rgba(59, 130, 246, 0.05); transition: transform 0.3s cubic-bezier(0.4, 0, 0.2, 1), box-shadow 0.3s cubic-bezier(0.4, 0, 0.2, 1), border-color 0.3s ease; }
    .stat-card:hover { transform: translateY(-5px); box-shadow: 0 20px 30px rgba(59, 130, 246, 0.12); border-color: #bfdbfe; }
    .stat-number { font-size: 30px; font-weight: 800; color: var(--blue-dark); letter-spacing: -0.5px; }
    .stat-label { font-size: 13px; font-weight: 600; color: #64748b; margin-top: 8px; }
    .panel { background: #fff; border: 1px solid var(--panel-border); border-radius: 18px; padding: 24px; margin-bottom: 24px; box-shadow: 0 12px 24px rgba(59, 130, 246, 0.06); transition: box-shadow 0.3s ease; }
    .panel:hover { box-shadow: 0 16px 36px rgba(59, 130, 246, 0.1); }
    .panel-title { font-size: 17px; font-weight: 700; margin-bottom: 16px; color: #1e293b; }
    .search-box { margin-bottom: 12px; padding: 10px 16px; border-radius: 10px; border: 1px solid #cbd5e1; transition: all 0.3s ease; background-color: #f8fafc; font-size: 14px; }
    .search-box:focus { background-color: #fff; box-shadow: 0 0 0 4px rgba(59, 130, 246, 0.15); border-color: var(--blue-main); outline: none; }
    table { width: 100%; border-collapse: separate; border-spacing: 0; font-size: 13.5px; }
    table th { background: linear-gradient(135deg, #60a5fa, var(--blue-main)); color: #ffffff; padding: 14px 12px; text-align: left; font-weight: 600; letter-spacing: 0.3px; border: none; }
    table th:first-child { border-top-left-radius: 10px; border-bottom-left-radius: 10px; }
    table th:last-child { border-top-right-radius: 10px; border-bottom-right-radius: 10px; }
    table td { padding: 14px 12px; border-bottom: 1px solid #f1f5f9; vertical-align: middle; transition: background-color 0.2s ease; }
    #dataTable th, #dataTable td { text-align: center; }
    table tr:hover td { background: #fef3c7; }
    .modal { display: none; position: fixed; z-index: 1000; left: 0; top: 0; width: 100%; height: 100%; background-color: rgba(15, 23, 42, 0.4); backdrop-filter: blur(4px); transition: opacity 0.3s ease; }
    .modal.show { display: block; animation: modalFadeIn 0.3s cubic-bezier(0.16, 1, 0.3, 1); }
    @keyframes modalFadeIn { from { opacity: 0; transform: translateY(-20px) scale(0.98); } to { opacity: 1; transform: translateY(0) scale(1); } }
    .modal-content { background-color: #fff; margin: 6% auto; padding: 28px; border: none; border-radius: 16px; width: 80%; max-width: 700px; box-shadow: 0 25px 50px rgba(0, 0, 0, 0.15); }
    .close { color: #94a3b8; float: right; font-size: 26px; cursor: pointer; transition: color 0.2s ease; line-height: 1; }
    .close:hover { color: #1e293b; }
    button { padding: 10px 18px; border-radius: 10px; border: 1px solid #e2e8f0; background: #fff; cursor: pointer; font-weight: 600; color: #475569; transition: all 0.25s cubic-bezier(0.4, 0, 0.2, 1); }
    button.btn-primary { background: linear-gradient(135deg, #60a5fa, var(--blue-main)); color: #fff; border: none; box-shadow: 0 4px 12px rgba(59, 130, 246, 0.25); }
    button.btn-info { background: linear-gradient(135deg, #fef3c7, var(--yellow-main)); color: #713f12; border: none; box-shadow: 0 4px 12px rgba(250, 204, 21, 0.25); }
    button:hover { transform: translateY(-2px); box-shadow: 0 6px 16px rgba(59, 130, 246, 0.3); }
    button.btn-info:hover { box-shadow: 0 6px 16px rgba(250, 204, 21, 0.35); }
    button:active { transform: translateY(0); box-shadow: none; }
    input:focus, select:focus { outline: none; border-color: var(--blue-main) !important; box-shadow: 0 0 0 4px rgba(59, 130, 246, 0.15); }
    @media (max-width: 980px) {
      .sidebar { position: relative; width: 100%; height: auto; min-height: 0; z-index: 1; }
      .main { margin-left: 0; }
      .stats { grid-template-columns: repeat(2, minmax(0, 1fr)); }
      body::before { left: 0; width: 100%; }
    }
  </style>
</head>
<body>
  <script>window.__APP_BASE__=<?= json_encode(app_base_path(), JSON_UNESCAPED_UNICODE) ?>;</script>
  <div class="layout">
    <aside class="sidebar">
      <div class="brand" style="display: flex; align-items: center; gap: 10px;">
        <img src="<?= htmlspecialchars(asset_url('images/SilayLogo.jpg'), ENT_QUOTES) ?>" alt="Logo" style="height: 32px; width: 32px; object-fit: contain;">
        <span>ENRIQUE B. MAGALONA</span>
      </div>
      <div class="nav-title">Navigation</div>
      <a class="nav-link" style="display:block !important;visibility:visible !important;opacity:1 !important;color:#1f2937 !important;font-size:14px !important;line-height:1.35 !important;" href="<?= htmlspecialchars(app_url('/barangay'), ENT_QUOTES, 'UTF-8') ?>">Person With Disability Analytics</a>
      <a class="nav-link active" style="display:block !important;visibility:visible !important;opacity:1 !important;color:#ffffff !important;font-size:14px !important;line-height:1.35 !important;" href="<?= htmlspecialchars(app_url('/barangay-senior-dashboard'), ENT_QUOTES, 'UTF-8') ?>">Senior Citizen Analytics</a>
      <a class="nav-link" style="display:block !important;visibility:visible !important;opacity:1 !important;color:#1f2937 !important;font-size:14px !important;line-height:1.35 !important;" href="<?= htmlspecialchars(app_url('/barangay-pwd'), ENT_QUOTES, 'UTF-8') ?>">Person With Disability List</a>
      <a class="nav-link" style="display:block !important;visibility:visible !important;opacity:1 !important;color:#1f2937 !important;font-size:14px !important;line-height:1.35 !important;" href="<?= htmlspecialchars(app_url('/barangay-senior'), ENT_QUOTES, 'UTF-8') ?>">Senior Citizens List</a>
      <a class="nav-link logout-link" style="display:block !important;visibility:visible !important;opacity:1 !important;color:#1f2937 !important;font-size:14px !important;line-height:1.35 !important;margin-top:auto !important;" href="<?= htmlspecialchars(app_url('/logout'), ENT_QUOTES, 'UTF-8') ?>">Logout</a>
    </aside>

    <main class="main">
      <div class="top-header" style="display:flex;justify-content:center;align-items:flex-start;position:relative;">
        <div style="text-align:center;">
          <h1 class="h4">Barangay Dashboard — Senior Citizen Analytics</h1>
          <?php if (!empty($assignedBarangayName)): ?>
          <p class="text-muted mb-0" style="font-size: 13px;"><strong>Barangay:</strong> <?= htmlspecialchars((string) $assignedBarangayName, ENT_QUOTES, 'UTF-8') ?></p>
          <?php endif; ?>
        </div>
        <div style="position:absolute;right:0;top:0;">
          <button id="notifBellBrgySenior" style="background:transparent;border:0;cursor:pointer;padding:8px;border-radius:8px;font-size:18px;">🔔 <span id="notifBadgeBrgySenior" style="background:#dc2626;color:#fff;border-radius:10px;padding:2px 6px;font-size:12px;display:none;margin-left:6px;">0</span></button>
          <div id="notifDropdownBrgySenior" style="display:none;position:absolute;right:0;top:48px;background:#fff;border:1px solid #e5e7eb;border-radius:8px;box-shadow:0 6px 18px rgba(0,0,0,0.08);width:360px;max-height:320px;overflow:auto;padding:8px;z-index:2000;">
            <div style="font-weight:700;padding:8px;border-bottom:1px solid #f3f4f6;">Notifications</div>
            <div id="notifListBrgySenior" style="padding:8px;font-size:13px;color:#374151;"></div>
          </div>
        </div>
      </div>

      <div class="stats">
        <div class="stat-card">
          <div class="stat-number" id="totalBarangays">1</div>
          <div class="stat-label">Your Jurisdiction</div>
        </div>
        <div class="stat-card">
          <div class="stat-number" id="totalOSCA">0</div>
          <div class="stat-label">Total Senior Citizen Count</div>
        </div>
        <div class="stat-card">
          <div class="stat-number" id="averageOSCA">0</div>
          <div class="stat-label">Average per purok</div>
        </div>
      </div>

      <div class="panel">
        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 14px;">
          <h2 class="panel-title mb-0">Purok Senior Citizen Analytics Table</h2>
          <div>
            <input type="text" class="search-box" id="searchInput" placeholder="🔍 Search puroks..." style="margin-bottom: 0; display: inline-block; width: 200px;">
            <button class="btn btn-info" onclick="showReportModal()" style="margin-left: 8px;">Generate Report</button>
          </div>
        </div>

        <table id="dataTable">
          <thead>
            <tr>
              <th>Purok Name</th>
              <th>Senior Citizen Count</th>
              <th>Action</th>
            </tr>
          </thead>
          <tbody id="tableBody">
            <tr>
              <td colspan="3" style="text-align: center; color: #999;">Loading purok data...</td>
            </tr>
          </tbody>
        </table>

        <div id="noResults" class="text-center" style="display: none; padding: 20px; color: #6b7280;">
          <h4>🔍 No results found</h4>
          <p>Try adjusting your search terms</p>
        </div>

        <div style="margin-top: 12px; display: flex; justify-content: space-between; align-items: center; font-size: 13px; color: #6b7280;">
          <span id="paginationInfo">Showing 0-0 of 0 entries</span>
          <div id="paginationControls"></div>
        </div>
      </div>
    </main>
  </div>

  <!-- Chart Modal -->
  <div id="chartModal" class="modal">
    <div class="modal-content">
      <span class="close" onclick="closeChartModal()">&times;</span>
      <h2 id="modalTitle" style="margin-bottom: 16px;">Purok Analysis</h2>
      <div style="margin-bottom: 12px;">
        <button class="chart-type-btn active" onclick="switchChartType('doughnut')" style="margin-right: 8px;">Donut Chart</button>
        <button class="chart-type-btn" onclick="switchChartType('table')">Table View</button>
      </div>
      <div id="chartContainer" style="position: relative; height: 300px;">
        <canvas id="pieChart"></canvas>
      </div>
      <div id="tableContainer" style="display: none;">
        <table style="width: 100%; margin-top: 16px;">
          <thead>
            <tr>
              <th>Category</th>
              <th>Percentage</th>
            </tr>
          </thead>
          <tbody id="chartTableBody">
          </tbody>
        </table>
      </div>
      <div id="chartInfo" style="margin-top: 12px; font-size: 13px; color: #6b7280;"></div>
      <div id="chartInsights" style="margin-top: 10px; padding: 10px 12px; border: 1px solid #e5e7eb; border-radius: 8px; background: #f8fafc; font-size: 13px; color: #374151;"></div>
    </div>
  </div>

  <!-- Report Modal -->
  <div id="reportTypeModal" class="modal">
    <div class="modal-content" style="max-width: 500px;">
      <span class="close" onclick="closeReportModal()">&times;</span>
      <h2 style="margin-bottom: 16px;">Select Report Type</h2>
      <div style="margin-bottom: 14px;">
        <label style="display: block; margin-bottom: 6px; font-weight: 600; font-size: 14px;">Report Type:</label>
        <select id="reportTypeSelect" onchange="toggleReportDateInputs()" style="width: 100%; padding: 8px; border-radius: 6px; border: 1px solid #e5e7eb;">
          <option value="annual">Annual Report</option>
          <option value="monthly">Monthly Report</option>
        </select>
      </div>
      <div id="yearInputContainer" style="margin-bottom: 14px;">
        <label style="display: block; margin-bottom: 6px; font-weight: 600; font-size: 14px;">Select Year:</label>
        <input type="number" id="reportYear" style="width: 100%; padding: 8px; border-radius: 6px; border: 1px solid #e5e7eb;" min="2020" max="2100">
      </div>
      <div id="monthInputContainer" style="margin-bottom: 14px; display: none;">
        <label style="display: block; margin-bottom: 6px; font-weight: 600; font-size: 14px;">Select Month:</label>
        <select id="reportMonth" style="width: 100%; padding: 8px; border-radius: 6px; border: 1px solid #e5e7eb;">
          <option value="1">January</option><option value="2">February</option><option value="3">March</option>
          <option value="4">April</option><option value="5">May</option><option value="6">June</option>
          <option value="7">July</option><option value="8">August</option><option value="9">September</option>
          <option value="10">October</option><option value="11">November</option><option value="12">December</option>
        </select>
      </div>
      <div style="text-align: right; margin-top: 16px;">
        <button onclick="closeReportModal()" style="margin-right: 8px;">Cancel</button>
        <button class="btn btn-primary" onclick="generateReport()">Generate Report</button>
      </div>
    </div>
  </div>

  <script>
    const LEFT_LOGO_SRC = <?= json_encode(asset_url('images/SilayLogo.jpg'), JSON_UNESCAPED_UNICODE) ?>;
    const RIGHT_LOGO_SRC = <?= json_encode(asset_url('images/BagongPilipinas.jpg'), JSON_UNESCAPED_UNICODE) ?>;
    function appPath(p) {
      var b = window.__APP_BASE__ || '';
      return b + (p.charAt(0) === '/' ? p : '/' + p);
    }
    function showChartModal(purokName) {
      document.getElementById('modalTitle').textContent = purokName;
      document.getElementById('chartModal').classList.add('show');
    }
    function closeChartModal() {
      document.getElementById('chartModal').classList.remove('show');
    }
    function switchChartType(type) {
      if (type === 'doughnut') {
        document.getElementById('chartContainer').style.display = 'block';
        document.getElementById('tableContainer').style.display = 'none';
      } else {
        document.getElementById('chartContainer').style.display = 'none';
        document.getElementById('tableContainer').style.display = 'block';
      }
    }
    function showReportModal() {
      document.getElementById('reportTypeModal').classList.add('show');
    }
    function closeReportModal() {
      document.getElementById('reportTypeModal').classList.remove('show');
    }
    function toggleReportDateInputs() {
      const type = document.getElementById('reportTypeSelect').value;
      document.getElementById('yearInputContainer').style.display = 'block';
      document.getElementById('monthInputContainer').style.display = type === 'monthly' ? 'block' : 'none';
    }
    function openPrintWindow(html) {
      const newWin = window.open('', '_blank', 'width=1100,height=800,scrollbars=yes');
      if (!newWin) { alert('Popup blocked! Allow popups to print.'); return; }
      newWin.document.open(); newWin.document.write(html); newWin.document.close();
    }

    function escapeHtml(value) {
      return String(value === null || value === undefined ? '' : value)
        .replace(/&/g, '&amp;')
        .replace(/</g, '&lt;')
        .replace(/>/g, '&gt;')
        .replace(/"/g, '&quot;')
        .replace(/'/g, '&#039;');
    }

    function buildOscaReportHtml(reportTitle, scopeName, rows) {
      const safeRows = Array.isArray(rows) ? rows : [];
      const bodyRows = safeRows.length ? safeRows.map((r) => `
        <tr>
          <td><strong>${escapeHtml(r.barangay || r.purok || r.name || 'Unknown')}</strong></td>
          <td>${Number(r.male || 0).toLocaleString()}</td>
          <td>${Number(r.female || 0).toLocaleString()}</td>
          <td>${Number(r.total || r.count || 0).toLocaleString()}</td>
        </tr>
      `).join('') : '<tr><td colspan="4" class="text-center">No data available.</td></tr>';
      const totals = safeRows.reduce((acc, r) => {
        acc.male += Number(r.male || 0);
        acc.female += Number(r.female || 0);
        acc.total += Number(r.total || r.count || 0);
        return acc;
      }, { male: 0, female: 0, total: 0 });

      return `<!doctype html>
<html>
<head>
  <meta charset="utf-8">
  <title>${escapeHtml(reportTitle)} - ${escapeHtml(scopeName)}</title>
  <meta name="viewport" content="width=device-width,initial-scale=1">
  <link rel="stylesheet" href="/files/bower_components/bootstrap/css/bootstrap.min.css">
  <style>
    @page { size: A4 landscape; margin: 8mm; }
    body { margin: 0; padding: 12px 16px; font-family: Arial, sans-serif; color: #1f2937; font-size: 14px; }
    .print-button-container { text-align: center; margin-bottom: 12px; padding: 10px; background: #f3f4f6; border-radius: 6px; }
    .print-button-container button { background: #0d6efd; color: #fff; padding: 8px 16px; border: none; border-radius: 6px; font-weight: 700; font-size: 13px; }
    .container { max-width: 100%; width: 100%; padding: 0; }
    .header-wrapper { position: relative; min-height: 100px; margin-bottom: 8px; }
    .logo-left { position: absolute; top: 0; left: 0; width: 72px; }
    .logo-right { position: absolute; top: 0; right: 0; width: 90px; }
    .main-header { text-align: center; padding-top: 4px; }
    .main-header h4, .main-header h2, .main-header p { margin: 0; }
    .title-section { text-align: center; margin: 8px 0 10px; }
    .title-section h5, .title-section h4 { margin: 0; }
    .title-section .as-of { margin-top: 5px; font-size: 13px; }
    .table { margin-bottom: 6px; font-size: 13px; table-layout: fixed; width: 100%; border-collapse: separate; border-spacing: 0; }
    .table thead th {
      background: #d1d5db;
      color: #1f2937;
      font-weight: 700;
      text-align: center;
      border: 1px solid #eef2f7 !important;
      padding: 6px 10px;
    }
    .table tbody td {
      background: #f3f4f6;
      border: 1px solid #eef2f7 !important;
      vertical-align: middle;
      padding: 6px 10px;
      color: #1f2937;
    }
    .table tfoot td {
      background: #e5e7eb;
      border: 1px solid #eef2f7 !important;
      vertical-align: middle;
      padding: 6px 10px;
      color: #1f2937;
    }
    .table tbody td:first-child,
    .table tfoot td:first-child { font-weight: 600; }
    .total-row td { font-weight: 700; }
    .summary-box { margin-top: 6px; border-top: 1px solid #e5e7eb; padding-top: 8px; }
    .summary-box h5 { margin-bottom: 4px; }
    .summary-grid { display: grid; grid-template-columns: 1fr 1fr; gap: 6px 18px; }
    .summary-box p { margin: 2px 0; font-weight: 600; }
    .summary-box .right-col { text-align: right; }
    @media print {
      .print-button-container { display: none; }
      body { padding: 0; font-size: 12px; }
      .header-wrapper { min-height: 88px; margin-bottom: 6px; }
      .logo-left { width: 62px; }
      .logo-right { width: 80px; }
      .title-section { margin: 6px 0 8px; }
      .title-section .as-of { margin-top: 4px; }
      .table { font-size: 12px; }
      .table thead th, .table tbody td, .table tfoot td { padding: 5px 8px; }
      .summary-box { margin-top: 4px; padding-top: 6px; }
    }
  </style>
</head>
<body>
  <div class="print-button-container"><button onclick="window.print()">🖨️ Print Report</button></div>

  <div class="container">
    <div class="header-wrapper">
      <img src="${escapeHtml(LEFT_LOGO_SRC)}" class="logo-left">
      <img src="${escapeHtml(RIGHT_LOGO_SRC)}" class="logo-right">
      <div class="main-header">
        <h4>Republic of the Philippines</h4>
        <h2><strong>ENRIQUE B. MAGALONA</strong></h2>
        <p>Office Senior Citizens Affairs</p>
      </div>
    </div>

    <div class="title-section">
      <h4>OFFICE OF SENIOR CITIZENS AFFAIRS</h4>
      <h5>${escapeHtml(reportTitle)}</h5>
      <h4><strong>${escapeHtml(scopeName)}</strong></h4>
      <div class="as-of">As of - <strong>${new Date().toLocaleDateString('en-PH', { year: 'numeric', month: 'long', day: 'numeric' })}</strong></div>
    </div>

    <table class="table table-bordered table-striped">
      <thead>
        <tr>
          <th>Barangay</th>
          <th>Male</th>
          <th>Female</th>
          <th>Total of Citizens</th>
        </tr>
      </thead>
      <tbody>${bodyRows}</tbody>
      <tfoot>
        <tr class="total-row">
          <td>TOTAL</td>
          <td>${totals.male.toLocaleString()}</td>
          <td>${totals.female.toLocaleString()}</td>
          <td>${totals.total.toLocaleString()}</td>
        </tr>
      </tfoot>
    </table>

    <div class="summary-box">
      <h5>Report Summary</h5>
      <div class="summary-grid">
        <div>
          <p>Total Senior Citizens: ${totals.total.toLocaleString()}</p>
          <p>Total Male: ${totals.male.toLocaleString()}</p>
          <p>Total Female: ${totals.female.toLocaleString()}</p>
        </div>
        <div class="right-col">
          <p>Number of Barangays: ${safeRows.length.toLocaleString()}</p>
        </div>
      </div>
    </div>
  </div>
</body>
</html>`;
    }

    function buildPurokDetailReportHtml(title, purokName, rows) {
      const safeRows = Array.isArray(rows) ? rows : [];
      const total = safeRows.length;
      const male = safeRows.filter(function (r) { return String(r.gender || '').toLowerCase() === 'male'; }).length;
      const female = safeRows.filter(function (r) { return String(r.gender || '').toLowerCase() === 'female'; }).length;
      const rowsHtml = safeRows.length
        ? safeRows.map(function (r, idx) {
            return `<tr>
              <td>${idx + 1}</td>
              <td>${escapeHtml(r.fullName || 'Unnamed')}</td>
              <td>${escapeHtml(r.contact || 'N/A')}</td>
              <td>${escapeHtml(r.gender || 'N/A')}</td>
              <td>${escapeHtml(r.age || 'N/A')}</td>
            </tr>`;
          }).join('')
        : '<tr><td colspan="5" class="text-center">No data available.</td></tr>';

      return `<!doctype html>
<html>
<head>
  <meta charset="utf-8">
  <title>${escapeHtml(title)} - ${escapeHtml(purokName || 'Unknown')}</title>
  <meta name="viewport" content="width=device-width,initial-scale=1">
  <link rel="stylesheet" href="/files/bower_components/bootstrap/css/bootstrap.min.css">
  <style>
    @page { size: auto; margin: 8mm; }
    body { padding: 10px; font-family: Arial, sans-serif; font-size: 14px; }
    .container-fluid { width: 100%; max-width: 100%; padding-left: 6px; padding-right: 6px; }
    .header-wrapper { position: relative; margin-bottom: 16px; min-height: 130px; }
    .logo-left { position: absolute; top: 6px; left: 0; width: 92px; }
    .logo-right { position: absolute; top: 2px; right: 0; width: 110px; }
    .main-header { text-align: center; margin-top: 8px; line-height: 1.15; }
    .main-header h4 { margin: 0; font-size: 20px; font-weight: 700; letter-spacing: .2px; }
    .main-header h3 { margin: 0 0 6px; font-size: 14px; font-weight: 500; color: #333; }
    .main-header p { margin: 0; font-size: 14px; font-weight: 500; }
    .title-section { margin-top: 8px; text-align: center; }
    .title-section h5 { margin: 3px 0; font-size: 16px; font-weight: 600; }
    .as-of-label { margin-top: 10px; margin-bottom: 0; font-size: 13px; }
    .as-of-date { margin-top: 2px; margin-bottom: 0; font-size: 13px; font-weight: 700; }
    .print-button-container { text-align: center; margin: 4px 0 12px; }
    .print-button-container button { background-color: #2f80ed; color: white; padding: 8px 20px; border: none; border-radius: 5px; cursor: pointer; font-size: 13px; font-weight: 600; }
    .table { width: 100%; margin-top: 8px; margin-bottom: 8px; table-layout: fixed; border-collapse: separate; border-spacing: 0; }
    .table thead th { background: #cfd3d8 !important; color: #1f2937 !important; text-align: center; font-size: 13px; font-weight: 700; border: 1px solid #e5e7eb !important; padding: 4px 8px !important; }
    .table tbody td { padding: 5px 8px !important; font-size: 13px; color: #1f2937; border: 1px solid #f0f1f3 !important; vertical-align: middle; line-height: 1.2; background: #ffffff; text-align: center; }
    .table tbody td:nth-child(2) { text-align: left; }
    .summary-box { margin-top: 6px; border-top: 1px solid #e5e7eb; padding-top: 8px; }
    @media print { .print-button-container { display: none; } body { padding: 0; } .container-fluid { padding-left: 0; padding-right: 0; } }
  </style>
</head>
<body>
  <div class="print-button-container"><button onclick="window.print()">🖨️ Print Report</button></div>
  <div class="container-fluid">
    <div class="header-wrapper">
      <img src="${escapeHtml(LEFT_LOGO_SRC)}" class="logo-left">
      <img src="${escapeHtml(RIGHT_LOGO_SRC)}" class="logo-right">
      <div class="main-header">
        <h3>Republic of the Philippines</h3>
        <h4>ENRIQUE B. MAGALONA</h4>
        <p>Office Senior Citizens Affairs</p>
      </div>
    </div>
    <div class="title-section">
      <h5>OFFICE OF SENIOR CITIZENS AFFAIRS</h5>
      <h5>${escapeHtml(title)}</h5>
      <h5><strong>${escapeHtml(purokName || 'Unknown')}</strong></h5>
      <p class="as-of-label">As of -</p>
      <p class="as-of-date">${new Date().toLocaleDateString('en-PH', { year: 'numeric', month: 'long', day: 'numeric' })}</p>
    </div>
    <table class="table table-bordered table-striped">
      <thead><tr><th>#</th><th>Name</th><th>Contact</th><th>Gender</th><th>Age</th></tr></thead>
      <tbody>${rowsHtml}</tbody>
    </table>
    <div class="summary-box">
      <h5>Report Summary</h5>
      <p><strong>Total Count:</strong> ${total}</p>
      <p><strong>Total Male:</strong> ${male}</p>
      <p><strong>Total Female:</strong> ${female}</p>
    </div>
  </div>
</body>
</html>`;
    }

    async function generateReport() {
      try {
        const type = document.getElementById('reportTypeSelect').value;
        const year = encodeURIComponent(String(document.getElementById('reportYear').value || new Date().getFullYear()));
        const month = type === 'monthly' ? `&month=${encodeURIComponent(String(document.getElementById('reportMonth').value))}` : '';
        const query = `?groupBy=purok&year=${year}${month}`;

        const res = await fetch(appPath('/api/analytics/osca') + query, { credentials: 'same-origin' });
        const json = await res.json();
        if (!res.ok || !json) { throw new Error((json && json.message) || 'Unable to load report data'); }

        let rows = [];
        if (Array.isArray(json.data)) {
          rows = json.data.map(function (p) {
            return {
              barangay: p.name || p.barangay || p.purok || 'Unknown',
              male: Number(p.male || 0),
              female: Number(p.female || 0),
              total: Number(p.total || p.count || 0)
            };
          });
        }

        const html = buildOscaReportHtml(type === 'monthly' ? 'MONTHLY ACCOMPLISHMENT REPORT' : 'ANNUAL ACCOMPLISHMENT REPORT', <?= json_encode($assignedBarangayName ?? '', JSON_UNESCAPED_UNICODE) ?>, rows);
        openPrintWindow(html);
      } catch (err) {
        alert('Report generation failed: ' + (err.message || err));
      } finally {
        closeReportModal();
      }
    }
    window.onclick = function(event) {
      const modal1 = document.getElementById('chartModal');
      const modal2 = document.getElementById('reportTypeModal');
      if (event.target === modal1) closeChartModal();
      if (event.target === modal2) closeReportModal();
    };
  </script>
  <script>
    (function () {
      function appPath(p) {
        var b = window.__APP_BASE__ || '';
        return b + (p.charAt(0) === '/' ? p : '/' + p);
      }
      const assignedBarangayName = <?= json_encode($assignedBarangayName ?? '', JSON_UNESCAPED_UNICODE) ?>;
      const tableBody = document.getElementById('tableBody');
      const searchInput = document.getElementById('searchInput');
      const noResults = document.getElementById('noResults');
      const paginationInfo = document.getElementById('paginationInfo');
      const paginationControls = document.getElementById('paginationControls');
      const totalOscaEl = document.getElementById('totalOSCA');
      const averageOscaEl = document.getElementById('averageOSCA');
      const chartCanvas = document.getElementById('pieChart');
      let chartInstance = null;
      let allRows = [];
      let filteredRows = [];
      let currentPage = 1;
      const pageSize = 10;
      const breakdownByPurok = {};

      function renderTablePage() {
        if (!tableBody) return;
        const total = filteredRows.length;
        const start = total === 0 ? 0 : ((currentPage - 1) * pageSize) + 1;
        const end = Math.min(currentPage * pageSize, total);
        if (paginationInfo) paginationInfo.textContent = `Showing ${start}-${end} of ${total} entries`;

        if (total === 0) {
          tableBody.innerHTML = '<tr><td colspan="3" style="text-align:center;color:#999;">No records found.</td></tr>';
          if (noResults) noResults.style.display = '';
        } else {
          if (noResults) noResults.style.display = 'none';
          const pageRows = filteredRows.slice((currentPage - 1) * pageSize, currentPage * pageSize);
          tableBody.innerHTML = pageRows.map(function (row) {
            const safePurok = String(row.purok || 'Unknown').replace(/'/g, "\\'");
            return '<tr>'
              + '<td>' + escapeHtml(row.purok || 'Unknown') + '</td>'
              + '<td>' + Number(row.count || 0).toLocaleString() + '</td>'
              + '<td>'
              + '<div style="display:flex;gap:6px;flex-wrap:wrap;justify-content:center;">'
              + '<button class="btn btn-primary btn-sm" type="button" onclick="showChartModal(\'' + safePurok + '\')">View Chart</button>'
              + '<button class="btn btn-secondary btn-sm" type="button" onclick="openPurokPrint(\'' + safePurok + '\')" style="background-color:#6c757d;border-color:#6c757d;color:#fff;">Print</button>'
              + '<button class="btn btn-info btn-sm" type="button" onclick="monthlyReport(\'' + safePurok + '\')" style="background-color:#17a2b8;border-color:#17a2b8;color:#fff;">Monthly Report</button>'
              + '</div>'
              + '</td>'
              + '</tr>';
          }).join('');
        }

        renderPaginationControls();
      }

      function renderPaginationControls() {
        if (!paginationControls) return;
        paginationControls.innerHTML = '';
        const totalPages = Math.max(1, Math.ceil(filteredRows.length / pageSize));
        if (totalPages <= 1) return;

        const prev = document.createElement('button');
        prev.textContent = 'Prev';
        prev.disabled = currentPage <= 1;
        prev.onclick = function () { if (currentPage > 1) { currentPage--; renderTablePage(); } };
        paginationControls.appendChild(prev);

        const pageLabel = document.createElement('span');
        pageLabel.textContent = ` Page ${currentPage} of ${totalPages} `;
        pageLabel.style.margin = '0 8px';
        paginationControls.appendChild(pageLabel);

        const next = document.createElement('button');
        next.textContent = 'Next';
        next.disabled = currentPage >= totalPages;
        next.onclick = function () { if (currentPage < totalPages) { currentPage++; renderTablePage(); } };
        paginationControls.appendChild(next);
      }

      function applySearch() {
        const q = (searchInput && searchInput.value ? searchInput.value : '').trim().toLowerCase();
        filteredRows = allRows.filter(function (row) {
          return String(row.purok || '').toLowerCase().includes(q);
        });
        currentPage = 1;
        renderTablePage();
      }

      async function loadPurokAnalytics() {
        try {
          const res = await fetch(appPath('/api/senior-citizens-for-report'), { credentials: 'same-origin' });
          const json = await res.json();
          const rows = Array.isArray(json && json.data) ? json.data : [];
          const aggregate = {};
          rows.forEach(function (item) {
            const info = item.identifying_information || {};
            const addr = info.address || {};
            const purok = String(item.purok || addr.purok || 'Unknown');
            const gender = String(info.gender || item.gender || '').toLowerCase();
            if (!aggregate[purok]) {
              aggregate[purok] = { purok: purok, count: 0, male: 0, female: 0, other: 0 };
            }
            aggregate[purok].count += 1;
            if (gender === 'male') aggregate[purok].male += 1;
            else if (gender === 'female') aggregate[purok].female += 1;
            else aggregate[purok].other += 1;
          });

          allRows = Object.values(aggregate).sort(function (a, b) { return b.count - a.count; });
          allRows.forEach(function (r) { breakdownByPurok[r.purok] = r; });
          filteredRows = allRows.slice();
          renderTablePage();

          const total = allRows.reduce(function (sum, r) { return sum + Number(r.count || 0); }, 0);
          if (totalOscaEl) totalOscaEl.textContent = String(total);
          if (averageOscaEl) {
            const avg = allRows.length ? (total / allRows.length) : 0;
            averageOscaEl.textContent = avg.toFixed(1);
          }
        } catch (error) {
          if (tableBody) {
            tableBody.innerHTML = '<tr><td colspan="3" style="text-align:center;color:#dc2626;">Failed to load data.</td></tr>';
          }
        }
      }

      window.showChartModal = function (purokName) {
        const detail = breakdownByPurok[purokName] || { male: 0, female: 0, other: 0, count: 0 };
        const total = Number(detail.count || 0);
        const labels = ['Male', 'Female', 'Other'];
        const values = [Number(detail.male || 0), Number(detail.female || 0), Number(detail.other || 0)];
        const colors = ['#2563eb', '#ec4899', '#f59e0b'];
        const maxValue = Math.max.apply(null, values);
        const dominantIndexes = values
          .map(function (v, idx) { return { value: v, idx: idx }; })
          .filter(function (item) { return item.value === maxValue && maxValue > 0; })
          .map(function (item) { return item.idx; });
        const dominantLabel = dominantIndexes.length
          ? dominantIndexes.map(function (idx) { return labels[idx]; }).join(', ')
          : 'N/A';
        const dominantPct = total > 0 && maxValue > 0 ? ((maxValue / total) * 100).toFixed(1) : '0.0';

        document.getElementById('modalTitle').textContent = purokName || 'Purok Analysis';
        document.getElementById('chartModal').classList.add('show');

        const tableBodyEl = document.getElementById('chartTableBody');
        if (tableBodyEl) {
          tableBodyEl.innerHTML = labels.map(function (label, idx) {
            const val = values[idx];
            const pct = total > 0 ? ((val / total) * 100).toFixed(1) : '0.0';
            return '<tr><td>' + label + '</td><td>' + pct + '% (' + val + ')</td></tr>';
          }).join('');
        }
        const infoEl = document.getElementById('chartInfo');
        if (infoEl) {
          infoEl.textContent = `Barangay: ${assignedBarangayName || 'N/A'} | Purok: ${purokName || 'N/A'} | Total Seniors: ${total}`;
        }
        const insightsEl = document.getElementById('chartInsights');
        if (insightsEl) {
          const hasNoData = total <= 0;
          if (hasNoData) {
            insightsEl.innerHTML = '<strong>Insights:</strong> No records available for this purok yet.';
          } else {
            const zeroGroups = labels.filter(function (_, idx) { return values[idx] === 0; });
            const zeroText = zeroGroups.length ? (' Zero count: ' + zeroGroups.join(', ') + '.') : ' All groups have at least one record.';
            insightsEl.innerHTML =
              '<strong>Insights:</strong> '
              + `Dominant group is <strong>${dominantLabel}</strong> with <strong>${maxValue}</strong> record(s) (${dominantPct}%). `
              + `Distribution check: Male ${((values[0] / total) * 100).toFixed(1)}%, Female ${((values[1] / total) * 100).toFixed(1)}%, Other ${((values[2] / total) * 100).toFixed(1)}%.`
              + zeroText;
          }
        }

        if (chartCanvas) {
          if (chartInstance) chartInstance.destroy();
          chartInstance = new Chart(chartCanvas, {
            type: 'doughnut',
            data: { labels: labels, datasets: [{ data: values, backgroundColor: colors }] },
            options: { responsive: true, maintainAspectRatio: false, plugins: { legend: { position: 'bottom' } } }
          });
        }
      };

      window.openPurokPrint = async function (purokName) {
        try {
          const res = await fetch(appPath('/api/senior-citizens/purok/' + encodeURIComponent(String(purokName || ''))), { credentials: 'same-origin' });
          const json = await res.json();
          if (!res.ok || !json || json.success !== true) {
            throw new Error((json && json.message) ? json.message : 'Unable to load print data');
          }
          const rows = Array.isArray(json.data) ? json.data : [];
          const html = buildPurokDetailReportHtml('SENIOR PUROK REPORT', purokName || 'Unknown', rows);
          openPrintWindow(html);
        } catch (error) {
          alert(error.message || 'Error generating print report.');
        }
      };

      window.monthlyReport = async function (purokName) {
        try {
          const now = new Date();
          const month = now.getMonth() + 1;
          const year = now.getFullYear();
          const res = await fetch(appPath('/api/senior-citizens/purok/' + encodeURIComponent(String(purokName || '')) + '?month=' + encodeURIComponent(String(month)) + '&year=' + encodeURIComponent(String(year))), { credentials: 'same-origin' });
          const json = await res.json();
          if (!res.ok || !json || json.success !== true) {
            throw new Error((json && json.message) ? json.message : 'Unable to load monthly report data');
          }
          const rows = Array.isArray(json.data) ? json.data : [];
          const male = rows.filter(function (r) {
            return String(r && r.gender ? r.gender : '').trim().toLowerCase() === 'male';
          }).length;
          const female = rows.filter(function (r) {
            return String(r && r.gender ? r.gender : '').trim().toLowerCase() === 'female';
          }).length;
          const reportRows = [{
            barangay: purokName || 'Unknown',
            male: male,
            female: female,
            total: rows.length
          }];
          const html = buildOscaReportHtml('MONTHLY ACCOMPLISHMENT REPORT', assignedBarangayName || 'Barangay', reportRows);
          openPrintWindow(html);
        } catch (error) {
          alert(error.message || 'Error generating monthly report.');
        }
      };

      if (searchInput) searchInput.addEventListener('input', applySearch);
      loadPurokAnalytics();
    })();
  </script>
  <script src="https://cdn.socket.io/4.6.1/socket.io.min.js"></script>
  <script>
    (function () {
      const badge = document.getElementById('notifBadgeBrgySenior');
      const list = document.getElementById('notifListBrgySenior');
      const dropdown = document.getElementById('notifDropdownBrgySenior');
      const bell = document.getElementById('notifBellBrgySenior');
      let notifications = [];
      function escapeHtml(text) {
        return String(text == null ? '' : text).replace(/[&<>"']/g, function (c) {
          return { '&': '&amp;', '<': '&lt;', '>': '&gt;', '"': '&quot;', "'": '&#39;' }[c];
        });
      }
      function formatDate(value) {
        if (!value) return new Date().toLocaleString();
        const parsed = new Date(value);
        return Number.isNaN(parsed.getTime()) ? new Date().toLocaleString() : parsed.toLocaleString();
      }
      async function markNotificationRead(notificationId) {
        try {
          await fetch((window.__APP_BASE__ || '') + '/api/notifications/mark-read', {
            method: 'POST',
            credentials: 'same-origin',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify({ notification_id: notificationId })
          });
        } catch (error) {
          console.warn('Failed to mark notification as read', error);
        }
      }
      function renderNotifications() {
        if (!list) return;
        const unreadCount = notifications.filter(n => !n.is_read).length;
        if (badge) {
          badge.style.display = unreadCount > 0 ? '' : 'none';
          badge.textContent = String(unreadCount);
        }
        if (notifications.length === 0) {
          list.innerHTML = '<div style="padding:8px;color:#6b7280;">No notifications yet.</div>';
          return;
        }
        list.innerHTML = '';
        notifications.forEach(function (notif) {
          const item = document.createElement('div');
          item.style.padding = '10px';
          item.style.borderBottom = '1px solid #f3f4f6';
          item.style.cursor = 'pointer';
          item.style.background = notif.is_read ? '#fff' : '#f8fafc';
          item.innerHTML =
            '<div style="display:flex;justify-content:space-between;gap:8px;">' +
              '<div style="font-weight:700;">' + escapeHtml(notif.subject || notif.from || 'Alert') + '</div>' +
              (notif.is_read ? '' : '<span style="font-size:11px;color:#0f766e;font-weight:700;">NEW</span>') +
            '</div>' +
            '<div style="font-size:13px;margin-top:4px;">' + escapeHtml(notif.message || '') + '</div>' +
            '<div style="font-size:12px;color:#6b7280;margin-top:6px;">' + escapeHtml(formatDate(notif.created_at)) + '</div>';
          item.addEventListener('click', async function () {
            if (!notif.is_read) {
              await markNotificationRead(notif.id);
              notif.is_read = true;
              renderNotifications();
            }
          });
          list.appendChild(item);
        });
      }
      async function loadNotifications() {
        try {
          const res = await fetch((window.__APP_BASE__ || '') + '/api/notifications', { credentials: 'same-origin' });
          const json = await res.json();
          notifications = (json && json.success && Array.isArray(json.data)) ? json.data : [];
          renderNotifications();
        } catch (error) {
          console.warn('Failed to load notifications', error);
        }
      }
      async function markVisibleNotificationsRead() {
        const unread = notifications.filter(n => !n.is_read && n.id);
        if (unread.length === 0) return;
        await Promise.all(unread.map(n => markNotificationRead(n.id)));
        notifications = notifications.map(n => ({ ...n, is_read: true }));
        renderNotifications();
      }
      if (bell) {
        bell.addEventListener('click', async function (e) {
          e.stopPropagation();
          if (!dropdown) return;
          dropdown.style.display = dropdown.style.display === 'none' ? 'block' : 'none';
          if (dropdown.style.display === 'block') {
            await loadNotifications();
            await markVisibleNotificationsRead();
          }
        });
        if (dropdown) {
          dropdown.addEventListener('click', function (e) { e.stopPropagation(); });
        }
        document.addEventListener('click', function () { if (dropdown) dropdown.style.display = 'none'; });
      }

      const host = window.location.hostname || 'localhost';
      const proto = window.location.protocol === 'https:' ? 'https' : 'http';
      var __pagePort = String(window.location.port || '');
      var ports = [null];
      ['3000', '8080'].forEach(function (p) { if (p !== __pagePort) { ports.push(p); } });
      (function tryConnect(i) {
        if (i >= ports.length) return;
        try {
          const s = ports[i] === null ? io({ transports: ['websocket','polling'], timeout: 4000 }) : io(proto + '://' + host + ':' + ports[i], { transports: ['websocket','polling'], timeout: 4000 });
          s.on('connect', function () { window._socket = s; console.debug('brgy senior socket connected', s.id); });
          s.on('connect_error', function () { tryConnect(i + 1); });
          s.on('receive-alert', function () { loadNotifications(); });
        } catch (error) { tryConnect(i + 1); }
      })(0);
      loadNotifications();
      setInterval(loadNotifications, 10000);
      document.addEventListener('visibilitychange', function () {
        if (!document.hidden) loadNotifications();
      });
    })();

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
      setInterval(checkSession, 3000);
      setInterval(checkLocalActiveUser, 1000);
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

