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
      <a class="nav-link active" href="/pdao-admin-dashboard">PDAO Admin Dashboard</a>
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
              // Calculate total barangays from barangayCounts
              $barangayCounts = [];
              if (isset($pwds) && is_array($pwds)) {
                foreach ($pwds as $pwd) {
                  $barangay = trim((string)($pwd['barangay'] ?? 'Unknown'));
                  if ($barangay === '') $barangay = 'Unknown';
                  if (!isset($barangayCounts[$barangay])) $barangayCounts[$barangay] = 0;
                  $barangayCounts[$barangay]++;
                }
              }
              echo count($barangayCounts);
            ?>
          </div>
        </div>
        <div class="card-metric bg-blue">
          <div class="label">Total PWDs</div>
          <div class="value">
            <?php
              echo isset($pwds) && is_array($pwds) ? count($pwds) : 0;
            ?>
          </div>
        </div>
        <div class="card-metric bg-green">
          <div class="label">Average per Barangay</div>
          <div class="value">
            <?php
              $totalBarangays = count($barangayCounts);
              $totalPWDs = isset($pwds) && is_array($pwds) ? count($pwds) : 0;
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
              <?php if (!empty($barangayCounts)): ?>
                <?php foreach ($barangayCounts as $barangay => $count): ?>
                  <tr>
                    <td><?= htmlspecialchars($barangay, ENT_QUOTES, 'UTF-8') ?></td>
                    <td><?= (int)$count ?></td>
                    <td>
                      <div style="display: flex; gap: 8px;">
                        <button class="btn btn-primary btn-sm" type="button" onclick="viewChart('<?= htmlspecialchars($barangay, ENT_QUOTES, 'UTF-8') ?>')">View Chart</button>
                        <button class="btn btn-secondary btn-sm" type="button" onclick="window.print()">Print</button>
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
// Search barangay functionality
$(document).ready(function() {
  $('#searchInput').on('input', function() {
    var filter = this.value.toLowerCase();
    var table = $('.table-responsive table');
    var rows = table.find('tbody tr');
    rows.each(function() {
      var barangay = $(this).find('td').eq(0).text().toLowerCase();
      $(this).toggle(barangay.indexOf(filter) !== -1);
    });
  });
});
// Placeholder for chart viewing
function viewChart(barangay) {
  alert('Show chart for ' + barangay);
}
</script>
