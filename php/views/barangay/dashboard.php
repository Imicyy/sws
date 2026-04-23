<!doctype html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width,initial-scale=1">
  <link rel="icon" type="image/png" href="<?= htmlspecialchars(asset_url('images/logo-ebmag.png'), ENT_QUOTES) ?>">
  <title>Barangay — Person With Disability Analytics</title>
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
      <a class="nav-link active" href="/barangay">Person With Disability Analytics</a>
      <a class="nav-link" href="/barangay-senior-dashboard">Senior Citizen Analytics</a>
      <a class="nav-link" href="/barangay-pwd">Person With Disability List</a>
      <a class="nav-link" href="/barangay-senior">Senior Citizens List</a>
      <a class="nav-link" href="/logout">Logout</a>
    </aside>

    <main class="main">
      <div class="top-header" style="display:flex;align-items:center;justify-content:space-between;">
        <div>
          <h1 class="h4">Barangay Dashboard — PWD Analytics</h1>
          <?php if (!empty($assignedBarangayName)): ?>
          <p class="text-muted mb-0" style="font-size: 13px;"><strong>Barangay:</strong> <?= htmlspecialchars((string) $assignedBarangayName, ENT_QUOTES, 'UTF-8') ?></p>
          <?php endif; ?>
        </div>
        <div style="position:relative;">
          <button id="notifBellBrgy" style="background:transparent;border:0;cursor:pointer;padding:8px;border-radius:8px;font-size:18px;">🔔 <span id="notifBadgeBrgy" style="background:#dc2626;color:#fff;border-radius:10px;padding:2px 6px;font-size:12px;display:none;margin-left:6px;">0</span></button>
          <div id="notifDropdownBrgy" style="display:none;position:absolute;right:0;top:48px;background:#fff;border:1px solid #e5e7eb;border-radius:8px;box-shadow:0 6px 18px rgba(0,0,0,0.08);width:360px;max-height:320px;overflow:auto;padding:8px;z-index:2000;">
            <div style="font-weight:700;padding:8px;border-bottom:1px solid #f3f4f6;">Notifications</div>
            <div id="notifListBrgy" style="padding:8px;font-size:13px;color:#374151;"></div>
          </div>
        </div>
      </div>

      <div class="stats">
        <div class="stat-card">
          <div class="stat-number" id="totalBarangays">1</div>
          <div class="stat-label">Your Jurisdiction</div>
        </div>
        <div class="stat-card">
          <div class="stat-number" id="totalPDAO">0</div>
          <div class="stat-label">Total Person With Disability Count</div>
        </div>
        <div class="stat-card">
          <div class="stat-number" id="averagePDAO">0</div>
          <div class="stat-label">Average per purok</div>
        </div>
      </div>

      <div class="panel">
        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 14px;">
          <h2 class="panel-title mb-0">Purok PWD Analytics Table</h2>
          <div>
            <input type="text" class="search-box" id="searchInput" placeholder="🔍 Search puroks..." style="margin-bottom: 0; display: inline-block; width: 200px;">
            <button class="btn btn-info" onclick="showReportModal()" style="margin-left: 8px;">Generate Report</button>
          </div>
        </div>

        <table id="dataTable">
          <thead>
            <tr>
              <th>Purok Name</th>
              <th>PWD Count</th>
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
        <p>Persons with Disability Affairs Office</p>
      </div>
    </div>

    <div class="title-section">
      <h4>PERSONS WITH DISABILITY AFFAIRS OFFICE</h4>
      <h5>${escapeHtml(reportTitle)}</h5>
      <h4><strong>${escapeHtml(scopeName)}</strong></h4>
      <div class="as-of">As of - <strong>${new Date().toLocaleDateString('en-PH', { year: 'numeric', month: 'long', day: 'numeric' })}</strong></div>
    </div>

    <table class="table table-bordered table-striped">
      <thead>
        <tr>
          <th>Purok</th>
          <th>Total PWD</th>
        </tr>
      </thead>
      <tbody>${rowsHtml}</tbody>
    </table>

    <div class="summary-box">
      <h5>Report Summary</h5>
      <p>Barangay: ${escapeHtml(scopeName)}</p>
      <p>Total PWD: ${total.toLocaleString()}</p>
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
        const query = `?year=${year}${month}`;

        const res = await fetch(`/api/pwds${query}`, { credentials: 'same-origin' });
        const json = await res.json();
        if (!res.ok || !json) { throw new Error((json && json.message) || 'Unable to load report data'); }

        // If endpoint returns list of purok aggregates use it; otherwise aggregate by purok
        let rows = [];
        if (Array.isArray(json.pwds) && json.pwds.length && json.pwds[0].purok) {
          rows = json.pwds.map(p => ({ purok: p.purok || p.name, count: p.count || p.total || 0 }));
        } else if (Array.isArray(json.pwds)) {
          const agg = {};
          json.pwds.forEach(p => { const k = (p.purok||p.address?.purok||'Unknown'); agg[k] = (agg[k]||0)+1; });
          rows = Object.keys(agg).map(k => ({ purok: k, count: agg[k] }));
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
  <script src="https://cdn.socket.io/4.6.1/socket.io.min.js"></script>
  <script>
    (function () {
      const badge = document.getElementById('notifBadgeBrgy');
      const list = document.getElementById('notifListBrgy');
      const dropdown = document.getElementById('notifDropdownBrgy');
      const bell = document.getElementById('notifBellBrgy');
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
          s.on('connect', function () { window._socket = s; console.debug('brgy socket connected', s.id); });
          s.on('connect_error', function () { tryConnect(i+1); });
          s.on('receive-alert', function () { loadNotifications(); });
        } catch (e) { tryConnect(i+1); }
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

