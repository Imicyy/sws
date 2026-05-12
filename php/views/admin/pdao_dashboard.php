<?php
// php/views/admin/pdao_dashboard.php
// PDAO Admin Dashboard styled like analytics.php
?>
<!doctype html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width,initial-scale=1">
  <link rel="icon" type="image/jpeg" href="<?= htmlspecialchars(asset_url('images/SilayLogo.jpg'), ENT_QUOTES) ?>">
  <title>PDAO Admin Dashboard</title>
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
    .user-profile { 
      padding: 16px; 
      margin-bottom: 20px; 
      background: rgba(255, 255, 255, 0.1); 
      border-radius: 14px; 
      display: flex; 
      flex-direction: column; 
      align-items: center;
      text-align: center;
      gap: 6px; 
      border: 1px solid rgba(255, 255, 255, 0.1); 
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
    
    .nav-title { 
      font-size: 13px; 
      color: #94a3b8 !important; 
      text-transform: uppercase; 
      margin: 12px 10px 8px; 
      font-weight: 700; 
      letter-spacing: 0.5px; 
    }
    .sidebar .nav-link { 
      display: flex !important; 
      align-items: center !important; 
      justify-content: flex-start !important; 
      text-align: left !important; 
      min-height: 48px !important; 
      padding: 14px 18px; 
      margin-bottom: 8px; 
      border-radius: 12px; 
      background: transparent !important; 
      color: #e2e8f0 !important; 
      text-decoration: none !important; 
      font-weight: 600; 
      font-size: 18px !important; 
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

    .user-profile { 
      padding: 16px; 
      margin-bottom: 20px; 
      background: rgba(255, 255, 255, 0.1); 
      border-radius: 14px; 
      display: flex; 
      flex-direction: column; 
      gap: 4px; 
      border: 1px solid rgba(255, 255, 255, 0.1); 
    }
    .user-name { font-size: 14px; font-weight: 700; color: #fff; display: flex; align-items: center; gap: 8px; }
    .user-email { font-size: 11px; color: #94a3b8; word-break: break-all; font-weight: 500; }
    
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

    .panel { margin: 16px 0 0; width: 100%; background: linear-gradient(180deg, #ffffff 0%, #fffcf3 100%); border: 1px solid var(--panel-border); border-radius: 14px; padding: 22px; box-shadow: 0 14px 26px rgba(59, 130, 246, 0.08); }
    .table-responsive { margin-top: 24px; }
    
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
  </style>
</head>
<body>
    <aside class="sidebar">
      <div class="brand">
        <img src="<?= htmlspecialchars(asset_url('images/SilayLogo.png'), ENT_QUOTES) ?>" alt="Logo" style="width: 30px; height: 30px;">
        <span>Enrique B. Magalona</span>
      </div>

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

      <div class="nav-title">NAVIGATION</div>
      <a class="nav-link active" href="/pdao-admin-dashboard">
        <i class="fas fa-wheelchair"></i>
        <span>Person With Disability</span>
      </a>
      <a class="nav-link" href="/admin-alert?from=pdao">
        <i class="fas fa-bell"></i>
        <span>System Alerts</span>
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
          <h1>PDAO Admin Dashboard</h1>
        </div>
      </div>
      <section class="cards">
        <div class="card-metric bg-yellow">
          <div class="card-icon">
            <i class="fas fa-map-marked-alt"></i>
          </div>
          <div class="card-info">
            <div class="label">Total Barangays</div>
            <div class="value">
              <?php echo isset($barangayData) && is_array($barangayData) ? count($barangayData) : 0; ?>
            </div>
          </div>
        </div>
        <div class="card-metric bg-blue">
          <div class="card-icon">
            <i class="fas fa-wheelchair"></i>
          </div>
          <div class="card-info">
            <div class="label">Total PWDs</div>
            <div class="value">
              <?php echo isset($totalPwds) ? $totalPwds : 0; ?>
            </div>
          </div>
        </div>
        <div class="card-metric bg-green">
          <div class="card-icon">
            <i class="fas fa-chart-line"></i>
          </div>
          <div class="card-info">
            <div class="label">Avg per Barangay</div>
            <div class="value">
              <?php
                $totalBarangays = isset($barangayData) && is_array($barangayData) ? count($barangayData) : 0;
                $totalPWDs = isset($totalPwds) ? $totalPwds : 0;
                echo $totalBarangays > 0 ? round($totalPWDs / $totalBarangays, 1) : 0;
              ?>
            </div>
          </div>
        </div>
      </section>
      <section class="panel">
        <div style="display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; margin-bottom: 10px;">
          <div style="display: flex; align-items: center; gap: 10px;">
            <a href="/Map" target="_blank" rel="noopener noreferrer" class="btn btn-info" style="font-weight:600; border-radius:8px;">&#128506; PWD Map</a>
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
                      <div style="display: flex; gap: 8px; justify-content: center;">
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
        <div class="pagination-container">
          <div class="page-info" id="paginationInfo">Showing 0 to 0 of 0 entries</div>
          <div class="page-btns" id="paginationBtns"></div>
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
            <th>No.</th>
            <th>Disability Type</th>
            <th>Age Range</th>
            <th>Total</th>
            <th>Percent</th>
            <th>Male</th>
            <th>Female</th>
            <th>Other/Unknown</th>
          </tr>
        </thead>
        <tbody>`;

  const grandTotal = Object.values(stats).reduce((sum, s) => sum + (s.count || 0), 0);
  let rowIndex = 1;
  keys.forEach(k => {
    if (!stats[k]) return;
    const s = stats[k];
    const minAge = s.ages.length ? Math.min(...s.ages) : 'N/A';
    const maxAge = s.ages.length ? Math.max(...s.ages) : 'N/A';
    const ageRange = s.ages.length ? `${minAge} - ${maxAge}` : 'N/A';
    const percent = grandTotal > 0 ? ((s.count / grandTotal) * 100).toFixed(1) : '0.0';

    html += `
      <tr>
        <td>${rowIndex++}</td>
        <td><strong>${escReport(k)}</strong></td>
        <td>${escReport(ageRange)}</td>
        <td><span class="badge bg-primary">${s.count}</span></td>
        <td>${percent}%</td>
        <td>${s.male}</td>
        <td>${s.female}</td>
        <td>${s.otherGender}</td>
      </tr>`;
  });

  let totalMale = 0, totalFemale = 0, totalOther = 0, totalCount = 0;
  Object.values(stats).forEach(s => {
    totalMale += s.male;
    totalFemale += s.female;
    totalOther += s.otherGender;
    totalCount += s.count;
  });

  html += `
      <tr>
        <td></td>
        <td><strong>TOTAL</strong></td>
        <td></td>
        <td><strong>${totalCount}</strong></td>
        <td><strong>100%</strong></td>
        <td><strong>${totalMale}</strong></td>
        <td><strong>${totalFemale}</strong></td>
        <td><strong>${totalOther}</strong></td>
      </tr>`;

  html += '</tbody></table></div>';

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
    .report-info {
      margin: 12px auto 10px; text-align: center; font-size: 12px; line-height: 1.5;
      max-width: 100%; white-space: nowrap;
    }
    .info-item { display: inline-block; margin: 0 15px; }
    .underline { display: inline-block; border-bottom: 1px solid #000; width: 120px; height: 14px; vertical-align: bottom; margin-left: 5px; }
    .address-underline { width: 150px; }
    .print-button-container { text-align: center; margin: 4px 0 12px; }
    .print-button-container button { background-color: #2f80ed; color: white; padding: 8px 20px; border: none; border-radius: 5px; cursor: pointer; font-size: 13px; font-weight: 600; }
    .print-button-container button:hover { background-color: #0056b3; }
    .table { width: 100%; margin-bottom: 8px; table-layout: fixed; border-collapse: separate; border-spacing: 0; }
    .table thead th {
      background: #cfd3d8 !important;
      color: #1f2937 !important;
      text-align: center;
      font-size: 13px;
      font-weight: 700;
      border: 1px solid #e5e7eb !important;
      padding: 4px 8px !important;
    }
    .table tbody td {
      padding: 5px 8px !important;
      font-size: 13px;
      color: #1f2937;
      border: 1px solid #f0f1f3 !important;
      vertical-align: middle;
      line-height: 1.2;
      background: #ffffff;
    }
    .table tbody td:not(:first-child) { text-align: center; }
    .table-striped tbody tr:nth-of-type(odd) td,
    .table-striped tbody tr:nth-of-type(even) td { background: #ffffff; }
    .table tbody tr:last-child td {
      background: #eef0f3 !important;
      font-weight: 700;
    }
    .summary { margin-top: 6px !important; }
    .summary ul { margin-bottom: 4px; }
    @media print { .print-button-container { display: none; } body { padding: 0; } .container-fluid { padding-left: 0; padding-right: 0; } }
  </style>
</head>
<body>
  <div class="print-button-container">
    <button onclick="window.print()">Print Report</button>
  </div>

  <div class="container-fluid">
    <div class="header-wrapper">
      <img src="/assets/images/SilayLogo.jpg" class="logo-left">
      <img src="/assets/images/BagongPilipinas.jpg" class="logo-right">
      <div class="main-header">
        <h3>Republic of the Philippines</h3>
        <h4>ENRIQUE B. MAGALONA</h4>
        <p>Persons with Disability Affairs Office</p>
      </div>
    </div>

    <div class="title-section">
      <h5>PERSONS WITH DISABILITY AFFAIRS OFFICE</h5>
      <h5>${escReport(reportTitle)}</h5>
      <h5><strong>${escReport(scopeName)}</strong></h5>
      <p class="as-of-label">As of -</p>
      <p class="as-of-date">${now.toLocaleDateString('en-PH', { year: 'numeric', month: 'long', day: 'numeric' })}</p>
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

function buildSimpleRows(pwds, scopeLabel) {
  if (!Array.isArray(pwds) || pwds.length === 0) {
    return '<tr><td colspan="7" class="text-center">No records found.</td></tr>';
  }
  const rows = pwds.map((pwd, idx) => {
    const fullName = pwd.fullName
      || [pwd.last_name, pwd.first_name, pwd.middle_name].filter(Boolean).join(' ')
      || 'Unnamed';
    const disability = Array.isArray(pwd.disability) ? pwd.disability.join(', ') : (pwd.disability || 'N/A');
    return `<tr>
      <td>${idx + 1}</td>
      <td>${escapeHtml(scopeLabel || 'N/A')}</td>
      <td>${escapeHtml(fullName)}</td>
      <td>${escapeHtml(pwd.gender || 'N/A')}</td>
      <td>${escapeHtml(pwd.age || 'N/A')}</td>
      <td>${escapeHtml(pwd.contact || 'N/A')}</td>
      <td>${escapeHtml(disability)}</td>
    </tr>`;
  }).join('');
  return `${rows}
    <tr>
      <td></td>
      <td><strong>TOTAL</strong></td>
      <td colspan="5"><strong>${pwds.length}</strong></td>
    </tr>`;
}

function buildReportHtml(title, subtitle, rowsHtml) {
  const now = new Date();
  return `<!doctype html>
<html>
<head>
  <meta charset="utf-8">
  <title>${escapeHtml(title)}</title>
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
    .print-button-container button:hover { background-color: #0056b3; }
    .report-scope { margin-top: 6px; font-size: 14px; }
    .table { width: 100%; margin-top: 8px; margin-bottom: 8px; table-layout: fixed; border-collapse: separate; border-spacing: 0; }
    .table thead th {
      background: #cfd3d8 !important;
      color: #1f2937 !important;
      text-align: center;
      font-size: 13px;
      font-weight: 700;
      border: 1px solid #e5e7eb !important;
      padding: 4px 8px !important;
    }
    .table tbody td {
      padding: 5px 8px !important;
      font-size: 13px;
      color: #1f2937;
      border: 1px solid #f0f1f3 !important;
      vertical-align: middle;
      line-height: 1.2;
      background: #ffffff;
    }
    .table tbody td:not(:first-child) { text-align: center; }
    .table-striped tbody tr:nth-of-type(odd) td,
    .table-striped tbody tr:nth-of-type(even) td { background: #ffffff; }
    .table tbody tr:last-child td {
      background: #eef0f3 !important;
      font-weight: 700;
    }
    @media print { .print-button-container { display: none; } body { padding: 0; } .container-fluid { padding-left: 0; padding-right: 0; } }
  </style>
</head>
<body>
  <div class="print-button-container">
    <button onclick="window.print()">Print Report</button>
  </div>
  <div class="container-fluid">
    <div class="header-wrapper">
      <img src="/assets/images/SilayLogo.jpg" class="logo-left">
      <img src="/assets/images/BagongPilipinas.jpg" class="logo-right">
      <div class="main-header">
        <h3>Republic of the Philippines</h3>
        <h4>ENRIQUE B. MAGALONA</h4>
        <p>Persons with Disability Affairs Office</p>
      </div>
    </div>
    <div class="title-section">
      <h5>PERSONS WITH DISABILITY AFFAIRS OFFICE</h5>
      <h5>${escapeHtml(title)}</h5>
      <p class="as-of-label">As of -</p>
      <p class="as-of-date">${now.toLocaleDateString('en-PH', { year: 'numeric', month: 'long', day: 'numeric' })}</p>
      <div class="report-scope">${escapeHtml(subtitle)}</div>
    </div>
  </div>
  <table class="table table-bordered table-striped">
    <thead class="thead-dark">
      <tr><th>#</th><th>Scope</th><th>Name</th><th>Gender</th><th>Age</th><th>Contact</th><th>Disability</th></tr>
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
    const html = buildReportHtml('PWD BARANGAY REPORT', barangay, buildSimpleRows(json.data || [], barangay));
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

  document.getElementById('searchInput').addEventListener('input', function(event) {
    const filter = String(event.target.value || '').toLowerCase();
    const rows = Array.from(document.querySelectorAll('.table-responsive table tbody tr'));
    
    rows.forEach(function(row) {
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
