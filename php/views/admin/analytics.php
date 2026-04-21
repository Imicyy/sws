</script>
<script>
// Search barangay functionality
document.getElementById('searchInput').addEventListener('input', function() {
  var filter = this.value.toLowerCase();
  var table = document.querySelector('.table-responsive table');
  var rows = table.querySelectorAll('tbody tr');
  rows.forEach(function(row) {
    var barangay = row.cells[0].textContent.toLowerCase();
    row.style.display = barangay.indexOf(filter) !== -1 ? '' : 'none';
  });
});
</script>
<!doctype html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width,initial-scale=1">
  <title>Social Welfare System - Senior Citizen Table</title>
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
    @media (max-width: 980px) {
      .layout { grid-template-columns: 1fr; }
      .cards { grid-template-columns: repeat(2, minmax(0, 1fr)); }
      .table-responsive { overflow-x: auto; }
      table { min-width: 600px; }
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
        <h1 class="h4 mb-0" style="width: 100%; text-align: center;">Senior Citizen Table</h1>
        <div class="welcome" style="width: 100%; text-align: center;">Signed in as <?= htmlspecialchars((string) ($user['email'] ?? 'admin'), ENT_QUOTES, 'UTF-8') ?></div>
      </div>

      <section class="cards">
        <div class="card-metric bg-yellow">
          <div class="label">Total Barangays</div>
          <div class="value">23</div>
        </div>
        <div class="card-metric bg-blue">
          <div class="label">Total Senior Citizen</div>
          <div class="value">
            <?php
              if (isset($seniors) && is_array($seniors)) {
                echo count($seniors);
              } else {
                echo 0;
              }
            ?>
          </div>
        </div>
        <div class="card-metric bg-green">
          <div class="label">Average per Barangay</div>
          <div class="value">1</div>
        </div>
      </section>
      <!-- Generate Report Modal -->
      <div class="modal fade" id="reportModal" tabindex="-1" role="dialog" aria-labelledby="reportModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
          <div class="modal-content">
            <div class="modal-header">
              <h5 class="modal-title" id="reportModalLabel">Generate Report</h5>
              <button type="button" class="close" data-dismiss="modal" aria-label="Close" onclick="closeReportModal()">
                <span aria-hidden="true">&times;</span>
              </button>
            </div>
            <div class="modal-body">
              <form id="reportForm">
                <div class="form-group">
                  <label for="reportType">Report Type</label>
                  <select class="form-control" id="reportType" required>
                    <option value="">Select type</option>
                    <option value="annual">Annual Report</option>
                    <option value="monthly">Monthly Report</option>
                  </select>
                </div>
                <div class="form-group">
                  <label for="reportYear">Select Year</label>
                  <select class="form-control" id="reportYear" required>
                    <option value="">Select year</option>
                  </select>
                </div>
              </form>
            </div>
            <div class="modal-footer">
              <button type="button" class="btn btn-secondary" onclick="closeReportModal()">Cancel</button>
              <button type="button" class="btn btn-success" id="modalGenerateBtn">Generate Report</button>
            </div>
          </div>
        </div>
      </div>

      <section class="panel">
              <div style="display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; margin-bottom: 10px;">
                <div style="display: flex; align-items: center; gap: 10px;">
                  <a href="/Maps" class="btn btn-info" style="font-weight:600; border-radius:8px;">&#128506; Senior Map</a>
                  <h2 class="h6 mb-0" style="margin-bottom:0;">Barangay Senior Citizen Table</h2>
                </div>
                <div style="display: flex; gap: 8px; align-items: center;">
                  <input id="searchInput" type="text" class="form-control" placeholder="Search barangay..." style="max-width: 180px; font-size: 13px;">
                  <button id="generateReportBtn" class="btn btn-success" type="button" style="font-size: 13px;">Generate Report</button>
                </div>
              </div>
              <div class="table-responsive">
                              
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
                ?>
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
                              <button class="btn btn-info btn-sm" type="button" onclick="showMonthlyReportModal('<?= htmlspecialchars($barangay, ENT_QUOTES, 'UTF-8') ?>')">Monthly Report</button>
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
</body>
</html>
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/js/bootstrap.bundle.min.js"></script>
<script>
// Show modal on Generate Report button click
document.getElementById('generateReportBtn').addEventListener('click', function() {
  populateYearDropdown();
  $('#reportModal').modal('show');
});

// Populate year dropdown with last 10 years
function populateYearDropdown() {
  var yearSelect = document.getElementById('reportYear');
  var currentYear = new Date().getFullYear();
  yearSelect.innerHTML = '<option value="">Select year</option>';
  for (var y = currentYear; y >= currentYear - 9; y--) {
    yearSelect.innerHTML += '<option value="' + y + '">' + y + '</option>';
  }
}

// Close modal
function closeReportModal() {
  $('#reportModal').modal('hide');
}

// Handle modal Generate Report button (add your logic here)
document.getElementById('modalGenerateBtn').addEventListener('click', function() {
  var type = document.getElementById('reportType').value;
  var year = document.getElementById('reportYear').value;
  if (!type || !year) {
    alert('Please select both report type and year.');
    return;
  }
  // Add your report generation logic here
  closeReportModal();
  alert('Generating ' + (type === 'annual' ? 'Annual' : 'Monthly') + ' Report for ' + year);
});
</script>

