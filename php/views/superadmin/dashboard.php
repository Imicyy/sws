<!doctype html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width,initial-scale=1">
  <link rel="icon" type="image/png" href="<?= htmlspecialchars(asset_url('images/SilayLogo.png'), ENT_QUOTES) ?>">
  <title><?= htmlspecialchars((string)($title ?? 'Super Admin'), ENT_QUOTES, 'UTF-8') ?></title>
  <link rel="stylesheet" href="<?= htmlspecialchars(asset_url('css/light-theme.css?v=20260424'), ENT_QUOTES) ?>">
  <style>
    * { margin: 0; padding: 0; box-sizing: border-box; }
    :root {
      --bg-page: linear-gradient(180deg, #fffdf0 0%, #f5f9ff 55%, #eef5ff 100%);
      --panel-border: #dbe5f3;
      --blue-main: #3b82f6;
      --blue-strong: #2563eb;
      --blue-soft: #eaf3ff;
      --yellow-soft: #fef3c7;
      --yellow-main: #facc15;
    }
    body { font-family: Segoe UI, Arial, sans-serif; background: var(--bg-page); display: flex; position: relative; }
    
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
      display: flex;
      align-items: center;
      gap: 8px;
      justify-content: center;
      margin-bottom: 4px;
    }
    .user-email { 
      font-size: 13px; 
      color: #94a3b8; 
      word-break: break-all; 
      font-weight: 500; 
      text-align: center;
    }
    
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
    
    .user-profile {
      padding: 16px;
      margin-bottom: 20px;
      display: flex;
      flex-direction: column;
      align-items: center;
      background: rgba(255, 255, 255, 0.05);
      border-radius: 14px;
      border: 1px solid rgba(255, 255, 255, 0.1);
    }
    
    /* Main Content */
    .main-content { 
      margin-left: 260px; 
      flex: 1; 
      padding: 24px; 
      min-height: 100vh;
      display: flex;
      flex-direction: column;
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
    .wrap { max-width: 100%; margin: 0; padding: 0 10px; }
    
    /* Header & Welcome */
    .dashboard-header { 
      display: flex; 
      justify-content: space-between; 
      align-items: center; 
      margin-bottom: 30px; 
      padding: 20px; 
      background: #fff; 
      border-radius: 16px; 
      box-shadow: 0 4px 20px rgba(0,0,0,0.05);
    }
    .admin-info h1 { font-size: 24px; color: #1e3a8a; margin-bottom: 4px; }
    .admin-info p { color: #64748b; font-size: 14px; }
    
    /* Stats Cards */
    .stats-grid { display: grid; grid-template-columns: 1fr 1fr; gap: 24px; margin-bottom: 30px; width: 100%; }
    .stat-card { 
      background: #fff; 
      padding: 32px; 
      border-radius: 20px; 
      display: flex; 
      align-items: center; 
      gap: 30px; 
      box-shadow: 0 10px 30px rgba(59, 130, 246, 0.08); 
      border: 1px solid #e2e8f0;
      width: 100%;
      cursor: pointer;
      transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
    }
    .stat-card:hover {
      transform: translateY(-10px);
      box-shadow: 0 25px 50px -12px rgba(59, 130, 246, 0.2);
      border-color: #3b82f6;
    }
    .stat-card:active {
      transform: translateY(-4px);
      box-shadow: 0 15px 30px -10px rgba(59, 130, 246, 0.15);
    }
    .stat-card:hover .stat-icon {
      transform: scale(1.1) rotate(5deg);
    }
    .stat-icon { 
      width: 60px; 
      height: 60px; 
      border-radius: 14px; 
      display: flex; 
      align-items: center; 
      justify-content: center; 
      font-size: 24px;
      transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
    }
    .stat-icon.blue { background: #eff6ff; color: #3b82f6; }
    .stat-icon.indigo { background: #f5f3ff; color: #6366f1; }
    .stat-details h3 { font-size: 14px; color: #64748b; margin-bottom: 4px; text-transform: uppercase; letter-spacing: 0.5px; }
    .stat-details span { font-size: 32px; font-weight: 800; color: #1e293b; }

    .stat-content { display: flex; flex-direction: column; }
    .stat-top-label { font-size: 12px; color: #64748b; font-weight: 600; text-transform: uppercase; letter-spacing: 0.5px; }
    .stat-number { font-size: 42px; font-weight: 800; color: #1e293b; line-height: 1.1; margin: 4px 0; }
    .stat-bottom-label { font-size: 14px; color: #64748b; font-weight: 500; }

    /* Content Sections */
    .content-grid { display: grid; grid-template-columns: 320px 1fr; gap: 24px; width: 100%; align-items: stretch; }
    .section-card { background: #fff; border-radius: 16px; border: 1px solid #e2e8f0; box-shadow: 0 4px 6px -1px rgba(0,0,0,0.1); padding: 24px; height: 100%; display: flex; flex-direction: column; }
    .section-title { font-size: 20px; font-weight: 700; color: #1e293b; margin-bottom: 24px; display: flex; align-items: center; gap: 12px; }
    
    /* Forms */
    .form-group { margin-bottom: 16px; }
    label { display: block; font-size: 13px; font-weight: 600; margin-bottom: 8px; color: #475569; }
    input, select { 
      width: 100%; 
      padding: 12px; 
      border: 1px solid #e2e8f0; 
      border-radius: 10px; 
      font-size: 14px; 
      transition: all 0.2s; 
      background: #f8fafc;
    }
    input:focus, select:focus { border-color: #3b82f6; box-shadow: 0 0 0 4px rgba(59, 130, 246, 0.1); background: #fff; outline: none; }
    
    /* Buttons */
    .btn { 
      display: inline-flex; 
      align-items: center; 
      justify-content: center; 
      gap: 8px; 
      padding: 12px 20px; 
      border-radius: 10px; 
      font-weight: 600; 
      font-size: 14px; 
      cursor: pointer; 
      transition: all 0.2s; 
      border: none;
    }
    .btn-primary { 
      background: linear-gradient(135deg, #60a5fa, #2563eb); 
      color: #fff; 
      width: 100%; 
      box-shadow: 0 4px 12px rgba(59, 130, 246, 0.2);
    }
    .btn-primary:hover { background: linear-gradient(135deg, #3b82f6, #1d4ed8); transform: translateY(-1px); box-shadow: 0 6px 15px rgba(37, 99, 235, 0.3); }
    .btn-outline { 
      background: linear-gradient(135deg, #fef3c7, #fbbf24); 
      border: none; 
      color: #713f12; 
      box-shadow: 0 4px 12px rgba(251, 191, 36, 0.15);
    }
    .btn-outline:hover { 
      background: linear-gradient(135deg, #fde68a, #f59e0b); 
      color: #713f12;
      transform: translateY(-1px);
      box-shadow: 0 6px 15px rgba(245, 158, 11, 0.25);
    }
    .btn-sm { padding: 6px 12px; font-size: 12px; border-radius: 8px; }
    
    /* Barangay List */
    .barangay-list { 
      display: grid; 
      grid-template-columns: repeat(auto-fill, minmax(380px, 1fr)); 
      gap: 24px; 
    }
    .barangay-row { 
      background: #fff; 
      border-radius: 16px; 
      overflow: hidden;
      box-shadow: 0 10px 25px rgba(0,0,0,0.05);
      border: 1px solid #e2e8f0;
      transition: all 0.3s ease;
      display: flex;
      flex-direction: column;
      min-height: 220px;
    }
    .barangay-row:hover { transform: translateY(-8px); box-shadow: 0 20px 40px rgba(59, 130, 246, 0.12); }
    
    .card-header {
      padding: 24px;
      background: var(--card-gradient, #f8fafc);
      color: #1e3a8a;
      display: flex;
      justify-content: space-between;
      align-items: center;
      border-bottom: 1px solid rgba(0,0,0,0.05);
    }
    .card-header h4 { font-size: 20px; font-weight: 800; margin: 0; letter-spacing: -0.5px; }
    .card-header p { font-size: 14px; margin: 4px 0 0; color: inherit; opacity: 0.7; font-weight: 600; }
    .card-header.is-yellow { color: #854d0e; }
    
    .card-body {
      padding: 24px;
      display: flex;
      gap: 12px;
      background: #fff;
      margin-top: auto;
    }
    .btn-card {
      flex: 1;
      padding: 14px 16px;
      font-size: 14px;
      font-weight: 700;
      border-radius: 12px;
      cursor: pointer;
      border: none;
      display: flex;
      align-items: center;
      justify-content: center;
      gap: 8px;
      transition: all 0.2s;
    }
    .btn-outline-card {
      background: linear-gradient(135deg, #fef3c7, #fbbf24);
      color: #713f12;
      box-shadow: 0 4px 10px rgba(251, 191, 36, 0.15);
    }
    .btn-outline-card:hover { background: linear-gradient(135deg, #fde68a, #f59e0b); }
    .btn-primary-card {
      background: linear-gradient(135deg, #60a5fa, #2563eb);
      color: #fff;
      box-shadow: 0 4px 10px rgba(59, 130, 246, 0.2);
    }
    .btn-primary-card:hover { background: linear-gradient(135deg, #3b82f6, #1d4ed8); }
    
    /* Badges */
    .purok-count { background: #eff6ff; color: #2563eb; padding: 2px 8px; border-radius: 6px; font-weight: 700; font-size: 12px; margin-right: 4px; }
    
    /* Pagination */
    .pagination-wrap { display: flex; justify-content: space-between; align-items: center; margin-top: 30px; padding-top: 20px; border-top: 1px solid #e2e8f0; }
    .pagination-info { font-size: 14px; color: #64748b; font-weight: 500; }
    .pagination-btns { display: flex; gap: 8px; }
    

    
    /* Modal */
    .modal { 
      display: none; 
      position: fixed; 
      top: 0; left: 0; width: 100%; height: 100%; 
      background: rgba(15, 23, 42, 0.6); 
      backdrop-filter: blur(4px); 
      z-index: 1000; 
      align-items: center; 
      justify-content: center; 
    }
    .modal.active { display: flex; animation: fadeIn 0.2s ease-out; }
    .modal-content { 
      background: #fff; 
      width: 100%; 
      max-width: 650px; 
      padding: 40px; 
      border-radius: 24px; 
      box-shadow: 0 25px 50px -12px rgba(0,0,0,0.25); 
    }
    @keyframes fadeIn { from { opacity: 0; transform: scale(0.95); } to { opacity: 1; transform: scale(1); } }
    
    .purok-list-container { 
      margin-top: 20px; 
      max-height: 300px; 
      overflow-y: auto; 
      display: grid; 
      grid-template-columns: repeat(auto-fill, minmax(130px, 1fr)); 
      gap: 12px; 
      padding: 4px;
    }
    .purok-item { 
      padding: 12px; 
      background: #f8fafc; 
      border-radius: 12px; 
      font-size: 13px; 
      font-weight: 600; 
      color: #1e293b; 
      text-align: center;
      border: 1px solid #e2e8f0;
      transition: all 0.2s;
    }
    .purok-item:hover { background: #eff6ff; border-color: #3b82f6; color: #2563eb; }
    
    #statusMessage { margin-top: 15px; text-align: center; font-size: 13px; font-weight: 500; }
    .text-success { color: #059669; }
    .text-danger { color: #dc2626; }
  </style>
</head>
<body>
  <!-- Sidebar Navigation -->
  <aside class="sidebar">
    <div class="brand">
      <img src="<?= htmlspecialchars(asset_url('images/SilayLogo.png'), ENT_QUOTES) ?>" alt="Logo" style="height: 32px; width: 32px; object-fit: contain;">
      <span>ENRIQUE B. MAGALONA</span>
    </div>
    
    <div class="nav-title">Navigation</div>
    
    <div class="user-profile">
      <div class="profile-icon-wrapper">
        <svg width="32" height="32" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="color: #fff;">
          <path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"></path>
          <circle cx="12" cy="7" r="4"></circle>
        </svg>
      </div>
      <div class="user-name">
        <span><?= htmlspecialchars($_SESSION['user']['name'] ?? 'Super Admin', ENT_QUOTES) ?></span>
      </div>
      <div class="user-email">
        <?= htmlspecialchars($_SESSION['user']['email'] ?? 'superadmin@example.com', ENT_QUOTES) ?>
      </div>
    </div>
    
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
    <div class="wrap">
      <!-- Dashboard Header -->
      <!-- Live Clock Outside Card (Aligned with Action Button) -->
      <div style="display: flex; justify-content: space-between; padding: 0 20px; margin-bottom: 10px;">
        <div></div> <!-- Spacer to match left side -->
        <div style="min-width: 250px;"> <!-- Increased width to prevent AM/PM wrapping -->
          <div id="liveClock" style="font-size:14px;font-weight:700;color:#1e293b;text-align:left;line-height:1.1;border-left:3px solid #3b82f6;padding-left:20px;">
            <div id="clockDate" style="font-size:15px;color:#64748b;font-weight:600;margin-bottom:4px; white-space: nowrap;"></div>
            <div id="clockTime" style="color:#2563eb;font-size:28px;font-weight:800;font-variant-numeric: tabular-nums; white-space: nowrap;"></div>
          </div>
        </div>
      </div>

      <header class="dashboard-header">
        <div class="admin-info">
          <h1>Welcome, Super Admin</h1>
          <p>System Overview & Maintenance Panel</p>
        </div>
        
        <div class="header-actions">
          <button class="btn btn-outline" onclick="openPurokModal()" style="width: auto; padding: 12px 24px;">
            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><line x1="12" y1="5" x2="12" y2="19"></line><line x1="5" y1="12" x2="19" y2="12"></line></svg>
            Quick Add Purok
          </button>
        </div>
      </header>

      <!-- Stats Grid -->
      <?php 
        $totalBrgys = !empty($barangays) ? count($barangays) : 0;
        $totalPuroks = 0;
        if (!empty($barangays)) {
          foreach ($barangays as $brgy) {
            $totalPuroks += is_array($brgy) ? count($brgy) : 0;
          }
        }
      ?>
      <div class="stats-grid">
        <div class="stat-card">
          <div class="stat-icon blue">
            <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M3 9l9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"></path><polyline points="9 22 9 12 15 12 15 22"></polyline></svg>
          </div>
          <div class="stat-content">
            <div class="stat-top-label">Administrative Scope</div>
            <div class="stat-number"><?= $totalBrgys ?></div>
            <div class="stat-bottom-label">Total Barangays</div>
          </div>
        </div>
        <div class="stat-card">
          <div class="stat-icon indigo">
            <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0z"></path><circle cx="12" cy="10" r="3"></circle></svg>
          </div>
          <div class="stat-content">
            <div class="stat-top-label">Geographic Coverage</div>
            <div class="stat-number"><?= $totalPuroks ?></div>
            <div class="stat-bottom-label">Total Puroks</div>
          </div>
        </div>
      </div>

      <!-- Content Grid -->
      <div class="content-grid">
        <!-- Sidebar Actions -->
        <aside class="sidebar-actions">
          <div class="section-card">
            <div class="section-title">
              <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"></path><path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"></path></svg>
              Register Barangay
            </div>
            <form id="registerBarangayForm">
              <div class="form-group">
                <label for="barangayName">Barangay Name</label>
                <input type="text" id="barangayName" name="barangayName" placeholder="e.g. Barangay I" required>
              </div>
              <button type="submit" class="btn btn-primary">
                Register Barangay
              </button>
            </form>
            <div id="statusMessage"></div>
          </div>
        </aside>

        <!-- Main List -->
        <main class="main-list">
          <div class="section-card">
            <div class="section-title">
              <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="8" y1="6" x2="21" y2="6"></line><line x1="8" y1="12" x2="21" y2="12"></line><line x1="8" y1="18" x2="21" y2="18"></line><line x1="3" y1="6" x2="3.01" y2="6"></line><line x1="3" y1="12" x2="3.01" y2="12"></line><line x1="3" y1="18" x2="3.01" y2="18"></line></svg>
              Barangay List
            </div>
            
            <?php if (!empty($barangays) && is_array($barangays)): ?>
              <?php 
                $cardThemes = [
                  ['bg' => 'linear-gradient(135deg, #eff6ff, #dbeafe)', 'class' => ''],           // Light Blue
                  ['bg' => 'linear-gradient(135deg, #fffbeb, #fef3c7)', 'class' => 'is-yellow']  // Light Yellow
                ];
              ?>
              <div id="barangayList" class="barangay-list">
                <?php $brgyIdx = 0; foreach ($barangays as $barangayName => $purokList): 
                  $currentTheme = $cardThemes[$brgyIdx % count($cardThemes)];
                ?>
                  <div class="barangay-row" style="--card-gradient: <?= $currentTheme['bg'] ?>;">
                    <div class="card-header <?= $currentTheme['class'] ?>">
                      <div class="header-info" style="display: flex; align-items: center; gap: 20px;">
                        <div class="header-icon-box" style="background: rgba(255,255,255,0.6); padding: 14px; border-radius: 14px; display: flex; align-items: center; justify-content: center; box-shadow: 0 4px 10px rgba(0,0,0,0.03);">
                          <svg width="32" height="32" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M3 9l9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"></path><polyline points="9 22 9 12 15 12 15 22"></polyline></svg>
                        </div>
                        <div>
                          <h4 style="font-size: 24px;"><?= htmlspecialchars((string) $barangayName, ENT_QUOTES, 'UTF-8') ?></h4>
                          <p style="font-size: 15px;"><?= count($purokList) ?> Registered Puroks</p>
                        </div>
                      </div>
                    </div>
                    <div class="card-body">
                      <button class="btn-card btn-outline-card" onclick="openDetailsModal('<?= htmlspecialchars($barangayName, ENT_QUOTES) ?>', <?= htmlspecialchars(json_encode($purokList), ENT_QUOTES) ?>)">View Details</button>
                      <button class="btn-card btn-primary-card" onclick="openPurokModal(<?= $brgyIdx + 1 ?>, '<?= htmlspecialchars($barangayName, ENT_QUOTES) ?>')">
                        Add Purok
                        <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="12" y1="5" x2="12" y2="19"></line><line x1="5" y1="12" x2="19" y2="12"></line></svg>
                      </button>
                    </div>
                  </div>
                <?php $brgyIdx++; endforeach; ?>
              </div>
              
              <div class="pagination-wrap">
                <div class="pagination-info" id="barangayPaginationInfo">
                  <!-- JS will populate -->
                </div>
                <div class="pagination-btns" id="barangayPaginationControls">
                  <!-- JS will populate -->
                </div>
              </div>
              

            <?php else: ?>
              <div style="text-align:center; padding: 40px; color: #64748b;">
                <p>No barangays registered yet.</p>
              </div>
            <?php endif; ?>
          </div>
        </main>
      </div>
    </div>
  </div>

  <!-- Add Purok Modal -->
  <div id="purokModal" class="modal">
    <div class="modal-content">
      <div class="section-title">
        <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="12" y1="5" x2="12" y2="19"></line><line x1="5" y1="12" x2="19" y2="12"></line></svg>
        Add New Purok
      </div>
      <form id="registerPurokForm">
        <div class="form-group">
          <label for="selectBarangay">Select Barangay</label>
          <select id="selectBarangay" name="barangayId" required>
            <option value="" selected disabled>-- Choose a Barangay --</option>
            <?php if (!empty($barangays) && is_array($barangays)): ?>
              <?php $index = 1; foreach ($barangays as $barangayName => $purokList): ?>
                <option value="<?= $index ?>"><?= htmlspecialchars((string) $barangayName, ENT_QUOTES, 'UTF-8') ?></option>
              <?php $index++; endforeach; ?>
            <?php endif; ?>
          </select>
        </div>
        <div class="form-group">
          <label for="purokName">Purok Name</label>
          <input type="text" id="purokName" name="purokName" placeholder="e.g. Purok Rose" required>
        </div>
        <div class="form-group">
          <label for="purokDesc">Description (Optional)</label>
          <input type="text" id="purokDesc" name="purokDesc" placeholder="Brief description...">
        </div>
        <div style="display:flex; gap:10px; margin-top:24px;">
          <button type="button" class="btn btn-outline" style="flex:1" onclick="closePurokModal()">Cancel</button>
          <button type="submit" class="btn btn-primary" style="flex:1">Register Purok</button>
        </div>
      </form>
    </div>
  </div>

  <!-- Barangay Details Modal -->
  <div id="detailsModal" class="modal">
    <div class="modal-content" style="max-width: 850px;">
      <div class="section-title">
        <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0z"></path><circle cx="12" cy="10" r="3"></circle></svg>
        Barangay Information
      </div>
      <div id="detailsContent">
        <h2 id="detailsBrgyName" style="color: #1e3a8a; margin-bottom: 5px;"></h2>
        <p id="detailsPurokCount" style="color: #64748b; font-size: 14px; font-weight: 500;"></p>
        
        <div style="margin-top: 25px;">
          <h3 style="font-size: 15px; color: #1e293b; font-weight: 700; display: flex; align-items: center; gap: 8px;">
            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="8" y1="6" x2="21" y2="6"></line><line x1="8" y1="12" x2="21" y2="12"></line><line x1="8" y1="18" x2="21" y2="18"></line><line x1="3" y1="6" x2="3.01" y2="6"></line><line x1="3" y1="12" x2="3.01" y2="12"></line><line x1="3" y1="18" x2="3.01" y2="18"></line></svg>
            Registered Puroks
          </h3>
          <div id="purokListContainer" class="purok-list-container">
            <!-- Puroks will be listed here -->
          </div>
        </div>
      </div>
      <div style="margin-top:30px;">
        <button type="button" class="btn btn-primary" onclick="closeDetailsModal()">Close Window</button>
      </div>
    </div>
  </div>

  <script>
    const statusMessage = document.getElementById('statusMessage');
    const purokModal = document.getElementById('purokModal');
    const detailsModal = document.getElementById('detailsModal');

    function openDetailsModal(brgyName, puroks) {
      document.getElementById('detailsBrgyName').textContent = brgyName;
      document.getElementById('detailsPurokCount').textContent = `${puroks.length} Registered Puroks`;
      
      const container = document.getElementById('purokListContainer');
      container.innerHTML = '';
      
      if (puroks && puroks.length > 0) {
        puroks.forEach(purok => {
          const item = document.createElement('div');
          item.className = 'purok-item';
          item.textContent = purok;
          container.appendChild(item);
        });
      } else {
        container.innerHTML = '<div style="grid-column: 1/-1; text-align: center; padding: 20px; color: #94a3b8; font-style: italic;">No puroks registered yet.</div>';
      }
      
      detailsModal.classList.add('active');
    }

    function closeDetailsModal() {
      detailsModal.classList.remove('active');
    }

    function openPurokModal(brgyId = null, brgyName = null) {
      if (brgyId) {
        document.getElementById('selectBarangay').value = brgyId;
      }
      purokModal.classList.add('active');
    }

    function closePurokModal() {
      purokModal.classList.remove('active');
      document.getElementById('registerPurokForm').reset();
    }

    // Close modal on outside click
    window.onclick = function(event) {
      if (event.target == purokModal) closePurokModal();
      if (event.target == detailsModal) closeDetailsModal();
    }

    document.getElementById('registerBarangayForm').addEventListener('submit', async function (e) {
      e.preventDefault();
      const btn = e.target.querySelector('button');
      btn.disabled = true;
      btn.textContent = 'Registering...';
      
      const payload = new URLSearchParams();
      payload.set('barangayName', document.getElementById('barangayName').value);
      try {
        const res = await fetch('/api/barangay', {
          method: 'POST',
          headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
          body: payload.toString(),
        });
        const json = await res.json();
        statusMessage.textContent = json.message || (json.success ? 'Barangay registered.' : 'Failed to register barangay.');
        statusMessage.className = json.success ? 'text-success' : 'text-danger';
        if (json.success) { setTimeout(() => window.location.reload(), 1000); }
      } catch (err) {
        statusMessage.textContent = 'Connection error. Please try again.';
        statusMessage.className = 'text-danger';
      } finally {
        btn.disabled = false;
        btn.textContent = 'Register Barangay';
      }
    });

    document.getElementById('registerPurokForm').addEventListener('submit', async function (e) {
      e.preventDefault();
      const btn = e.target.querySelector('button[type="submit"]');
      btn.disabled = true;
      btn.textContent = 'Adding...';

      const payload = new URLSearchParams();
      payload.set('barangayId', document.getElementById('selectBarangay').value);
      payload.set('purokName', document.getElementById('purokName').value);
      try {
        const res = await fetch('/api/purok', {
          method: 'POST',
          headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
          body: payload.toString(),
        });
        const json = await res.json();
        if (json.success) { 
          window.location.reload(); 
        } else {
          alert(json.message || 'Failed to add purok.');
        }
      } catch (err) {
        alert('Connection error.');
      } finally {
        btn.disabled = false;
        btn.textContent = 'Register Purok';
      }
    });

    // Pagination for barangay list
    let barangayCurrentPage = 1;
    const barangayItemsPerPage = 8;

    function getBarangayItems() {
      return Array.from(document.querySelectorAll('#barangayList .barangay-row'));
    }

    function updateBarangayPagination() {
      const items = getBarangayItems();
      const totalItems = items.length;
      const totalPages = Math.ceil(totalItems / barangayItemsPerPage);
      
      if (barangayCurrentPage > totalPages && totalPages > 0) {
        barangayCurrentPage = totalPages;
      } else if (totalPages === 0) {
        barangayCurrentPage = 1;
      }
      
      const startItem = totalItems === 0 ? 0 : (barangayCurrentPage - 1) * barangayItemsPerPage + 1;
      const endItem = Math.min(barangayCurrentPage * barangayItemsPerPage, totalItems);
      const info = document.getElementById('barangayPaginationInfo');
      if (info) info.textContent = `Showing ${startItem}-${endItem} of ${totalItems} records`;
      
      items.forEach((item, index) => {
        const itemPage = Math.floor(index / barangayItemsPerPage) + 1;
        item.style.display = itemPage === barangayCurrentPage ? 'flex' : 'none';
      });
      
      renderBarangayPaginationControls(totalPages);
    }

    function renderBarangayPaginationControls(totalPages) {
      const controls = document.getElementById('barangayPaginationControls');
      if (!controls) return;
      controls.innerHTML = '';
      
      if (totalPages <= 1) return;
      
      const prevBtn = document.createElement('button');
      prevBtn.className = 'btn btn-outline btn-sm';
      prevBtn.style.width = 'auto';
      prevBtn.textContent = 'Previous';
      prevBtn.disabled = barangayCurrentPage === 1;
      prevBtn.onclick = () => {
        if (barangayCurrentPage > 1) {
          barangayCurrentPage--;
          updateBarangayPagination();
          window.scrollTo({ top: 0, behavior: 'smooth' });
        }
      };
      controls.appendChild(prevBtn);
      
      for (let i = 1; i <= totalPages; i++) {
        const pageBtn = document.createElement('button');
        pageBtn.className = `btn btn-sm ${i === barangayCurrentPage ? 'btn-primary' : 'btn-outline'}`;
        pageBtn.style.width = '40px';
        pageBtn.textContent = i;
        pageBtn.onclick = () => {
          barangayCurrentPage = i;
          updateBarangayPagination();
          window.scrollTo({ top: 0, behavior: 'smooth' });
        };
        controls.appendChild(pageBtn);
      }
      
      const nextBtn = document.createElement('button');
      nextBtn.className = 'btn btn-outline btn-sm';
      nextBtn.style.width = 'auto';
      nextBtn.textContent = 'Next';
      nextBtn.disabled = barangayCurrentPage === totalPages;
      nextBtn.onclick = () => {
        if (barangayCurrentPage < totalPages) {
          barangayCurrentPage++;
          updateBarangayPagination();
          window.scrollTo({ top: 0, behavior: 'smooth' });
        }
      };
      controls.appendChild(nextBtn);
    }

    updateBarangayPagination();

    function updateClock() {
      const now = new Date();
      const optionsDate = { weekday: 'long', year: 'numeric', month: 'long', day: 'numeric' };
      const dateStr = now.toLocaleDateString('en-US', optionsDate);
      const timeStr = now.toLocaleTimeString('en-US', { 
        hour: '2-digit', 
        minute: '2-digit', 
        second: '2-digit', 
        hour12: true 
      });
      
      const clockDate = document.getElementById('clockDate');
      const clockTime = document.getElementById('clockTime');
      if (clockDate) clockDate.textContent = dateStr;
      if (clockTime) clockTime.textContent = timeStr;
    }
    setInterval(updateClock, 1000);
    updateClock();
  </script>
</body>
</html>
