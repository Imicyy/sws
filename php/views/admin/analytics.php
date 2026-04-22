<!doctype html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width,initial-scale=1">
  <title>OSCA Admin Dashboard</title>
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/css/bootstrap.min.css">
  <link rel="stylesheet" href="/files/assets/css/admin.css">
  <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
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
    .cards { display: grid; grid-template-columns: repeat(4, minmax(0, 1fr)); gap: 14px; }
    .card-metric { border-radius: 12px; padding: 16px; color: #fff; box-shadow: 0 10px 24px rgba(0,0,0,.12); }
    .card-metric .label { font-size: 12px; text-transform: uppercase; opacity: 0.9; }
    .card-metric .value { font-size: 26px; font-weight: 700; margin-top: 6px; }
    .bg-yellow { background: linear-gradient(135deg, #f59e0b, #fbbf24); }
    .bg-blue { background: linear-gradient(135deg, #2563eb, #60a5fa); }
    .bg-green { background: linear-gradient(135deg, #059669, #34d399); }
    .bg-red { background: linear-gradient(135deg, #dc2626, #f87171); }
    .panel { margin-top: 16px; background: #fff; border: 1px solid #e5e7eb; border-radius: 12px; padding: 18px; }
    .table-responsive { margin-top: 24px; }
    table { width: 100%; border-collapse: collapse; font-size: 13px; }
    th, td { padding: 10px 14px; border-bottom: 1px solid #e5e7eb; }
    th { background: #f4f6f8; color: #5f6c79; font-weight: 600; }
    tr:last-child td { border-bottom: none; }
    .action-group { display: flex; gap: 6px; }
    .chip-btn { border: 0; background: #0f766e; color: #fff; border-radius: 999px; font-size: 12px; font-weight: 700; padding: 6px 14px; cursor: pointer; }
    .chip-btn:hover { background: #0d5f58; }
    .chart-shell { display: grid; grid-template-columns: 1.2fr 0.8fr; gap: 14px; align-items: stretch; }
    .chart-card { border: 1px solid #e5e7eb; border-radius: 12px; padding: 12px; background: #fff; }
    .chart-toolbar { display: flex; gap: 8px; align-items: center; justify-content: space-between; margin-bottom: 10px; flex-wrap: wrap; }
    .chart-type-btn { border: 1px solid #d1d5db; background: #f9fafb; color: #111827; border-radius: 999px; font-weight: 700; font-size: 12px; padding: 6px 12px; cursor: pointer; }
    .chart-type-btn.active { background: #0f766e; border-color: #0f766e; color: #fff; }
    .chart-box { height: 340px; position: relative; }
    .insight-box h6 { font-weight: 800; margin-bottom: 10px; }
    .kpi-row { display: grid; grid-template-columns: 1fr 1fr; gap: 10px; margin-bottom: 10px; }
    .kpi { border: 1px solid #e5e7eb; border-radius: 10px; padding: 10px 12px; background: #f9fafb; }
    .kpi .label { font-size: 12px; color: #6b7280; font-weight: 700; }
    .kpi .value { font-size: 18px; font-weight: 900; margin-top: 4px; }
    .kpi .sub { font-size: 12px; color: #6b7280; margin-top: 2px; }
    .insight-text { font-size: 13px; color: #374151; line-height: 1.5; }
    .chart-table { width: 100%; border-collapse: collapse; font-size: 13px; }
    .chart-table th, .chart-table td { padding: 10px 12px; border-bottom: 1px solid #e5e7eb; }
    .chart-table th { background: #f4f6f8; color: #5f6c79; font-weight: 800; }
    .muted { color: #6b7280; }
    @media (max-width: 980px) {
      .layout { grid-template-columns: 1fr; }
      .cards { grid-template-columns: repeat(2, minmax(0, 1fr)); }
      .table-responsive { overflow-x: auto; }
      table { min-width: 600px; }
      .chart-shell { grid-template-columns: 1fr; }
    }
  </style>
</head>
<body>
  <div class="layout">
    <aside class="sidebar">
      <div class="brand">ENRIQUE B. MAGALONA</div>
      <div class="nav-title">Navigation</div>
      
      <a class="nav-link active" href="/Analytics">Senior Citizen Table</a>
      
      <a class="nav-link" href="/admin-alert">Alerts</a>
      <a class="nav-link" href="/logout">Logout</a>
    </aside>

    <main class="main">
      <div class="top" style="justify-content: center; text-align: center; flex-direction: column;">
        <h1 class="h4 mb-0" style="width: 100%; text-align: center;">OSCA Admin Dashboard</h1>
        <div class="welcome" style="width: 100%; text-align: center;">Signed in as <?= htmlspecialchars((string) ($user['email'] ?? 'admin'), ENT_QUOTES, 'UTF-8') ?></div>
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
          <div class="label">Total Barangays</div>
          <div class="value"><?php echo $totalBarangays; ?></div>
        </div>
        <div class="card-metric bg-blue">
          <div class="label">Total Senior Citizen</div>
          <div class="value">
            <?php echo $totalSeniors; ?>
          </div>
        </div>
        <div class="card-metric bg-green">
          <div class="label">Average per Barangay</div>
          <div class="value"><?php echo $averagePerBarangay; ?></div>
        </div>
      </section>

      <section class="panel">
              <div style="display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; margin-bottom: 10px;">
                <div style="display: flex; align-items: center; gap: 10px;">
                  <a href="/Maps" class="btn btn-info" style="font-weight:600; border-radius:8px;">&#128506; Senior Map</a>
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
                            <div style="display: flex; gap: 8px;">
                              <button class="btn btn-primary btn-sm" type="button" onclick="viewChart('<?= htmlspecialchars($barangay, ENT_QUOTES, 'UTF-8') ?>')">View Chart</button>
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
      </section>
    </main>
  </div>

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
      <td>${Number(r.male || 0).toLocaleString()}</td>
      <td>${Number(r.female || 0).toLocaleString()}</td>
      <td><span class="badge badge-primary">${Number(r.total || 0).toLocaleString()}</span></td>
    </tr>
  `).join('') : '<tr><td colspan="4" class="text-center">No data available.</td></tr>';

  const totals = safeRows.reduce((acc, r) => {
    acc.total += Number(r.total || 0);
    acc.male += Number(r.male || 0);
    acc.female += Number(r.female || 0);
    return acc;
  }, { total: 0, male: 0, female: 0 });

  const displayScope = scopeName && scopeName.trim() !== '' ? scopeName : 'All Barangays';

  return `<!doctype html>
<html>
<head>
  <meta charset="utf-8">
  <title>${escapeHtml(reportTitle)} - ${escapeHtml(displayScope)}</title>
  <meta name="viewport" content="width=device-width,initial-scale=1">
  <link rel="stylesheet" href="/bower_components/bootstrap/css/bootstrap.min.css">
  <style>
    body { padding: 22px; font-family: Arial, sans-serif; color: #1f2937; }
    .print-button-container { text-align: center; margin-bottom: 14px; padding: 10px; background: #f8fafc; border-radius: 6px; }
    .print-button-container button { background: #0d6efd; color: #fff; padding: 8px 16px; border: none; border-radius: 6px; font-weight: 700; font-size: 13px; }
    .header-wrapper { position: relative; min-height: 120px; margin-bottom: 10px; }
    .logo-left { position: absolute; top: 0; left: 0; width: 88px; }
    .logo-right { position: absolute; top: 0; right: 0; width: 108px; }
    .main-header { text-align: center; padding-top: 6px; }
    .main-header h4, .main-header h2, .main-header p { margin: 0; }
    .title-section { text-align: center; margin: 12px 0 14px; }
    .title-section h5, .title-section h4 { margin: 0; }
    .title-section .as-of { margin-top: 8px; font-size: 13px; }
    .table th { background: #d1d5db; color: #111827; }
    .summary-box { margin-top: 10px; border-top: 1px solid #e5e7eb; padding-top: 10px; }
    .summary-box h5 { margin-bottom: 6px; }
    .summary-box p { margin: 2px 0; font-weight: 600; }
    @media print { .print-button-container { display: none; } body { padding: 0; } }
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
    </table>

    <div class="summary-box">
      <h5>Report Summary</h5>
      <p>Barangay: ${escapeHtml(displayScope)}</p>
      <p>Total Senior Citizens: ${totals.total.toLocaleString()}</p>
      <p>Total Male: ${totals.male.toLocaleString()}</p>
      <p>Total Female: ${totals.female.toLocaleString()}</p>
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
  let male = 0;
  let female = 0;
  seniors.forEach((s) => {
    const g = String(s.gender || '');
    if (/^male$/i.test(g)) male += 1;
    else if (/^female$/i.test(g)) female += 1;
  });
  const rows = [{ barangay, male, female, total: seniors.length }];
  await openPrintWindow(buildOscaReportHtml('SENIOR CITIZEN BARANGAY REPORT', barangay, rows));
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
    const rows = [{ barangay, male, female, total: seniors.length }];
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
  populateYearDropdown();
  toggleReportDateInputs();

  document.getElementById('searchInput').addEventListener('input', function(e) {
    const filter = String(e.target.value || '').toLowerCase();
    document.querySelectorAll('.table-responsive table tbody tr').forEach(function(row) {
      const barangay = (row.querySelector('td') ? row.querySelector('td').textContent : '').toLowerCase();
      row.style.display = barangay.indexOf(filter) !== -1 ? '' : 'none';
    });
  });

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

