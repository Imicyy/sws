<!doctype html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width,initial-scale=1">
  <link rel="icon" type="image/png" href="<?= htmlspecialchars(asset_url('images/logo-ebmag.png'), ENT_QUOTES) ?>">
  <title>Barangay — Senior Citizens List</title>
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/css/bootstrap.min.css">
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
    .card { background: #fff; border: 1px solid #e5e7eb; border-radius: 12px; padding: 16px; }
    table { width: 100%; border-collapse: collapse; font-size: 13px; }
    th, td { padding: 8px; border-bottom: 1px solid #e8edf3; text-align: left; }
    @media (max-width: 980px) { .layout { grid-template-columns: 1fr; } .sidebar { position: static; height: auto; max-height: none; } }
  </style>
</head>
<body>
  <div class="layout">
    <aside class="sidebar">
      <div class="brand">ENRIQUE B. MAGALONA</div>
      <div class="nav-title">Navigation</div>
      <a class="nav-link" href="/barangay">Person With Disability Analytics</a>
      <a class="nav-link" href="/barangay-senior-dashboard">Senior Citizen Analytics</a>
      <a class="nav-link" href="/barangay-pwd">Person With Disability List</a>
      <a class="nav-link active" href="/barangay-senior">Senior Citizens List</a>
      <a class="nav-link" href="/logout">Logout</a>
    </aside>

    <main class="main">
      <div class="card">
        <div style="display:flex;justify-content:space-between;align-items:center;">
          <h2 style="margin:0;">Senior — <?= htmlspecialchars((string)($assignedBarangayName ?? ''), ENT_QUOTES, 'UTF-8') ?></h2>
          <a href="/add_senior" class="btn btn-info" style="background:#0f766e;border-color:#0f766e;color:#fff;">ADD SENIOR</a>
        </div>

        <div style="margin-top:16px; background:#fff; padding:14px; border-radius:8px; border:1px solid #eef2f6; display:flex; gap:12px; align-items:center;">
          <div style="flex:1">
            <label style="font-size:12px; color:#6b7280; display:block; margin-bottom:6px;">Barangay:</label>
            <input type="text" value="<?= htmlspecialchars((string)($assignedBarangayName ?? ''), ENT_QUOTES, 'UTF-8') ?>" disabled style="width:100%; padding:8px; border-radius:6px; border:1px solid #e5e7eb; background:#f8fafc;">
          </div>
          <div style="flex:1">
            <label style="font-size:12px; color:#6b7280; display:block; margin-bottom:6px;">Purok:</label>
            <select id="purokFilter" style="width:100%; padding:8px; border-radius:6px; border:1px solid #e5e7eb;">
              <option value="">Select Purok</option>
              <?php foreach (($puroks ?? []) as $pr): ?>
                <option value="<?= htmlspecialchars((string)$pr, ENT_QUOTES, 'UTF-8') ?>"><?= htmlspecialchars((string)$pr, ENT_QUOTES, 'UTF-8') ?></option>
              <?php endforeach; ?>
            </select>
          </div>
          <div style="width:160px">
            <label style="font-size:12px; color:#6b7280; display:block; margin-bottom:6px;">Status:</label>
            <select id="statusFilter" style="width:100%; padding:8px; border-radius:6px; border:1px solid #e5e7eb;">
              <option value="">Active Only</option>
              <option value="Active">Active</option>
              <option value="Archived">Archived</option>
              <option value="all">All</option>
            </select>
          </div>
          <div style="width:220px">
            <label style="font-size:12px; color:#6b7280; display:block; margin-bottom:6px;">Search:</label>
            <input id="searchInput" type="text" placeholder="Search..." style="width:100%; padding:8px; border-radius:6px; border:1px solid #e5e7eb;">
          </div>
        </div>

        <div style="margin-top:16px; overflow:auto">
          <table id="seniorTable" class="table table-striped">
            <thead class="thead-dark">
              <tr>
                <th style="width:40px;"><input id="selectAll" type="checkbox"></th>
                <th>FULL NAME</th>
                <th>AGE</th>
                <th>BARANGAY</th>
                <th>PUROK</th>
                <th>GENDER</th>
                <th>STATUS</th>
                <th>ACTIONS</th>
              </tr>
            </thead>
            <tbody>
              <?php foreach (($seniors ?? []) as $s): ?>
                <?php $full = trim(((string)($s['last_name'] ?? '')) . ' ' . ((string)($s['first_name'] ?? '')) . ' ' . ((string)($s['middle_name'] ?? '')) . ' ' . ((string)($s['extension'] ?? ''))); ?>
                <tr data-purok="<?= htmlspecialchars((string)($s['purok'] ?? ''), ENT_QUOTES, 'UTF-8') ?>">
                  <td><input class="rowCheckbox" type="checkbox" value="<?= (int)($s['id'] ?? 0) ?>"></td>
                  <td><?= htmlspecialchars(strtoupper($full), ENT_QUOTES, 'UTF-8') ?></td>
                  <td><?= htmlspecialchars((string)($s['age'] ?? ''), ENT_QUOTES, 'UTF-8') ?></td>
                  <td><?= htmlspecialchars((string)($s['barangay'] ?? ''), ENT_QUOTES, 'UTF-8') ?></td>
                  <td><?= htmlspecialchars((string)($s['purok'] ?? ''), ENT_QUOTES, 'UTF-8') ?></td>
                  <td><?= htmlspecialchars((string)($s['gender'] ?? ''), ENT_QUOTES, 'UTF-8') ?></td>
                  <td><span class="badge badge-<?= ((($s['status'] ?? '') === 'Archived') ? 'secondary' : 'success') ?>"><?= htmlspecialchars((string)($s['status'] ?? ''), ENT_QUOTES, 'UTF-8') ?></span></td>
                  <td>
                    <a href="/senior/<?= (int)($s['id'] ?? 0) ?>/application-pdf" class="btn btn-sm btn-print-app" target="_blank">PDF</a>
                  </td>
                </tr>
              <?php endforeach; ?>
            </tbody>
          </table>
        </div>
      </div>
    </main>
  </div>
</body>
</html>
<script>
  (function(){
    const purokFilter = document.getElementById('purokFilter');
    const statusFilter = document.getElementById('statusFilter');
    const searchInput = document.getElementById('searchInput');
    const selectAll = document.getElementById('selectAll');

    function applyFilters(){
      const purok = purokFilter ? purokFilter.value.trim().toLowerCase() : '';
      const status = statusFilter ? statusFilter.value : '';
      const search = searchInput ? searchInput.value.trim().toLowerCase() : '';
      document.querySelectorAll('#seniorTable tbody tr').forEach(function(row){
        const rowPurok = (row.dataset.purok || '').toLowerCase();
        const cols = row.querySelectorAll('td');
        const name = (cols[1] ? cols[1].textContent : '').toLowerCase();
        const rowStatus = (cols[6] ? cols[6].textContent : '').trim();

        let show = true;
        if (purok && rowPurok !== purok) show = false;
        if (status && status !== 'all' && rowStatus !== status) show = false;
        if (search && name.indexOf(search) === -1 && rowPurok.indexOf(search) === -1) show = false;

        row.style.display = show ? '' : 'none';
      });
    }

    if (purokFilter) purokFilter.addEventListener('change', applyFilters);
    if (statusFilter) statusFilter.addEventListener('change', applyFilters);
    if (searchInput) searchInput.addEventListener('input', applyFilters);
    if (selectAll) selectAll.addEventListener('change', function(){
      const checked = !!selectAll.checked;
      document.querySelectorAll('.rowCheckbox').forEach(function(cb){ cb.checked = checked; });
    });
  })();
</script>
