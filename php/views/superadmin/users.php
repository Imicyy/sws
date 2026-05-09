<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link rel="icon" type="image/png" href="<?= htmlspecialchars(asset_url('images/SilayLogo.png'), ENT_QUOTES) ?>">
  <title><?= htmlspecialchars((string)($title ?? 'Superadmin Users'), ENT_QUOTES, 'UTF-8') ?></title>
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/css/bootstrap.min.css">
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">
  <link rel="stylesheet" type="text/css" href="/files/assets/css/user.css">
  <link rel="stylesheet" href="<?= htmlspecialchars(asset_url('css/light-theme.css?v=20260424'), ENT_QUOTES) ?>">
  <style>
    * { margin: 0; padding: 0; box-sizing: border-box; }
    body { background: linear-gradient(135deg, #fffde8 0%, #eef4ff 52%, #fff9d9 100%); display: flex; }
    
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
    /* Main Content Layout */
    .main-content { 
      margin-left: 260px; 
      flex: 1; 
      padding: 30px; 
      min-height: 100vh;
      background: transparent;
      position: relative;
    }
    .main-content::before {
      content: "";
      position: fixed;
      top: 0;
      left: 260px;
      right: 0;
      bottom: 0;
      background: url('<?= htmlspecialchars(asset_url("images/SilayLogo.png"), ENT_QUOTES) ?>') no-repeat center;
      background-size: 35%;
      opacity: 0.04;
      pointer-events: none;
      z-index: 0;
    }

    .page-header { margin-bottom: 30px; }
    .page-title { font-size: 26px; font-weight: 800; color: #1e293b; margin: 0; }
    
    /* Summary Cards */
    .user-stats-grid { display: grid; grid-template-columns: repeat(2, 1fr); gap: 24px; margin-bottom: 30px; }
    .user-stat-card { 
      background: #fff; 
      padding: 24px; 
      border-radius: 20px; 
      display: flex; 
      align-items: center; 
      gap: 20px; 
      box-shadow: 0 4px 6px -1px rgba(0,0,0,0.1);
      border: 1px solid #e2e8f0;
      position: relative;
      overflow: hidden;
      cursor: pointer;
      transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
    }
    .user-stat-card:hover {
      transform: translateY(-10px);
      box-shadow: 0 20px 40px -10px rgba(0,0,0,0.15);
      border-color: #3b82f6;
    }
    .user-stat-card:active {
      transform: translateY(-4px);
      box-shadow: 0 10px 20px -5px rgba(0,0,0,0.1);
    }
    .user-stat-card:hover .stat-icon {
      transform: scale(1.1) rotate(5deg);
    }
    .user-stat-card::after {
      content: "";
      position: absolute;
      top: 0; left: 0; width: 6px; height: 100%;
    }
    .stat-active { background: #fff; border-color: #e2e8f0; }
    .stat-inactive { background: #fff; border-color: #e2e8f0; }
    
    .stat-active::after { background: #3b82f6; }
    .stat-inactive::after { background: #fbbf24; }
    
    .stat-icon { 
      width: 54px; height: 54px; border-radius: 14px; 
      display: flex; align-items: center; justify-content: center; 
      font-size: 24px;
      transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
    }
    .icon-active { background: #eff6ff; color: #3b82f6; }
    .icon-inactive { background: #fffbeb; color: #d97706; }
    
    .stat-info h3 { font-size: 32px; font-weight: 800; color: #1e293b; margin: 0; line-height: 1; }
    .stat-info p { font-size: 14px; color: #64748b; font-weight: 600; margin: 4px 0 0; text-transform: uppercase; letter-spacing: 0.5px; }

    /* Action Bar */
    .action-bar { 
      background: #fff; 
      padding: 20px; 
      border-radius: 16px; 
      border: 1px solid #e2e8f0; 
      display: flex; 
      justify-content: space-between; 
      align-items: center; 
      gap: 20px;
      margin-bottom: 24px;
      box-shadow: 0 1px 3px rgba(0,0,0,0.1);
    }
    .search-box { position: relative; flex: 1; max-width: 400px; }
    .search-box svg { position: absolute; left: 14px; top: 50%; transform: translateY(-50%); color: #94a3b8; }
    .search-box input { 
      width: 100%; padding: 12px 12px 12px 42px; 
      border-radius: 12px; border: 1px solid #e2e8f0; 
      background: #f8fafc; font-size: 14px; transition: all 0.2s;
    }
    .search-box input:focus { border-color: #3b82f6; outline: none; background: #fff; box-shadow: 0 0 0 4px rgba(59, 130, 246, 0.1); }
    
    .filter-group { display: flex; gap: 12px; }
    .filter-select { 
      padding: 10px 16px; border-radius: 12px; border: 1px solid #e2e8f0; 
      background: #fff; font-size: 14px; font-weight: 600; color: #475569;
      cursor: pointer; transition: all 0.2s;
    }
    .filter-select:hover { border-color: #cbd5e1; }

    .btn-add { 
      background: #3b82f6; color: #fff; padding: 12px 24px; 
      border-radius: 12px; font-weight: 700; display: flex; 
      align-items: center; gap: 10px; border: none; transition: all 0.2s;
      box-shadow: 0 4px 10px rgba(59, 130, 246, 0.2);
    }
    .btn-add:hover { background: #2563eb; transform: translateY(-1px); box-shadow: 0 6px 15px rgba(37, 99, 235, 0.3); color: #fff; text-decoration: none; }

    /* Table Design */
    .table-container { 
      background: #fff; border-radius: 20px; border: 1px solid #e2e8f0; 
      overflow: hidden; box-shadow: 0 4px 6px -1px rgba(0,0,0,0.1); 
    }
    .user-table { width: 100%; border-collapse: collapse; }
    .user-table thead { background: #f8fafc; border-bottom: 1px solid #e2e8f0; }
    .user-table th { padding: 16px 24px; text-align: left; font-size: 13px; font-weight: 700; color: #64748b; text-transform: uppercase; letter-spacing: 0.5px; }
    .user-table td { padding: 20px 24px; border-bottom: 1px solid #f1f5f9; vertical-align: middle; }
    .user-table tr:last-child td { border-bottom: none; }
    .row-blue { background: #eff6ff !important; }
    .row-yellow { background: #fffbeb !important; }
    .user-table tr:hover { background: #f8fafc !important; transform: scale(1.002); transition: all 0.2s ease; }

    /* Table Components */
    .user-info { display: flex; align-items: center; gap: 14px; }
    .avatar { 
      width: 44px; height: 44px; border-radius: 50%; 
      background: var(--avatar-bg, linear-gradient(135deg, #3b82f6, #60a5fa)); 
      color: var(--avatar-text, #fff); display: flex; align-items: center; justify-content: center; 
      font-weight: 700; font-size: 16px; border: 2px solid #fff; box-shadow: 0 2px 5px rgba(0,0,0,0.1);
    }
    .avatar-blue { --avatar-bg: linear-gradient(135deg, #eff6ff, #dbeafe); --avatar-text: #1e3a8a; }
    .avatar-yellow { --avatar-bg: linear-gradient(135deg, #fffbeb, #fef3c7); --avatar-text: #854d0e; }
    
    .user-details h4 { font-size: 15px; font-weight: 700; color: #1e293b; margin: 0; }
    .user-details p { font-size: 13px; color: #64748b; margin: 2px 0 0; }
    
    .role-badge { 
      padding: 6px 12px; border-radius: 8px; font-size: 12px; font-weight: 700; 
      display: inline-flex; align-items: center; gap: 6px;
    }
    .role-admin { background: #eff6ff; color: #1e40af; }
    .role-super { background: #fef2f2; color: #991b1b; }
    .role-staff { background: #f0fdf4; color: #166534; }
    .role-barangay { background: #fff7ed; color: #9a3412; }

    .status-pill { 
      padding: 6px 12px; border-radius: 50px; font-size: 12px; font-weight: 700; 
      display: inline-flex; align-items: center; gap: 6px;
    }
    .status-active { background: #dcfce7; color: #15803d; }
    .status-active::before { content: ""; width: 6px; height: 6px; background: #15803d; border-radius: 50%; }
    .status-inactive { background: #fee2e2; color: #b91c1c; }
    .status-inactive::before { content: ""; width: 6px; height: 6px; background: #b91c1c; border-radius: 50%; }

    .action-btns { display: flex; gap: 8px; }
    .btn-icon { 
      width: 36px; height: 36px; border-radius: 10px; 
      display: flex; align-items: center; justify-content: center; 
      border: 1px solid #e2e8f0; background: #fff; color: #64748b; 
      transition: all 0.2s; cursor: pointer;
    }
    .btn-icon.btn-edit { color: #2563eb; background: #eff6ff; border-color: #dbeafe; }
    .btn-icon.btn-edit:hover { background: #3b82f6; color: #fff; border-color: #3b82f6; transform: translateY(-1px); }
    
    .btn-icon.btn-status-toggle { color: #d97706; background: #fffbeb; border-color: #fef3c7; }
    .btn-icon.btn-status-toggle:hover { background: #f59e0b; color: #fff; border-color: #f59e0b; transform: translateY(-1px); }
    
    .user-table tr.row-blue { background-color: #eff6ff !important; }
    .user-table tr.row-yellow { background-color: #fffbeb !important; }
    
    .user-table tr.row-blue:hover { background-color: #dbeafe !important; }
    .user-table tr.row-yellow:hover { background-color: #fef3c7 !important; }
    
    .btn-icon.btn-delete:hover { background: #ef4444; color: #fff; border-color: #ef4444; }

    .pagination-wrap { 
      display: flex; justify-content: space-between; align-items: center; 
      margin-top: 30px; padding: 20px 24px; 
      background: #f8fafc; border-top: 1px solid #e2e8f0; 
      border-bottom-left-radius: 20px; border-bottom-right-radius: 20px;
    }
    .pagination-info { font-size: 14px; color: #64748b; font-weight: 600; }
    .pagination-btns { display: flex; gap: 8px; }
    .pagination-btns .btn { 
      font-weight: 700; border-radius: 8px; 
      display: flex; align-items: center; justify-content: center; 
      transition: all 0.2s;
    }
    .pagination-btns .btn-sm { padding: 8px 16px; font-size: 13px; }
    
    .btn-pagination-blue { background: #eff6ff; color: #1e40af; border: 1px solid #dbeafe !important; }
    .btn-pagination-blue:hover { background: #3b82f6 !important; color: #fff !important; border-color: #3b82f6 !important; }
    .btn-pagination-blue.active { background: #2563eb !important; color: #fff !important; border-color: #2563eb !important; }

    .btn-pagination-yellow { background: #fffbeb; color: #854d0e; border: 1px solid #fef3c7 !important; }
    .btn-pagination-yellow:hover { background: #f59e0b !important; color: #fff !important; border-color: #f59e0b !important; }
    .btn-pagination-yellow:disabled { opacity: 0.5; cursor: not-allowed; }

    /* Modal */
    .modal { display: none; position: fixed; z-index: 1050; left: 0; top: 0; width: 100%; height: 100%; background: rgba(15, 23, 42, 0.45); backdrop-filter: blur(4px); align-items: center; justify-content: center; }
    .modal-content { background: #fff; padding: 30px; border-radius: 20px; width: 100%; max-width: 500px; border: 1px solid #e2e8f0; box-shadow: 0 25px 50px -12px rgba(0,0,0,0.25); }
    .modal-header { display: flex; justify-content: space-between; align-items: center; margin-bottom: 24px; }
    .modal-header h4 { font-size: 20px; font-weight: 800; color: #1e293b; margin: 0; }
    .close-btn { color: #94a3b8; font-size: 24px; cursor: pointer; transition: color 0.2s; }
    .close-btn:hover { color: #64748b; }
    /* Add User button styling: square and colored */
    .btn-add-user {
      border-radius: 0 !important;
      background: linear-gradient(90deg, #06b6d4 0%, #0ea5e9 100%) !important;
      border-color: #06b6d4 !important;
      color: #fff !important;
      padding: 6px 12px !important;
      box-shadow: 0 8px 18px rgba(14,165,233,0.16) !important;
      font-weight: 700 !important;
    }
    .btn-add-user:hover {
      background: linear-gradient(90deg, #0b94a6 0%, #0b93d1 100%) !important;
      border-color: #0b94a6 !important;
      color: #fff !important;
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
    <!-- Live Clock Aligned with Action Group -->
    <div style="display: flex; justify-content: space-between; margin-bottom: 10px;">
      <div></div> <!-- Spacer -->
      <div style="min-width: 250px;">
        <div id="liveClock" style="font-size:14px;font-weight:700;color:#1e293b;text-align:left;line-height:1.1;border-left:3px solid #3b82f6;padding-left:20px;">
          <div id="clockDate" style="font-size:15px;color:#64748b;font-weight:600;margin-bottom:4px; white-space: nowrap;"></div>
          <div id="clockTime" style="color:#2563eb;font-size:28px;font-weight:800;font-variant-numeric: tabular-nums; white-space: nowrap;"></div>
        </div>
      </div>
    </div>
    <div class="page-header">
      <h1 class="page-title">User Management</h1>
    </div>

    <!-- Summary Cards -->
    <?php
      $activeCount = 0;
      $inactiveCount = 0;
      if (!empty($users)) {
        foreach ($users as $u) {
          if (($u['status'] ?? '') === 'Active') $activeCount++;
          else $inactiveCount++;
        }
      }
    ?>
    <div class="user-stats-grid">
      <div class="user-stat-card stat-active">
        <div class="stat-icon icon-active">
          <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M16 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"></path><circle cx="9" cy="7" r="4"></circle><polyline points="16 11 18 13 22 9"></polyline></svg>
        </div>
        <div class="stat-info">
          <h3><?= $activeCount ?></h3>
          <p>Active Users</p>
        </div>
      </div>
      <div class="user-stat-card stat-inactive">
        <div class="stat-icon icon-inactive">
          <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M16 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"></path><circle cx="9" cy="7" r="4"></circle><line x1="18" y1="9" x2="22" y2="13"></line><line x1="22" y1="9" x2="18" y2="13"></line></svg>
        </div>
        <div class="stat-info">
          <h3><?= $inactiveCount ?></h3>
          <p>Inactive Users</p>
        </div>
      </div>
    </div>

    <!-- Action Bar -->
    <div class="action-bar">
      <div class="search-box">
        <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="11" cy="11" r="8"></circle><line x1="21" y1="21" x2="16.65" y2="16.65"></line></svg>
        <input type="text" id="userSearchInput" placeholder="Search by name, email or role...">
      </div>
      <div class="filter-group">
        <select id="roleFilter" class="filter-select">
          <option value="">All Roles</option>
          <option value="Super Admin">Super Admin</option>
          <option value="Admin">Admin</option>
          <option value="Staff">Staff</option>
          <option value="Barangay">Barangay</option>
        </select>
        <select id="statusFilter" class="filter-select">
          <option value="Active">Show Active</option>
          <option value="Inactive">Show Inactive</option>
        </select>
        <a href="/register" target="_blank" class="btn-add">
          <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3" stroke-linecap="round" stroke-linejoin="round"><line x1="12" y1="5" x2="12" y2="19"></line><line x1="5" y1="12" x2="19" y2="12"></line></svg>
          Add User
        </a>
      </div>
    </div>

    <!-- User Table -->
    <div class="table-container">
      <table class="user-table" id="user-table">
        <thead>
          <tr>
            <th>User Details</th>
            <th>Role</th>
            <th>Status</th>
            <th style="text-align: right;">Actions</th>
          </tr>
        </thead>
        <tbody id="user-list">
          <?php if (!empty($users) && is_array($users)): ?>
            <?php $uIdx = 0; foreach ($users as $user): ?>
              <?php 
                $userId = (int) ($user['id'] ?? 0); 
                $uName = htmlspecialchars((string) ($user['name'] ?? ''), ENT_QUOTES, 'UTF-8');
                $uEmail = htmlspecialchars((string) ($user['email'] ?? ''), ENT_QUOTES, 'UTF-8');
                $uRole = htmlspecialchars((string) ($user['role'] ?? ''), ENT_QUOTES, 'UTF-8');
                $uStatus = htmlspecialchars((string) ($user['status'] ?? ''), ENT_QUOTES, 'UTF-8');
                $uInitial = !empty($uName) ? strtoupper(substr($uName, 0, 1)) : '?';
                
                $roleClass = 'role-staff';
                if ($uRole === 'Super Admin') $roleClass = 'role-super';
                else if ($uRole === 'Admin') $roleClass = 'role-admin';
                else if ($uRole === 'Barangay') $roleClass = 'role-barangay';
              ?>
              <?php 
                $rowTheme = ($uIdx % 2 === 0) ? 'row-blue' : 'row-yellow'; 
                $avatarTheme = ($uIdx % 2 === 0) ? 'avatar-blue' : 'avatar-yellow'; 
              ?>
              <tr class="user-row status-<?= $uStatus ?> <?= $rowTheme ?>">
                <td>
                  <div class="user-info">
                    <div class="avatar <?= $avatarTheme ?>"><?= $uInitial ?></div>
                    <div class="user-details">
                      <h4><?= $uName ?></h4>
                      <p><?= $uEmail ?></p>
                    </div>
                  </div>
                </td>
                <td>
                  <span class="role-badge <?= $roleClass ?>"><?= $uRole ?></span>
                </td>
                <td>
                  <span class="status-pill status-<?= strtolower($uStatus) ?>"><?= $uStatus ?></span>
                </td>
                <td style="text-align: right;">
                  <div class="action-btns" style="justify-content: flex-end;">
                    <button type="button" class="btn-icon btn-edit open-edit"
                      data-id="<?= $userId ?>"
                      data-name="<?= $uName ?>"
                      data-email="<?= $uEmail ?>"
                      data-role="<?= $uRole ?>"
                      data-barangay-id="<?= htmlspecialchars((string) ($user['barangay_id'] ?? ''), ENT_QUOTES, 'UTF-8') ?>"
                      data-status="<?= $uStatus ?>" title="Edit User">
                      <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"></path><path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"></path></svg>
                    </button>

                    <form action="/edit-user" method="POST" style="display:inline-block;" class="status-form">
                      <input type="hidden" name="id" value="<?= $userId ?>" required>
                      <?php $isInactive = ($uStatus === 'Inactive'); ?>
                      <button type="button" class="btn-icon btn-status-toggle status-btn" 
                        data-action="<?= $isInactive ? 'activate' : 'deactivate' ?>" 
                        title="<?= $isInactive ? 'Activate User' : 'Deactivate User' ?>">
                        <?php if ($isInactive): ?>
                          <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="20 6 9 17 4 12"></polyline></svg>
                        <?php else: ?>
                          <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="3 6 5 6 21 6"></polyline><path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"></path><line x1="10" y1="11" x2="10" y2="17"></line><line x1="14" y1="11" x2="14" y2="17"></line></svg>
                        <?php endif; ?>
                      </button>
                    </form>
                  </div>
                </td>
              </tr>
            <?php $uIdx++; endforeach; ?>
          <?php else: ?>
            <tr id="no-users-row"><td colspan="4" style="text-align: center; padding: 40px; color: #94a3b8;">No users found</td></tr>
          <?php endif; ?>
        </tbody>
      </table>
      
      <div class="pagination-wrap">
        <div class="pagination-info" id="paginationInfo"></div>
        <div class="pagination-btns" id="paginationControls"></div>
      </div>
    </div>
  </div>

  <div id="edit-modal" class="modal">
    <div class="modal-content">
      <div class="modal-header">
        <h4>Edit User Information</h4>
        <span class="close-btn" id="edit-close-btn">&times;</span>
      </div>
      <form id="edit-user-form" method="POST" action="/update-user">
        <input type="hidden" id="edit-user-id" name="id">
        <div class="form-group">
          <label for="edit-username">Name</label>
          <input type="text" id="edit-username" name="name" class="form-control" required>
        </div>
        <div class="form-group">
          <label for="edit-email">Email</label>
          <input type="email" id="edit-email" name="email" class="form-control" required>
        </div>
        <div class="form-group">
          <label for="edit-password">New Password</label>
          <input type="password" id="edit-password" name="password" class="form-control" minlength="6" placeholder="Leave blank to keep current">
        </div>
        <div class="form-group">
          <label for="edit-confirm">Confirm Password</label>
          <input type="password" id="edit-confirm" name="confirm_password" class="form-control" minlength="6" placeholder="Leave blank to keep current">
        </div>
        <div class="form-group">
          <label for="edit-role">Role</label>
          <select id="edit-role" name="role" class="form-control" required>
            <option value="Admin">Admin</option>
            <option value="Staff">Staff</option>
            <option value="Super Admin">Super Admin</option>
            <option value="Barangay">Barangay</option>
          </select>
        </div>
        <div class="form-group" id="edit-barangay-row" style="display:none;">
          <label for="edit-barangay-id">Barangay</label>
          <select id="edit-barangay-id" name="barangay_id" class="form-control">
            <option value="">Select barangay</option>
            <?php if (!empty($barangayList) && is_array($barangayList)): ?>
              <?php foreach ($barangayList as $barangay): ?>
                <option value="<?= (int) ($barangay['id'] ?? 0) ?>"><?= htmlspecialchars((string) ($barangay['barangay'] ?? ''), ENT_QUOTES, 'UTF-8') ?></option>
              <?php endforeach; ?>
            <?php endif; ?>
          </select>
        </div>
        <div class="form-actions">
          <button type="button" class="btn btn-light" id="edit-cancel-btn">Cancel</button>
          <button type="submit" class="btn btn-primary">Save Changes</button>
        </div>
      </form>
    </div>
  </div>

  <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
  <script>
    (function () {
      const rows = Array.from(document.querySelectorAll('.user-row'));
      const searchInput = document.getElementById('userSearchInput');
      const roleFilter = document.getElementById('roleFilter');
      const statusFilter = document.getElementById('statusFilter');
      const paginationInfo = document.getElementById('paginationInfo');
      const paginationControls = document.getElementById('paginationControls');
      let activeStatus = 'Active';
      let currentPage = 1;

      function getFilteredRows() {
        const search = (searchInput.value || '').trim().toLowerCase();
        const role = roleFilter.value;
        const status = statusFilter.value;

        return rows.filter((row) => {
          const rowStatus = row.classList.contains('status-Active') ? 'Active' : 'Inactive';
          if (rowStatus !== status) return false;
          
          const roleBadge = row.querySelector('.role-badge');
          const roleText = roleBadge ? roleBadge.textContent.trim() : '';
          
          // Fix: roleFilter.value is empty for "All Roles"
          if (role && roleText !== role) return false;
          
          if (!search) return true;
          
          // Better search across multiple fields
          const name = row.querySelector('h4').textContent.toLowerCase();
          const email = row.querySelector('p').textContent.toLowerCase();
          return name.includes(search) || email.includes(search) || roleText.toLowerCase().includes(search);
        });
      }

      statusFilter.addEventListener('change', () => {
        currentPage = 1;
        applyTable();
      });

      roleFilter.addEventListener('change', () => {
        currentPage = 1;
        applyTable();
      });

      function renderPagination(totalPages) {
        paginationControls.innerHTML = '';
        if (totalPages <= 1) return;

        const prevBtn = document.createElement('button');
        prevBtn.type = 'button';
        prevBtn.className = 'btn btn-pagination-yellow btn-sm';
        prevBtn.style.width = 'auto';
        prevBtn.textContent = 'Previous';
        prevBtn.disabled = currentPage === 1;
        prevBtn.addEventListener('click', function () {
          if (currentPage <= 1) return;
          currentPage -= 1;
          applyTable();
          window.scrollTo({ top: 0, behavior: 'smooth' });
        });
        paginationControls.appendChild(prevBtn);

        for (let i = 1; i <= totalPages; i++) {
          const btn = document.createElement('button');
          btn.type = 'button';
          btn.className = `btn btn-pagination-blue btn-sm ${i === currentPage ? 'active' : ''}`;
          btn.style.width = '40px';
          btn.textContent = String(i);
          btn.addEventListener('click', function () {
            currentPage = i;
            applyTable();
            window.scrollTo({ top: 0, behavior: 'smooth' });
          });
          paginationControls.appendChild(btn);
        }

        const nextBtn = document.createElement('button');
        nextBtn.type = 'button';
        nextBtn.className = 'btn btn-pagination-yellow btn-sm';
        nextBtn.style.width = 'auto';
        nextBtn.textContent = 'Next';
        nextBtn.disabled = currentPage === totalPages;
        nextBtn.addEventListener('click', function () {
          if (currentPage >= totalPages) return;
          currentPage += 1;
          applyTable();
          window.scrollTo({ top: 0, behavior: 'smooth' });
        });
        paginationControls.appendChild(nextBtn);
      }

      function applyTable() {
        rows.forEach((row) => { row.style.display = 'none'; });
        const filtered = getFilteredRows();
        const pageSize = 10;
        const total = filtered.length;
        const totalPages = Math.max(1, Math.ceil(total / pageSize));
        if (currentPage > totalPages) currentPage = 1;
        const start = (currentPage - 1) * pageSize;
        const end = Math.min(start + pageSize, total);

        filtered.slice(start, end).forEach((row) => { row.style.display = ''; });
        paginationInfo.textContent = total === 0
          ? 'Showing 0-0 of 0 users'
          : ('Showing ' + (start + 1) + '-' + end + ' of ' + total + ' users');
        renderPagination(totalPages);
      }

      searchInput.addEventListener('input', function () { currentPage = 1; applyTable(); });

      // Initial call
      applyTable();

      const modal = document.getElementById('edit-modal');
      const closeBtn = document.getElementById('edit-close-btn');
      const cancelBtn = document.getElementById('edit-cancel-btn');
      const editRole = document.getElementById('edit-role');
      const editBarangayRow = document.getElementById('edit-barangay-row');

      function syncBarangayField() {
        editBarangayRow.style.display = editRole.value === 'Barangay' ? 'block' : 'none';
      }

      document.querySelectorAll('.open-edit').forEach((btn) => {
        btn.addEventListener('click', function () {
          document.getElementById('edit-user-id').value = btn.getAttribute('data-id') || '';
          document.getElementById('edit-username').value = btn.getAttribute('data-name') || '';
          document.getElementById('edit-email').value = btn.getAttribute('data-email') || '';
          editRole.value = btn.getAttribute('data-role') || 'Staff';
          document.getElementById('edit-barangay-id').value = btn.getAttribute('data-barangay-id') || '';
          syncBarangayField();
          modal.style.display = 'flex';
        });
      });

      closeBtn.addEventListener('click', function () { modal.style.display = 'none'; });
      cancelBtn.addEventListener('click', function () { modal.style.display = 'none'; });
      window.addEventListener('click', function (event) { if (event.target === modal) modal.style.display = 'none'; });
      editRole.addEventListener('change', syncBarangayField);

      document.querySelectorAll('.status-btn').forEach((btn) => {
        btn.addEventListener('click', function () {
          const form = btn.closest('form');
          const action = btn.getAttribute('data-action') || 'deactivate';
          const title = action === 'deactivate' ? 'Confirm Deactivation' : 'Confirm Activation';
          const text = action === 'deactivate'
            ? 'Make sure you want to deactivate this account.'
            : 'Make sure you want to activate this account.';
          Swal.fire({ title, text, icon: 'warning', showCancelButton: true, confirmButtonText: 'Yes', cancelButtonText: 'Cancel' })
            .then((result) => { if (result.isConfirmed) form.submit(); });
        });
      });

      function updateClock() {
        const now = new Date();
        const options = { weekday: 'long', year: 'numeric', month: 'long', day: 'numeric' };
        document.getElementById('clockDate').textContent = now.toLocaleDateString('en-US', options);
        document.getElementById('clockTime').textContent = now.toLocaleTimeString('en-US', { hour12: true, hour: '2-digit', minute: '2-digit', second: '2-digit' });
      }
      setInterval(updateClock, 1000);
      updateClock();

    })();
  </script>
  <script>
    (function () {
      const navLinks = document.querySelectorAll('.sidebar-nav a, .nav-center a');
      if (!navLinks || navLinks.length === 0) return;

      function normalizePath(p) {
        try { return new URL(p, window.location.origin).pathname.replace(/\/$/, ''); } catch (e) { return String(p || '').replace(/\/$/, ''); }
      }

      function setActiveByLocation() {
        const current = window.location.pathname.replace(/\/$/, '');
        navLinks.forEach(a => {
          const href = a.getAttribute('href') || '';
          const hrefPath = normalizePath(href);
          if (hrefPath !== '' && hrefPath !== '/' && current.includes(hrefPath)) {
            a.classList.add('active');
          } else if (hrefPath === current) {
            a.classList.add('active');
          } else {
            a.classList.remove('active');
          }
        });
      }

      navLinks.forEach(a => {
        a.addEventListener('click', function () {
          navLinks.forEach(x => x.classList.remove('active'));
          this.classList.add('active');
        });
      });

      setActiveByLocation();
    })();
  </script>
  </div>
  </div>
</body>
</html>
