<!doctype html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width,initial-scale=1">
  <link rel="icon" type="image/png" href="<?= htmlspecialchars(asset_url('images/logo-ebmag.png'), ENT_QUOTES) ?>">
  <title>Super Admin - System Logs</title>
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/css/bootstrap.min.css">
  <style>
    * { margin: 0; padding: 0; box-sizing: border-box; }
    body { font-family: Open Sans, Segoe UI, Arial, sans-serif; background: #f3f6fb; color: #1f2937; display: flex; }
    
    /* Sidebar Styles */
    .sidebar { 
      width: 260px; 
      background: #0f766e; 
      color: #fff; 
      min-height: 100vh; 
      padding: 20px 0; 
      position: fixed; 
      left: 0; 
      top: 0; 
      overflow-y: auto; 
    }
    .sidebar-header { 
      padding: 0 20px 24px; 
      border-bottom: 1px solid rgba(255,255,255,0.1); 
      margin-bottom: 20px; 
    }
    .sidebar-header h2 { 
      font-size: 18px; 
      font-weight: 600; 
      white-space: nowrap; 
    }
    .sidebar-nav { 
      list-style: none; 
    }
    .sidebar-nav li { 
      margin: 0; 
    }
    .sidebar-nav a { 
      display: block; 
      padding: 12px 20px; 
      color: rgba(255,255,255,0.8); 
      text-decoration: none; 
      transition: all 0.3s ease; 
      border-left: 3px solid transparent; 
    }
    .sidebar-nav a:hover { 
      background: rgba(255,255,255,0.1); 
      color: #fff; 
      border-left-color: #fff; 
    }
    .sidebar-nav a.active { 
      background: #0f766e; 
      color: #fff; 
      border-left-color: #fbbf24; 
    }
    .sidebar-nav-label {
      font-size: 12px;
      font-weight: 600;
      color: rgba(255,255,255,0.6);
      text-transform: uppercase;
      padding: 16px 20px 8px;
      letter-spacing: 0.5px;
    }
    .user-name {
      font-size: 14px;
      font-weight: 600;
      color: #fff;
      padding: 0 20px;
      margin-bottom: 8px;
      word-break: break-word;
    }
    
    /* Main Content */
    .main-content { 
      margin-left: 260px; 
      flex: 1; 
      padding: 24px; 
    }
    
    .main { padding: 0; }
    .panel { background: #fff; border: 1px solid #e5e7eb; border-radius: 12px; padding: 16px; margin-bottom: 20px; }
    .panel-title { font-size: 14px; font-weight: 600; margin-bottom: 12px; }
    table { width: 100%; border-collapse: collapse; font-size: 13px; }
    table th { background: #f9fafb; padding: 10px; text-align: left; font-weight: 600; border-bottom: 2px solid #e5e7eb; }
    table td { padding: 10px; border-bottom: 1px solid #e5e7eb; }
    table tr:hover { background: #f9fafb; }
    .filter-bar { display: flex; gap: 12px; margin-bottom: 14px; }
    input { padding: 8px 12px; border: 1px solid #e5e7eb; border-radius: 6px; }
    button { padding: 8px 14px; border-radius: 6px; border: 1px solid #e5e7eb; background: #fff; cursor: pointer; }
    button.btn-primary { background: #0f766e; color: #fff; border: none; }
    .btn-outline-secondary { padding: 6px 10px; border: 1px solid #ccc; background: #fff; border-radius: 4px; cursor: pointer; }
    .btn-outline-secondary.active { background: #0f766e; color: #fff; border-color: #0f766e; }
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
  <div class="sidebar">
    <div class="sidebar-header">
      <div class="user-name"><?= htmlspecialchars((string)($user['name'] ?? 'Super Admin'), ENT_QUOTES, 'UTF-8') ?></div>
      <div class="sidebar-nav-label">Navigation</div>
    </div>
    <ul class="sidebar-nav">
      <li><a href="/index-superadmin" class="<?= strpos($_SERVER['REQUEST_URI'], 'index-superadmin') !== false ? 'active' : '' ?>">Dashboard</a></li>
      <li><a href="/superadmin-users" class="<?= strpos($_SERVER['REQUEST_URI'], 'superadmin-users') !== false ? 'active' : '' ?>">User Management</a></li>
      <li><a href="/superadmin-logs" class="<?= strpos($_SERVER['REQUEST_URI'], 'superadmin-logs') !== false ? 'active' : '' ?>">System Logs</a></li>
      <li style="margin-top: 24px; border-top: 1px solid rgba(255,255,255,0.1); padding-top: 16px;"><a href="/logout">Logout</a></li>
    </ul>
  </div>

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
      let loginCurrentPage = 1;
      
      function renderLoginPagination() {
        loginRows.forEach(row => row.style.display = 'none');
        const filtered = loginRows;
        const total = filtered.length;
        const totalPages = Math.max(1, Math.ceil(total / pageSize));
        if (loginCurrentPage > totalPages) loginCurrentPage = 1;
        const start = (loginCurrentPage - 1) * pageSize;
        const end = Math.min(start + pageSize, total);
        
        filtered.slice(start, end).forEach(row => row.style.display = '');
        loginInfo.textContent = total === 0 ? 'Showing 0-0 of 0 records' : `Showing ${start + 1}-${end} of ${total} records`;
        
        loginControls.innerHTML = '';
        if (totalPages <= 1) return;
        for (let i = 1; i <= totalPages; i++) {
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
      }
      
      // Initial render
      renderLoginPagination();
      
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
      
      function displayEditLogs(logs) {
        const container = document.getElementById('edit-logs-content');
        if (!logs || logs.length === 0) {
          container.textContent = 'No edit logs found in the last 24 hours';
          return;
        }
        
        const logsHtml = logs.map(log => 
          `<div style="margin-bottom: 8px; padding: 4px; border-left: 3px solid #0f766e;">
            <strong>${log.field}</strong>: "${log.old_value || 'N/A'}" → "${log.new_value || 'N/A'}"<br>
            <small style="color: #6b7280;">${log.edited_at} (${log.record_type})</small>
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
            <strong>${activity.activity_type}</strong>: ${activity.activity_description}<br>
            <small style="color: #6b7280;">${activity.created_at}</small>
          </div>`
        ).join('');
        
        container.innerHTML = activitiesHtml;
      }
    })();
  </script>
</body>
</html>
