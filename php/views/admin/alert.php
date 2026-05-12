<!doctype html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width,initial-scale=1">
  <link rel="icon" type="image/png" href="<?= htmlspecialchars(asset_url('images/SilayLogo.png'), ENT_QUOTES) ?>">
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
      font-size: 12px; 
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
      font-size: 17px !important; 
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
    
    .alert-table th { background: #f8fafc; padding: 22px 24px; text-align: left; font-size: 16px; font-weight: 800; color: #64748b; text-transform: uppercase; border-bottom: 2px solid #e2e8f0; }
    .alert-table td { padding: 24px; border-bottom: 1px solid #f1f5f9; font-size: 18px; color: #1e293b; }
    
    .channel-badge { padding: 6px 12px; border-radius: 8px; font-size: 12px; font-weight: 900; text-transform: uppercase; }
    .badge-staff { background: #eff6ff; color: #3b82f6; }
    .badge-barangay { background: #f0fdf4; color: #10b981; }
    .badge-all { background: #fef2f2; color: #ef4444; }

    /* New Advanced UI Styles */
    .history-toolbar { display: flex; align-items: center; gap: 12px; margin-bottom: 24px; background: #f8fafc; padding: 12px 20px; border-radius: 14px; border: 1px solid #e2e8f0; }
    .toolbar-actions { display: flex; gap: 8px; margin-left: auto; }
    .btn-icon { background: white; border: 1px solid #e2e8f0; padding: 8px; border-radius: 10px; color: #64748b; cursor: pointer; transition: all 0.2s; width: 38px; height: 38px; display: flex; align-items: center; justify-content: center; }
    .btn-icon:hover { background: #f1f5f9; color: #3b82f6; border-color: #3b82f6; transform: translateY(-1px); }
    .btn-icon.danger:hover { color: #ef4444; border-color: #ef4444; background: #fef2f2; }
    
    .toggle-wrapper { display: flex; align-items: center; gap: 8px; font-size: 13px; font-weight: 700; color: #64748b; }
    .switch { position: relative; display: inline-block; width: 34px; height: 20px; }
    .switch input { opacity: 0; width: 0; height: 0; }
    .slider { position: absolute; cursor: pointer; top: 0; left: 0; right: 0; bottom: 0; background-color: #cbd5e1; transition: .4s; border-radius: 34px; }
    .slider:before { position: absolute; content: ""; height: 14px; width: 14px; left: 3px; bottom: 3px; background-color: white; transition: .4s; border-radius: 50%; }
    input:checked + .slider { background-color: #3b82f6; }
    input:checked + .slider:before { transform: translateX(14px); }

    .channel-select { width: 160px; padding: 8px 12px; font-size: 13px; font-weight: 600; border-radius: 10px; background: white; border: 1px solid #e2e8f0; }
    
    .alert-table { width: 100%; border-collapse: separate; border-spacing: 0; }
    .alert-table tr { transition: all 0.2s; }
    .alert-table tr:hover { background-color: #f8fafc; }
    .alert-table th { position: sticky; top: 0; z-index: 10; }
    
    .row-actions { transition: all 0.2s; display: flex; gap: 8px; justify-content: flex-end; }
    .btn-action { width: 32px; height: 32px; border-radius: 8px; border: 1px solid #e2e8f0; background: white; color: #64748b; display: flex; align-items: center; justify-content: center; cursor: pointer; font-size: 13px; transition: all 0.2s; }
    .btn-action:hover { border-color: #3b82f6; color: #3b82f6; background: #eff6ff; }
    .btn-action.delete:hover { border-color: #ef4444; color: #ef4444; background: #fef2f2; }

    .bulk-checkbox { width: 18px !important; height: 18px !important; margin: 0 !important; cursor: pointer; accent-color: #3b82f6; }
    .col-cb { width: 40px; text-align: center !important; }
    .col-actions { width: 120px; text-align: right !important; }
    
    .history-card-header { padding: 0 0 20px 0; display: flex; justify-content: space-between; align-items: center; }
    .history-card-header h2 { font-size: 18px; font-weight: 800; color: #1e3a8a; margin: 0; }
  </style>
</head>
<body>
  <div class="layout">
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
            <div class="history-card-header">
              <h2><i class="fas fa-history mr-2"></i>Alert History</h2>
              <div class="toggle-wrapper">
                <span>Auto-Refresh</span>
                <label class="switch">
                  <input type="checkbox" id="autoRefreshToggle">
                  <span class="slider"></span>
                </label>
              </div>
            </div>

            <div class="history-toolbar">
              <select id="channelFilter" class="channel-select">
                <option value="all">All Channels</option>
                <option value="staff">Staff Only</option>
                <option value="barangay">Barangay Only</option>
              </select>
              
              <div class="history-search" style="margin-bottom: 0;">
                <i class="fas fa-search"></i>
                <input type="text" id="historySearch" placeholder="Search alerts...">
              </div>

              <div class="toolbar-actions">
                <button class="btn-icon" id="refreshBtn" title="Refresh Now">
                  <i class="fas fa-sync-alt"></i>
                </button>
                <button class="btn-icon" id="exportBtn" title="Export to CSV">
                  <i class="fas fa-file-export"></i>
                </button>
                <button class="btn-icon danger" id="clearBtn" title="Clear History">
                  <i class="fas fa-trash-alt"></i>
                </button>
              </div>
            </div>
            
            <div class="table-responsive" style="margin-top: 0; border-radius: 12px; border: 1px solid #f1f5f9; max-height: 600px;">
              <table class="alert-table">
                <thead>
                  <tr>
                    <th class="col-cb"><input type="checkbox" id="selectAll" class="bulk-checkbox"></th>
                    <th>Channel</th>
                    <th>Sender</th>
                    <th>Subject</th>
                    <th>Sent At</th>
                    <th class="col-actions">Actions</th>
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
                        $id = $row['id'] ?? 0;
                      ?>
                      <tr data-id="<?= $id ?>" data-channel="<?= htmlspecialchars($room) ?>" data-subject="<?= htmlspecialchars($row['subject'] ?? '') ?>" data-message="<?= htmlspecialchars($row['message'] ?? '') ?>">
                        <td class="col-cb"><input type="checkbox" class="bulk-checkbox row-select"></td>
                        <td><span class="channel-badge <?= $badgeClass ?>"><?= htmlspecialchars($room) ?></span></td>
                        <td style="color: #64748b; font-size: 14px; font-weight: 600;"><?= htmlspecialchars($row['created_by_name'] ?? 'Admin') ?></td>
                        <td style="font-weight: 600;"><?= htmlspecialchars($row['subject'] ?? '—') ?></td>
                        <td style="color: #64748b; font-size: 13px;"><?= htmlspecialchars(date('m/d/Y, h:i:s A', strtotime($row['created_at']))) ?></td>
                        <td class="col-actions">
                          <div class="row-actions">
                            <button class="btn-action view-alert" title="View Details"><i class="fas fa-eye"></i></button>
                            <button class="btn-action resend-alert" title="Resend Alert"><i class="fas fa-redo"></i></button>
                            <button class="btn-action delete-alert danger" title="Delete Alert"><i class="fas fa-trash-alt"></i></button>
                          </div>
                        </td>
                      </tr>
                    <?php endforeach; ?>
                  <?php else: ?>
                    <tr>
                      <td colspan="6" style="padding: 40px; text-align: center; color: #94a3b8; font-weight: 600;">
                        <i class="fas fa-inbox fa-2x mb-3 d-block"></i>
                        No alert history recorded yet
                      </td>
                    </tr>
                  <?php endif; ?>
                </tbody>
              </table>
            </div>
            <p style="font-size: 12px; color: #94a3b8; font-weight: 600; margin-top: 16px; text-align: center;">
              <i class="fas fa-info-circle mr-1"></i>
              Alert history is automatically cleared after 24 hours
            </p>
          </div>
        </div>
      </div>
    </main>
  </div>

  <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
  <script src="https://cdn.socket.io/4.6.1/socket.io.min.js"></script>
  <script>
    const currentUserName = <?= json_encode(($_SESSION['user']['name'] ?? $_SESSION['user']['email'] ?? 'Admin')) ?>;

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

    // Alert History Logic
    let autoRefreshInterval = null;

    function startAutoRefresh() {
      if (autoRefreshInterval) clearInterval(autoRefreshInterval);
      autoRefreshInterval = setInterval(() => {
        refreshHistory(true);
      }, 10000); // refresh every 10s
    }

    function stopAutoRefresh() {
      if (autoRefreshInterval) clearInterval(autoRefreshInterval);
      autoRefreshInterval = null;
    }

    async function refreshHistory(isAuto = false) {
      try {
        const res = await fetch('/admin-alert'); // This might be better as an API call, but we'll use the current page fetch or specialized API if available
        // Since we don't have a specialized history API yet that returns just JSON for the admin history,
        // we'll fetch the whole page and extract the tbody or just reload the data if we had an API.
        // For now, let's assume we want to refresh the table.
        
        const response = await fetch('/api/notifications'); // Wait, /api/notifications is for users.
        // Let's use a full page reload or a specialized endpoint.
        // Actually, let's just use window.location.reload() for now or fetch the page and swap the tbody.
        if (!isAuto) {
          const btn = document.getElementById('refreshBtn');
          if (btn) btn.innerHTML = '<i class="fas fa-spinner fa-spin"></i>';
        }

        const res2 = await fetch(window.location.href);
        const html = await res2.text();
        const parser = new DOMParser();
        const doc = parser.parseFromString(html, 'text/html');
        const newBody = doc.getElementById('historyBody');
        if (newBody) {
          document.getElementById('historyBody').innerHTML = newBody.innerHTML;
          applyFilters(); // re-apply search/channel filters
        }

        if (!isAuto) {
          const btn = document.getElementById('refreshBtn');
          if (btn) btn.innerHTML = '<i class="fas fa-sync-alt"></i>';
        }
      } catch (err) {
        console.error('Refresh failed', err);
      }
    }

    function applyFilters() {
      const search = document.getElementById('historySearch').value.toLowerCase();
      const channel = document.getElementById('channelFilter').value;
      const rows = document.querySelectorAll('#historyBody tr');
      
      rows.forEach(row => {
        if (row.querySelector('td[colspan]')) return; // skip empty state
        const text = row.textContent.toLowerCase();
        const rowChannel = row.dataset.channel;
        
        const matchesSearch = text.includes(search);
        const matchesChannel = channel === 'all' || rowChannel === channel;
        
        row.style.display = (matchesSearch && matchesChannel) ? '' : 'none';
      });
    }

    // Event Listeners for History
    document.getElementById('autoRefreshToggle').addEventListener('change', function() {
      if (this.checked) startAutoRefresh();
      else stopAutoRefresh();
    });

    document.getElementById('channelFilter').addEventListener('change', applyFilters);
    document.getElementById('historySearch').addEventListener('input', applyFilters);
    document.getElementById('refreshBtn').addEventListener('click', () => refreshHistory());

    document.getElementById('selectAll').addEventListener('change', function() {
      const checked = this.checked;
      document.querySelectorAll('.row-select').forEach(cb => {
        if (cb.closest('tr').style.display !== 'none') cb.checked = checked;
      });
    });

    document.getElementById('exportBtn').addEventListener('click', async function() {
      const rows = Array.from(document.querySelectorAll('#historyBody tr:not([style*="display: none"])'));
      if (rows.length === 0 || rows[0].querySelector('td[colspan]')) {
        Swal.fire('No data', 'Nothing to export', 'info');
        return;
      }

      const now = new Date();
      const currentYear = now.getFullYear();
      const months = ['January','February','March','April','May','June','July','August','September','October','November','December'];
      
      let monthOptions = '';
      months.forEach((m, i) => {
        monthOptions += `<option value="${m}" ${i === now.getMonth() ? 'selected' : ''}>${m}</option>`;
      });

      const { value: formValues } = await Swal.fire({
        title: 'Authorize Export',
        html: `
          <div style="text-align: left; padding: 5px;">
            <div style="margin-bottom: 24px;">
              <label style="display: block; font-size: 13px; font-weight: 800; color: #64748b; margin-bottom: 10px; text-transform: uppercase; letter-spacing: 0.5px;">1. Report Period</label>
              <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 12px;">
                <select id="swal-month" class="swal2-select" style="margin: 0; width: 100%; height: 50px; border-radius: 12px; border: 1px solid #e2e8f0; font-size: 15px; font-weight: 600;">${monthOptions}</select>
                <input id="swal-year" type="number" class="swal2-input" value="${currentYear}" style="margin: 0; width: 100%; height: 50px; border-radius: 12px; border: 1px solid #e2e8f0; font-size: 15px; font-weight: 600;">
              </div>
            </div>
            
            <div style="background: #f8fafc; padding: 20px; border-radius: 16px; border: 1px solid #e2e8f0;">
              <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 12px;">
                <label style="display: block; font-size: 13px; font-weight: 800; color: #ef4444; text-transform: uppercase; letter-spacing: 0.5px;">2. Identity Verification</label>
                <button id="send-code-btn" type="button" style="padding: 8px 14px; font-size: 12px; font-weight: 800; background: #fee2e2; color: #ef4444; border: 1px solid #fecaca; border-radius: 10px; cursor: pointer; transition: all 0.2s;">Get Code</button>
              </div>
              <input id="swal-code" type="text" maxlength="6" class="swal2-input" placeholder="••••••" style="margin: 0; width: 100%; height: 55px; border-radius: 12px; border: 1px solid #e2e8f0; text-align: center; font-size: 24px; font-weight: 800; letter-spacing: 8px; color: #1e293b;">
              <p id="code-status-msg" style="font-size: 12px; color: #64748b; margin: 10px 0 0 0; text-align: center; font-weight: 600;"></p>
            </div>
          </div>
        `,
        focusConfirm: false,
        showCancelButton: true,
        confirmButtonText: 'Validate & Download',
        confirmButtonColor: '#3b82f6',
        didOpen: () => {
          const sendBtn = document.getElementById('send-code-btn');
          const statusMsg = document.getElementById('code-status-msg');
          
          sendBtn.addEventListener('click', async function() {
            sendBtn.disabled = true;
            sendBtn.textContent = 'Sending...';
            statusMsg.textContent = 'Contacting security server...';
            
            try {
              const res = await fetch('/api/notifications/send-export-code', { 
                method: 'POST',
                headers: { 'Accept': 'application/json' }
              });
              
              const contentType = res.headers.get('content-type');
              if (!contentType || !contentType.includes('application/json')) {
                const text = await res.text();
                console.error('Server returned non-JSON:', text);
                throw new Error('Server error: Please check your SMTP configuration in the .env file.');
              }
              
              const data = await res.json();
              if (data.success) {
                statusMsg.style.color = '#10b981';
                statusMsg.textContent = 'Check your email for the 6-digit code.';
                sendBtn.textContent = 'Sent!';
                sendBtn.style.background = '#dcfce7';
                sendBtn.style.color = '#10b981';
                sendBtn.style.borderColor = '#bbf7d0';
              } else {
                throw new Error(data.message || 'Failed to send code');
              }
            } catch (err) {
              statusMsg.style.color = '#ef4444';
              statusMsg.textContent = err.message;
              sendBtn.disabled = false;
              sendBtn.textContent = 'Retry';
              console.error('Export Error:', err);
            }
          });
        },
        preConfirm: async () => {
          const code = document.getElementById('swal-code').value;
          if (!code || code.length < 6) {
            Swal.showValidationMessage('Please enter the 6-digit verification code');
            return false;
          }
          
          try {
            const res = await fetch('/api/notifications/verify-export-code', {
              method: 'POST',
              headers: { 
                'Content-Type': 'application/json',
                'Accept': 'application/json'
              },
              body: JSON.stringify({ code })
            });
            
            const contentType = res.headers.get('content-type');
            if (!contentType || !contentType.includes('application/json')) {
              throw new Error('Security server returned an invalid response.');
            }
            
            const data = await res.json();
            if (!data.success) {
              Swal.showValidationMessage(data.message || 'Invalid or expired code');
              return false;
            }
            
            return {
              month: document.getElementById('swal-month').value,
              year: document.getElementById('swal-year').value
            };
          } catch (err) {
            Swal.showValidationMessage(err.message);
            return false;
          }
        }
      });

      if (!formValues) return;

      let csv = `Alert History Report - ${formValues.month} ${formValues.year}\n`;
      csv += 'Channel,Sender,Subject,Sent At\n';
      
      rows.forEach(row => {
        const cols = row.querySelectorAll('td');
        if (cols.length < 5) return;
        const channel = cols[1].textContent.trim();
        const sender = cols[2].textContent.trim();
        const subject = cols[3].textContent.trim();
        const date = cols[4].textContent.trim();
        csv += `"${channel}","${sender}","${subject}","${date}"\n`;
      });

      const blob = new Blob([csv], { type: 'text/csv' });
      const url = URL.createObjectURL(blob);
      const a = document.createElement('a');
      a.href = url;
      a.download = `alert_report_${formValues.month}_${formValues.year}.csv`;
      a.click();
    });

    document.getElementById('clearBtn').addEventListener('click', async function() {
      const selected = Array.from(document.querySelectorAll('.row-select:checked')).map(cb => cb.closest('tr').dataset.id);
      
      const result = await Swal.fire({
        title: selected.length > 0 ? `Delete ${selected.length} alerts?` : 'Clear all history?',
        text: "This action cannot be undone!",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#ef4444',
        confirmButtonText: 'Yes, delete!'
      });

      if (result.isConfirmed) {
        if (selected.length === 0) {
           // Clear all history
           try {
             const res = await fetch('/api/notifications/delete-all', { method: 'POST' });
             if (res.ok) {
               Swal.fire('Cleared!', 'All alert history has been removed.', 'success');
               refreshHistory();
             } else {
               Swal.fire('Error', 'Failed to clear history.', 'error');
             }
           } catch (e) {
             Swal.fire('Error', 'Failed to clear history: ' + e.message, 'error');
           }
           return;
        }

        let successCount = 0;
        for (const id of selected) {
          try {
            const res = await fetch(`/api/notifications/delete/${id}`, { method: 'POST' });
            if (res.ok) successCount++;
          } catch (e) {}
        }
        
        Swal.fire('Deleted!', `${successCount} alerts removed.`, 'success');
        refreshHistory();
      }
    });

    // Row Action Delegation
    document.getElementById('historyBody').addEventListener('click', async function(e) {
      const btn = e.target.closest('.btn-action');
      if (!btn) return;
      
      const tr = btn.closest('tr');
      const id = tr.dataset.id;
      const subject = tr.dataset.subject;
      const message = tr.dataset.message;
      const channel = tr.dataset.channel;

      if (btn.classList.contains('view-alert')) {
        Swal.fire({
          title: subject || 'Notification Details',
          html: `<div style="text-align: left; padding: 10px;">
                  <p><strong>Channel:</strong> ${channel}</p>
                  <hr>
                  <p style="white-space: pre-wrap;">${message}</p>
                 </div>`,
          confirmButtonText: 'Close'
        });
      } else if (btn.classList.contains('resend-alert')) {
        document.getElementById('room').value = channel;
        document.getElementById('room').dispatchEvent(new Event('change'));
        document.getElementById('subject').value = subject;
        document.getElementById('message').value = message;
        window.scrollTo({ top: 0, behavior: 'smooth' });
        Swal.fire('Loaded', 'Alert details populated into the form.', 'success');
      } else if (btn.classList.contains('delete-alert')) {
        const result = await Swal.fire({
          title: 'Are you sure?',
          text: "Delete this alert from history?",
          icon: 'warning',
          showCancelButton: true,
          confirmButtonColor: '#ef4444',
          confirmButtonText: 'Yes, delete it!'
        });
        if (result.isConfirmed) {
          try {
            const res = await fetch(`/api/notifications/delete/${id}`, { 
              method: 'POST',
              headers: { 'Accept': 'application/json' }
            });
            
            const data = await res.json();
            if (res.ok && data.success) {
              Swal.fire('Deleted!', 'Alert has been removed.', 'success');
              refreshHistory();
            } else {
              Swal.fire('Error', data.error || data.message || 'Failed to delete alert.', 'error');
            }
          } catch (err) {
            Swal.fire('Error', 'Network error or server unavailable: ' + err.message, 'error');
          }
        }
      }
    });

    // Replace addAlertLog to support new structure
    function addAlertLog(message, room) {
      refreshHistory(); // simplest way to get the new ID and structure
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
    });

    function escapeHtml(s) {
      if (s == null) return '';
      return String(s).replace(/[&<>"']/g, function (c) { return { '&': '&amp;', '<': '&lt;', '>': '&gt;', '"': '&quot;', "'": '&#39;' }[c]; });
    }
  </script>
</body>
</html>

