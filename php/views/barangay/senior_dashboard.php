<!doctype html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width,initial-scale=1">
  <link rel="icon" type="image/png" href="<?= htmlspecialchars(asset_url('images/logo-ebmag.png'), ENT_QUOTES) ?>">
  <title>Barangay — Senior Citizen Analytics</title>
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/css/bootstrap.min.css">
  <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/3.9.1/chart.min.js"></script>
  <style>
    body { font-family: Open Sans, Segoe UI, Arial, sans-serif; margin: 0; background: #f3f6fb; color: #1f2937; }
    .layout { display: grid; grid-template-columns: 260px 1fr; min-height: 100vh; }
    .sidebar { background: #fff; border-right: 1px solid #e5e7eb; padding: 20px 14px; max-height: 100vh; overflow-y: auto; position: sticky; top: 0; height: 100vh; align-self: start; }
    .brand { font-weight: 800; font-size: 13px; letter-spacing: 0.4px; margin-bottom: 18px; }
    .nav-title { font-size: 12px; color: #6b7280; text-transform: uppercase; margin: 8px 10px; }
    .nav-link { display: block; padding: 10px 12px; margin-bottom: 6px; border-radius: 8px; color: #1f2937; text-decoration: none; }
    .nav-link.active { background: #0f766e; color: #fff; }
    .nav-link:hover { background: #edf2f7; }
    .main { padding: 20px; }
    .top-header { margin-bottom: 20px; }
    .stats { display: grid; grid-template-columns: repeat(3, minmax(0, 1fr)); gap: 14px; margin-bottom: 20px; }
    .stat-card { background: #fff; border: 1px solid #e5e7eb; border-radius: 12px; padding: 14px; text-align: center; }
    .stat-number { font-size: 24px; font-weight: 700; color: #0f766e; }
    .stat-label { font-size: 12px; color: #6b7280; margin-top: 6px; }
    .panel { background: #fff; border: 1px solid #e5e7eb; border-radius: 12px; padding: 16px; margin-bottom: 20px; }
    .panel-title { font-size: 14px; font-weight: 600; margin-bottom: 12px; }
    .search-box { margin-bottom: 12px; }
    .search-box input { padding: 8px 12px; border-radius: 6px; border: 1px solid #e5e7eb; }
    table { width: 100%; border-collapse: collapse; font-size: 13px; }
    table th { background: #f9fafb; padding: 10px; text-align: left; font-weight: 600; }
    table td { padding: 10px; border-bottom: 1px solid #e5e7eb; }
    table tr:hover { background: #f9fafb; }
    .modal { display: none; position: fixed; z-index: 1000; left: 0; top: 0; width: 100%; height: 100%; background-color: rgba(0,0,0,0.4); }
    .modal.show { display: block; }
    .modal-content { background-color: #fff; margin: 5% auto; padding: 20px; border: 1px solid #888; border-radius: 12px; width: 80%; max-width: 700px; }
    .close { color: #aaa; float: right; font-size: 28px; cursor: pointer; }
    .close:hover { color: #000; }
    button { padding: 8px 14px; border-radius: 6px; border: 1px solid #e5e7eb; background: #fff; cursor: pointer; }
    button.btn-primary { background: #0f766e; color: #fff; border: none; }
    button.btn-info { background: #2563eb; color: #fff; border: none; }
    @media (max-width: 980px) {
      .layout { grid-template-columns: 1fr; }
      .sidebar { position: static; height: auto; max-height: none; }
      .stats { grid-template-columns: repeat(2, minmax(0, 1fr)); }
    }
  </style>
</head>
<body>
  <div class="layout">
    <aside class="sidebar">
      <div class="brand">ENRIQUE B. MAGALONA</div>
      <div class="nav-title">Navigation</div>
      <a class="nav-link" href="/barangay">Person With Disability Analytics</a>
      <a class="nav-link active" href="/barangay-senior-dashboard">Senior Citizen Analytics</a>
      <a class="nav-link" href="/barangay-pwd">Person With Disability List</a>
      <a class="nav-link" href="/barangay-senior">Senior Citizens List</a>
      <a class="nav-link" href="/logout">Logout</a>
    </aside>

    <main class="main">
      <div class="top-header" style="display:flex;justify-content:space-between;align-items:flex-start;gap:12px;">
        <div>
          <h1 class="h4">Barangay Dashboard — Senior Citizen Analytics</h1>
          <?php if (!empty($assignedBarangayName)): ?>
          <p class="text-muted mb-0" style="font-size: 13px;"><strong>Barangay:</strong> <?= htmlspecialchars((string) $assignedBarangayName, ENT_QUOTES, 'UTF-8') ?></p>
          <?php endif; ?>
        </div>
        <div style="position:relative;">
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
      const rowsHtml = safeRows.length ? safeRows.map(r => `
        <tr>
          <td><strong>${escapeHtml(r.purok || r.name || 'Unknown')}</strong></td>
          <td>${Number(r.count || r.total || 0).toLocaleString()}</td>
        </tr>
      `).join('') : '<tr><td colspan="2" class="text-center">No data available.</td></tr>';

      const total = safeRows.reduce((acc, r) => acc + Number(r.count || r.total || 0), 0);

      return `<!doctype html>
<html>
<head>
  <meta charset="utf-8">
  <title>${escapeHtml(reportTitle)} - ${escapeHtml(scopeName)}</title>
  <meta name="viewport" content="width=device-width,initial-scale=1">
  <link rel="stylesheet" href="/bower_components/bootstrap/css/bootstrap.min.css">
  <style>
    @page { size: A4 portrait; margin: 10mm; }
    body { padding: 10px; font-family: Arial, sans-serif; color: #1f2937; background: #f3f4f6; }
    .container { max-width: 190mm; margin: 0 auto; background: #fff; padding: 10px 12px 12px; border: 1px solid #e5e7eb; }
    .print-button-container { text-align: center; margin-bottom: 14px; padding: 10px; background: #f8fafc; border-radius: 6px; }
    .print-button-container button { background: #0d6efd; color: #fff; padding: 8px 16px; border: none; border-radius: 6px; font-weight: 700; font-size: 13px; }
    .header-wrapper { position: relative; min-height: 82px; margin-bottom: 8px; }
    .logo-left { position: absolute; top: 0; left: 0; width: 60px; }
    .logo-right { position: absolute; top: 0; right: 0; width: 76px; }
    .main-header { text-align: center; padding-top: 2px; }
    .main-header h4, .main-header h2, .main-header p { margin: 0; }
    .main-header h4 { font-size: 14px; }
    .main-header h2 { font-size: 22px; }
    .main-header p { font-size: 13px; }
    .title-section { text-align: center; margin: 12px 0 14px; }
    .title-section h5, .title-section h4 { margin: 0; }
    .title-section h4 { font-size: 16px; }
    .title-section h5 { font-size: 14px; margin-top: 2px; }
    .title-section .as-of { margin-top: 8px; font-size: 13px; }
    .table th { background: #d1d5db; color: #111827; }
    .table { font-size: 13px; margin-bottom: 8px; }
    .table th, .table td { padding: 6px 8px !important; }
    .summary-box { margin-top: 10px; border-top: 1px solid #e5e7eb; padding-top: 10px; }
    .summary-box h5 { margin-bottom: 6px; }
    .summary-box p { margin: 2px 0; font-weight: 600; font-size: 13px; }
    @media print {
      .print-button-container { display: none; }
      body { padding: 0; background: #fff; }
      .container { width: 100% !important; max-width: none !important; border: none; padding: 0; }
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
      <h4><strong>${escapeHtml(scopeName)}</strong></h4>
      <div class="as-of">As of - <strong>${new Date().toLocaleDateString('en-PH', { year: 'numeric', month: 'long', day: 'numeric' })}</strong></div>
    </div>

    <table class="table table-bordered table-striped">
      <thead>
        <tr>
          <th>Purok</th>
          <th>Total Senior Citizens</th>
        </tr>
      </thead>
      <tbody>${rowsHtml}</tbody>
    </table>

    <div class="summary-box">
      <h5>Report Summary</h5>
      <p>Barangay: ${escapeHtml(scopeName)}</p>
      <p>Total Senior Citizens: ${total.toLocaleString()}</p>
      <p>Total Purok Covered: ${safeRows.length.toLocaleString()}</p>
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

        const res = await fetch(`/api/analytics/osca${query}`, { credentials: 'same-origin' });
        const json = await res.json();
        if (!res.ok || !json) { throw new Error((json && json.message) || 'Unable to load report data'); }

        let rows = [];
        if (Array.isArray(json.data)) {
          rows = json.data.map(function (p) {
            return {
              purok: p.name || p.purok || 'Unknown',
              count: Number(p.total || p.count || 0)
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
              + '<div style="display:flex;gap:6px;flex-wrap:wrap;">'
              + '<button class="btn btn-primary btn-sm" onclick="showChartModal(\'' + safePurok + '\')">View Chart</button>'
              + '<button class="btn btn-secondary btn-sm" onclick="openPurokPrint(\'' + safePurok + '\')">Print</button>'
              + '<button class="btn btn-info btn-sm" onclick="monthlyReport(\'' + safePurok + '\')">Monthly Report</button>'
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
          const res = await fetch('/api/senior-citizens-for-report', { credentials: 'same-origin' });
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
          const res = await fetch('/api/senior-citizens/purok/' + encodeURIComponent(String(purokName || '')), { credentials: 'same-origin' });
          const json = await res.json();
          if (!res.ok || !json || json.success !== true) {
            throw new Error((json && json.message) ? json.message : 'Unable to load print data');
          }
          const rows = Array.isArray(json.data) ? json.data : [];
          const total = rows.length;
          const male = rows.filter(function (r) { return String(r.gender || '').toLowerCase() === 'male'; }).length;
          const female = rows.filter(function (r) { return String(r.gender || '').toLowerCase() === 'female'; }).length;
          const rowHtml = rows.length
            ? rows.map(function (r, idx) {
                return '<tr>'
                  + '<td>' + (idx + 1) + '</td>'
                  + '<td>' + escapeHtml(r.fullName || 'Unnamed') + '</td>'
                  + '<td>' + escapeHtml(r.contact || 'N/A') + '</td>'
                  + '<td>' + escapeHtml(r.gender || 'N/A') + '</td>'
                  + '<td>' + escapeHtml(r.age || 'N/A') + '</td>'
                  + '</tr>';
              }).join('')
            : '<tr><td colspan="5" style="text-align:center;color:#6b7280;">No data available.</td></tr>';
          const html = '<!doctype html><html><head><meta charset="utf-8"><title>Senior Report</title><style>'
            + '@page{size:A4 portrait;margin:10mm;}'
            + 'body{font-family:Arial,sans-serif;margin:0;padding:10px;color:#111827;background:#f3f4f6;}'
            + '.print-wrap{width:190mm;max-width:190mm;margin:0 auto;background:#fff;padding:0 0 10px;border:1px solid #e5e7eb;}'
            + '.print-actions{text-align:center;padding:8px 0;border-bottom:1px solid #e5e7eb;background:#f9fafb;}'
            + '.print-btn{background:#0d6efd;color:#fff;border:none;border-radius:8px;padding:8px 14px;font-weight:700;cursor:pointer;}'
            + '.header-wrap{position:relative;padding:10px 14px 6px;min-height:82px;border-bottom:1px solid #e5e7eb;}'
            + '.logo-left{position:absolute;left:14px;top:12px;width:60px;height:60px;object-fit:contain;}'
            + '.logo-right{position:absolute;right:14px;top:12px;width:76px;height:60px;object-fit:contain;}'
            + '.header{text-align:center;padding-top:2px;}'
            + '.header .rp{font-size:14px;font-weight:700;margin:0;}'
            + '.header .city{font-size:22px;font-weight:800;letter-spacing:.4px;margin:2px 0;}'
            + '.header .office{font-size:13px;margin:0;}'
            + '.title{text-align:center;padding:8px 10px 4px;}'
            + '.title h3{margin:0;font-size:15px;font-weight:800;} .title h4{margin:2px 0;font-size:14px;font-weight:800;}'
            + '.title .scope{font-size:18px;font-weight:800;margin:4px 0 2px;} .title .asof{font-size:12px;color:#374151;}'
            + 'table{width:100%;border-collapse:collapse;font-size:12px;margin-top:8px;} th,td{border:1px solid #d1d5db;padding:6px 7px;text-align:left;} th{background:#d1d5db;}'
            + '.summary{margin:10px 10px 0;border-top:1px solid #d1d5db;padding-top:8px;font-size:12px;} .summary p{margin:3px 0;font-weight:600;}'
            + '@media print{.print-actions{display:none;} body{padding:0;background:#fff;} .print-wrap{width:100%;max-width:none;margin:0;border:none;}}</style></head><body>'
            + '<div class="print-wrap"><div class="print-actions"><button class="print-btn" onclick="window.print()">🖨️ Print Report</button></div>'
            + '<div class="header-wrap"><img src="/assets/images/SilayLogo.jpg" class="logo-left"><img src="/assets/images/BagongPilipinas.jpg" class="logo-right">'
            + '<div class="header"><p class="rp">Republic of the Philippines</p><p class="city">ENRIQUE B. MAGALONA</p><p class="office">Office Senior Citizens Affairs</p></div></div>'
            + '<div class="title"><h3>OFFICE OF SENIOR CITIZENS AFFAIRS</h3><h4>SENIOR PUROK REPORT</h4><div class="scope">' + escapeHtml(purokName || 'Unknown') + '</div><div class="asof">As of - ' + escapeHtml(new Date().toLocaleDateString('en-PH', { year: 'numeric', month: 'long', day: 'numeric' })) + '</div></div>'
            + '<table><thead><tr><th>#</th><th>Name</th><th>Contact</th><th>Gender</th><th>Age</th></tr></thead><tbody>' + rowHtml + '</tbody></table>'
            + '<div class="summary"><h3 style="margin:0 0 8px 0;font-size:14px;">Report Summary</h3>'
            + '<p>Total Count: ' + total + '</p><p>Total Male: ' + male + '</p><p>Total Female: ' + female + '</p></div>'
            + '</div></body></html>';
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
          const res = await fetch('/api/senior-citizens/purok/' + encodeURIComponent(String(purokName || '')) + '?month=' + encodeURIComponent(String(month)) + '&year=' + encodeURIComponent(String(year)), { credentials: 'same-origin' });
          const json = await res.json();
          if (!res.ok || !json || json.success !== true) {
            throw new Error((json && json.message) ? json.message : 'Unable to load monthly report data');
          }
          const rows = Array.isArray(json.data) ? json.data : [];
          const reportRows = [{ purok: purokName || 'Unknown', count: rows.length }];
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
          await fetch('/api/notifications/mark-read', {
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
          const res = await fetch('/api/notifications', { credentials: 'same-origin' });
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
      const ports = [null, '3000', '8080'];
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
  </script>
</body>
</html>

