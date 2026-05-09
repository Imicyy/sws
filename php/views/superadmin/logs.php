<?php date_default_timezone_set('Asia/Manila'); ?>
<!doctype html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width,initial-scale=1">
  <link rel="icon" type="image/png" href="<?= htmlspecialchars(asset_url('images/SilayLogo.png'), ENT_QUOTES) ?>">
  <title>Super Admin - System Logs</title>
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/css/bootstrap.min.css">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
  <link rel="stylesheet" href="<?= htmlspecialchars(asset_url('css/light-theme.css?v=20260424'), ENT_QUOTES) ?>">
  <style>
    * { margin: 0; padding: 0; box-sizing: border-box; }
    :root {
      --bg-page: linear-gradient(135deg, #fffde8 0%, #eef4ff 52%, #fff9d9 100%);
      --blue-primary: #3b82f6;
      --blue-dark: #1e3a8a;
      --green-success: #10b981;
      --red-failure: #ef4444;
      --gray-text: #64748b;
      --shadow-sm: 0 4px 6px -1px rgba(0,0,0,0.1), 0 2px 4px -1px rgba(0,0,0,0.06);
      --shadow-md: 0 10px 15px -3px rgba(0,0,0,0.1), 0 4px 6px -2px rgba(0,0,0,0.05);
      --shadow-lg: 0 20px 25px -5px rgba(0,0,0,0.1), 0 10px 10px -5px rgba(0,0,0,0.04);
    }
    body { font-family: 'Inter', system-ui, -apple-system, sans-serif; background: var(--bg-page); color: #1e293b; display: flex; min-height: 100vh; }
    
    /* Sidebar Styles */
    .sidebar { 
      background: linear-gradient(180deg, rgba(20, 32, 74, 0.95) 0%, rgba(35, 66, 140, 0.85) 100%), url('<?= htmlspecialchars(asset_url("images/ebmagtownhall.png"), ENT_QUOTES) ?>') center bottom/cover no-repeat; 
      background-blend-mode: normal; 
      padding: 20px 14px; 
      height: 100vh; 
      width: 260px; 
      position: fixed; 
      top: 0; left: 0; 
      display: flex; flex-direction: column; 
      z-index: 100; color: #fff; 
    }
    .brand { font-weight: 800; font-size: 13px; letter-spacing: 0.7px; margin-bottom: 24px; display: flex; align-items: center; gap: 10px; }
    .nav-title { font-size: 11px; color: rgba(255,255,255,0.5); text-transform: uppercase; margin: 12px 10px; font-weight: 700; letter-spacing: 1px; }
    .sidebar .nav-link { 
      display: flex; align-items: center; gap: 12px; padding: 12px 16px; margin-bottom: 6px; 
      border-radius: 12px; color: rgba(255,255,255,0.7) !important; text-decoration: none !important; 
      font-weight: 500; font-size: 14px; transition: all .3s; 
    }
    .sidebar .nav-link.active { background: var(--blue-primary); color: #fff !important; font-weight: 600; box-shadow: 0 4px 15px rgba(59, 130, 246, 0.4); }
    .sidebar .nav-link:hover:not(.active) { background: rgba(255, 255, 255, 0.1); color: #fff !important; transform: translateX(4px); }
    
    .logout-btn { 
      margin-top: auto; display: flex; align-items: center; justify-content: center; gap: 10px; padding: 14px; 
      border-radius: 12px; background: rgba(239, 68, 68, 0.1); color: #fecaca !important; font-weight: 700; text-decoration: none !important; 
      transition: all .3s; 
    }
    .logout-btn:hover { background: #ef4444; color: #fff !important; transform: translateY(-2px); }

    /* Main Content */
    .main-content { margin-left: 260px; flex: 1; padding: 32px; position: relative; z-index: 1; }
    .main-content::before {
      content: ""; position: fixed; top: 0; left: 260px; right: 0; bottom: 0;
      background: url('<?= htmlspecialchars(asset_url("images/SilayLogo.png"), ENT_QUOTES) ?>') no-repeat center;
      background-size: 35%; opacity: 0.04; pointer-events: none; z-index: -1;
    }

    /* Header */
    .page-header { display: flex; justify-content: space-between; align-items: flex-start; margin-bottom: 32px; }
    .page-title h1 { font-size: 28px; font-weight: 800; color: #1e3a8a; margin: 0; }
    .page-title p { color: #64748b; font-size: 15px; margin-top: 4px; }

    /* Live Clock */
    .clock-container { text-align: right; border-left: 3px solid var(--blue-primary); padding-left: 20px; }
    #clockDate { font-size: 14px; font-weight: 600; color: #64748b; margin-bottom: 2px; }
    #clockTime { font-size: 26px; font-weight: 800; color: var(--blue-dark); font-variant-numeric: tabular-nums; }

    /* Filter Bar */
    .filter-section { 
      background: #fff; padding: 24px; border-radius: 20px; border: 1px solid #e2e8f0; 
      box-shadow: var(--shadow-sm); margin-bottom: 32px; display: grid; 
      grid-template-columns: 1fr repeat(3, 180px) 150px; gap: 16px; align-items: end;
    }
    .filter-group { display: flex; flex-direction: column; gap: 8px; }
    .filter-group label { font-size: 12px; font-weight: 700; color: #64748b; text-transform: uppercase; letter-spacing: 0.5px; }
    .filter-input { 
      padding: 12px 16px; border-radius: 12px; border: 1px solid #e2e8f0; background: #f8fafc; 
      font-size: 14px; transition: all 0.2s; 
    }
    .filter-input:focus { border-color: var(--blue-primary); outline: none; background: #fff; box-shadow: 0 0 0 4px rgba(59, 130, 246, 0.1); }
    .btn-apply { 
      background: var(--blue-primary); color: #fff; padding: 12px; border-radius: 12px; 
      border: none; font-weight: 700; cursor: pointer; transition: all 0.3s;
      display: flex; align-items: center; justify-content: center; gap: 8px;
    }
    .btn-apply:hover { background: var(--blue-dark); transform: translateY(-2px); box-shadow: 0 4px 12px rgba(59, 130, 246, 0.3); }

    /* Logs Grid */
    .logs-grid { display: grid; grid-template-columns: repeat(auto-fill, minmax(450px, 1fr)); gap: 24px; margin-bottom: 32px; }
    .log-card { 
      background: #fff; border-radius: 20px; border: 1px solid #e2e8f0; 
      padding: 24px; transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1); 
      box-shadow: var(--shadow-sm); position: relative; overflow: hidden;
      display: flex; flex-direction: column; gap: 16px; cursor: pointer;
    }
    .log-card:hover { transform: translateY(-8px); box-shadow: var(--shadow-lg); border-color: var(--blue-primary); }
    .log-card::before { content: ""; position: absolute; left: 0; top: 0; width: 6px; height: 100%; background: var(--status-color, #e2e8f0); }
    
    .log-header { display: flex; justify-content: space-between; align-items: flex-start; }
    .user-info { display: flex; gap: 16px; align-items: center; }
    .user-avatar { 
      width: 48px; height: 48px; border-radius: 14px; 
      background: #f1f5f9; display: flex; align-items: center; justify-content: center; 
      font-size: 20px; color: var(--blue-dark); border: 2px solid #fff; box-shadow: var(--shadow-sm);
    }
    .user-meta h4 { font-size: 16px; font-weight: 700; color: #1e293b; margin: 0; }
    .user-meta p { font-size: 13px; color: #64748b; margin: 2px 0 0; }

    .status-badge { 
      padding: 6px 12px; border-radius: 50px; font-size: 12px; font-weight: 700; 
      display: flex; align-items: center; gap: 6px;
    }
    .status-success { color: #15803d; --status-color: #10b981; }
    .status-failed { color: #b91c1c; --status-color: #ef4444; }
    
    .status-badge.status-success { background: #dcfce7; }
    .status-badge.status-failed { background: #fee2e2; }
    
    .role-badge { 
      font-size: 11px; font-weight: 700; padding: 4px 10px; border-radius: 6px; 
      text-transform: uppercase; letter-spacing: 0.5px; margin-top: 4px; display: inline-block;
    }
    .role-super { background: #eff6ff; color: #1e40af; }
    .role-admin { background: #fdf2f8; color: #9d174d; }
    .role-staff { background: #f0fdf4; color: #166534; }
    .role-barangay { background: #fff7ed; color: #9a3412; }

    .timestamp-box { background: #f8fafc; padding: 12px; border-radius: 14px; display: flex; justify-content: space-between; align-items: center; }
    .time-exact { font-size: 13px; font-weight: 600; color: #475569; display: flex; align-items: center; gap: 6px; }
    .time-relative { font-size: 12px; font-weight: 700; color: var(--blue-primary); background: #fff; padding: 4px 10px; border-radius: 8px; border: 1px solid rgba(59, 130, 246, 0.1); }

    .card-actions { display: flex; gap: 10px; border-top: 1px solid #f1f5f9; padding-top: 16px; }
    .btn-action { 
      flex: 1; padding: 12px; border-radius: 12px; border: 1px solid #e2e8f0; 
      font-size: 14px; font-weight: 700; display: flex; align-items: center; justify-content: center; gap: 8px;
      transition: all 0.2s; cursor: pointer; background: #fff;
    }
    .btn-view { color: var(--blue-primary); }
    .btn-view:hover { background: #eff6ff; border-color: var(--blue-primary); }

    /* Expandable Details */
    .details-expand { 
      max-height: 0; overflow: hidden; transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1); 
      opacity: 0; background: #f8fafc; border-radius: 14px;
    }
    .log-card.expanded .details-expand { max-height: 200px; opacity: 1; margin-top: 8px; padding: 16px; }
    .detail-item { display: flex; justify-content: space-between; margin-bottom: 8px; border-bottom: 1px dashed #e2e8f0; padding-bottom: 8px; }
    .detail-item:last-child { margin-bottom: 0; border-bottom: none; }
    .detail-label { font-size: 11px; font-weight: 700; color: #94a3b8; text-transform: uppercase; }
    .detail-value { font-size: 13px; font-weight: 600; color: #1e293b; }

    /* Pagination */
    .pagination-wrap { 
      display: flex; justify-content: space-between; align-items: center; 
      margin-top: 30px; padding: 20px 24px; 
      background: #f8fafc; border-top: 1px solid #e2e8f0; 
      border-radius: 20px; box-shadow: var(--shadow-sm);
    }
    .pagination-info { font-size: 14px; color: #64748b; font-weight: 600; }
    .pagination-btns { display: flex; gap: 8px; }
    
    .btn-pagination-blue { 
      background: #eff6ff; color: #1e40af; border: 1px solid #dbeafe !important; 
      padding: 8px 16px; border-radius: 8px; font-weight: 700; transition: all 0.2s; font-size: 13px; 
    }
    .btn-pagination-blue:hover { background: #3b82f6 !important; color: #fff !important; border-color: #3b82f6 !important; }
    .btn-pagination-blue.active { background: #2563eb !important; color: #fff !important; border-color: #2563eb !important; }

    .btn-pagination-yellow { 
      background: #fffbeb; color: #854d0e; border: 1px solid #fef3c7 !important; 
      padding: 8px 16px; border-radius: 8px; font-weight: 700; transition: all 0.2s; font-size: 13px; 
    }
    .btn-pagination-yellow:hover { background: #f59e0b !important; color: #fff !important; border-color: #f59e0b !important; }
    .btn-pagination-yellow:disabled { opacity: 0.5; cursor: not-allowed; }

    /* Modal */
    .modal { 
      display: none; position: fixed; z-index: 1050; left: 0; top: 0; width: 100%; height: 100%; 
      background: rgba(15, 23, 42, 0.6); backdrop-filter: blur(8px); 
      align-items: center; justify-content: center; 
    }
    .modal.active { display: flex; }
    .modal-content { 
      background: #fff; border-radius: 24px; width: 90%; max-width: 800px; padding: 32px; 
      box-shadow: var(--shadow-lg); border: 1px solid rgba(255,255,255,0.2); position: relative;
    }
    .close-modal { position: absolute; top: 24px; right: 24px; font-size: 24px; color: #94a3b8; cursor: pointer; transition: all 0.2s; }
    .close-modal:hover { color: #1e293b; transform: rotate(90deg); }
    
    .modal-section { margin-top: 24px; }
    .section-label { font-size: 12px; font-weight: 800; color: #64748b; text-transform: uppercase; letter-spacing: 1px; margin-bottom: 12px; display: block; }
    .data-container { background: #f8fafc; border: 1px solid #e2e8f0; border-radius: 16px; padding: 16px; max-height: 250px; overflow-y: auto; }

    @media (max-width: 1024px) {
      .filter-section { grid-template-columns: 1fr 1fr; }
      .btn-apply { grid-column: span 2; }
      .logs-grid { grid-template-columns: 1fr; }
    }
  </style>
</head>
<body>
  <!-- Sidebar Navigation -->
  <aside class="sidebar">
    <div class="brand">
      <img src="<?= htmlspecialchars(asset_url('images/SilayLogo.png'), ENT_QUOTES) ?>" alt="Logo" style="height: 32px; width: 32px; object-fit: contain;">
      <span>ENRIQUE B. MAGALONA</span>
    </div>
    
    <div class="user-name"><?= htmlspecialchars((string)($user['name'] ?? 'Super Admin'), ENT_QUOTES, 'UTF-8') ?></div>
    
    <div class="nav-title">Main Navigation</div>
    <a href="/index-superadmin" class="nav-link">
      <i class="fas fa-th-large"></i> Dashboard
    </a>
    <a href="/superadmin-users" class="nav-link">
      <i class="fas fa-users"></i> User Management
    </a>
    <a href="/superadmin-logs" class="nav-link active">
      <i class="fas fa-history"></i> System Logs
    </a>
    
    <a class="logout-btn" href="/logout">
      <i class="fas fa-sign-out-alt"></i> Logout
    </a>
  </aside>

  <div class="main-content">
    <!-- Header Area -->
    <header class="page-header">
      <div class="page-title">
        <h1>System Logs</h1>
        <p>Monitor system activity and login attempts</p>
      </div>
      <div class="clock-container">
        <div id="clockDate"></div>
        <div id="clockTime"></div>
      </div>
    </header>

    <!-- Filter Bar -->
    <section class="filter-section">
      <div class="filter-group">
        <label for="loginSearch">Search Account</label>
        <input type="text" id="loginSearch" class="filter-input" placeholder="Search by name or email...">
      </div>
      <div class="filter-group">
        <label for="roleFilter">User Role</label>
        <select id="roleFilter" class="filter-input">
          <option value="">All Roles</option>
          <option value="Super Admin">Super Admin</option>
          <option value="Admin">Admin</option>
          <option value="Staff">Staff</option>
          <option value="Barangay">Barangay</option>
        </select>
      </div>
      <div class="filter-group">
        <label for="statusFilter">Status</label>
        <select id="statusFilter" class="filter-input">
          <option value="">All Status</option>
          <option value="success">Success</option>
          <option value="failed">Failed</option>
        </select>
      </div>
      <div class="filter-group">
        <label for="loginDate">Date</label>
        <input type="date" id="loginDate" class="filter-input">
      </div>
      <button class="btn-apply" onclick="filterLogs()">
        <i class="fas fa-filter"></i> Apply Filters
      </button>
    </section>

    <!-- Logs Container -->
    <div class="logs-grid" id="logsContainer">
      <?php if (!empty($loginLogs) && is_array($loginLogs)): ?>
        <?php foreach ($loginLogs as $l): 
          $isSuccess = strpos(strtolower($l['login_result'] ?? ''), 'success') !== false;
          $uRole = htmlspecialchars((string)($l['user_role'] ?? ''), ENT_QUOTES, 'UTF-8');
          $roleClass = 'role-staff';
          if ($uRole === 'Super Admin') $roleClass = 'role-super';
          else if ($uRole === 'Admin') $roleClass = 'role-admin';
          else if ($uRole === 'Barangay') $roleClass = 'role-barangay';

          // High-Precision Time Correction (UTC Database -> Local Manila)
          $uTimeRaw = $l['created_at'] ?? date('Y-m-d H:i:s');
          try {
              // We assume the database stores time in UTC
              $dateObj = new DateTime($uTimeRaw, new DateTimeZone('UTC'));
              $dateObj->setTimezone(new DateTimeZone('Asia/Manila'));
              $uTimeISO = $dateObj->format('c'); 
          } catch (Exception $e) {
              $uTimeISO = date('c');
          }

          $uName = htmlspecialchars((string)($l['user_name'] ?? 'Unknown User'), ENT_QUOTES, 'UTF-8');
          $uEmail = htmlspecialchars((string)($l['user_email'] ?? ''), ENT_QUOTES, 'UTF-8');
          $uInitial = !empty($uName) ? strtoupper(substr($uName, 0, 1)) : '?';
          
          $uIp = htmlspecialchars((string)($l['ip_address'] ?? 'N/A'), ENT_QUOTES, 'UTF-8');
          $uDevice = htmlspecialchars((string)($l['device_info'] ?? 'Unknown Device'), ENT_QUOTES, 'UTF-8');
          $uLocation = htmlspecialchars((string)($l['location'] ?? 'Unknown Location'), ENT_QUOTES, 'UTF-8');
        ?>
          <div class="log-card <?= $isSuccess ? 'status-success' : 'status-failed' ?>" 
               data-role="<?= $uRole ?>" 
               data-status="<?= $isSuccess ? 'success' : 'failed' ?>"
               data-email="<?= strtolower($uEmail) ?>"
               data-name="<?= strtolower($uName) ?>"
               onclick="this.classList.toggle('expanded')">
            
            <div class="log-header">
              <div class="user-info">
                <div class="user-avatar"><?= $uInitial ?></div>
                <div class="user-meta">
                  <h4><?= $uName ?></h4>
                  <p><?= $uEmail ?></p>
                  <span class="role-badge <?= $roleClass ?>"><?= $uRole ?></span>
                </div>
              </div>
              <div class="status-badge <?= $isSuccess ? 'status-success' : 'status-failed' ?>">
                <i class="fas <?= $isSuccess ? 'fa-check-circle' : 'fa-times-circle' ?>"></i>
                <?= $isSuccess ? 'Success' : 'Failed' ?>
              </div>
            </div>

            <div class="timestamp-box" data-time="<?= $uTimeISO ?>">
              <div class="time-exact">
                <i class="far fa-clock"></i>
                <span class="exact-label">Loading...</span>
              </div>
              <div class="time-relative">Calculating...</div>
            </div>

            <div class="details-expand">
              <div class="detail-item">
                <span class="detail-label">IP Address</span>
                <span class="detail-value"><?= $uIp ?></span>
              </div>
              <div class="detail-item">
                <span class="detail-label">Location</span>
                <span class="detail-value"><?= $uLocation ?></span>
              </div>
              <div class="detail-item">
                <span class="detail-label">Device</span>
                <span class="detail-value"><?= $uDevice ?></span>
              </div>
            </div>

            <div class="card-actions" onclick="event.stopPropagation()">
              <button class="btn-action btn-view view-details-btn" 
                      data-user-id="<?= (int)($l['user_id'] ?? 0) ?>" 
                      data-user-email="<?= $uEmail ?>" 
                      data-user-name="<?= $uName ?>" 
                      data-log-type="login">
                <i class="fas fa-search-plus"></i> View Full Activity Report
              </button>
            </div>
          </div>
        <?php endforeach; ?>
      <?php else: ?>
        <div style="grid-column: 1/-1; text-align: center; padding: 60px; background: #fff; border-radius: 20px; border: 1px dashed #cbd5e1;">
          <i class="fas fa-clipboard-list" style="font-size: 48px; color: #e2e8f0; margin-bottom: 16px; display: block;"></i>
          <h3 style="color: #64748b; font-weight: 600;">No activity logs found</h3>
          <p style="color: #94a3b8;">Try adjusting your filters or search terms.</p>
        </div>
      <?php endif; ?>
    </div>

    <!-- Pagination -->
    <footer class="pagination-wrap">
      <div style="display: flex; align-items: center; gap: 24px;">
        <div class="pagination-info" id="paginationInfo">Showing 0-0 of 0 logs</div>
        <div style="display: flex; align-items: center; gap: 8px;">
          <label for="pageSizeSelect" style="font-size: 13px; font-weight: 700; color: #64748b; margin: 0;">Rows:</label>
          <select id="pageSizeSelect" class="filter-input" style="padding: 6px 12px; width: 80px; border-radius: 10px;">
            <option value="5">5</option>
            <option value="10" selected>10</option>
            <option value="15">15</option>
            <option value="20">20</option>
            <option value="25">25</option>
            <option value="50">50</option>
          </select>
        </div>
      </div>
      <div class="pagination-btns" id="paginationControls"></div>
    </footer>
  </div>

  <!-- View Details Modal -->
  <div id="view-details-modal" class="modal">
    <div class="modal-content">
      <i class="fas fa-times close-modal" id="view-details-close-btn"></i>
      <h3 style="font-weight: 800; color: var(--blue-dark); margin-bottom: 8px;">Activity Intelligence</h3>
      <p style="color: #64748b; font-size: 14px; margin-bottom: 24px;" id="view-user-info-text"></p>
      
      <div class="modal-section">
        <span class="section-label"><i class="fas fa-edit"></i> Edit History (Last 24h)</span>
        <div class="data-container" id="edit-logs-content">Loading data...</div>
      </div>
      
      <div class="modal-section">
        <span class="section-label"><i class="fas fa-running"></i> Interaction Logs (Last 24h)</span>
        <div class="data-container" id="user-activities-content">Loading data...</div>
      </div>

      <div style="margin-top: 32px; display: flex; justify-content: flex-end;">
        <button type="button" class="btn-apply" style="min-width: 120px;" id="view-details-close-modal-btn">Close Report</button>
      </div>
    </div>
  </div>

  <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
  <script>
    (function () {
      const logs = Array.from(document.querySelectorAll('.log-card'));
      const logsContainer = document.getElementById('logsContainer');
      const searchInput = document.getElementById('loginSearch');
      const roleFilter = document.getElementById('roleFilter');
      const statusFilter = document.getElementById('statusFilter');
      const dateFilter = document.getElementById('loginDate');
      const paginationInfo = document.getElementById('paginationInfo');
      const paginationControls = document.getElementById('paginationControls');
      const pageSizeSelect = document.getElementById('pageSizeSelect');
      
      let currentPage = 1;
      let pageSize = parseInt(pageSizeSelect.value) || 10;

      pageSizeSelect.addEventListener('change', function() {
        pageSize = parseInt(this.value);
        currentPage = 1;
        updatePagination();
      });

      function getFilteredLogs() {
        const search = searchInput.value.toLowerCase().trim();
        const role = roleFilter.value;
        const status = statusFilter.value;
        const date = dateFilter.value;

        return logs.filter(card => {
          const matchSearch = !search || card.dataset.email.includes(search) || card.dataset.name.includes(search);
          const matchRole = !role || card.dataset.role === role;
          const matchStatus = !status || card.dataset.status === status;
          
          let matchDate = true;
          if (date) {
            const cardDate = card.querySelector('.time-exact').textContent.trim();
            // Simple date check
            matchDate = cardDate.includes(new Date(date).toLocaleDateString('en-US', { month: 'short', day: '2-digit', year: 'numeric' }));
          }

          return matchSearch && matchRole && matchStatus && matchDate;
        });
      }

      function updatePagination() {
        const filtered = getFilteredLogs();
        const total = filtered.length;
        const totalPages = Math.ceil(total / pageSize);
        
        if (currentPage > totalPages && totalPages > 0) currentPage = totalPages;
        if (totalPages === 0) currentPage = 1;

        const start = (currentPage - 1) * pageSize;
        const end = Math.min(start + pageSize, total);

        logs.forEach(card => card.style.display = 'none');
        filtered.slice(start, end).forEach(card => card.style.display = 'flex');

        paginationInfo.textContent = total === 0 ? 'Showing 0-0 of 0 logs' : `Showing ${start + 1}-${end} of ${total} logs`;
        renderControls(totalPages);
      }

      function renderControls(totalPages) {
        paginationControls.innerHTML = '';
        if (totalPages <= 1) return;

        const maxVisible = 5;
        let start = Math.max(1, currentPage - Math.floor(maxVisible / 2));
        let end = Math.min(start + maxVisible - 1, totalPages);
        
        if (end - start + 1 < maxVisible) {
          start = Math.max(1, end - maxVisible + 1);
        }

        const prev = document.createElement('button');
        prev.className = 'btn btn-pagination-yellow';
        prev.textContent = 'Previous';
        prev.disabled = currentPage === 1;
        prev.onclick = () => { currentPage--; updatePagination(); window.scrollTo({top:0, behavior:'smooth'}); };
        paginationControls.appendChild(prev);

        for (let i = start; i <= end; i++) {
          const btn = document.createElement('button');
          btn.className = `btn btn-pagination-blue ${i === currentPage ? 'active' : ''}`;
          btn.style.width = '40px';
          btn.textContent = i;
          btn.onclick = () => { currentPage = i; updatePagination(); window.scrollTo({top:0, behavior:'smooth'}); };
          paginationControls.appendChild(btn);
        }

        const next = document.createElement('button');
        next.className = 'btn btn-pagination-yellow';
        next.textContent = 'Next';
        next.disabled = currentPage === totalPages;
        next.onclick = () => { currentPage++; updatePagination(); window.scrollTo({top:0, behavior:'smooth'}); };
        paginationControls.appendChild(next);
      }

      window.filterLogs = () => { currentPage = 1; updatePagination(); };
      searchInput.addEventListener('input', () => { currentPage = 1; updatePagination(); });

      // Live Clock
      function updateClock() {
        const now = new Date();
        document.getElementById('clockDate').textContent = now.toLocaleDateString('en-US', { weekday: 'long', year: 'numeric', month: 'long', day: 'numeric' });
        document.getElementById('clockTime').textContent = now.toLocaleTimeString('en-US', { hour12: true, hour: '2-digit', minute: '2-digit', second: '2-digit' });
      }
      setInterval(updateClock, 1000);
      updateClock();

      // Ultra-Precise Time Synchronization Engine
      function updateAllTimes() {
        const now = new Date();
        document.querySelectorAll('.timestamp-box').forEach(box => {
          const time = new Date(box.dataset.time);
          const exactEl = box.querySelector('.exact-label');
          const relativeEl = box.querySelector('.time-relative');
          
          if (isNaN(time.getTime())) {
            if (relativeEl) relativeEl.textContent = 'Unknown time';
            return;
          }

          // 1. Localized Exact Time
          if (exactEl) {
            exactEl.textContent = time.toLocaleString('en-US', { 
              month: 'short', 
              day: '2-digit', 
              year: 'numeric', 
              hour: '2-digit', 
              minute: '2-digit', 
              hour12: true 
            });
          }

          // 2. Localized Relative Time (Time-Ago)
          let diff = Math.floor((now - time) / 1000);
          
          if (diff < 30) {
            relativeEl.textContent = 'Just now';
          } else if (diff < 60) {
            relativeEl.textContent = 'Seconds ago';
          } else if (diff < 3600) {
            const mins = Math.floor(diff / 60);
            relativeEl.textContent = `${mins}m ago`;
          } else if (diff < 86400) {
            const hours = Math.floor(diff / 3600);
            relativeEl.textContent = `${hours}h ago`;
          } else {
            const days = Math.floor(diff / 86400);
            relativeEl.textContent = `${days}d ago`;
          }
        });
      }
      setInterval(updateAllTimes, 30000);
      updateAllTimes();

      // Modal Logic
      const modal = document.getElementById('view-details-modal');
      const closeBtn = document.getElementById('view-details-close-btn');
      const closeInner = document.getElementById('view-details-close-modal-btn');

      document.querySelectorAll('.view-details-btn').forEach(btn => {
        btn.addEventListener('click', function(e) {
          e.stopPropagation();
          const email = this.dataset.userEmail;
          const name = this.dataset.userName;
          document.getElementById('view-user-info-text').textContent = `Full analysis report for ${name} (${email})`;
          document.getElementById('edit-logs-content').innerHTML = '<div style="text-align:center;padding:20px;"><i class="fas fa-spinner fa-spin"></i> Loading historical data...</div>';
          document.getElementById('user-activities-content').innerHTML = '<div style="text-align:center;padding:20px;"><i class="fas fa-spinner fa-spin"></i> Analyzing interactions...</div>';
          
          fetchDetails(email);
          modal.classList.add('active');
        });
      });

      closeBtn.onclick = () => modal.classList.remove('active');
      closeInner.onclick = () => modal.classList.remove('active');
      window.onclick = (e) => { if (e.target === modal) modal.classList.remove('active'); };

      async function fetchDetails(userEmail) {
        try {
          const [editRes, actRes] = await Promise.all([
            fetch(`/api/user-edit-logs?user=${encodeURIComponent(userEmail)}&hours=24`),
            fetch(`/api/user-activities?user=${encodeURIComponent(userEmail)}&hours=24`)
          ]);
          
          const editData = await editRes.json();
          const actData = await actRes.json();
          
          displayEditLogs(editData.logs || []);
          displayActivities(actData.activities || []);
        } catch (err) {
          console.error(err);
        }
      }

      function displayEditLogs(logs) {
        const el = document.getElementById('edit-logs-content');
        if (!logs.length) { el.textContent = 'No modifications detected in the last 24h.'; return; }
        el.innerHTML = logs.map(l => `
          <div style="padding:12px;border-bottom:1px solid #e2e8f0;">
            <div style="font-size:13px;font-weight:700;color:var(--blue-dark);">${l.field} updated</div>
            <div style="font-size:12px;color:#64748b;margin-top:2px;">"${l.old_value}" → "${l.new_value}"</div>
            <div style="font-size:11px;color:#94a3b8;margin-top:4px;">${new Date(l.edited_at).toLocaleString()}</div>
          </div>
        `).join('');
      }

      function displayActivities(acts) {
        const el = document.getElementById('user-activities-content');
        if (!acts.length) { el.textContent = 'No digital footprints found in the last 24h.'; return; }
        el.innerHTML = acts.map(a => `
          <div style="padding:12px;border-bottom:1px solid #e2e8f0;">
            <div style="font-size:13px;font-weight:700;color:#1e293b;">${a.activity_type.replace(/_/g, ' ').toUpperCase()}</div>
            <div style="font-size:12px;color:#64748b;margin-top:2px;">${a.activity_description}</div>
            <div style="font-size:11px;color:#94a3b8;margin-top:4px;">${new Date(a.created_at).toLocaleString()}</div>
          </div>
        `).join('');
      }

      updatePagination();
    })();
  </script>
</body>
</html>
