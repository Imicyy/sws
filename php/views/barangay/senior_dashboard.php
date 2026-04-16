<!doctype html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width,initial-scale=1">
  <title>Barangay — Senior Citizen Analytics</title>
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/css/bootstrap.min.css">
  <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/3.9.1/chart.min.js"></script>
  <style>
    body { font-family: Open Sans, Segoe UI, Arial, sans-serif; margin: 0; background: #f3f6fb; color: #1f2937; }
    .layout { display: grid; grid-template-columns: 260px 1fr; min-height: 100vh; }
    .sidebar { background: #fff; border-right: 1px solid #e5e7eb; padding: 20px 14px; max-height: 100vh; overflow-y: auto; }
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
      <div class="top-header">
        <h1 class="h4">Barangay Dashboard — Senior Citizen Analytics</h1>
        <?php if (!empty($assignedBarangayName)): ?>
        <p class="text-muted mb-0" style="font-size: 13px;"><strong>Barangay:</strong> <?= htmlspecialchars((string) $assignedBarangayName, ENT_QUOTES, 'UTF-8') ?></p>
        <?php endif; ?>
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
      document.getElementById('yearInputContainer').style.display = type === 'annual' ? 'block' : 'none';
      document.getElementById('monthInputContainer').style.display = type === 'monthly' ? 'block' : 'none';
    }
    function generateReport() {
      alert('Report generation pending implementation');
      closeReportModal();
    }
    window.onclick = function(event) {
      const modal1 = document.getElementById('chartModal');
      const modal2 = document.getElementById('reportTypeModal');
      if (event.target === modal1) closeChartModal();
      if (event.target === modal2) closeReportModal();
    };
  </script>
</body>
</html>

