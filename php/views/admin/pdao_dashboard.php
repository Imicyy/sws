<?php
// php/views/admin/pdao_dashboard.php
// PDAO Admin Dashboard styled like analytics.php
?>
<!doctype html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width,initial-scale=1">
  <title>PDAO Admin Dashboard</title>
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
      <a class="nav-link active" href="/pdao-admin-dashboard">Person With Disability Table</a>
      <a class="nav-link" href="/admin-alert">Alerts</a>
      <a class="nav-link" href="/logout">Logout</a>
    </aside>
    <main class="main">
      <div class="top" style="justify-content: center; text-align: center; flex-direction: column;">
        <h1 class="h4 mb-0" style="width: 100%; text-align: center;">PDAO Admin Dashboard</h1>
        <div class="welcome" style="width: 100%; text-align: center;">Signed in as <?= htmlspecialchars((string) ($user['email'] ?? 'pdao_admin'), ENT_QUOTES, 'UTF-8') ?></div>
      </div>
      <section class="cards">
        <div class="card-metric bg-yellow">
          <div class="label">Total Barangays</div>
          <div class="value">
            <?php
              echo isset($barangayData) && is_array($barangayData) ? count($barangayData) : 0;
            ?>
          </div>
        </div>
        <div class="card-metric bg-blue">
          <div class="label">Total PWDs</div>
          <div class="value">
            <?php
              echo isset($totalPwds) ? $totalPwds : 0;
            ?>
          </div>
        </div>
        <div class="card-metric bg-green">
          <div class="label">Average per Barangay</div>
          <div class="value">
            <?php
              $totalBarangays = isset($barangayData) && is_array($barangayData) ? count($barangayData) : 0;
              $totalPWDs = isset($totalPwds) ? $totalPwds : 0;
              echo $totalBarangays > 0 ? round($totalPWDs / $totalBarangays, 1) : 0;
            ?>
          </div>
        </div>
      </section>
      <section class="panel">
        <div style="display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; margin-bottom: 10px;">
          <div style="display: flex; align-items: center; gap: 10px;">
            <a href="/Map" class="btn btn-info" style="font-weight:600; border-radius:8px;">&#128506; PWD Map</a>
            <h2 class="h6 mb-0" style="margin-bottom:0;">Barangay PWD Table</h2>
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
                <th>PWD Count</th>
                <th>Action</th>
              </tr>
            </thead>
            <tbody>
              <?php if (!empty($barangayData)): ?>
                <?php foreach ($barangayData as $data): ?>
                  <tr>
                    <td><?= htmlspecialchars($data['name'], ENT_QUOTES, 'UTF-8') ?></td>
                    <td><?= (int)$data['pwdCount'] ?></td>
                    <td>
                      <div style="display: flex; gap: 8px;">
                        <button class="btn btn-primary btn-sm" type="button" onclick="viewChart('<?= htmlspecialchars($data['name'], ENT_QUOTES, 'UTF-8') ?>')">View Chart</button>
                        <button class="btn btn-secondary btn-sm" type="button" onclick="window.print()">Print</button>
                        <button class="btn btn-info btn-sm" type="button" onclick="monthlyReport('<?= htmlspecialchars($data['name'], ENT_QUOTES, 'UTF-8') ?>')">Monthly Report</button>
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

  <!-- The Modal -->
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
            <select class="form-control" id="reportYear">
              <!-- Years will be populated by JavaScript -->
            </select>
          </div>
          <div class="form-group" id="reportMonthGroup" style="display: none;">
            <label for="reportMonth">Month</label>
            <select class="form-control" id="reportMonth">
              <option value="1">January</option>
              <option value="2">February</option>
              <option value="3">March</option>
              <option value="4">April</option>
              <option value="5">May</option>
              <option value="6">June</option>
              <option value="7">July</option>
              <option value="8">August</option>
              <option value="9">September</option>
              <option value="10">October</option>
              <option value="11">November</option>
              <option value="12">December</option>
            </select>
          </div>
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
          <h5 class="modal-title" id="chartModalLabel">PWD Analytics</h5>
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

              <div id="chartContainer" class="chart-box">
                <canvas id="barangayChart"></canvas>
              </div>

              <div id="tableContainer" style="display:none;">
                <table class="chart-table">
                  <thead>
                    <tr>
                      <th>Category</th>
                      <th>Count</th>
                      <th>Percentage</th>
                    </tr>
                  </thead>
                  <tbody id="chartTableBody">
                    <tr><td colspan="3" class="muted">No data loaded.</td></tr>
                  </tbody>
                </table>
              </div>
            </div>

            <div class="chart-card insight-box">
              <h6>Insights</h6>
              <div class="kpi-row">
                <div class="kpi">
                  <div class="label">Total PWD</div>
                  <div class="value" id="kpiTotalPwd">0</div>
                  <div class="sub" id="kpiTotalPwdNote">Active records in barangay</div>
                </div>
                <div class="kpi">
                  <div class="label">Top Disability</div>
                  <div class="value" id="kpiTopDisability">—</div>
                  <div class="sub" id="kpiTopDisabilityMeta">0 (0.0%)</div>
                </div>
              </div>
              <div class="kpi" style="margin-bottom:10px;">
                <div class="label">Disability Records</div>
                <div class="value" id="kpiDisabilityRecords">0</div>
                <div class="sub" id="kpiDisabilityRecordsNote">Counts per disability type (multi-disability included)</div>
              </div>
              <div class="insight-text" id="chartInsightText">
                Select “View Chart” to see donut graph, table graph, and insights.
              </div>
              <div class="muted" style="font-size:12px; margin-top:10px;">
                <strong>Top disabilities</strong>
                <div id="topDisabilitiesList">—</div>
              </div>
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
  const months = ['January', 'February', 'March', 'April', 'May', 'June', 'July',
    'August', 'September', 'October', 'November', 'December'];
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
  const reportType = document.getElementById('reportTypeSelect').value;
  const monthGroup = document.getElementById('reportMonthGroup');
  monthGroup.style.display = reportType === 'monthly' ? '' : 'none';
};

async function openPrintWindow(html) {
  const newWin = window.open('', '_blank', 'width=1200,height=800,scrollbars=yes');
  if (!newWin) {
    alert('Popup blocked! Please allow popups for this site.');
    return;
  }
  newWin.document.open();
  newWin.document.write(html);
  newWin.document.close();
}

function escReport(value) {
  return String(value === null || value === undefined ? '' : value)
    .replace(/&/g, '&amp;')
    .replace(/</g, '&lt;')
    .replace(/>/g, '&gt;');
}

function buildPwdDisabilityReportTableHtml(scopeName, pwds, analyticsGroupLabel) {
  const mapping = {
    'Hard of Hearing/Deaf': 'Deaf or Hard of Hearing',
    'Visual/Blind': 'Visual Disability',
    'Speech/Language Impairment': 'Speech and Language Impairment',
    'Learning Disability': 'Learning Disability',
    'Mental/Intellectual': 'Intellectual Disability',
    'Physical Disability': 'Physical Disability (Orthopedic)',
    'Psychosocial Disability': 'Psychosocial Disability',
    'Cancer': 'Cancer (RA11215)',
    'Rare Disease': 'Rare Disease (RA10747)',
    'Multiple Disability': 'Multiple Disability',
    'Other': 'Other'
  };

  const stats = {};
  const uniqueIds = new Set();

  (pwds || []).forEach(p => {
    if (p && (p._id || p.id)) uniqueIds.add(p._id || p.id);
    const age = (typeof p.age === 'number') ? p.age : (p.age ? parseInt(p.age, 10) : null);
    const gender = (p.gender || 'Unknown').toString();

    const disabilityField = p.disability;
    const disabilities = Array.isArray(disabilityField)
      ? disabilityField
      : (disabilityField ? String(disabilityField).split(',').map(s => s.trim()).filter(Boolean) : []);

    disabilities.forEach(d => {
      const key = mapping[d] || d || 'Other';
      if (!stats[key]) {
        stats[key] = { count: 0, male: 0, female: 0, otherGender: 0, ages: [] };
      }
      stats[key].count += 1;
      if (age !== null && !isNaN(age)) stats[key].ages.push(age);
      if (/^male$/i.test(gender)) stats[key].male += 1;
      else if (/^female$/i.test(gender)) stats[key].female += 1;
      else stats[key].otherGender += 1;
    });
  });

  const preferredOrder = [
    'Deaf or Hard of Hearing', 'Intellectual Disability', 'Learning Disability',
    'Mental Disability', 'Physical Disability (Orthopedic)', 'Psychosocial Disability',
    'Speech and Language Impairment', 'Visual Disability', 'Cancer (RA11215)',
    'Rare Disease (RA10747)', 'Multiple Disability', 'Other'
  ];

  const keys = Array.from(new Set([...preferredOrder, ...Object.keys(stats)]));

  let html = `
    <div class="table-responsive">
      <table class="table table-striped table-bordered table-hover">
        <thead class="table-dark">
          <tr>
            <th>Disability Type</th>
            <th>Age Range</th>
            <th>Total</th>
            <th>Male</th>
            <th>Female</th>
            <th>Other/Unknown</th>
          </tr>
        </thead>
        <tbody>`;

  keys.forEach(k => {
    if (!stats[k]) return;
    const s = stats[k];
    const minAge = s.ages.length ? Math.min(...s.ages) : 'N/A';
    const maxAge = s.ages.length ? Math.max(...s.ages) : 'N/A';
    const ageRange = s.ages.length ? `${minAge} - ${maxAge}` : 'N/A';

    html += `
      <tr>
        <td><strong>${escReport(k)}</strong></td>
        <td>${escReport(ageRange)}</td>
        <td><span class="badge bg-primary">${s.count}</span></td>
        <td>${s.male}</td>
        <td>${s.female}</td>
        <td>${s.otherGender}</td>
      </tr>`;
  });

  html += '</tbody></table></div>';

  let totalMale = 0, totalFemale = 0, totalOther = 0, totalCount = 0;
  Object.values(stats).forEach(s => {
    totalMale += s.male;
    totalFemale += s.female;
    totalOther += s.otherGender;
    totalCount += s.count;
  });

  const allAges = [].concat(...Object.values(stats).map(s => s.ages));
  const overallAgeRange = allAges.length ? `${Math.min(...allAges)} - ${Math.max(...allAges)}` : 'N/A';

  html += `
    <div class="summary" style="margin-top: 10px;">
      <h5>Report Summary</h5>
      <div class="row">
        <div class="col-md-6">
          <ul class="list-unstyled">
            <li><strong>${escReport(analyticsGroupLabel)}:</strong> ${escReport(scopeName)}</li>
            <li><strong>Unique PWDs:</strong> ${uniqueIds.size}</li>
            <li><strong>Total Disability Records:</strong> ${totalCount}</li>
            <li><strong>Overall Age Range:</strong> ${escReport(overallAgeRange)}</li>
          </ul>
        </div>
        <div class="col-md-6">
          <ul class="list-unstyled">
            <li><strong>Gender Distribution:</strong></li>
            <li>&nbsp;&nbsp;Male: ${totalMale}</li>
            <li>&nbsp;&nbsp;Female: ${totalFemale}</li>
            <li>&nbsp;&nbsp;Other/Unknown: ${totalOther}</li>
          </ul>
        </div>
      </div>
    </div>`;

  return html;
}

function buildPwdReportDocumentHtml(scopeName, reportTitle, tableHtml) {
  const now = new Date();
  return `<!doctype html>
<html>
<head>
  <meta charset="utf-8">
  <title>PWD Disability Report - ${escReport(scopeName)}</title>
  <meta name="viewport" content="width=device-width,initial-scale=1">
  <link rel="stylesheet" href="/bower_components/bootstrap/css/bootstrap.min.css">
  <style>
    body { padding: 30px; font-family: Arial, sans-serif; }
    .header-wrapper { position: relative; margin-bottom: 20px; min-height: 130px; }
    .logo-left { position: absolute; top: 0; left: 0; width: 95px; }
    .logo-right { position: absolute; top: 0; right: 0; width: 120px; }
    .main-header { text-align: center; margin-top: 15px; }
    .title-section { margin-top: 15px; text-align: center; }
    .report-info {
      margin: 20px auto; text-align: center; font-size: 13px; line-height: 1.8;
      max-width: 800px; white-space: nowrap;
    }
    .info-item { display: inline-block; margin: 0 15px; }
    .underline { display: inline-block; border-bottom: 1px solid #000; width: 120px; height: 14px; vertical-align: bottom; margin-left: 5px; }
    .address-underline { width: 150px; }
    .print-button-container { text-align: center; margin: 20px 0; padding: 15px; background-color: #f8f9fa; border-radius: 5px; }
    .print-button-container button { background-color: #007bff; color: white; padding: 10px 20px; border: none; border-radius: 5px; cursor: pointer; font-size: 14px; font-weight: bold; }
    .print-button-container button:hover { background-color: #0056b3; }
    @media print { .print-button-container { display: none; } body { padding: 0; } }
  </style>
</head>
<body>
  <div class="print-button-container">
    <button onclick="window.print()">🖨️ Print Report</button>
  </div>

  <div class="container">
    <div class="header-wrapper">
      <img src="/assets/images/SilayLogo.jpg" class="logo-left">
      <img src="/assets/images/BagongPilipinas.jpg" class="logo-right">
      <div class="main-header">
        <h4>Republic of the Philippines</h4>
        <h2><strong>ENRIQUE B. MAGALONA</strong></h2>
        <p>Persons with Disability Affairs Office</p>
      </div>
    </div>

    <div class="title-section">
      <h5>PERSONS WITH DISABILITY AFFAIRS OFFICE</h5>
      <h5>${escReport(reportTitle)}</h5>
      <h5><strong>${escReport(scopeName)}</strong></h5>
      <small class="text-center">
        As of - <p><strong>${now.toLocaleDateString('en-PH', { year: 'numeric', month: 'long', day: 'numeric' })}</strong></p>
      </small>
    </div>

    <div class="report-info">
      <span class="info-item">Region: <span class="underline"></span></span>
      <span class="info-item">Persons with Disability Statistics: <span class="underline"></span></span>
      <span class="info-item">Address: <span class="underline address-underline"></span></span>
    </div>

    ${tableHtml}
  </div>
</body>
</html>`;
}

function buildSimpleRows(pwds) {
  if (!Array.isArray(pwds) || pwds.length === 0) {
    return '<tr><td colspan="6" class="text-center">No records found.</td></tr>';
  }
  return pwds.map((pwd, idx) => {
    const fullName = pwd.fullName
      || [pwd.last_name, pwd.first_name, pwd.middle_name].filter(Boolean).join(' ')
      || 'Unnamed';
    const disability = Array.isArray(pwd.disability) ? pwd.disability.join(', ') : (pwd.disability || 'N/A');
    return `<tr>
      <td>${idx + 1}</td>
      <td>${escapeHtml(fullName)}</td>
      <td>${escapeHtml(pwd.gender || 'N/A')}</td>
      <td>${escapeHtml(pwd.age || 'N/A')}</td>
      <td>${escapeHtml(pwd.contact || 'N/A')}</td>
      <td>${escapeHtml(disability)}</td>
    </tr>`;
  }).join('');
}

function buildReportHtml(title, subtitle, rowsHtml) {
  return `<!doctype html>
<html>
<head>
  <meta charset="utf-8">
  <title>${escapeHtml(title)}</title>
  <meta name="viewport" content="width=device-width,initial-scale=1">
  <link rel="stylesheet" href="/bower_components/bootstrap/css/bootstrap.min.css">
  <style>
    body { font-family: Arial, sans-serif; padding: 24px; }
    .header { text-align: center; margin-bottom: 18px; }
    .print-actions { text-align: right; margin-bottom: 12px; }
    @media print { .print-actions { display: none; } body { padding: 0; } }
  </style>
</head>
<body>
  <div class="print-actions"><button class="btn btn-primary btn-sm" onclick="window.print()">Print</button></div>
  <div class="header">
    <h4>Republic of the Philippines</h4>
    <h3>ENRIQUE B. MAGALONA</h3>
    <h5>Persons with Disability Affairs Office</h5>
    <h5>${escapeHtml(title)}</h5>
    <div>${escapeHtml(subtitle)}</div>
  </div>
  <table class="table table-bordered table-striped">
    <thead class="thead-dark">
      <tr><th>#</th><th>Name</th><th>Gender</th><th>Age</th><th>Contact</th><th>Disability</th></tr>
    </thead>
    <tbody>${rowsHtml}</tbody>
  </table>
</body>
</html>`;
}

window.viewChart = async function(barangay) {
  try {
    const response = await fetch('/api/analytics/pdao', { credentials: 'same-origin' });
    const json = await response.json();
    const list = (json && Array.isArray(json.data)) ? json.data : [];
    const row = list.find((item) => item.name === barangay);
    if (!row) {
      alert('No data found for ' + barangay);
      return;
    }

    const male = parseInt(row.male || 0, 10);
    const female = parseInt(row.female || 0, 10);
    const total = parseInt(row.total || (male + female), 10);
    const malePct = total ? (male / total) * 100 : 0;
    const femalePct = total ? (female / total) * 100 : 0;

    // Pull disability distribution for insights
    const disRes = await fetch(`/api/pwds/barangay/${encodeURIComponent(barangay)}`, { credentials: 'same-origin' });
    const disJson = await disRes.json();
    const pwds = (disJson && disJson.success && Array.isArray(disJson.data)) ? disJson.data : [];

    const disabilityCounts = {};
    let disabilityRecords = 0;
    pwds.forEach(p => {
      const raw = (p && p.disability) ? String(p.disability) : '';
      const parts = raw.split(',').map(s => s.trim()).filter(Boolean);
      if (parts.length === 0) {
        return;
      }
      parts.forEach(d => {
        disabilityRecords += 1;
        disabilityCounts[d] = (disabilityCounts[d] || 0) + 1;
      });
    });

    const disabilityList = Object.entries(disabilityCounts)
      .sort((a, b) => b[1] - a[1])
      .map(([name, count]) => ({ name, count, pct: disabilityRecords ? (count / disabilityRecords) * 100 : 0 }));

    const top = disabilityList[0] || null;

    currentChartPayload = {
      barangay,
      male,
      female,
      total,
      malePct,
      femalePct,
      disabilityRecords,
      disabilityTop: top,
      disabilityTopList: disabilityList.slice(0, 5)
    };

    document.getElementById('chartModalLabel').textContent = 'PWD Analytics';
    document.getElementById('chartScopeTitle').textContent = barangay + ' - PDAO';
    document.getElementById('chartScopeSubtitle').textContent = 'Gender distribution (Male/Female)';
    $('#chartModal').modal('show');

    renderInsights(currentChartPayload);
    renderChartOrTable();
  } catch (error) {
    alert('Error loading chart. Please try again.');
  }
};

function renderInsights(payload) {
  if (!payload) return;

  const kTotal = document.getElementById('kpiTotalPwd');
  const kTop = document.getElementById('kpiTopDisability');
  const kTopMeta = document.getElementById('kpiTopDisabilityMeta');
  const kDisRec = document.getElementById('kpiDisabilityRecords');
  const topListEl = document.getElementById('topDisabilitiesList');

  if (kTotal) kTotal.textContent = Number(payload.total || 0).toLocaleString();
  if (kDisRec) kDisRec.textContent = Number(payload.disabilityRecords || 0).toLocaleString();

  if (payload.disabilityTop) {
    if (kTop) kTop.textContent = payload.disabilityTop.name;
    if (kTopMeta) kTopMeta.textContent = `${payload.disabilityTop.count.toLocaleString()} (${payload.disabilityTop.pct.toFixed(1)}%)`;
  } else {
    if (kTop) kTop.textContent = '—';
    if (kTopMeta) kTopMeta.textContent = '0 (0.0%)';
  }

  if (topListEl) {
    if (Array.isArray(payload.disabilityTopList) && payload.disabilityTopList.length) {
      topListEl.innerHTML = payload.disabilityTopList
        .map(d => `${escapeHtml(d.name)} — <strong>${d.count.toLocaleString()}</strong> (${d.pct.toFixed(1)}%)`)
        .join('<br>');
    } else {
      topListEl.textContent = '—';
    }
  }

  let insight = `In ${payload.barangay}, there are <strong>${Number(payload.total || 0).toLocaleString()}</strong> active PWD records. `;
  if (!payload.total) {
    insight = `No active PWD records found for ${payload.barangay}.`;
  } else if (!payload.disabilityTop) {
    insight += `No disability entries were found in the records (or they are marked as N/A).`;
  } else {
    insight += `The most common recorded disability is <strong>${escapeHtml(payload.disabilityTop.name)}</strong> at <strong>${payload.disabilityTop.pct.toFixed(1)}%</strong> of disability records (${payload.disabilityTop.count.toLocaleString()} occurrences).`;
  }
  document.getElementById('chartInsightText').innerHTML = insight;
}

function setChartType(type) {
  currentChartView = type;
  document.getElementById('chartTypeDonutBtn').classList.toggle('active', type === 'donut');
  document.getElementById('chartTypeTableBtn').classList.toggle('active', type === 'table');
  renderChartOrTable();
}

function renderChartOrTable() {
  const payload = currentChartPayload;
  const chartContainer = document.getElementById('chartContainer');
  const tableContainer = document.getElementById('tableContainer');

  if (!payload) return;

  if (currentChartView === 'table') {
    chartContainer.style.display = 'none';
    tableContainer.style.display = '';
    renderChartTable(payload);
    if (currentChart) {
      currentChart.destroy();
      currentChart = null;
    }
    return;
  }

  chartContainer.style.display = '';
  tableContainer.style.display = 'none';
  renderDonut(payload);
}

function renderChartTable(payload) {
  const tbody = document.getElementById('chartTableBody');
  const rows = [
    { label: 'Male', count: payload.male, pct: payload.malePct },
    { label: 'Female', count: payload.female, pct: payload.femalePct }
  ];
  tbody.innerHTML = rows.map(r => `
    <tr>
      <td><strong>${escapeHtml(r.label)}</strong></td>
      <td>${Number(r.count).toLocaleString()}</td>
      <td>${Number(r.pct).toFixed(1)}%</td>
    </tr>
  `).join('');
}

function renderDonut(payload) {
  const ctx = document.getElementById('barangayChart').getContext('2d');
  if (currentChart) currentChart.destroy();
  currentChart = new Chart(ctx, {
    type: 'doughnut',
    data: {
      labels: ['Male', 'Female'],
      datasets: [{
        data: [payload.male, payload.female],
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
              const pct = payload.total ? ((value / payload.total) * 100) : 0;
              return `${label}: ${value.toLocaleString()} (${pct.toFixed(1)}%)`;
            }
          }
        }
      }
    }
  });
}

window.monthlyReport = async function(barangay) {
  try {
    const now = new Date();
    const month = now.getMonth() + 1;
    const year = now.getFullYear();
    const response = await fetch(`/api/pwds/barangay/${encodeURIComponent(barangay)}?month=${month}&year=${year}`, { credentials: 'same-origin' });
    const json = await response.json();
    if (!response.ok || !json.success) throw new Error((json && json.message) || 'Unable to load report data');
    const tableHtml = buildPwdDisabilityReportTableHtml(barangay, json.data || [], 'Barangay');
    const docHtml = buildPwdReportDocumentHtml(barangay, 'MONTHLY ACCOMPLISHMENT REPORT', tableHtml);
    await openPrintWindow(docHtml);
  } catch (error) {
    alert(error.message || 'Error generating monthly report.');
  }
};

async function openBarangayPrint(barangay) {
  try {
    const response = await fetch(`/api/pwds/barangay/${encodeURIComponent(barangay)}`, { credentials: 'same-origin' });
    const json = await response.json();
    if (!response.ok || !json.success) throw new Error((json && json.message) || 'Unable to load print data');
    const html = buildReportHtml('PWD BARANGAY REPORT', barangay, buildSimpleRows(json.data || []));
    await openPrintWindow(html);
  } catch (error) {
    alert(error.message || 'Error generating print view.');
  }
}

async function generateReportWithSelection() {
  const reportType = document.getElementById('reportTypeSelect').value;
  const year = parseInt(document.getElementById('reportYear').value, 10);
  const month = parseInt(document.getElementById('reportMonth').value, 10);

  let query = `?year=${encodeURIComponent(String(year))}`;
  let reportTitle = `ANNUAL ACCOMPLISHMENT REPORT`;
  let scopeName = 'ALL BARANGAYS';
  if (reportType === 'monthly') {
    query = `?month=${encodeURIComponent(String(month))}&year=${encodeURIComponent(String(year))}`;
    reportTitle = `MONTHLY ACCOMPLISHMENT REPORT`;
  }

  const response = await fetch(`/api/pwds${query}`, { credentials: 'same-origin' });
  const json = await response.json();
  if (!response.ok || !json.success) {
    throw new Error((json && (json.error || json.message)) || 'Unable to load report data');
  }
  // Use the disability summary table like the screenshot (municipality-wide)
  const tableHtml = buildPwdDisabilityReportTableHtml(scopeName, json.pwds || [], 'Scope');
  const docHtml = buildPwdReportDocumentHtml(scopeName, reportTitle, tableHtml);
  await openPrintWindow(docHtml);
}

document.addEventListener('DOMContentLoaded', function() {
  populateYearDropdown();
  toggleReportDateInputs();

  document.getElementById('searchInput').addEventListener('input', function(event) {
    const filter = String(event.target.value || '').toLowerCase();
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
    } catch (error) {
      alert(error.message || 'Error generating report.');
    }
  });

  document.getElementById('chartTypeDonutBtn').addEventListener('click', function() {
    setChartType('donut');
  });
  document.getElementById('chartTypeTableBtn').addEventListener('click', function() {
    setChartType('table');
  });

  $('#chartModal').on('hidden.bs.modal', function() {
    currentChartPayload = null;
    if (currentChart) {
      currentChart.destroy();
      currentChart = null;
    }
    currentChartView = 'donut';
    document.getElementById('chartTypeDonutBtn').classList.add('active');
    document.getElementById('chartTypeTableBtn').classList.remove('active');
    document.getElementById('chartInsightText').textContent = 'Select “View Chart” to see donut graph, table graph, and insights.';
    document.getElementById('chartTableBody').innerHTML = '<tr><td colspan="3" class="muted">No data loaded.</td></tr>';
    const topListEl = document.getElementById('topDisabilitiesList');
    if (topListEl) topListEl.textContent = '—';
    const kTotal = document.getElementById('kpiTotalPwd');
    if (kTotal) kTotal.textContent = '0';
    const kTop = document.getElementById('kpiTopDisability');
    if (kTop) kTop.textContent = '—';
    const kTopMeta = document.getElementById('kpiTopDisabilityMeta');
    if (kTopMeta) kTopMeta.textContent = '0 (0.0%)';
    const kDis = document.getElementById('kpiDisabilityRecords');
    if (kDis) kDis.textContent = '0';
  });

  document.querySelectorAll('button[onclick^="window.print"]').forEach(function(button) {
    const row = button.closest('tr');
    const barangayCell = row ? row.querySelector('td') : null;
    const barangay = barangayCell ? barangayCell.textContent.trim() : '';
    if (!barangay) return;
    button.removeAttribute('onclick');
    button.addEventListener('click', function() {
      openBarangayPrint(barangay);
    });
  });
});
</script>
