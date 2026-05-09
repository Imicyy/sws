<!doctype html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width,initial-scale=1">
  <link rel="icon" type="image/jpeg" href="<?= htmlspecialchars(asset_url('images/SilayLogo.jpg'), ENT_QUOTES) ?>">
  <title>Admin Alert Management</title>
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/css/bootstrap.min.css">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
  <link rel="stylesheet" href="<?= htmlspecialchars(asset_url('css/light-theme.css?v=20260424'), ENT_QUOTES) ?>">
  <style>
    body { font-family: Segoe UI, Arial, sans-serif; margin: 0; background: linear-gradient(180deg, #fffdf0 0%, #f5f9ff 55%, #eef5ff 100%); color: #1f2937; display: flex; position: relative; }
    
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
      font-size: 14px; 
      letter-spacing: 0.5px; 
      margin-bottom: 22px; 
      color: #ffffff !important; 
      display: flex; 
      align-items: center; 
      gap: 10px;
      text-transform: uppercase;
      white-space: nowrap;
    }
    .nav-title { 
      font-size: 10px; 
      color: #94a3b8 !important; 
      text-transform: uppercase; 
      margin: 8px 10px; 
      font-weight: 600; 
      letter-spacing: 0.5px; 
    }
    .sidebar .nav-link { 
      display: flex !important; 
      align-items: center !important; 
      justify-content: flex-start !important; 
      text-align: left !important; 
      min-height: 42px !important; 
      padding: 12px 16px; 
      margin-bottom: 8px; 
      border-radius: 12px; 
      background: transparent !important; 
      color: #e2e8f0 !important; 
      text-decoration: none !important; 
      font-weight: 500; 
      font-size: 15px !important; 
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
      font-size: 15px;
      transition: all .3s ease;
    }
    .logout-btn:hover { 
      background: #ef4444 !important; 
      color: #fff !important; 
      transform: translateY(-2px) !important; 
      box-shadow: 0 6px 12px rgba(239, 68, 68, 0.2) !important; 
    }
    
    .main { flex: 1; padding: 30px; margin-left: 260px; position: relative; width: calc(100% - 260px); display: flex; flex-direction: column; align-items: center; z-index: 1; }
    .main::before {
      content: ""; position: fixed; top: 0; left: 260px; right: 0; bottom: 0;
      background: url('<?= htmlspecialchars(asset_url("images/SilayLogo.png"), ENT_QUOTES) ?>') no-repeat center;
      background-size: 35%; opacity: 0.04; pointer-events: none; z-index: -1;
    }
    .top { 
      display: flex; justify-content: space-between; align-items: center; 
      margin-bottom: 32px; padding: 24px 32px; border-radius: 20px; 
      border: 1px solid #dbe5f3; background: rgba(255,255,255,0.9); 
      backdrop-filter: blur(10px); box-shadow: 0 10px 30px rgba(0,0,0,0.05); 
      width: 100%;
    }
    .header-info h1 { font-size: 24px; font-weight: 800; color: #1e293b; margin: 0; text-align: left; }
    .header-info p { font-size: 15px; color: #64748b; margin: 6px 0 0 0; font-weight: 500; text-align: left; }
    
    /* Live Clock - Super Admin Style */
    .header-top { display: flex; justify-content: flex-end; margin-bottom: 20px; width: 100%; max-width: 1600px; }
    #liveClock { 
      min-width: 250px;
      text-align: left;
      line-height: 1.1;
      border-left: 3px solid #3b82f6;
      padding-left: 20px;
    }
    #clockDate { font-size: 15px; color: #64748b; font-weight: 600; margin-bottom: 4px; white-space: nowrap; }
    #clockTime { color: #2563eb; font-size: 28px; font-weight: 800; font-variant-numeric: tabular-nums; white-space: nowrap; }

    .panel { background: #ffffff; border: 1px solid #e2e8f0; border-radius: 20px; padding: 32px; width: 100%; box-shadow: 0 4px 25px rgba(0, 0, 0, 0.05); }
    .panel-title { font-size: 20px; font-weight: 800; color: #1e3a8a; margin-bottom: 24px; display: flex; align-items: center; gap: 12px; border-bottom: 1px solid #f1f5f9; padding-bottom: 16px; }
    .panel-title i { color: #3b82f6; font-size: 24px; }
    
    .alert-grid { 
      display: grid; grid-template-columns: 600px 1fr; gap: 40px; align-items: start; 
      width: 100%;
    }
    
    .form-group { margin-bottom: 24px; }
    label { font-size: 14px; font-weight: 700; color: #64748b; margin-bottom: 10px; display: block; text-transform: uppercase; letter-spacing: 0.8px; }
    input, select, textarea { 
      width: 100%; padding: 16px 18px; border: 1px solid #e2e8f0; border-radius: 14px; 
      font-family: inherit; background: #f8fafc; font-size: 16px; transition: all 0.2s;
    }
    input:focus, select:focus, textarea:focus { border-color: #3b82f6; background: #fff; outline: none; box-shadow: 0 0 0 5px rgba(59, 130, 246, 0.15); }
    textarea { resize: vertical; min-height: 180px; }
    
    button.btn-primary { 
      background: #3b82f6; color: #fff; padding: 18px 32px; border-radius: 14px; 
      border: none; cursor: pointer; font-weight: 800; font-size: 16px; width: 100%;
      transition: all 0.3s; box-shadow: 0 6px 15px rgba(59, 130, 246, 0.3);
      text-transform: uppercase; letter-spacing: 1px;
    }
    button.btn-primary:hover { background: #2563eb; transform: translateY(-3px); box-shadow: 0 8px 20px rgba(37, 99, 235, 0.4); }
    
    /* History Table Styles */
    .history-header { display: flex; justify-content: space-between; align-items: center; margin-bottom: 24px; flex-wrap: wrap; gap: 20px; }
    .history-search { max-width: 400px; position: relative; width: 100%; }
    .history-search i { position: absolute; left: 16px; top: 50%; transform: translateY(-50%); color: #94a3b8; font-size: 18px; }
    .history-search input { padding-left: 48px; }
    
    .alert-table th { background: #f8fafc; padding: 22px 24px; text-align: left; font-size: 14px; font-weight: 800; color: #64748b; text-transform: uppercase; border-bottom: 2px solid #e2e8f0; }
    .alert-table td { padding: 24px; border-bottom: 1px solid #f1f5f9; font-size: 16px; color: #1e293b; }
    
    .channel-badge { padding: 6px 12px; border-radius: 8px; font-size: 12px; font-weight: 900; text-transform: uppercase; }
    .badge-staff { background: #eff6ff; color: #3b82f6; }
    .badge-barangay { background: #f0fdf4; color: #10b981; }
    .badge-all { background: #fef2f2; color: #ef4444; }
  </style>
</head>
<body>
  <div class="layout">
    <aside class="sidebar">
      <div class="brand">
        <img src="<?= htmlspecialchars(asset_url('images/SilayLogo.png'), ENT_QUOTES) ?>" alt="Logo" style="width: 30px; height: 30px;">
        <span>Enrique B. Magalona</span>
      </div>

      <div class="nav-title">Navigation</div>
      
      <a class="nav-link" href="<?= (($_GET['from'] ?? '') === 'pdao') ? '/pdao-admin-dashboard' : '/Analytics' ?>">
        <i class="fas <?= (($_GET['from'] ?? '') === 'pdao') ? 'fa-wheelchair' : 'fa-users' ?>"></i>
        <span><?= (($_GET['from'] ?? '') === 'pdao') ? 'Person With Disability' : 'Senior Citizens' ?></span>
      </a>
      
      <a class="nav-link active" href="/admin-alert">
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
          <h1>System Alert Management</h1>
          <p>Send secure notifications to staff and barangay users</p>
        </div>
      </div>

      <div class="alert-grid">
        <div class="alert-column-left">
          <div class="panel">
            <h2 class="panel-title">
              <i class="fas fa-paper-plane"></i>
              <span>Create New Alert</span>
            </h2>
            <form id="alertForm">
              <div class="form-group">
                <label for="room">Alert Channel</label>
                <select id="room" name="room" required>
                  <option value="">-- Select Channel --</option>
                  <option value="staff">Staff Users</option>
                  <option value="barangay">Barangay Users</option>
                  <option value="all">Broadcast to All</option>
                </select>
              </div>

              <div id="staffPicker" class="form-group" style="display:none;">
                <label>Select Specific Staff</label>
                <div id="staffCheckboxes" style="max-height:180px; overflow:auto; border:1px solid #e2e8f0; padding:12px; border-radius:12px; background: #f8fafc;"></div>
              </div>

              <div id="barangayPicker" class="form-group" style="display:none;">
                <label>Select Specific Barangays</label>
                <div id="barangayCheckboxes" style="max-height:180px; overflow:auto; border:1px solid #e2e8f0; padding:12px; border-radius:12px; background: #f8fafc;">
                  <div style="margin-bottom:8px; display: flex; align-items: center; gap: 8px;">
                    <input type="checkbox" id="g_all_barangay" value="__all_barangay__" data-group="true" style="width: auto;">
                    <label for="g_all_barangay" style="margin: 0;">Select All Barangays</label>
                  </div>
                  <hr style="margin: 8px 0; border-color: #e2e8f0;">
                  <div id="barangayItems"></div>
                </div>
              </div>

              <div class="form-group">
                <label for="subject">Subject (Optional)</label>
                <input type="text" id="subject" name="subject" placeholder="Enter alert subject...">
              </div>

              <div class="form-group">
                <label for="message">Alert Message</label>
                <textarea id="message" name="message" placeholder="Type your notification message here..." required></textarea>
              </div>

              <div class="form-group" style="margin-bottom: 0;">
                <button type="submit" class="btn btn-primary">
                  <i class="fas fa-bullhorn mr-2"></i>
                  Send Alert
                </button>
              </div>
            </form>

            <div id="response" style="margin-top: 16px; display: none;">
              <pre id="responseOutput" style="border-radius: 12px; font-size: 13px; font-weight: 600;"></pre>
            </div>
          </div>
        </div>

        <div class="alert-column-right">
          <div class="panel">
            <div class="history-header">
              <h2 class="panel-title" style="margin-bottom: 0; border-bottom: 0; padding-bottom: 0;">
                <i class="fas fa-history"></i>
                <span>Alert History</span>
              </h2>
              <div class="history-search">
                <i class="fas fa-search"></i>
                <input type="text" id="historySearch" placeholder="Filter alerts...">
              </div>
            </div>
            
            <div class="table-responsive" style="margin-top: 0; border-radius: 12px; border: 1px solid #f1f5f9;">
              <table class="alert-table">
                <thead>
                  <tr>
                    <th>Channel</th>
                    <th>Type</th>
                    <th>Subject</th>
                    <th>Sent At</th>
                  </tr>
                </thead>
                <tbody id="historyBody">
                  <?php if (!empty($history)): ?>
                    <?php foreach ($history as $row): ?>
                      <?php 
                        $room = $row['target_role'] ?? 'staff';
                        $badgeClass = 'badge-all';
                        if($room === 'staff') $badgeClass = 'badge-staff';
                        if($room === 'barangay') $badgeClass = 'badge-barangay';
                      ?>
                      <tr>
                        <td><span class="channel-badge <?= $badgeClass ?>"><?= htmlspecialchars($room) ?></span></td>
                        <td>Manual</td>
                        <td style="font-weight: 600;"><?= htmlspecialchars($row['subject'] ?? '—') ?></td>
                        <td style="color: #64748b;"><?= htmlspecialchars(date('m/d/Y, h:i:s A', strtotime($row['created_at']))) ?></td>
                      </tr>
                    <?php endforeach; ?>
                  <?php else: ?>
                    <tr>
                      <td colspan="4" style="padding: 40px; text-align: center; color: #94a3b8; font-weight: 600;">
                        <i class="fas fa-inbox fa-2x mb-3 d-block"></i>
                        No alert history recorded yet
                      </td>
                    </tr>
                  <?php endif; ?>
                </tbody>
              </table>
            </div>
          </div>
        </div>
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
      var __pagePort = String(window.location.port || '');
      const portsToTry = ['3000', '8080'].filter(function (p) { return String(p) !== __pagePort; });
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
      if (tbody.querySelector('td') && tbody.querySelector('td').textContent.includes('No alert history yet')) {
        tbody.innerHTML = '';
      }
      const tr = document.createElement('tr');
      const now = new Date().toLocaleString();
      const type = 'Manual';
      const subject = document.getElementById('subject').value || '—';
      
      let badgeClass = 'badge-all';
      if(room === 'staff') badgeClass = 'badge-staff';
      if(room === 'barangay') badgeClass = 'badge-barangay';

      tr.innerHTML = `
        <td><span class="channel-badge ${badgeClass}">${room}</span></td>
        <td>${escapeHtml(type)}</td>
        <td style="font-weight: 600;">${escapeHtml(subject)}</td>
        <td style="color: #64748b;">${escapeHtml(now)}</td>
      `;
      tbody.insertBefore(tr, tbody.firstChild);
    }

    // Live filtering for history
    document.getElementById('historySearch').addEventListener('input', function(e) {
      const filter = e.target.value.toLowerCase();
      const rows = document.querySelectorAll('#historyBody tr');
      rows.forEach(row => {
        const text = row.textContent.toLowerCase();
        row.style.display = text.includes(filter) ? '' : 'none';
      });
    });

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
    });

    function escapeHtml(s) {
      if (s == null) return '';
      return String(s).replace(/[&<>"']/g, function (c) { return { '&': '&amp;', '<': '&lt;', '>': '&gt;', '"': '&quot;', "'": '&#39;' }[c]; });
    }
  </script>
</body>
</html>

