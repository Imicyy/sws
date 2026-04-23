<!doctype html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width,initial-scale=1">
  <link rel="icon" type="image/png" href="<?= htmlspecialchars(asset_url('images/logo-ebmag.png'), ENT_QUOTES) ?>">
  <title>Admin Alert Management</title>
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/css/bootstrap.min.css">
  <style>
    body { font-family: Open Sans, Segoe UI, Arial, sans-serif; margin: 0; background: #f3f6fb; color: #1f2937; }
    .layout { display: grid; grid-template-columns: 260px 1fr; min-height: 100vh; }
    .sidebar { background: #fff; border-right: 1px solid #e5e7eb; padding: 20px 14px; position: sticky; top: 0; height: 100vh; overflow-y: auto; align-self: start; }
    .brand { font-weight: 800; font-size: 13px; letter-spacing: 0.4px; margin-bottom: 18px; }
    .nav-title { font-size: 12px; color: #6b7280; text-transform: uppercase; margin: 8px 10px; }
    .nav-link { display: block; padding: 10px 12px; margin-bottom: 6px; border-radius: 8px; color: #1f2937; text-decoration: none; }
    .nav-link.active { background: #0f766e; color: #fff; }
    .nav-link:hover { background: #edf2f7; }
    .main { padding: 20px; }
    .panel { background: #fff; border: 1px solid #e5e7eb; border-radius: 12px; padding: 16px; max-width: 700px; }
    .form-group { margin-bottom: 16px; }
    label { font-weight: 600; margin-bottom: 6px; display: block; }
    input, select, textarea { width: 100%; padding: 8px 12px; border: 1px solid #e5e7eb; border-radius: 6px; font-family: inherit; }
    textarea { resize: vertical; }
    button { padding: 10px 16px; border-radius: 6px; border: none; cursor: pointer; font-weight: 600; }
    button.btn-primary { background: #0f766e; color: #fff; }
    button.btn-primary:hover { background: #0d5f58; }
    pre { background: #f9fafb; border: 1px solid #e5e7eb; border-radius: 6px; padding: 12px; font-size: 12px; }
  </style>
</head>
<body>
  <div class="layout">
    <aside class="sidebar">
      <div class="brand">ENRIQUE B. MAGALONA</div>
      <div class="nav-title">Navigation</div>
      
      <a class="nav-link" href="/pdao-admin-dashboard">Person With Disability Table</a>
      
      <a class="nav-link active" href="/admin-alert">Alerts</a>
      <a class="nav-link" href="/logout">Logout</a>
    </aside>

    <main class="main">
      <h1 class="h4 mb-4">Send Alert</h1>

      <div class="panel">
        <form id="alertForm">
          <div class="form-group">
            <label for="room">Alert Channel</label>
            <select id="room" name="room" required>
              <option value="">-- Select Channel --</option>
              <option value="staff">Staff</option>
              <option value="barangay">Barangay</option>
              <option value="all">All Users</option>
            </select>
          </div>

          <div id="staffPicker" class="form-group" style="display:none;">
            <label>Select Staff</label>
            <div id="staffCheckboxes" style="max-height:220px; overflow:auto; border:1px solid #e5e7eb; padding:8px; border-radius:6px;"></div>
          </div>

          <div id="barangayPicker" class="form-group" style="display:none;">
            <label>Select Barangay</label>
            <div id="barangayCheckboxes" style="max-height:220px; overflow:auto; border:1px solid #e5e7eb; padding:8px; border-radius:6px;">
              <div style="margin-bottom:6px;">
                <input type="checkbox" id="g_all_barangay" value="__all_barangay__" data-group="true">
                <label for="g_all_barangay" style="margin-left:8px;">All Barangays</label>
              </div>
              <hr>
              <div id="barangayItems"></div>
            </div>
          </div>



          <div class="form-group">
            <label for="subject">Subject (Optional)</label>
            <input type="text" id="subject" name="subject" placeholder="Alert subject line">
          </div>

          <div class="form-group">
            <label for="message">Message</label>
            <textarea id="message" name="message" rows="6" placeholder="Enter alert message..." required></textarea>
          </div>

          <div class="form-group">
            <button type="submit" class="btn btn-primary">Send Alert</button>
          </div>
        </form>

        <div id="response" style="margin-top: 16px; display: none;">
          <h3>Response:</h3>
          <pre id="responseOutput"></pre>
        </div>
      </div>

      <div class="panel" style="margin-top: 20px;">
        <h2 style="font-size: 14px; font-weight: 600; margin-bottom: 12px;">Alert History</h2>
        <table style="width: 100%; font-size: 13px;">
          <thead>
            <tr style="border-bottom: 2px solid #e5e7eb;">
              <th style="padding: 10px; text-align: left;">Channel</th>
              <th style="padding: 10px; text-align: left;">Type</th>
              <th style="padding: 10px; text-align: left;">Subject</th>
              <th style="padding: 10px; text-align: left;">Sent At</th>
            </tr>
          </thead>
          <tbody id="historyBody">
            <tr>
              <td colspan="4" style="padding: 20px; text-align: center; color: #999;">No alert history yet</td>
            </tr>
          </tbody>
        </table>
      </div>
    </main>
  </div>

  <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
  <script src="https://cdn.socket.io/4.6.1/socket.io.min.js"></script>
  <script>
    function showResponseMessage(message, isSuccess) {
      const responseBox = document.getElementById('response');
      const output = document.getElementById('responseOutput');
      if (!responseBox || !output) return;
      responseBox.style.display = 'block';
      output.style.whiteSpace = 'pre-wrap';
      output.style.fontFamily = 'inherit';
      output.style.background = isSuccess ? '#ecfdf5' : '#fef2f2';
      output.style.borderColor = isSuccess ? '#86efac' : '#fca5a5';
      output.style.color = isSuccess ? '#166534' : '#991b1b';
      output.textContent = message;
    }

    function buildSuccessResponseText(data, room) {
      const created = Number((data && data.notifications_created) || 0);
      const roomLabel = room === 'all'
        ? 'staff and barangay users'
        : (room === 'staff' ? 'staff users' : 'barangay users');
      if (created > 0) {
        return `Alert sent successfully to ${roomLabel}.\nCreated notifications: ${created}`;
      }
      return `Alert sent successfully to ${roomLabel}.`;
    }

    // Helper to lazily return the socket instance (may be undefined if client failed to load)
    let socket = null;
    function ensureSocket() {
      if (socket) return socket;
      if (window && window._socket) { socket = window._socket; return socket; }

      const host = window.location.hostname || 'localhost';
      const proto = window.location.protocol === 'https:' ? 'https' : 'http';
      // Do not probe same-origin /socket.io (causes repeated 404 on PHP-only servers).
      const portsToTry = ['3000', '8080'];
      let connected = false;

      function inst(port) {
        try {
          return io(`${proto}://${host}:${port}`, { transports: ['websocket','polling'], timeout: 5000 });
        } catch (e) {
          console.warn('Socket instantiation failed for port', port, e);
          return null;
        }
      }

      // Try ports synchronously with connect_error fallback
      for (let i = 0; i < portsToTry.length; i++) {
        const p = portsToTry[i];
        const s = inst(p);
        if (!s) continue;
        // temporary handlers
        s.on('connect', function () {
          connected = true;
          socket = s;
          window._socket = s;
          console.debug('socket connected to', p, s.id);
        });
        s.on('connect_error', function (err) {
          console.debug('connect_error for port', p, err && err.message);
          try { s.close && s.close(); } catch (e) {}
        });
        // wait briefly for connection state
        // If connected set socket and return
        // Note: socket.io connects asynchronously; we allow the connect handler to set `socket`.
        // Return the socket instance if connected quickly, otherwise continue trying.
        if (socket) return socket;
      }

      return socket || null;
    }
    async function fetchStaffList() {
      try {
        const res = await fetch('/api/staff', { credentials: 'same-origin' });
        const json = await res.json();
        if (Array.isArray(json)) return json;
        if (json && Array.isArray(json.data)) return json.data;
      } catch (err) {}
      return [];
    }

    async function fetchBarangayList() {
      try {
        const res = await fetch('/api/barangays', { credentials: 'same-origin' });
        const json = await res.json();
        if (Array.isArray(json)) return json;
        if (json && Array.isArray(json.data)) return json.data;
      } catch (err) {}
      return [];
    }

    document.getElementById('room').addEventListener('change', async function () {
      const v = this.value;
      const sp = document.getElementById('staffPicker');
      const bp = document.getElementById('barangayPicker');
      sp.style.display = 'none'; bp.style.display = 'none';
      if (v === 'staff') {
        sp.style.display = '';
        const list = await fetchStaffList();
        const container = document.getElementById('staffCheckboxes');
        container.innerHTML = '';
        // quick-group checkboxes
        const groups = [ ['__all_staff__','All Staff'], ['__all_pdao__','All Staff PDAO'], ['__all_osca__','All Staff OSCA'] ];
        groups.forEach(g => {
          const wrap = document.createElement('div'); wrap.style.marginBottom = '6px';
          const cb = document.createElement('input'); cb.type = 'checkbox'; cb.value = g[0]; cb.id = 'g_'+g[0]; cb.dataset.group = 'true';
          const lbl = document.createElement('label'); lbl.htmlFor = cb.id; lbl.style.marginLeft = '8px'; lbl.textContent = g[1];
          wrap.appendChild(cb); wrap.appendChild(lbl); container.appendChild(wrap);
        });
        container.appendChild(document.createElement('hr'));
        list.forEach(item => {
          const id = item.id ?? item.staff_id ?? item.user_id ?? item.username ?? item.email ?? item.name ?? String(item);
          const name = item.name ?? item.full_name ?? item.display_name ?? item.username ?? item.email ?? String(item);
          const wrap = document.createElement('div'); wrap.style.marginBottom = '6px';
          const cb = document.createElement('input'); cb.type = 'checkbox'; cb.value = id; cb.id = 's_'+id;
          // preserve staff classification for group toggles (e.g., PDAO / OSCA)
          if (item.staff_classification) cb.dataset.class = String(item.staff_classification);
          const lbl = document.createElement('label'); lbl.htmlFor = cb.id; lbl.style.marginLeft = '8px'; lbl.textContent = name;
          wrap.appendChild(cb); wrap.appendChild(lbl); container.appendChild(wrap);
        });
        // wire quick-group checkboxes to toggle staff checkboxes
        container.querySelectorAll('input[type="checkbox"][data-group="true"]').forEach(gcb => {
          gcb.addEventListener('change', function () {
            const val = this.value;
            const checked = this.checked;
            if (val === '__all_staff__') {
              container.querySelectorAll('input[type="checkbox"]').forEach(cb => {
                if (!cb.dataset.group) cb.checked = checked;
              });
            } else if (val === '__all_pdao__' || val === '__all_osca__') {
              const target = val === '__all_pdao__' ? 'PDAO' : 'OSCA';
              container.querySelectorAll('input[type="checkbox"]').forEach(cb => {
                if (!cb.dataset.group && cb.dataset.class && cb.dataset.class.toUpperCase() === target) cb.checked = checked;
              });
            }
          });
        });
      } else if (v === 'barangay') {
        bp.style.display = '';
        const list = await fetchBarangayList();
        // keep the static All Barangays checkbox markup in place and only populate the items container
        const items = document.getElementById('barangayItems');
        if (items) items.innerHTML = '';
        list.forEach(item => {
          const name = item.name ?? item.barangay ?? String(item);
          const wrap = document.createElement('div'); wrap.style.marginBottom = '6px';
          const cb = document.createElement('input'); cb.type = 'checkbox'; cb.value = name; cb.id = 'b_'+name.replace(/\s+/g,'_');
          const lbl = document.createElement('label'); lbl.htmlFor = cb.id; lbl.style.marginLeft = '8px'; lbl.textContent = name;
          wrap.appendChild(cb); wrap.appendChild(lbl);
          if (items) items.appendChild(wrap);
        });
        // wire up the All Barangays toggle to check/uncheck all loaded barangay checkboxes
        const gAll = document.getElementById('g_all_barangay');
        if (gAll) {
          gAll.addEventListener('change', function () {
            const checked = this.checked;
            const container = document.getElementById('barangayItems');
            if (!container) return;
            container.querySelectorAll('input[type="checkbox"]').forEach(cb => cb.checked = checked);
          });
        }
      }
    });

    document.getElementById('alertForm').addEventListener('submit', async (e) => {
      e.preventDefault();
      const room = document.getElementById('room').value;
      const message = document.getElementById('message').value;
      const subject = document.getElementById('subject').value;
      const messageType = document.getElementById('messageType') ? document.getElementById('messageType').value : undefined;

      // collect selected targets from checkboxes
      const staffContainer = document.getElementById('staffCheckboxes');
      const barangayContainer = document.getElementById('barangayCheckboxes');
      const staffTargets = [];
      const barangayTargets = [];
      if (staffContainer) {
        staffContainer.querySelectorAll('input[type="checkbox"]:checked').forEach(cb => staffTargets.push(cb.value));
      }
      if (barangayContainer) {
        barangayContainer.querySelectorAll('input[type="checkbox"]:checked').forEach(cb => barangayTargets.push(cb.value));
      }

      try {
        if (room === 'all') {
          // send to both staff and barangay
          const targets = ['staff', 'barangay'];
          const promises = targets.map(r => fetch('/send-alert', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify({
              room: r,
              message,
              subject,
              messageType,
              staffTargets: r === 'staff' ? staffTargets : [],
              barangayTargets: r === 'barangay' ? barangayTargets : []
            })
          }));

          const responses = await Promise.all(promises);
          const datas = await Promise.all(responses.map(async r => {
            if (!r.ok) return { success: false, status: r.status, statusText: r.statusText };
            try { return await r.json(); } catch (e) { return { success: false, error: 'Invalid JSON response' }; }
          }));

          const anySuccess = datas.some(d => d && d.success);
          if (anySuccess) {
            const totalCreated = datas.reduce((sum, d) => sum + Number((d && d.notifications_created) || 0), 0);
            showResponseMessage(`Alert sent successfully to staff and barangay users.\nCreated notifications: ${totalCreated}`, true);
            Swal.fire({ icon: 'success', title: 'Success', text: 'Alerts sent' });
            addAlertLog(message, 'all');
            try { const s = ensureSocket(); if (s) s.emit('alert_sent', { room: 'all', message, subject, staffTargets, barangayTargets }); else console.warn('socket not available to emit'); } catch (err) { console.warn('socket emit error', err); }
            document.getElementById('alertForm').reset();
          } else {
            showResponseMessage('Failed to send alerts. Please check selected recipients and try again.', false);
            Swal.fire({ icon: 'error', title: 'Error', text: 'Failed to send alerts' });
          }
        } else {
          console.debug('Sending alert payload', { room, message, subject, messageType, staffTargets, barangayTargets });
          const response = await fetch('/send-alert', {
            method: 'POST',
            credentials: 'same-origin',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify({ room, message, subject, messageType, staffTargets, barangayTargets })
          });
          let data;
          if (!response.ok) {
            data = { success: false, status: response.status, statusText: response.statusText };
          } else {
            try { data = await response.json(); } catch (e) { data = { success: false, error: 'Invalid JSON response' }; }
          }
          if (data.success) {
            showResponseMessage(buildSuccessResponseText(data, room), true);
            Swal.fire({ icon: 'success', title: 'Success', text: data.message || 'Alert sent' });
            addAlertLog(message, room);
            try { const s = ensureSocket(); if (s) s.emit('alert_sent', { room, message, subject, staffTargets, barangayTargets }); else console.warn('socket not available to emit'); } catch (err) { console.warn('socket emit failed', err); }
            document.getElementById('alertForm').reset();
          } else {
            showResponseMessage(data.error || 'Failed to send alert.', false);
            Swal.fire({ icon: 'error', title: 'Error', text: data.error || 'Failed to send alert' });
          }
        }
      } catch (err) {
        showResponseMessage('Error: ' + err.message, false);
        Swal.fire({ icon: 'error', title: 'Error', text: 'Failed to send alert: ' + err.message });
      }
    });

    // Add alert to the history table
    function addAlertLog(message, room) {
      const tbody = document.getElementById('historyBody');
      if (!tbody) return;
      // remove placeholder row if present
      if (tbody.querySelector('td') && tbody.querySelector('td').textContent.includes('No alert history yet')) {
        tbody.innerHTML = '';
      }
      const tr = document.createElement('tr');
      const now = new Date().toLocaleString();
      const type = 'Manual';
      const subject = document.getElementById('subject').value || '';
      tr.innerHTML = `
        <td style="padding: 10px;">${escapeHtml(room)}</td>
        <td style="padding: 10px;">${escapeHtml(type)}</td>
        <td style="padding: 10px;">${escapeHtml(subject)}</td>
        <td style="padding: 10px;">${escapeHtml(now)}</td>
      `;
      tbody.insertBefore(tr, tbody.firstChild);
    }

    function escapeHtml(s) {
      if (s == null) return '';
      return String(s).replace(/[&<>"']/g, function (c) { return { '&': '&amp;', '<': '&lt;', '>': '&gt;', '"': '&quot;', "'": '&#39;' }[c]; });
    }
  </script>
</body>
</html>

