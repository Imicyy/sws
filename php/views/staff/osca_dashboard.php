<!doctype html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width,initial-scale=1">
  <title>Social Welfare System - OSCA Dashboard</title>
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/css/bootstrap.min.css">
  <style>
    body { font-family: Open Sans, Segoe UI, Arial, sans-serif; margin: 0; background: #f3f6fb; color: #1f2937; }
    * { box-sizing: border-box; }
    html, body { width: 100%; min-height: 100%; }
    .layout { display: flex; align-items: stretch; min-height: 100vh; width: 100%; }
    .sidebar { flex: 0 0 260px; width: 260px; background: #fff; border-right: 1px solid #e5e7eb; padding: 20px 14px; overflow-y: auto; }
    .brand { font-weight: 800; font-size: 13px; letter-spacing: 0.4px; margin-bottom: 18px; }
    .nav-title { font-size: 12px; color: #6b7280; text-transform: uppercase; margin: 8px 10px; margin-top: 16px; }
    .nav-link { display: block; padding: 10px 12px; margin-bottom: 6px; border-radius: 8px; color: #1f2937; text-decoration: none; font-size: 14px; }
    .nav-link.active { background: #0f766e; color: #fff; }
    .nav-link:hover { background: #edf2f7; }
    .main { flex: 1 1 auto; min-width: 0; padding: 20px; }
    .top { display: flex; justify-content: space-between; align-items: center; margin-bottom: 16px; gap: 12px; }
    .top .welcome { color: #6b7280; font-size: 14px; }
    .cards { display: grid; grid-template-columns: repeat(2, minmax(0, 1fr)); gap: 14px; }
    .card-metric { border-radius: 12px; padding: 16px; color: #fff; box-shadow: 0 10px 24px rgba(0,0,0,.12); }
    .card-metric .label { font-size: 12px; text-transform: uppercase; opacity: 0.9; }
    .card-metric .value { font-size: 26px; font-weight: 700; margin-top: 6px; }
    .bg-green { background: linear-gradient(135deg, #059669, #34d399); }
    .bg-teal { background: linear-gradient(135deg, #0d9488, #2dd4bf); }
    .panel { margin-top: 16px; background: #fff; border: 1px solid #e5e7eb; border-radius: 12px; padding: 18px; }
    .quick a { display: inline-block; margin-right: 10px; margin-bottom: 10px; padding: 9px 12px; border-radius: 8px; background: #ecfdf5; color: #065f46; text-decoration: none; }
    .quick a:hover { background: #d1fae5; }
    .dept-badge { display: inline-block; padding: 4px 10px; background: #d1fae5; color: #065f46; border-radius: 6px; font-size: 12px; font-weight: 600; }
    .filters { display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 12px; margin-top: 12px; }
    .filter-group { display: flex; flex-direction: column; }
    .filter-group label { font-size: 12px; font-weight: 600; margin-bottom: 6px; color: #6b7280; }
    .filter-group select { padding: 8px; border: 1px solid #d1d5db; border-radius: 6px; font-size: 14px; }
    .table-container { background: #fff; border-radius: 12px; border: 1px solid #e5e7eb; overflow-x: auto; margin-top: 16px; }
    table { margin: 0; }
    table thead { background: #374151; color: #fff; font-weight: 600; font-size: 12px; }
    table th { padding: 12px; text-align: left; border: none; white-space: nowrap; }
    table td { padding: 12px; border-top: 1px solid #e5e7eb; vertical-align: middle; }
    table tbody tr:hover { background: #f9fafb; }
    .status-badge { display: inline-block; padding: 4px 8px; border-radius: 4px; font-size: 12px; font-weight: 600; }
    .status-active { background: #d1fae5; color: #065f46; }
    .status-archived { background: #f3f4f6; color: #374151; }
    .actions { display: flex; gap: 8px; }
    .btn-sm { padding: 6px 10px; font-size: 12px; border-radius: 4px; border: 1px solid #d1d5db; background: none; cursor: pointer; }
    .btn-view { color: #0f766e; }
    .btn-edit { color: #2563eb; }
    .btn-archive { color: #dc2626; }
    .btn-sm:hover { background: #f3f4f6; }
    .action-bar { margin-top: 16px; display: flex; gap: 12px; }
    .action-bar button { padding: 10px 16px; border: none; border-radius: 6px; font-weight: 600; cursor: pointer; font-size: 14px; }
    .btn-sms { background: #059669; color: white; }
    .btn-sms:disabled { background: #d1d5db; color: #6b7280; cursor: not-allowed; }
    .btn-history { background: #0f766e; color: white; }
    @media (max-width: 980px) {
      .layout { flex-direction: column; }
      .sidebar { flex: none; width: 100%; }
      .cards { grid-template-columns: 1fr; }
    }
  </style>
</head>
<body>
  <div class="layout">
    <aside class="sidebar">
      <div class="brand">ENRIQUE B. MAGALONA</div>
      <div style="font-size: 11px; color: #6b7280; margin-bottom: 14px;"><span class="dept-badge">OSCA Department</span></div>
      <div class="nav-title">Navigation</div>
      <a class="nav-link active" href="/osca-dashboard">Dashboard</a>
      <a class="nav-link" href="/add_senior">Register New Senior</a>
      <a class="nav-link" href="/logout">Logout</a>
    </aside>

    <main class="main">
      <div class="top">
        <h1 class="h4 mb-0">OSCA Dashboard</h1>
        <div class="welcome">Signed in as <?= htmlspecialchars((string) ($user['email'] ?? 'staff'), ENT_QUOTES, 'UTF-8') ?></div>
      </div>

      <section class="cards">
        <div class="card-metric bg-green">
          <div class="label">Senior Citizens Registered</div>
          <div class="value"><?= (int) ($totalSeniors ?? 0) ?></div>
        </div>
        <div class="card-metric bg-teal">
          <div class="label">Current Records Shown</div>
          <div class="value" id="entryCount"><?= count($seniors ?? []) ?></div>
        </div>
      </section>

      <section class="panel quick">
        <h2 class="h6 mb-3">Quick Entry Actions</h2>
        <a href="/add_senior">Register New Senior Citizen</a>
      </section>

      <section class="panel">
        <h2 class="h6 mb-3">Senior Citizens</h2>
        <div class="filters">
          <div class="filter-group">
            <label>Barangay:</label>
            <select id="barangayFilter">
              <option value="">All Barangays</option>
              <?php foreach (($barangays ?? []) as $brgy => $puroks): ?>
                <option value="<?= htmlspecialchars($brgy, ENT_QUOTES, 'UTF-8') ?>"><?= htmlspecialchars($brgy, ENT_QUOTES, 'UTF-8') ?></option>
              <?php endforeach; ?>
            </select>
          </div>
          <div class="filter-group">
            <label>Purok:</label>
            <select id="purokFilter" disabled>
              <option value="">First select a Barangay</option>
            </select>
          </div>
          <div class="filter-group">
            <label>Status:</label>
            <select id="statusFilter" onchange="filterTable()">
              <option value="Active">Active Only</option>
              <option value="Archived">Archived Only</option>
              <option value="">All Records</option>
            </select>
          </div>
        </div>

        <div class="table-container">
          <table id="seniorTable" class="table table-hover mb-0">
            <thead>
              <tr>
                <th style="width: 30px;"><input type="checkbox" id="selectAll"></th>
                <th>Full Name</th>
                <th>Age</th>
                <th>Barangay</th>
                <th>Purok</th>
                <th>Status</th>
                <th>Actions</th>
              </tr>
            </thead>
            <tbody>
              <?php foreach (($seniors ?? []) as $senior):
                $fullName = trim((string) ($senior['first_name'] ?? '') . ' ' . (string) ($senior['middle_name'] ?? '') . ' ' . (string) ($senior['last_name'] ?? ''));
                $barangay = (string) ($senior['barangay'] ?? '');
                $purok = (string) ($senior['purok'] ?? '');
                $status = (string) ($senior['status'] ?? 'Active');
                $statusClass = $status === 'Archived' ? 'status-archived' : 'status-active';
              ?>
                <tr data-barangay="<?= htmlspecialchars($barangay, ENT_QUOTES, 'UTF-8') ?>" data-purok="<?= htmlspecialchars($purok, ENT_QUOTES, 'UTF-8') ?>" data-status="<?= htmlspecialchars($status, ENT_QUOTES, 'UTF-8') ?>">
                  <td><input type="checkbox" class="rowCheckbox" value="<?= (int) ($senior['id'] ?? 0) ?>"></td>
                  <td><?= htmlspecialchars($fullName !== '' ? $fullName : 'Unnamed record', ENT_QUOTES, 'UTF-8') ?></td>
                  <td><?= isset($senior['age']) && $senior['age'] !== null ? (int) $senior['age'] : 'N/A' ?></td>
                  <td><?= htmlspecialchars($barangay, ENT_QUOTES, 'UTF-8') ?></td>
                  <td><?= htmlspecialchars($purok, ENT_QUOTES, 'UTF-8') ?></td>
                  <td><span class="status-badge <?= $statusClass ?>"><?= htmlspecialchars($status, ENT_QUOTES, 'UTF-8') ?></span></td>
                  <td>
                    <div class="actions">
                      <button class="btn-sm btn-view" title="View">View</button>
                      <button class="btn-sm btn-edit" title="Edit">Edit</button>
                      <button class="btn-sm btn-archive" title="Archive">Archive</button>
                    </div>
                  </td>
                </tr>
              <?php endforeach; ?>
            </tbody>
          </table>
        </div>

        <div class="action-bar">
          <button class="btn-sms" id="sendSmsBtn" disabled>Send SMS to Selected</button>
          <button class="btn-history" id="viewHistoryBtn">View SMS History</button>
        </div>
      </section>
    </main>
  </div>

  <script>
    const barangays = <?= json_encode($barangays ?? [], JSON_UNESCAPED_UNICODE) ?>;
    const barangayFilter = document.getElementById('barangayFilter');
    const purokFilter = document.getElementById('purokFilter');

    function getVisibleRows() {
      return Array.from(document.querySelectorAll('#seniorTable tbody tr')).filter(row => row.style.display !== 'none');
    }

    function filterTable() {
      const statusFilter = document.getElementById('statusFilter').value;
      const selectedBarangay = barangayFilter.value;
      const selectedPurok = purokFilter.value;
      let visibleCount = 0;

      document.querySelectorAll('#seniorTable tbody tr').forEach(row => {
        const rowBarangay = row.dataset.barangay || '';
        const rowPurok = row.dataset.purok || '';
        const rowStatus = row.dataset.status || '';
        const matchesBarangay = !selectedBarangay || rowBarangay === selectedBarangay;
        const matchesPurok = !selectedPurok || rowPurok === selectedPurok;
        const matchesStatus = !statusFilter || rowStatus === statusFilter;
        const show = matchesBarangay && matchesPurok && matchesStatus;
        row.style.display = show ? '' : 'none';
        if (show) visibleCount++;
      });

      document.getElementById('entryCount').textContent = visibleCount;
      updateActions();
    }

    function updateActions() {
      document.getElementById('sendSmsBtn').disabled = document.querySelectorAll('.rowCheckbox:checked').length === 0;
    }

    barangayFilter.addEventListener('change', function () {
      const selected = this.value;
      purokFilter.innerHTML = '<option value="">First select a Barangay</option>';
      purokFilter.disabled = true;

      if (selected && barangays[selected]) {
        barangays[selected].forEach(function (purok) {
          const option = document.createElement('option');
          option.value = purok;
          option.textContent = purok;
          purokFilter.appendChild(option);
        });
        purokFilter.disabled = false;
      }

      filterTable();
    });

    purokFilter.addEventListener('change', filterTable);

    document.getElementById('selectAll').addEventListener('change', function () {
      getVisibleRows().forEach(function (row) {
        const checkbox = row.querySelector('.rowCheckbox');
        if (checkbox) {
          checkbox.checked = this.checked;
        }
      }, this);
      updateActions();
    });

    document.querySelectorAll('.rowCheckbox').forEach(function (checkbox) {
      checkbox.addEventListener('change', updateActions);
    });

    document.getElementById('sendSmsBtn').addEventListener('click', function () {
      alert('SMS action is connected to the selected senior records.');
    });

    document.getElementById('viewHistoryBtn').addEventListener('click', function () {
      alert('SMS history view is not wired yet.');
    });
  </script>
</body>
</html>
