<!doctype html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width,initial-scale=1">
  <link rel="icon" type="image/png" href="<?= htmlspecialchars(asset_url('images/SilayLogo.png'), ENT_QUOTES) ?>">
  <title>Super Admin - System Logs</title>
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/css/bootstrap.min.css">
  <link rel="stylesheet" href="<?= htmlspecialchars(asset_url('css/light-theme.css?v=20260424'), ENT_QUOTES) ?>">
  <style>
    * { margin: 0; padding: 0; box-sizing: border-box; }
    body { font-family: Open Sans, Segoe UI, Arial, sans-serif; background: linear-gradient(135deg, #fffde8 0%, #eef4ff 52%, #fff9d9 100%); color: #1f2937; display: flex; }
    
    /* Sidebar Styles */
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
      font-weight: 800; 
      font-size: 13px; 
      letter-spacing: 0.7px; 
      margin-bottom: 18px; 
      color: #ffffff !important; 
      display: flex; 
      align-items: center; 
      gap: 10px;
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
    .user-name {
      font-size: 16px;
      font-weight: 700;
      color: #fff;
      padding: 0 10px;
      margin-bottom: 8px;
      word-break: break-word;
      opacity: 1;
      text-align: center;
    }
    
    /* Main Content */
    .main-content { 
      margin-left: 260px; 
      flex: 1; 
      padding: 24px; 
    }
    
    .main { padding: 0; }
    .panel { background: linear-gradient(180deg, #ffffff 0%, #f8fbff 100%); border: 1px solid #dbe5f3; border-radius: 12px; padding: 16px; margin-bottom: 20px; box-shadow: 0 12px 26px rgba(37, 99, 235, 0.12); }
    .panel-title { font-size: 14px; font-weight: 600; margin-bottom: 12px; }
    table { width: 100%; border-collapse: collapse; font-size: 13px; }
    table th { background: linear-gradient(135deg, #eff6ff, #fef9c3); color: #1e3a8a; padding: 10px; text-align: left; font-weight: 600; border-bottom: 2px solid #e5e7eb; }
    table td { padding: 10px; border-bottom: 1px solid #e5e7eb; }
    table tr:hover { background: #f9fafb; }
    .main h1 { text-align: center; }
    table th, table td { text-align: center; vertical-align: middle; }
    table td:last-child { white-space: nowrap; }
    .filter-bar { display: flex; gap: 12px; margin-bottom: 14px; }
    input { padding: 8px 12px; border: 1px solid #e5e7eb; border-radius: 6px; }
    button { padding: 8px 14px; border-radius: 6px; border: 1px solid #e5e7eb; background: #fff; cursor: pointer; }
    button.btn-primary { background: #0f766e; color: #fff; border: none; }
    .btn-outline-secondary {
      padding: 8px 14px;
      border: 1px solid #3b82f6;
      background: #3b82f6;
      color: #fff;
      border-radius: 10px;
      cursor: pointer;
      font-weight: 600;
      line-height: 1.1;
      min-width: 34px;
    }
    .btn-outline-secondary:hover {
      background: #2563eb;
      border-color: #2563eb;
      color: #fff;
    }
    .btn-outline-secondary.active {
      background: #2563eb;
      color: #fff;
      border-color: #2563eb;
      box-shadow: inset 0 0 0 1px rgba(255,255,255,0.18);
    }
    .btn-outline-secondary:disabled {
      opacity: .55;
      cursor: not-allowed;
      background: #93c5fd;
      border-color: #93c5fd;
      color: #eff6ff;
    }
    @media (max-width: 980px) {
      .filter-bar { flex-wrap: wrap; }
    }
    
    /* Modal Styles */
    .modal { display: none; position: fixed; z-index: 1000; left: 0; top: 0; width: 100%; height: 100%; background-color: rgba(0,0,0,0.5); }
    .modal-content { background-color: #fff; margin: 5% auto; padding: 20px; border-radius: 8px; width: 90%; max-width: 600px; position: relative; }
    .close-btn { position: absolute; top: 10px; right: 15px; color: #aaa; font-size: 28px; font-weight: bold; cursor: pointer; }
    .close-btn:hover { color: #000; }
    .form-group { margin-bottom: 15px; }
    .form-group label { display: block; margin-bottom: 5px; font-weight: 600; }
  </style>
</head>
<body>
  <!-- Sidebar Navigation -->
  <aside class="sidebar">
    <div class="brand">
      <img src="<?= htmlspecialchars(asset_url('images/SilayLogo.png'), ENT_QUOTES) ?>" alt="Logo" style="height: 32px; width: 32px; object-fit: contain;">
      <span>ENRIQUE B. MAGALONA</span>
    </div>
    
    <div class="user-name" style="color: #fff; opacity: 1; font-weight: 700; margin-top: 10px;"><?= htmlspecialchars((string)($user['name'] ?? 'Super Admin'), ENT_QUOTES, 'UTF-8') ?></div>
    
    <div class="nav-title">Navigation</div>
    
    <a href="/index-superadmin" class="nav-link <?= strpos($_SERVER['REQUEST_URI'], 'index-superadmin') !== false ? 'active' : '' ?>">
      <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="3" width="7" height="7"></rect><rect x="14" y="3" width="7" height="7"></rect><rect x="14" y="14" width="7" height="7"></rect><rect x="3" y="14" width="7" height="7"></rect></svg>
      Dashboard
    </a>
    
    <a href="/superadmin-users" class="nav-link <?= strpos($_SERVER['REQUEST_URI'], 'superadmin-users') !== false ? 'active' : '' ?>">
      <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"></path><circle cx="9" cy="7" r="4"></circle><path d="M23 21v-2a4 4 0 0 0-3-3.87"></path><path d="M16 3.13a4 4 0 0 1 0 7.75"></path></svg>
      User Management
    </a>
    
    <a href="/superadmin-logs" class="nav-link <?= strpos($_SERVER['REQUEST_URI'], 'superadmin-logs') !== false ? 'active' : '' ?>">
      <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"></path><polyline points="14 2 14 8 20 8"></polyline><line x1="16" y1="13" x2="8" y2="13"></line><line x1="16" y1="17" x2="8" y2="17"></line><polyline points="10 9 9 9 8 9"></polyline></svg>
      System Logs
    </a>

    <a class="logout-btn" href="/logout">
      <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4"/><polyline points="16 17 21 12 16 7"/><line x1="21" y1="12" x2="9" y2="12"/></svg>
      Logout
    </a>
  </aside>

  <!-- Main Content -->
  <div class="main-content">
  <main class="main">
      <h1 class="h4 mb-4">System Logs</h1>

      <div class="panel">
        <h2 class="panel-title">Login Activity Logs</h2>
        <div class="filter-bar">
          <input type="text" placeholder="Search by email..." id="loginSearch">
          <input type="date" id="loginDate">
          <button class="btn btn-primary" onclick="filterLoginLogs()">Filter</button>
        </div>
        <table>
          <thead>
            <tr>
              <th>ID</th>
              <th>User Email</th>
              <th>User Name</th>
              <th>Role</th>
              <th>Login Result</th>
              <th>Timestamp</th>
              <th>Actions</th>
            </tr>
          </thead>
          <tbody id="loginLogsBody">
            <?php foreach (($loginLogs ?? []) as $l): ?>
            <tr class="login-log-row">
              <td><?= (int)($l['id'] ?? 0) ?></td>
              <td><?= htmlspecialchars((string)($l['user_email'] ?? ''), ENT_QUOTES, 'UTF-8') ?></td>
              <td><?= htmlspecialchars((string)($l['user_name'] ?? ''), ENT_QUOTES, 'UTF-8') ?></td>
              <td><?= htmlspecialchars((string)($l['user_role'] ?? ''), ENT_QUOTES, 'UTF-8') ?></td>
              <td><span style="padding: 4px 8px; border-radius: 4px; <?= strpos($l['login_result'] ?? 'failed', 'success') !== false ? 'background: #d1fae5; color: #065f46;' : 'background: #fee2e2; color: #991b1b;' ?>"><?= htmlspecialchars((string)($l['login_result'] ?? ''), ENT_QUOTES, 'UTF-8') ?></span></td>
              <td><?= htmlspecialchars((string)($l['created_at'] ?? ''), ENT_QUOTES, 'UTF-8') ?></td>
              <td><button class="btn btn-sm btn-outline-primary view-details-btn" data-user-id="<?= (int)($l['user_id'] ?? 0) ?>" data-user-email="<?= htmlspecialchars((string)($l['user_email'] ?? ''), ENT_QUOTES, 'UTF-8') ?>" data-user-name="<?= htmlspecialchars((string)($l['user_name'] ?? ''), ENT_QUOTES, 'UTF-8') ?>" data-log-type="login">View Details</button></td>
            </tr>
            <?php endforeach; ?>
          </tbody>
        </table>
        <div style="margin-top: 12px; display: flex; justify-content: space-between; align-items: center;">
          <small id="loginPaginationInfo">Showing 0-0 of 0 records</small>
          <div id="loginPaginationControls" class="btn-group btn-group-sm" style="display: flex; gap: 4px;"></div>
        </div>
      </div>

  </main>
  </div>
  </div>

  <!-- View Details Modal -->
  <div id="view-details-modal" class="modal">
    <div class="modal-content" style="max-width: 800px;">
      <span class="close-btn" id="view-details-close-btn">&times;</span>
      <h4>User Activity Details</h4>
      <div id="view-details-content" style="margin-top: 16px;">
        <div class="form-group">
          <label style="font-weight: 600; color: #334155;">User Information</label>
          <div style="margin-top: 8px;">
            <p id="view-user-info" style="margin: 0; color: #1f2937; padding: 8px 0; border-bottom: 1px solid #e2e8f0;"></p>
          </div>
        </div>
        
        <div class="form-group" style="margin-top: 16px;">
          <label style="font-weight: 600; color: #334155;">Edit Logs (Last 24 Hours)</label>
          <div id="edit-logs-container" style="margin-top: 8px; max-height: 200px; overflow-y: auto; border: 1px solid #e2e8f0; border-radius: 4px; padding: 8px;">
            <p id="edit-logs-content" style="margin: 0; color: #1f2937;"></p>
          </div>
        </div>
        
        <div class="form-group" style="margin-top: 16px;">
          <label style="font-weight: 600; color: #334155;">User Activities (Last 24 Hours)</label>
          <div id="user-activities-container" style="margin-top: 8px; max-height: 200px; overflow-y: auto; border: 1px solid #e2e8f0; border-radius: 4px; padding: 8px;">
            <p id="user-activities-content" style="margin: 0; color: #1f2937;"></p>
          </div>
        </div>
      </div>
      <div style="display: flex; gap: 10px; justify-content: flex-end; margin-top: 24px;">
        <button type="button" class="btn btn-light" id="view-details-close-modal-btn">Close</button>
      </div>
    </div>
  </div>

  <script>
    (function () {
      const pageSize = 10;
      
      // Login Logs Pagination
      const loginRows = Array.from(document.querySelectorAll('.login-log-row'));
      const loginInfo = document.getElementById('loginPaginationInfo');
      const loginControls = document.getElementById('loginPaginationControls');
      const loginSearch = document.getElementById('loginSearch');
      const loginDate = document.getElementById('loginDate');
      let loginCurrentPage = 1;

      function getFilteredLoginRows() {
        const searchValue = String((loginSearch && loginSearch.value) || '').trim().toLowerCase();
        const dateValue = String((loginDate && loginDate.value) || '').trim(); // YYYY-MM-DD
        return loginRows.filter((row) => {
          const emailText = String((row.children[1] && row.children[1].textContent) || '').toLowerCase();
          const timestampText = String((row.children[5] && row.children[5].textContent) || '');
          const matchesSearch = searchValue === '' || emailText.includes(searchValue);
          const matchesDate = dateValue === '' || timestampText.includes(dateValue);
          return matchesSearch && matchesDate;
        });
      }
      
      function renderLoginPagination() {
        loginRows.forEach(row => row.style.display = 'none');
        const filtered = getFilteredLoginRows();
        const total = filtered.length;
        const totalPages = Math.max(1, Math.ceil(total / pageSize));
        if (loginCurrentPage > totalPages) loginCurrentPage = 1;
        const start = (loginCurrentPage - 1) * pageSize;
        const end = Math.min(start + pageSize, total);
        
        filtered.slice(start, end).forEach(row => row.style.display = '');
        loginInfo.textContent = total === 0 ? 'Showing 0-0 of 0 records' : `Showing ${start + 1}-${end} of ${total} records`;
        
        loginControls.innerHTML = '';
        if (totalPages <= 1) return;
        const maxVisiblePages = 10;
        let startPage = Math.max(1, loginCurrentPage - Math.floor(maxVisiblePages / 2));
        let endPage = startPage + maxVisiblePages - 1;
        if (endPage > totalPages) {
          endPage = totalPages;
          startPage = Math.max(1, endPage - maxVisiblePages + 1);
        }
        const prevBtn = document.createElement('button');
        prevBtn.type = 'button';
        prevBtn.className = 'btn btn-outline-secondary';
        prevBtn.textContent = 'Previous';
        prevBtn.disabled = loginCurrentPage === 1;
        prevBtn.addEventListener('click', function () {
          if (loginCurrentPage <= 1) return;
          loginCurrentPage -= 1;
          renderLoginPagination();
        });
        loginControls.appendChild(prevBtn);

        for (let i = startPage; i <= endPage; i++) {
          const btn = document.createElement('button');
          btn.type = 'button';
          btn.className = 'btn btn-outline-secondary' + (i === loginCurrentPage ? ' active' : '');
          btn.textContent = String(i);
          btn.addEventListener('click', function () {
            loginCurrentPage = i;
            renderLoginPagination();
          });
          loginControls.appendChild(btn);
        }

        const nextBtn = document.createElement('button');
        nextBtn.type = 'button';
        nextBtn.className = 'btn btn-outline-secondary';
        nextBtn.textContent = 'Next';
        nextBtn.disabled = loginCurrentPage === totalPages;
        nextBtn.addEventListener('click', function () {
          if (loginCurrentPage >= totalPages) return;
          loginCurrentPage += 1;
          renderLoginPagination();
        });
        loginControls.appendChild(nextBtn);
      }

      window.filterLoginLogs = function () {
        loginCurrentPage = 1;
        renderLoginPagination();
      };
      
      // Initial render
      renderLoginPagination();
      if (loginSearch) {
        loginSearch.addEventListener('input', window.filterLoginLogs);
      }
      if (loginDate) {
        loginDate.addEventListener('change', window.filterLoginLogs);
      }
      
      // View Details Modal
      const viewDetailsModal = document.getElementById('view-details-modal');
      const viewDetailsCloseBtn = document.getElementById('view-details-close-btn');
      const viewDetailsCloseModalBtn = document.getElementById('view-details-close-modal-btn');
      
      document.querySelectorAll('.view-details-btn').forEach((btn) => {
        btn.addEventListener('click', function () {
          const userEmail = btn.getAttribute('data-user-email');
          const userId = btn.getAttribute('data-user-id');
          const userName = btn.getAttribute('data-user-name');
          const logType = btn.getAttribute('data-log-type');
          
          // Set user info
          document.getElementById('view-user-info').textContent = 
            `Name: ${userName || 'N/A'}, Email: ${userEmail || 'N/A'}`;
          
          // Fetch edit logs and user activities
          fetchUserDetails(userEmail || userId, logType);
          
          viewDetailsModal.style.display = 'block';
        });
      });
      
      viewDetailsCloseBtn.addEventListener('click', function () { viewDetailsModal.style.display = 'none'; });
      viewDetailsCloseModalBtn.addEventListener('click', function () { viewDetailsModal.style.display = 'none'; });
      window.addEventListener('click', function (event) { if (event.target === viewDetailsModal) viewDetailsModal.style.display = 'none'; });
      
      async function fetchUserDetails(userIdentifier, logType) {
        try {
          // Fetch edit logs for the last 24 hours
          const editLogsResponse = await fetch(`/api/user-edit-logs?user=${encodeURIComponent(userIdentifier)}&hours=24`);
          const editLogsData = await editLogsResponse.json();
          
          // Fetch user activities for the last 24 hours
          const activitiesResponse = await fetch(`/api/user-activities?user=${encodeURIComponent(userIdentifier)}&hours=24`);
          const activitiesData = await activitiesResponse.json();
          
          // Check for API errors
          if (!editLogsResponse.ok) {
            throw new Error(editLogsData.message || 'Failed to load edit logs');
          }
          if (!activitiesResponse.ok) {
            throw new Error(activitiesData.message || 'Failed to load user activities');
          }
          
          // Display edit logs
          displayEditLogs(editLogsData.logs || []);
          
          // Display user activities
          displayUserActivities(activitiesData.activities || []);
          
        } catch (error) {
          console.error('Error fetching user details:', error);
          document.getElementById('edit-logs-content').textContent = 'Error loading edit logs: ' + error.message;
          document.getElementById('user-activities-content').textContent = 'Error loading user activities: ' + error.message;
        }
      }

      function formatLogDateTime(dateValue) {
        if (!dateValue) return 'N/A';
        const raw = String(dateValue).trim();
        if (!raw) return 'N/A';

        // DB datetime usually has no timezone; treat it as UTC for consistent conversion.
        const iso = raw.includes('T') ? raw : raw.replace(' ', 'T');
        const hasTz = /([zZ]|[+\-]\d\d:\d\d)$/.test(iso);
        const parsed = new Date(hasTz ? iso : (iso + 'Z'));
        if (Number.isNaN(parsed.getTime())) return raw;

        return parsed.toLocaleString('en-PH', {
          year: 'numeric',
          month: 'short',
          day: '2-digit',
          hour: '2-digit',
          minute: '2-digit',
          second: '2-digit',
          hour12: true
        });
      }

      function toTitleCaseWords(value) {
        return String(value || '')
          .split(/\s+/)
          .filter(Boolean)
          .map(word => word.charAt(0).toUpperCase() + word.slice(1).toLowerCase())
          .join(' ');
      }

      function simplifyActivityType(activityType) {
        const raw = String(activityType || '').trim();
        if (!raw) return 'Activity';

        let normalized = raw;
        if (normalized.startsWith('view_api_')) {
          normalized = normalized.replace(/^view_api_/, 'view ');
        } else if (normalized.startsWith('view_')) {
          normalized = normalized.replace(/^view_/, 'view ');
        }

        normalized = normalized.replace(/_/g, ' ');

        // Turn API-heavy labels into simpler wording.
        normalized = normalized.replace(/\bapi\b/gi, '').replace(/\s{2,}/g, ' ').trim();
        return toTitleCaseWords(normalized);
      }

      function simplifyActivityDescription(description) {
        const raw = String(description || '').trim();
        if (!raw) return 'No details available.';

        // Example: "GET /api/senior-citizens/barangay/Alacaygan" -> "Viewed page: /senior-citizens/barangay/Alacaygan"
        const match = raw.match(/^(GET|POST|PUT|PATCH|DELETE)\s+(.+)$/i);
        if (match) {
          const method = match[1].toUpperCase();
          let path = match[2].trim();
          path = path.replace(/^https?:\/\/[^/]+/i, '');
          path = path.replace(/^\/api\b/i, '');
          const action = method === 'GET' ? 'Viewed' : (method === 'DELETE' ? 'Deleted via' : 'Updated via');
          return `${action} ${path || '/'}`;
        }

        return raw;
      }
      function escapeHtml(value) {
        return String(value == null ? '' : value)
          .replace(/&/g, '&amp;')
          .replace(/</g, '&lt;')
          .replace(/>/g, '&gt;')
          .replace(/"/g, '&quot;')
          .replace(/'/g, '&#39;');
      }
      
      function displayEditLogs(logs) {
        const container = document.getElementById('edit-logs-content');
        if (!logs || logs.length === 0) {
          container.textContent = 'No edit logs found in the last 24 hours';
          return;
        }
        
        const logsHtml = logs.map(log => 
          `<div style="margin-bottom: 8px; padding: 4px; border-left: 3px solid #0f766e;">
            <strong>${escapeHtml(log.field || '')}</strong> (${escapeHtml(log.record_type || '')}${log.record_name ? ': ' + escapeHtml(log.record_name) : ''}): "${escapeHtml(log.old_value || 'N/A')}" → "${escapeHtml(log.new_value || 'N/A')}"<br>
            <small style="color: #6b7280;">${formatLogDateTime(log.edited_at)} by ${escapeHtml(log.edited_by || 'Unknown')}</small>
          </div>`
        ).join('');
        
        container.innerHTML = logsHtml;
      }
      
      function displayUserActivities(activities) {
        const container = document.getElementById('user-activities-content');
        if (!activities || activities.length === 0) {
          container.textContent = 'No user activities found in the last 24 hours';
          return;
        }
        
        const activitiesHtml = activities.map(activity => 
          `<div style="margin-bottom: 8px; padding: 4px; border-left: 3px solid #fbbf24;">
            <strong>${escapeHtml(simplifyActivityType(activity.activity_type))}</strong>: ${escapeHtml(simplifyActivityDescription(activity.activity_description))}<br>
            <small style="color: #6b7280;">${formatLogDateTime(activity.created_at)}${activity.email ? ' • ' + escapeHtml(activity.email) : ''}</small>
          </div>`
        ).join('');
        
        container.innerHTML = activitiesHtml;
      }
    })();
  </script>
</body>
</html>
