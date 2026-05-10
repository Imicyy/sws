<!doctype html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width,initial-scale=1">
  <link rel="icon" type="image/png" href="<?= htmlspecialchars(asset_url('images/SilayLogo.png'), ENT_QUOTES) ?>">
  <title>OSCA Admin Dashboard</title>
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/css/bootstrap.min.css">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
  <link rel="stylesheet" href="/files/assets/css/admin.css">
  <link rel="stylesheet" href="<?= htmlspecialchars(asset_url('css/light-theme.css?v=20260424'), ENT_QUOTES) ?>">
  <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
  <style>
    :root {
      --bg-page: linear-gradient(180deg, #fffdf0 0%, #f5f9ff 55%, #eef5ff 100%);
      --panel-border: #dbe5f3;
      --blue-main: #3b82f6;
      --blue-soft: #bfdbfe;
      --yellow-soft: #fef3c7;
      --yellow-main: #facc15;
    }
    body { font-family: Segoe UI, Arial, sans-serif; background: var(--bg-page); display: flex; position: relative; }
    
    /* Sidebar Styles */
    .sidebar { 
      background: linear-gradient(180deg, rgba(20, 32, 74, 0.95) 0%, rgba(35, 66, 140, 0.85) 100%), url('<?= htmlspecialchars(asset_url("images/ebmagtownhall.png"), ENT_QUOTES) ?>') center bottom/cover no-repeat; 
      background-blend-mode: normal; 
      padding: 20px 14px; 
      height: 100vh; 
      width: 260px; 
      box-sizing: border-box; 
      overflow-y: auto; 
      position: fixed; 
      top: 0; 
      left: 0; 
      box-shadow: 10px 0 30px rgba(0, 0, 0, 0.15); 
      display: flex; 
      flex-direction: column; 
      z-index: 100; 
      color: #fff; 
    }
    .brand { 
      font-weight: 900; 
      font-size: 16px; 
      letter-spacing: 0.5px; 
      margin-bottom: 22px; 
      color: #ffffff !important; 
      display: flex; 
      align-items: center; 
      gap: 10px;
      text-transform: uppercase;
      white-space: nowrap;
    }
    .nav-title { 
      font-size: 12px; 
      color: #94a3b8 !important; 
      text-transform: uppercase; 
      margin: 8px 10px; 
      font-weight: 600; 
      letter-spacing: 0.5px; 
    }
    .sidebar .nav-link { 
      display: flex !important; 
      align-items: center !important; 
      justify-content: flex-start !important; 
      text-align: left !important; 
      min-height: 42px !important; 
      padding: 12px 16px; 
      margin-bottom: 8px; 
      border-radius: 12px; 
      background: transparent !important; 
      color: #e2e8f0 !important; 
      text-decoration: none !important; 
      font-weight: 500; 
      font-size: 17px !important; 
      transition: all .3s cubic-bezier(0.4, 0, 0.2, 1); 
      border: none; 
      gap: 12px; 
    }
    .sidebar .nav-link.active { 
      background: #3b82f6 !important; 
      color: #ffffff !important; 
      font-weight: 600; 
      box-shadow: 0 4px 15px rgba(59, 130, 246, 0.4) !important; 
    }
    .sidebar .nav-link:hover:not(.active) { 
      background: rgba(255, 255, 255, 0.1) !important; 
      color: #ffffff !important; 
      transform: translateX(2px); 
    }
    .logout-btn { 
      margin-top: auto !important; 
      display: flex !important; 
      align-items: center !important; 
      justify-content: center !important;
      gap: 12px;
      padding: 12px 16px; 
      border-radius: 12px; 
      background: #fee2e2 !important; 
      color: #991b1b !important; 
      font-weight: 700 !important; 
      text-decoration: none !important; 
      font-size: 17px;
      transition: all .3s ease;
    }
    .logout-btn:hover { 
      background: #ef4444 !important; 
      color: #fff !important; 
      transform: translateY(-2px) !important; 
      box-shadow: 0 6px 12px rgba(239, 68, 68, 0.2) !important; 
    }
    .main { flex: 1; padding: 30px; margin-left: 260px; position: relative; width: calc(100% - 260px); z-index: 1; }
    .main::before {
      content: ""; position: fixed; top: 0; left: 260px; right: 0; bottom: 0;
      background: url('<?= htmlspecialchars(asset_url("images/SilayLogo.png"), ENT_QUOTES) ?>') no-repeat center;
      background-size: 35%; opacity: 0.04; pointer-events: none; z-index: -1;
    }
    
    /* Live Clock - Super Admin Style */
    .header-top { display: flex; justify-content: flex-end; margin-bottom: 20px; }
    #liveClock { 
      min-width: 250px;
      text-align: left;
      line-height: 1.1;
      border-left: 3px solid #3b82f6;
      padding-left: 20px;
    }
    #clockDate { font-size: 15px; color: #64748b; font-weight: 600; margin-bottom: 4px; white-space: nowrap; }
    #clockTime { color: #2563eb; font-size: 28px; font-weight: 800; font-variant-numeric: tabular-nums; white-space: nowrap; }
    .top { 
      display: flex; justify-content: space-between; align-items: center; 
      margin-bottom: 24px; padding: 18px 24px; border-radius: 16px; 
      border: 1px solid var(--panel-border); background: rgba(255,255,255,0.9); 
      backdrop-filter: blur(10px); box-shadow: 0 10px 30px rgba(0,0,0,0.05); 
      width: 100%;
    }
    .header-info h1 { font-size: 28px; font-weight: 800; color: #1e293b; margin: 0; }
    .header-info p { font-size: 16px; color: #64748b; margin: 4px 0 0 0; font-weight: 500; }
    .top .welcome { color: #6b7280; font-size: 14px; }
    .cards { display: grid; grid-template-columns: repeat(3, 1fr); gap: 16px; width: 100%; margin: 0 0 20px 0; }
    .card-metric { 
      border-radius: 16px; padding: 20px; color: #1e3a8a; border: 1px solid #dbe5f3; 
      box-shadow: 0 14px 26px rgba(59, 130, 246, 0.10); text-align: left; 
      display: flex; align-items: center; gap: 20px; transition: transform 0.3s ease;
      background: #fff;
    }
    .card-metric:hover { transform: translateY(-5px); }
    .card-icon { 
      width: 50px; height: 50px; border-radius: 12px; display: flex; align-items: center; justify-content: center; 
      font-size: 22px; flex-shrink: 0;
    }
    .card-metric .label { font-size: 14px; text-transform: uppercase; font-weight: 700; opacity: 0.8; letter-spacing: 0.5px; }
    .card-metric .value { font-size: 32px; font-weight: 800; margin-top: 2px; }
    
    .bg-yellow .card-icon { background: #fef3c7; color: #b45309; }
    .bg-blue .card-icon { background: #dbeafe; color: #1d4ed8; }
    .bg-green .card-icon { background: #d1fae5; color: #047857; }
    
    .bg-yellow { background: linear-gradient(135deg, #ffffff, #fffbeb); }
    .bg-blue { background: linear-gradient(135deg, #ffffff, #eff6ff); }
    .bg-green { background: linear-gradient(135deg, #ffffff, #f0fdf4); }
    .bg-red { background: linear-gradient(135deg, #fff7ed, #fed7aa); color: #9a3412; }
    .panel { margin: 16px 0 0; width: 100%; background: linear-gradient(180deg, #ffffff 0%, #fffcf3 100%); border: 1px solid var(--panel-border); border-radius: 14px; padding: 22px; box-shadow: 0 14px 26px rgba(59, 130, 246, 0.08); }
    .table-responsive { margin-top: 24px; }
    table { width: 100%; border-collapse: collapse; font-size: 15px; }
    th, td { padding: 12px 16px; border-bottom: 1px solid #e5e7eb; text-align: center; }
    th { background: #eff6ff; color: #1e3a8a; font-weight: 700; }
    tr:last-child td { border-bottom: none; }
    .action-group { display: flex; gap: 6px; justify-content: center; }
    .chip-btn { border: 0; background: linear-gradient(135deg, #60a5fa, #3b82f6); color: #fff; border-radius: 999px; font-size: 12px; font-weight: 700; padding: 6px 14px; cursor: pointer; }
    .chip-btn:hover { background: linear-gradient(135deg, #3b82f6, #2563eb); }
    .chart-shell { display: grid; grid-template-columns: 1.2fr 0.8fr; gap: 14px; align-items: stretch; }
    .chart-card { border: 1px solid var(--panel-border); border-radius: 12px; padding: 12px; background: linear-gradient(180deg, #ffffff 0%, #fffdf5 100%); }
    .chart-toolbar { display: flex; gap: 8px; align-items: center; justify-content: space-between; margin-bottom: 10px; flex-wrap: wrap; }
    .chart-type-btn { border: 1px solid #cbd5e1; background: #f8fbff; color: #1e3a8a; border-radius: 999px; font-weight: 700; font-size: 12px; padding: 6px 12px; cursor: pointer; }
    .chart-type-btn.active { background: #3b82f6; border-color: #3b82f6; color: #fff; }
    .chart-box { height: 340px; position: relative; }
    .insight-box h6 { font-weight: 800; margin-bottom: 10px; }
    .kpi-row { display: grid; grid-template-columns: 1fr 1fr; gap: 10px; margin-bottom: 10px; }
    .kpi { border: 1px solid #dbe5f3; border-radius: 10px; padding: 10px 12px; background: #fffbeb; }
    .kpi .label { font-size: 12px; color: #6b7280; font-weight: 700; }
    .kpi .value { font-size: 18px; font-weight: 900; margin-top: 4px; }
    .kpi .sub { font-size: 12px; color: #6b7280; margin-top: 2px; }
    .insight-text { font-size: 13px; color: #374151; line-height: 1.5; }
    .chart-table { width: 100%; border-collapse: collapse; font-size: 13px; }
    .chart-table th, .chart-table td { padding: 10px 12px; border-bottom: 1px solid #e5e7eb; }
    .chart-table th { background: #eff6ff; color: #1e3a8a; font-weight: 800; }
    .muted { color: #6b7280; }
    @media (max-width: 980px) {
      .layout { grid-template-columns: 1fr; }
      .sidebar { position: static; height: auto; max-height: none; }
      .cards { grid-template-columns: repeat(2, minmax(0, 1fr)); }
      .table-responsive { overflow-x: auto; }
      table { min-width: 600px; }
      .chart-shell { grid-template-columns: 1fr; }
    }

    /* Pagination Styles */
    .pagination-container { 
      display: flex; justify-content: space-between; align-items: center; 
      margin-top: 24px; padding-top: 20px; border-top: 1px solid #e2e8f0;
      flex-wrap: wrap; gap: 16px;
    }
    .page-info { font-size: 13px; color: #64748b; font-weight: 600; }
    .page-btns { display: flex; gap: 6px; }
    .page-btn { 
      min-width: 36px; height: 36px; padding: 0 12px; border-radius: 8px; 
      border: 1px solid #e2e8f0; background: #fff; color: #64748b; 
      font-weight: 700; font-size: 13px; cursor: pointer; transition: all 0.2s;
      display: flex; align-items: center; justify-content: center;
    }
    .page-btn:hover:not(:disabled) { background: #f8fafc; border-color: var(--blue-main); color: var(--blue-main); }
    .page-btn.active { background: var(--blue-main); border-color: var(--blue-main); color: #fff; box-shadow: 0 4px 10px rgba(59, 130, 246, 0.2); }
    .page-btn:disabled { opacity: 0.4; cursor: not-allowed; }
    .page-ellipsis { padding: 0 8px; color: #94a3b8; font-weight: 700; }
  </style>
</head>
<body>
    <aside class="sidebar">
      <div class="brand">
        <img src="<?= htmlspecialchars(asset_url('images/SilayLogo.png'), ENT_QUOTES) ?>" alt="Logo" style="width: 30px; height: 30px;">
        <span>Enrique B. Magalona</span>
      </div>

      <div class="nav-title">NAVIGATION</div>
      <a class="nav-link active" href="/Analytics">
        <i class="fas fa-users"></i>
        <span>Senior Citizen Table</span>
      </a>
      <a class="nav-link" href="/admin-alert?from=osca">
        <i class="fas fa-bell"></i>
        <span>Alerts</span>
      </a>

      <a class="logout-btn" href="/logout">
        <i class="fas fa-sign-out-alt"></i>
        <span>Logout</span>
      </a>
    </aside>

    <main class="main">
      <div class="header-top">
        <div id="liveClock">
          <div id="clockDate"></div>
          <div id="clockTime"></div>
        </div>
      </div>
      <div class="top">
        <div class="header-info">
          <h1>OSCA Admin Dashboard</h1>
          <p>Signed in as <?= htmlspecialchars((string) ($user['email'] ?? 'admin'), ENT_QUOTES, 'UTF-8') ?></p>
        </div>
      </div>

      <?php
      // Aggregate senior count per barangay from $seniors
      $barangayCounts = [];
      if (isset($seniors) && is_array($seniors)) {
        foreach ($seniors as $senior) {
          $barangay = trim((string)($senior['barangay'] ?? 'Unknown'));
          if ($barangay === '') $barangay = 'Unknown';
          if (!isset($barangayCounts[$barangay])) $barangayCounts[$barangay] = 0;
          $barangayCounts[$barangay]++;
        }
      }
      $totalBarangays = count($barangayCounts);
      $totalSeniors = isset($seniors) && is_array($seniors) ? count($seniors) : 0;
      $averagePerBarangay = $totalBarangays > 0 ? round($totalSeniors / $totalBarangays, 1) : 0;
      ?>

      <section class="cards">
        <div class="card-metric bg-yellow">
          <div class="card-icon">
            <i class="fas fa-map-marked-alt"></i>
          </div>
          <div class="card-info">
            <div class="label">Total Barangays</div>
            <div class="value"><?php echo $totalBarangays; ?></div>
          </div>
        </div>
        <div class="card-metric bg-blue">
          <div class="card-icon">
            <i class="fas fa-users"></i>
          </div>
          <div class="card-info">
            <div class="label">Total Senior Citizen</div>
            <div class="value">
              <?php echo $totalSeniors; ?>
            </div>
          </div>
        </div>
        <div class="card-metric bg-green">
          <div class="card-icon">
            <i class="fas fa-chart-line"></i>
          </div>
          <div class="card-info">
            <div class="label">Average per Barangay</div>
            <div class="value"><?php echo $averagePerBarangay; ?></div>
          </div>
        </div>
      </section>

      <section class="panel">
              <div style="display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; margin-bottom: 10px;">
                <div style="display: flex; align-items: center; gap: 10px;">
                  <a href="/Maps" target="_blank" rel="noopener noreferrer" class="btn btn-info" style="font-weight:600; border-radius:8px;">&#128506; Senior Map</a>
                  <h2 class="h6 mb-0" style="margin-bottom:0;">Barangay Senior Citizen Table</h2>
                </div>
                <div style="display: flex; gap: 8px; align-items: center;">
                  <button type="button" class="btn btn-success btn-sm" id="generateReportBtn" style="font-weight:600; border-radius:8px; white-space:nowrap;">Generate Report</button>
                  <input id="searchInput" type="text" class="form-control" placeholder="Search barangay..." style="max-width: 180px; font-size: 13px;">
                </div>
              </div>
              <div class="table-responsive">
                              
                <table>
                  <thead>
                    <tr>
                      <th>Barangay Name</th>
                      <th>Senior Citizen Count</th>
                      <th>Action</th>
                    </tr>
                  </thead>
                  <tbody>
                    <?php if (!empty($barangayCounts)): ?>
                      <?php foreach ($barangayCounts as $barangay => $count): ?>
                        <tr>
                          <td><?= htmlspecialchars($barangay, ENT_QUOTES, 'UTF-8') ?></td>
                          <td><?= (int)$count ?></td>
                          <td>
                            <div class="action-group">
                              <button class="chip-btn" type="button" onclick="viewChart('<?= htmlspecialchars($barangay, ENT_QUOTES, 'UTF-8') ?>')">View Chart</button>
                              <button class="btn btn-secondary btn-sm" type="button" onclick="window.print()">Print</button>
                              <button class="btn btn-info btn-sm" type="button" onclick="monthlyReport('<?= htmlspecialchars($barangay, ENT_QUOTES, 'UTF-8') ?>')">Monthly Report</button>
                            </div>
                          </td>
                        </tr>
                      <?php endforeach; ?>
                    <?php else: ?>
                      <tr><td colspan="3" style="text-align:center; color:#888;">No data available</td></tr>
                    <?php endif; ?>
                  </tbody>
                </table>
              </div>
              <div class="pagination-container">
                <div class="page-info" id="paginationInfo">Showing 0 to 0 of 0 entries</div>
                <div class="page-btns" id="paginationBtns"></div>
              </div>
      </section>
    </main>

  <!-- Report Modal -->
  <div class="modal fade" id="reportModal" tabindex="-1" role="dialog" aria-labelledby="reportModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="reportModalLabel">Generate Report</h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body">
          <div class="form-group">
            <label for="reportTypeSelect">Report Type</label>
            <select class="form-control" id="reportTypeSelect" onchange="toggleReportDateInputs()">
              <option value="annual">Annual</option>
              <option value="monthly">Monthly</option>
            </select>
          </div>
          <div class="form-group">
            <label for="reportYear">Year</label>
            <select class="form-control" id="reportYear"></select>
          </div>
          <div class="form-group" id="reportMonthGroup" style="display:none;">
            <label for="reportMonth">Month</label>
            <select class="form-control" id="reportMonth">
              <option value="1">January</option><option value="2">February</option><option value="3">March</option>
              <option value="4">April</option><option value="5">May</option><option value="6">June</option>
              <option value="7">July</option><option value="8">August</option><option value="9">September</option>
              <option value="10">October</option><option value="11">November</option><option value="12">December</option>
            </select>
          </div>
          <div class="muted" style="font-size:12px;">Generates an OSCA report based on the selected period.</div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
          <button type="button" class="btn btn-primary" id="modalGenerateBtn">Generate Report</button>
        </div>
      </div>
    </div>
  </div>

  <!-- Chart Modal -->
  <div class="modal fade" id="chartModal" tabindex="-1" role="dialog" aria-labelledby="chartModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="chartModalLabel">Senior Citizen Analytics</h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body">
          <div class="chart-shell">
            <div class="chart-card">
              <div class="chart-toolbar">
                <div>
                  <div style="font-weight:900;" id="chartScopeTitle">Barangay</div>
                  <div class="muted" id="chartScopeSubtitle">Gender distribution</div>
                </div>
                <div style="display:flex; gap:8px; align-items:center;">
                  <button type="button" class="chart-type-btn active" id="chartTypeDonutBtn">Donut Graph</button>
                  <button type="button" class="chart-type-btn" id="chartTypeTableBtn">Table Graph</button>
                </div>
              </div>
              <div id="chartContainer" class="chart-box"><canvas id="barangayChart"></canvas></div>
              <div id="tableContainer" style="display:none;">
                <table class="chart-table">
                  <thead><tr><th>Category</th><th>Count</th><th>Percentage</th></tr></thead>
                  <tbody id="chartTableBody"><tr><td colspan="3" class="muted">No data loaded.</td></tr></tbody>
                </table>
              </div>
            </div>
            <div class="chart-card insight-box">
              <h6>Insights</h6>
              <div class="kpi-row">
                <div class="kpi">
                  <div class="label">Total Male</div>
                  <div class="value" id="kpiMaleCount">0</div>
                  <div class="sub" id="kpiMalePct">0.0%</div>
                </div>
                <div class="kpi">
                  <div class="label">Total Female</div>
                  <div class="value" id="kpiFemaleCount">0</div>
                  <div class="sub" id="kpiFemalePct">0.0%</div>
                </div>
              </div>
              <div class="kpi" style="margin-bottom:10px;">
                <div class="label">Total Seniors</div>
                <div class="value" id="kpiTotalCount">0</div>
                <div class="sub" id="kpiTotalNote">Male + Female</div>
              </div>
              <div class="insight-text" id="chartInsightText">Select “View Chart” to see donut graph, table graph, and insights.</div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</body>
</html>
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/js/bootstrap.bundle.min.js"></script>
<script>
let currentChart = null;
let currentChartPayload = null;
let currentChartView = 'donut';

function escapeHtml(value) {
  return String(value === null || value === undefined ? '' : value)
    .replace(/&/g, '&amp;')
    .replace(/</g, '&lt;')
    .replace(/>/g, '&gt;')
    .replace(/"/g, '&quot;')
    .replace(/'/g, '&#039;');
}

function monthName(monthNum) {
  const months = ['January','February','March','April','May','June','July','August','September','October','November','December'];
  return months[(monthNum || 1) - 1] || '';
}

function toNumber(value) {
  const n = Number(value);
  return Number.isFinite(n) ? n : 0;
}

function populateYearDropdown() {
  const yearSelect = document.getElementById('reportYear');
  const currentYear = new Date().getFullYear();
  yearSelect.innerHTML = '';
  for (let y = currentYear; y >= currentYear - 9; y -= 1) {
    const option = document.createElement('option');
    option.value = String(y);
    option.textContent = String(y);
    yearSelect.appendChild(option);
  }
}

window.toggleReportDateInputs = function() {
  const type = document.getElementById('reportTypeSelect').value;
  document.getElementById('reportMonthGroup').style.display = type === 'monthly' ? '' : 'none';
};

async function openPrintWindow(html) {
  const newWin = window.open('', '_blank', 'width=1200,height=800,scrollbars=yes');
  if (!newWin) { alert('Popup blocked! Please allow popups.'); return; }
  newWin.document.open(); newWin.document.write(html); newWin.document.close();
}

function buildOscaReportHtml(reportTitle, scopeName, rows) {
  const safeRows = Array.isArray(rows) ? rows : [];
  const bodyRows = safeRows.length ? safeRows.map((r) => `
    <tr>
      <td><strong>${escapeHtml(r.barangay || 'Unknown')}</strong></td>
      <td>${toNumber(r.male).toLocaleString()}</td>
      <td>${toNumber(r.female).toLocaleString()}</td>
      <td>${toNumber(r.total).toLocaleString()}</td>
    </tr>
  `).join('') : '<tr><td colspan="4" class="text-center">No data available.</td></tr>';

  const totals = safeRows.reduce((acc, r) => {
    acc.total += toNumber(r.total);
    acc.male += toNumber(r.male);
    acc.female += toNumber(r.female);
    return acc;
  }, { total: 0, male: 0, female: 0 });
  const barangayCount = safeRows.length;

  const displayScope = scopeName && scopeName.trim() !== '' ? scopeName : 'All Barangays';

  return `<!doctype html>
<html>
<head>
  <meta charset="utf-8">
  <title>${escapeHtml(reportTitle)} - ${escapeHtml(displayScope)}</title>
  <meta name="viewport" content="width=device-width,initial-scale=1">
  <link rel="stylesheet" href="/bower_components/bootstrap/css/bootstrap.min.css">
  <style>
    @page { size: A4 landscape; margin: 8mm; }
    body { margin: 0; padding: 12px 16px; font-family: Arial, sans-serif; color: #1f2937; font-size: 14px; }
    .print-button-container { text-align: center; margin-bottom: 12px; padding: 10px; background: #f8fafc; border-radius: 6px; }
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
      <img src="/assets/images/SilayLogo.jpg" class="logo-left">
      <img src="/assets/images/BagongPilipinas.jpg" class="logo-right">
      <div class="main-header">
        <h4>Republic of the Philippines</h4>
        <h2><strong>ENRIQUE B. MAGALONA</strong></h2>
        <p>Office Senior Citizens Affairs</p>
      </div>
    </div>

    <div class="title-section">
      <h4>OFFICE OF SENIOR CITIZENS AFFAIRS</h4>
      <h5>${escapeHtml(reportTitle)}</h5>
      <h4><strong>${escapeHtml(displayScope)}</strong></h4>
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
          <p>Number of Barangays: ${barangayCount.toLocaleString()}</p>
        </div>
      </div>
    </div>
  </div>
</body>
</html>`;
}

function buildSeniorDetailReportHtml(title, scopeName, rows) {
  const safeRows = Array.isArray(rows) ? rows : [];
  const total = safeRows.length;
  const male = safeRows.filter((r) => /^male$/i.test(String(r.gender || ''))).length;
  const female = safeRows.filter((r) => /^female$/i.test(String(r.gender || ''))).length;
  const rowsHtml = safeRows.length
    ? safeRows.map((r, idx) => `
      <tr>
        <td>${idx + 1}</td>
        <td>${escapeHtml(r.fullName || 'Unnamed')}</td>
        <td>${escapeHtml(r.contact || 'N/A')}</td>
        <td>${escapeHtml(r.gender || 'N/A')}</td>
        <td>${escapeHtml(r.age || 'N/A')}</td>
      </tr>
    `).join('')
    : '<tr><td colspan="5" class="text-center">No data available.</td></tr>';

  return `<!doctype html>
<html>
<head>
  <meta charset="utf-8">
  <title>${escapeHtml(title)} - ${escapeHtml(scopeName || 'Unknown')}</title>
  <meta name="viewport" content="width=device-width,initial-scale=1">
  <link rel="stylesheet" href="/bower_components/bootstrap/css/bootstrap.min.css">
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
      <img src="/assets/images/SilayLogo.jpg" class="logo-left">
      <img src="/assets/images/BagongPilipinas.jpg" class="logo-right">
      <div class="main-header">
        <h3>Republic of the Philippines</h3>
        <h4>ENRIQUE B. MAGALONA</h4>
        <p>Office Senior Citizens Affairs</p>
      </div>
    </div>
    <div class="title-section">
      <h5>OFFICE OF SENIOR CITIZENS AFFAIRS</h5>
      <h5>${escapeHtml(title)}</h5>
      <h5><strong>${escapeHtml(scopeName || 'Unknown')}</strong></h5>
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

function setChartType(type) {
  currentChartView = type;
  document.getElementById('chartTypeDonutBtn').classList.toggle('active', type === 'donut');
  document.getElementById('chartTypeTableBtn').classList.toggle('active', type === 'table');
  renderChartOrTable();
}

function renderInsights(p) {
  if (!p) return;
  document.getElementById('kpiMaleCount').textContent = p.male.toLocaleString();
  document.getElementById('kpiFemaleCount').textContent = p.female.toLocaleString();
  document.getElementById('kpiTotalCount').textContent = p.total.toLocaleString();
  document.getElementById('kpiMalePct').textContent = p.malePct.toFixed(1) + '%';
  document.getElementById('kpiFemalePct').textContent = p.femalePct.toFixed(1) + '%';

  let insight = `In ${p.barangay}, there are <strong>${p.total.toLocaleString()}</strong> active senior records. `;
  if (p.total === 0) {
    insight = `No active senior records found for ${p.barangay}.`;
  } else if (p.malePct > p.femalePct) {
    insight += `Male accounts for <strong>${p.malePct.toFixed(1)}%</strong> (${p.male.toLocaleString()}), higher than female at <strong>${p.femalePct.toFixed(1)}%</strong> (${p.female.toLocaleString()}).`;
  } else if (p.femalePct > p.malePct) {
    insight += `Female accounts for <strong>${p.femalePct.toFixed(1)}%</strong> (${p.female.toLocaleString()}), higher than male at <strong>${p.malePct.toFixed(1)}%</strong> (${p.male.toLocaleString()}).`;
  } else {
    insight += `Male and female are balanced at <strong>${p.malePct.toFixed(1)}%</strong> each.`;
  }
  document.getElementById('chartInsightText').innerHTML = insight;
}

function renderChartTable(p) {
  const tbody = document.getElementById('chartTableBody');
  tbody.innerHTML = `
    <tr><td><strong>Male</strong></td><td>${p.male.toLocaleString()}</td><td>${p.malePct.toFixed(1)}%</td></tr>
    <tr><td><strong>Female</strong></td><td>${p.female.toLocaleString()}</td><td>${p.femalePct.toFixed(1)}%</td></tr>
  `;
}

function renderDonut(p) {
  const ctx = document.getElementById('barangayChart').getContext('2d');
  if (currentChart) currentChart.destroy();
  currentChart = new Chart(ctx, {
    type: 'doughnut',
    data: {
      labels: ['Male', 'Female'],
      datasets: [{
        data: [p.male, p.female],
        backgroundColor: ['#061727', '#415E72'],
        borderColor: ['#061727', '#FDFAF6'],
        borderWidth: 2,
        hoverOffset: 14
      }]
    },
    options: {
      responsive: true,
      maintainAspectRatio: false,
      plugins: {
        legend: { position: 'bottom' },
        tooltip: {
          callbacks: {
            label: function(context) {
              const label = context.label || '';
              const value = context.parsed || 0;
              const pct = p.total ? ((value / p.total) * 100) : 0;
              return `${label}: ${value.toLocaleString()} (${pct.toFixed(1)}%)`;
            }
          }
        }
      }
    }
  });
}

function renderChartOrTable() {
  const p = currentChartPayload;
  if (!p) return;
  const chartContainer = document.getElementById('chartContainer');
  const tableContainer = document.getElementById('tableContainer');
  if (currentChartView === 'table') {
    chartContainer.style.display = 'none';
    tableContainer.style.display = '';
    renderChartTable(p);
    if (currentChart) { currentChart.destroy(); currentChart = null; }
    return;
  }
  chartContainer.style.display = '';
  tableContainer.style.display = 'none';
  renderDonut(p);
}

window.viewChart = async function(barangay) {
  try {
    const res = await fetch('/api/analytics/osca', { credentials: 'same-origin' });
    const json = await res.json();
    const list = (json && Array.isArray(json.data)) ? json.data : [];
    const row = list.find(item => item.name === barangay);
    if (!row) { alert('No data found for ' + barangay); return; }

    const male = parseInt(row.male || 0, 10);
    const female = parseInt(row.female || 0, 10);
    const total = parseInt(row.total || (male + female), 10);
    const malePct = total ? (male / total) * 100 : 0;
    const femalePct = total ? (female / total) * 100 : 0;
    currentChartPayload = { barangay, male, female, total, malePct, femalePct };

    document.getElementById('chartScopeTitle').textContent = barangay + ' - OSCA';
    document.getElementById('chartScopeSubtitle').textContent = 'Gender distribution (Male/Female)';
    $('#chartModal').modal('show');
    renderInsights(currentChartPayload);
    renderChartOrTable();
  } catch (e) {
    alert('Error loading chart. Please try again.');
  }
};

async function openBarangayPrint(barangay) {
  const res = await fetch(`/api/senior-citizens/barangay/${encodeURIComponent(barangay)}`, { credentials: 'same-origin' });
  const json = await res.json();
  if (!res.ok || !json.success) throw new Error((json && json.message) || 'Unable to load seniors');
  const seniors = json.data || [];
  await openPrintWindow(buildSeniorDetailReportHtml('SENIOR BARANGAY REPORT', barangay, seniors));
}

window.monthlyReport = async function(barangay) {
  try {
    const now = new Date();
    const month = now.getMonth() + 1;
    const year = now.getFullYear();
    const res = await fetch(`/api/senior-citizens/barangay/${encodeURIComponent(barangay)}?month=${month}&year=${year}`, { credentials: 'same-origin' });
    const json = await res.json();
    if (!res.ok || !json.success) throw new Error((json && json.message) || 'Unable to load seniors');
    const seniors = json.data || [];
    let male = 0;
    let female = 0;
    seniors.forEach((s) => {
      const g = String(s.gender || '');
      if (/^male$/i.test(g)) male += 1;
      else if (/^female$/i.test(g)) female += 1;
    });
    const rows = [{
      barangay,
      male,
      female,
      total: seniors.length
    }];
    await openPrintWindow(buildOscaReportHtml('MONTHLY ACCOMPLISHMENT REPORT', barangay, rows));
  } catch (e) {
    alert(e.message || 'Error generating monthly report.');
  }
};

async function generateReportWithSelection() {
  const type = document.getElementById('reportTypeSelect').value;
  const year = parseInt(document.getElementById('reportYear').value, 10);
  const month = parseInt(document.getElementById('reportMonth').value, 10);

  let query = `?year=${encodeURIComponent(String(year))}`;
  let subtitle = `Year ${year}`;
  if (type === 'monthly') {
    query = `?month=${encodeURIComponent(String(month))}&year=${encodeURIComponent(String(year))}`;
    subtitle = `${monthName(month)} ${year}`;
  }

  const res = await fetch(`/api/senior-citizens-for-report${query}`, { credentials: 'same-origin' });
  const json = await res.json();
  if (!res.ok || !json.success) throw new Error((json && json.message) || 'Unable to load report data');

  // Aggregate by barangay with gender counts (matches the payload shape of getSeniorCitizensForReport)
  const rows = Array.isArray(json.data) ? json.data : [];
  const agg = {};
  rows.forEach(r => {
    const b = (r.identifying_information && r.identifying_information.address && r.identifying_information.address.barangay) ? r.identifying_information.address.barangay : 'Unknown';
    const g = (r.identifying_information && r.identifying_information.gender) ? String(r.identifying_information.gender) : 'Unknown';
    if (!agg[b]) agg[b] = { total: 0, male: 0, female: 0, other: 0 };
    agg[b].total += 1;
    if (/^male$/i.test(g)) agg[b].male += 1;
    else if (/^female$/i.test(g)) agg[b].female += 1;
    else agg[b].other += 1;
  });

  const barangays = Object.keys(agg).sort((a, b) => a.localeCompare(b));
  const reportRows = barangays.map((b) => ({
    barangay: b,
    male: agg[b].male,
    female: agg[b].female,
    total: agg[b].total
  }));

  const title = type === 'monthly' ? 'MONTHLY ACCOMPLISHMENT REPORT' : 'ANNUAL ACCOMPLISHMENT REPORT';
  await openPrintWindow(buildOscaReportHtml(title, barangays.length === 1 ? barangays[0] : 'All Barangays', reportRows));
}

document.addEventListener('DOMContentLoaded', function() {
  function updateClock() {
    const now = new Date();
    const days = ['Sunday','Monday','Tuesday','Wednesday','Thursday','Friday','Saturday'];
    const months = ['January','February','March','April','May','June','July','August','September','October','November','December'];
    
    const dateStr = `${days[now.getDay()]}, ${months[now.getMonth()]} ${now.getDate()}, ${now.getFullYear()}`;
    const timeStr = now.toLocaleTimeString('en-US', { hour12: true, hour: '2-digit', minute: '2-digit', second: '2-digit' });

    const dEl = document.getElementById('clockDate');
    const tEl = document.getElementById('clockTime');
    if(dEl) dEl.textContent = dateStr;
    if(tEl) tEl.textContent = timeStr;
  }
  setInterval(updateClock, 1000);
  updateClock();

  populateYearDropdown();
  toggleReportDateInputs();

  document.getElementById('searchInput').addEventListener('input', function(e) {
    const filter = String(e.target.value || '').toLowerCase();
    document.querySelectorAll('.table-responsive table tbody tr').forEach(function(row) {
      const barangay = (row.querySelector('td') ? row.querySelector('td').textContent : '').toLowerCase();
      if (barangay.indexOf(filter) !== -1) {
        row.dataset.filtered = 'false';
      } else {
        row.dataset.filtered = 'true';
      }
    });
    currentPage = 1;
    updateTablePagination();
  });

  let currentPage = 1;
  const rowsPerPage = 3;

  function updateTablePagination() {
    const rows = Array.from(document.querySelectorAll('.table-responsive table tbody tr'));
    const visibleRows = rows.filter(r => r.dataset.filtered !== 'true');
    const totalVisible = visibleRows.length;
    const totalPages = Math.ceil(totalVisible / rowsPerPage);
    
    if (currentPage > totalPages && totalPages > 0) currentPage = totalPages;
    if (currentPage < 1) currentPage = 1;

    const start = (currentPage - 1) * rowsPerPage;
    const end = start + rowsPerPage;

    rows.forEach(r => r.style.display = 'none');
    visibleRows.slice(start, end).forEach(r => r.style.display = '');

    // Info
    const infoEl = document.getElementById('paginationInfo');
    if (infoEl) {
      const displayStart = totalVisible === 0 ? 0 : start + 1;
      const displayEnd = Math.min(end, totalVisible);
      infoEl.textContent = `Showing ${displayStart} to ${displayEnd} of ${totalVisible} entries`;
    }

    // Buttons
    const btnContainer = document.getElementById('paginationBtns');
    if (btnContainer) {
      btnContainer.innerHTML = '';
      
      const prevBtn = document.createElement('button');
      prevBtn.className = 'page-btn';
      prevBtn.innerHTML = '<i class="fas fa-chevron-left"></i>';
      prevBtn.disabled = currentPage === 1;
      prevBtn.onclick = () => { currentPage--; updateTablePagination(); };
      btnContainer.appendChild(prevBtn);

      // Simple range
      let startPage = Math.max(1, currentPage - 2);
      let endPage = Math.min(totalPages, startPage + 4);
      if (endPage - startPage < 4) startPage = Math.max(1, endPage - 4);

      for (let i = startPage; i <= endPage; i++) {
        const pBtn = document.createElement('button');
        pBtn.className = 'page-btn' + (i === currentPage ? ' active' : '');
        pBtn.textContent = i;
        pBtn.onclick = () => { currentPage = i; updateTablePagination(); };
        btnContainer.appendChild(pBtn);
      }

      const nextBtn = document.createElement('button');
      nextBtn.className = 'page-btn';
      nextBtn.innerHTML = '<i class="fas fa-chevron-right"></i>';
      nextBtn.disabled = currentPage === totalPages || totalPages === 0;
      nextBtn.onclick = () => { currentPage++; updateTablePagination(); };
      btnContainer.appendChild(nextBtn);
    }
  }

  // Initial call
  updateTablePagination();

  document.getElementById('generateReportBtn').addEventListener('click', function() {
    const now = new Date();
    document.getElementById('reportYear').value = String(now.getFullYear());
    document.getElementById('reportMonth').value = String(now.getMonth() + 1);
    $('#reportModal').modal('show');
  });

  document.getElementById('modalGenerateBtn').addEventListener('click', async function() {
    try {
      await generateReportWithSelection();
      $('#reportModal').modal('hide');
    } catch (e) {
      alert(e.message || 'Error generating report.');
    }
  });

  document.getElementById('chartTypeDonutBtn').addEventListener('click', function() { setChartType('donut'); });
  document.getElementById('chartTypeTableBtn').addEventListener('click', function() { setChartType('table'); });

  $('#chartModal').on('hidden.bs.modal', function() {
    currentChartPayload = null;
    if (currentChart) { currentChart.destroy(); currentChart = null; }
    currentChartView = 'donut';
    document.getElementById('chartTypeDonutBtn').classList.add('active');
    document.getElementById('chartTypeTableBtn').classList.remove('active');
    document.getElementById('chartInsightText').textContent = 'Select “View Chart” to see donut graph, table graph, and insights.';
    document.getElementById('chartTableBody').innerHTML = '<tr><td colspan="3" class="muted">No data loaded.</td></tr>';
  });

  // Replace row "Print" (window.print) with barangay-print page using API
  document.querySelectorAll('button[onclick^="window.print"]').forEach(function(btn) {
    const row = btn.closest('tr');
    const barangayCell = row ? row.querySelector('td') : null;
    const barangay = barangayCell ? barangayCell.textContent.trim() : '';
    if (!barangay) return;
    btn.removeAttribute('onclick');
    btn.addEventListener('click', function() {
      openBarangayPrint(barangay).catch(err => alert(err.message || 'Error printing.'));
    });
  });
});
</script>

