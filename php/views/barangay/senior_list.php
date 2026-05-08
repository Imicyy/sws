<!doctype html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width,initial-scale=1">
  <title>Barangay — Senior Citizens List</title>
  <link rel="icon" type="image/png" href="<?= htmlspecialchars(asset_url('images/SilayLogo.png'), ENT_QUOTES) ?>">
  <link rel="stylesheet" href="/files/bower_components/bootstrap/css/bootstrap.min.css">
  <link rel="stylesheet" href="<?= htmlspecialchars(asset_url('css/light-theme.css?v=20260424'), ENT_QUOTES) ?>">
  <style>
    :root {
      --bg-page: linear-gradient(180deg, #fffdf0 0%, #f5f9ff 55%, #eef5ff 100%);
      --panel-border: #dbe5f3;
      --blue-main: #3b82f6;
      --blue-dark: #1d4ed8;
      --yellow-soft: #fef3c7;
      --yellow-main: #facc15;
    }
    body { font-family: Open Sans, Segoe UI, Arial, sans-serif; margin: 0; background: var(--bg-page); color: #1f2937; line-height: 1.45; position: relative; }
    body::before {
      content: "";
      position: fixed;
      top: 0;
      left: 260px;
      width: calc(100% - 260px);
      height: 100%;
      background-image: url('<?= htmlspecialchars(asset_url("images/SilayLogo.jpg"), ENT_QUOTES) ?>');
      background-repeat: no-repeat;
      background-position: center;
      background-size: 600px;
      opacity: 0.05;
      z-index: 0;
      pointer-events: none;
    }
    .layout { display: block; min-height: 100vh; position: relative; z-index: 1; }
    .sidebar { background: linear-gradient(180deg, rgba(20, 32, 74, 0.95) 0%, rgba(35, 66, 140, 0.85) 100%), url('<?= htmlspecialchars(asset_url("images/ebmagtownhall.png"), ENT_QUOTES) ?>') center bottom/cover no-repeat; background-blend-mode: normal; border-right: none; padding: 20px 14px; height: 100vh; width: 260px; box-sizing: border-box; overflow-y: auto; position: fixed; top: 0; left: 0; box-shadow: 10px 0 30px rgba(0, 0, 0, 0.15); display: flex; flex-direction: column; z-index: 100; color: #fff; }
    .brand { font-weight: 800; font-size: 13px; letter-spacing: 0.7px; margin-bottom: 18px; color: #ffffff !important; opacity: 1 !important; visibility: visible !important; }
    .nav-title { font-size: 10px; color: #94a3b8 !important; text-transform: uppercase; margin: 8px 10px; opacity: 1 !important; visibility: visible !important; font-weight: 600; letter-spacing: 0.5px; }
    .sidebar .nav-link { display: flex !important; align-items: center !important; justify-content: flex-start !important; text-align: left !important; min-height: 42px !important; padding: 12px 16px; margin-bottom: 8px; border-radius: 12px; background: transparent !important; color: #e2e8f0 !important; text-decoration: none !important; font-weight: 500; font-size: 13px !important; line-height: 1.4 !important; letter-spacing: .2px !important; text-indent: 0 !important; opacity: 1 !important; visibility: visible !important; transition: all .3s cubic-bezier(0.4, 0, 0.2, 1); border: none; gap: 12px; }
    .sidebar .nav-link.active { background: #3b82f6 !important; color: #ffffff !important; font-weight: 600; box-shadow: 0 4px 15px rgba(59, 130, 246, 0.4) !important; border: none !important; }
    .sidebar .nav-link:hover:not(.active) { background: rgba(255, 255, 255, 0.1) !important; color: #ffffff !important; transform: translateX(2px); border: none !important; }
    .sidebar .logout-link { background: #fee2e2 !important; color: #991b1b !important; font-weight: 700 !important; border: none !important; border-radius: 12px; }
    .sidebar .logout-link:hover { background: #ef4444 !important; color: #fff !important; transform: translateY(-2px) !important; box-shadow: 0 6px 12px rgba(239, 68, 68, 0.2) !important; border: none !important; }
    .nav-fallback-item { padding: 8px 10px; margin-bottom: 4px; border-radius: 8px; font-size: 13px; font-weight: 600; color: #1f2937; cursor: pointer; }
    .nav-fallback-item.active { background: #3b82f6; color: #fff; }
    .main { padding: 24px; margin-left: 260px; }
    .card:hover { box-shadow: 0 16px 40px rgba(59, 130, 246, 0.1); }
    .stats { display: grid; grid-template-columns: repeat(3, minmax(0, 1fr)); gap: 16px; margin-bottom: 24px; }
    .stat-card { background: #fff; border: 1px solid var(--panel-border); border-radius: 20px; padding: 20px; display: flex; align-items: center; gap: 16px; box-shadow: 0 10px 25px rgba(59, 130, 246, 0.06); transition: all 0.3s ease; }
    .stat-card:hover { transform: translateY(-4px); box-shadow: 0 15px 35px rgba(59, 130, 246, 0.1); border-color: #bfdbfe; }
    .stat-icon { width: 56px; height: 56px; border-radius: 50%; display: flex; align-items: center; justify-content: center; flex-shrink: 0; color: #fff; font-size: 24px; }
    .stat-icon.blue { background: #2563eb; }
    .stat-icon.yellow { background: #f59e0b; }
    .stat-icon.slate { background: #64748b; }
    .stat-content { display: flex; flex-direction: column; text-align: left !important; }
    .stat-top-label { font-size: 12px; font-weight: 600; color: #64748b; margin-bottom: 2px; text-transform: uppercase; letter-spacing: 0.5px; }
    .stat-number { font-size: 28px; font-weight: 800; color: #1e293b; line-height: 1.2; }
    .stat-bottom-label { font-size: 12px; font-weight: 500; color: #94a3b8; margin-top: 2px; }
    /* Pagination Styles */
    .pagination-container { display: flex; align-items: center; justify-content: space-between; margin-top: 24px; padding: 16px; border-top: 1px solid #f1f5f9; background: #f8fafc; border-bottom-left-radius: 20px; border-bottom-right-radius: 20px; }
    .pagination-controls { display: flex; align-items: center; gap: 8px; }
    .page-btn { padding: 8px 14px; border: 1px solid #e2e8f0; background: #fff; border-radius: 8px; font-size: 13px; font-weight: 600; color: #475569; cursor: pointer; transition: all 0.2s ease; }
    .page-btn:hover:not(:disabled) { background: #f1f5f9; border-color: #cbd5e1; color: #1e293b; }
    .page-btn.active { background: #3b82f6; border-color: #3b82f6; color: #fff; box-shadow: 0 4px 12px rgba(59, 130, 246, 0.25); }
    .page-btn:disabled { opacity: 0.5; cursor: not-allowed; }
    .pagination-info { font-size: 13px; color: #64748b; font-weight: 500; }
    .items-per-page { padding: 6px 10px; border-radius: 8px; border: 1px solid #e2e8f0; font-size: 13px; font-weight: 600; color: #475569; background: #fff; }
    table { width: 100%; border-collapse: separate; border-spacing: 0; font-size: 13.5px; }
    th, td { padding: 14px 12px; border-bottom: 1px solid #f1f5f9; text-align: left; vertical-align: middle; transition: background-color 0.2s ease; }
    th { background: linear-gradient(135deg, #f8fafc, #f1f5f9); color: #475569; font-weight: 700; letter-spacing: 0.5px; border-bottom: 2px solid #e2e8f0; text-transform: uppercase; font-size: 12px; position: sticky; top: 0; z-index: 1; border-top: none; }
    th:first-child { border-top-left-radius: 12px; }
    th:last-child { border-top-right-radius: 12px; }
    table tr:hover td { background: #f8fafc; }
    #seniorTable th, #seniorTable td { text-align: center; }
    .actions { display: flex; gap: 8px; flex-wrap: wrap; align-items: center; justify-content: center; }
    .actions .btn-sm { width: 36px; height: 36px; padding: 0; font-size: 16px; border-radius: 10px; border: 1px solid #e2e8f0; background: #fff; cursor: pointer; display: inline-flex; align-items: center; justify-content: center; line-height: 1; transition: all 0.25s cubic-bezier(0.4, 0, 0.2, 1); box-shadow: 0 2px 4px rgba(0,0,0,0.02); }
    .btn-view { color: #2563eb; }
    .btn-edit { color: #4f46e5; }
    .btn-archive { color: #dc2626; }
    .actions .btn-sm:hover { transform: translateY(-2px); box-shadow: 0 4px 10px rgba(0,0,0,0.08); border-color: #cbd5e1; }
    .actions .btn-sm:active { transform: translateY(0); box-shadow: none; }
    .icon-only { display: inline-block; transform: translateY(-0.5px); }
    .btn-print-app { padding: 10px 18px; border-radius: 10px; font-weight: 600; font-size: 14px; background: linear-gradient(135deg, #60a5fa, var(--blue-main)); color: #fff; border: none; box-shadow: 0 4px 12px rgba(59, 130, 246, 0.25); transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1); cursor: pointer; }
    .btn-print-app:hover { transform: translateY(-2px); box-shadow: 0 6px 16px rgba(59, 130, 246, 0.3); }
    .btn-print-app:active { transform: translateY(0); box-shadow: none; }
    .status-badge { display: inline-block; padding: 4px 10px; border-radius: 999px; font-size: 11px; font-weight: 700; text-transform: uppercase; letter-spacing: 0.5px; }
    .status-active { background: #dcfce7; color: #166534; border: 1px solid #bbf7d0; }
    .status-archived { background: #f1f5f9; color: #475569; border: 1px solid #e2e8f0; }
    .modal-overlay { display: none; position: fixed; inset: 0; background: rgba(15, 23, 42, 0.4); backdrop-filter: blur(8px); z-index: 2000; align-items: center; justify-content: center; padding: 16px; transition: opacity 0.3s ease; }
    .modal-overlay.show { display: flex; animation: modalOverlayFadeIn 0.3s ease; }
    @keyframes modalOverlayFadeIn { from { opacity: 0; } to { opacity: 1; } }
    .modal-card { width: min(1100px, 100%); background: #fff; border-radius: 20px; box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.25); overflow: hidden; animation: modalFadeIn 0.4s cubic-bezier(0.16, 1, 0.3, 1); }
    @keyframes modalFadeIn { from { opacity: 0; transform: translateY(20px) scale(0.98); } to { opacity: 1; transform: translateY(0) scale(1); } }
    .modal-header { display: flex; align-items: center; justify-content: space-between; padding: 16px 20px; border-bottom: 1px solid #f1f5f9; background: #f8fafc; }
    .modal-body { padding: 16px; }
    .modal-actions { display: flex; justify-content: flex-end; gap: 10px; padding: 16px 20px; border-top: 1px solid #f1f5f9; background: #f8fafc; }
    #viewModal .modal-actions { justify-content: flex-end; }
    .modal-actions button { border: 1px solid #e2e8f0; background: #fff; border-radius: 10px; padding: 10px 18px; font-size: 14px; font-weight: 600; cursor: pointer; transition: all 0.25s cubic-bezier(0.4, 0, 0.2, 1); color: #475569; box-shadow: 0 2px 4px rgba(0,0,0,0.02); }
    .modal-actions button:hover { transform: translateY(-2px); box-shadow: 0 4px 12px rgba(0,0,0,0.08); border-color: #cbd5e1; }
    .modal-actions button:active { transform: translateY(0); box-shadow: none; }
    .modal-actions .btn-print-app { background: linear-gradient(135deg, #60a5fa, var(--blue-main)); border: none; color: #fff; box-shadow: 0 4px 12px rgba(59, 130, 246, 0.25); }
    .modal-actions .btn-print-app:hover { box-shadow: 0 6px 16px rgba(59, 130, 246, 0.3); color: #fff; transform: translateY(-2px); }
    .modal-close { border: none; background: transparent; font-size: 24px; line-height: 1; cursor: pointer; color: #94a3b8; transition: color 0.2s ease; }
    .modal-close:hover { color: #1e293b; }
    .modal-body-view-inner { display: flex; flex-direction: column; gap: 10px; padding: 12px; max-height: min(82vh, 860px); }
    .view-edit-log-panel { flex: 0 0 auto; max-height: 220px; overflow: auto; border: 1px solid #e2e8f0; border-radius: 12px; background: #fff; padding: 12px; box-shadow: 0 2px 8px rgba(0,0,0,0.02); }
    .view-edit-log-panel h6 { margin: 0 0 10px 0; font-size: 14px; line-height: 1.2; color: var(--blue-dark); font-weight: 700; }
    .view-edit-log-table-wrap { border: 1px solid #e2e8f0; border-radius: 8px; overflow: hidden; }
    .view-edit-log-table { width: 100%; border-collapse: separate; border-spacing: 0; font-size: 13px; }
    .view-edit-log-table th, .view-edit-log-table td { padding: 10px; border-bottom: 1px solid #f1f5f9; text-align: left; vertical-align: top; }
    .view-edit-log-table th { background: #f8fafc; color: #475569; position: sticky; top: 0; font-weight: 600; z-index: 1; white-space: nowrap; border-bottom: 2px solid #e2e8f0; }
    .edit-frame-body { padding: 0; flex: 1 1 auto; min-height: 320px; height: min(58vh, 560px); }
    .edit-frame-body iframe { width: 100%; height: 100%; border: none; }
    .toolbar-search { width: 100%; max-width: 320px; padding: 10px 14px; border: 1px solid #cbd5e1; border-radius: 10px; font-size: 14px; }
    input, select, textarea { transition: border-color .2s ease, box-shadow .2s ease; }
    input:focus, select:focus, textarea:focus { outline: none; border-color: var(--blue-main) !important; box-shadow: 0 0 0 4px rgba(59, 130, 246, 0.15); }
    .birthday-modal-table { width: 100%; border-collapse: separate; border-spacing: 0; }
    .birthday-modal-table th, .birthday-modal-table td { border-bottom: 1px solid #f1f5f9; padding: 12px 10px; font-size: 13px; text-align: left; transition: background-color 0.2s ease; }
    .birthday-modal-table th { background: linear-gradient(135deg, #f8fafc, #f1f5f9); font-weight: 600; text-transform: uppercase; color: #475569; border-bottom: 2px solid #e2e8f0; }
    .birthday-modal-table tr:hover td { background: #f8fafc; }
    .modal-actions .primary { background: linear-gradient(135deg, #60a5fa, var(--blue-main)); border: none; color: #fff; box-shadow: 0 4px 12px rgba(59, 130, 246, 0.25); }
    .modal-actions .primary:hover { box-shadow: 0 6px 16px rgba(59, 130, 246, 0.3); border-color: transparent; }
    .muted { color: #64748b; font-size: 13px; font-weight: 500; }
    .add-record-btn {
      display: inline-flex;
      align-items: center;
      justify-content: center;
      padding: 10px 18px;
      border-radius: 10px;
      border: none;
      background: linear-gradient(135deg, #fef3c7, var(--yellow-main));
      color: #713f12 !important;
      font-weight: 600;
      text-decoration: none !important;
      line-height: 1.2;
      font-size: 14px;
      box-shadow: 0 4px 12px rgba(250, 204, 21, 0.25);
      transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
    }
    .add-record-btn:hover,
    .add-record-btn:focus {
      color: #713f12 !important;
      text-decoration: none !important;
      transform: translateY(-2px);
      box-shadow: 0 6px 16px rgba(250, 204, 21, 0.35);
    }
    @media (max-width: 980px) {
      .sidebar { position: relative; width: 100%; height: auto; min-height: 0; z-index: 1; }
      .main { margin-left: 0; }
      body::before { left: 0; width: 100%; }
    }
  </style>
</head>
<body>
  <script>window.__APP_BASE__=<?= json_encode(app_base_path(), JSON_UNESCAPED_UNICODE) ?>;</script>
  <div class="layout">
    <aside class="sidebar">
      <div class="brand" style="display: flex; align-items: center; gap: 10px;">
        <img src="<?= htmlspecialchars(asset_url('images/SilayLogo.png'), ENT_QUOTES) ?>" alt="Logo" style="height: 32px; width: 32px; object-fit: contain;">
        <span>ENRIQUE B. MAGALONA</span>
      </div>
      <div class="nav-title">Navigation</div>
      <a class="nav-link" href="<?= htmlspecialchars(app_url('/barangay'), ENT_QUOTES, 'UTF-8') ?>">
        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M3 3v18h18"/><path d="M18 17V9"/><path d="M13 17V5"/><path d="M8 17v-3"/></svg>
        PWD Analytics
      </a>
      <a class="nav-link" href="<?= htmlspecialchars(app_url('/barangay-senior-dashboard'), ENT_QUOTES, 'UTF-8') ?>">
        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="22 12 18 12 15 21 9 3 6 12 2 12"/></svg>
        Senior Citizen Analytics
      </a>
      <a class="nav-link" href="<?= htmlspecialchars(app_url('/barangay-pwd'), ENT_QUOTES, 'UTF-8') ?>">
        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/><polyline points="14 2 14 8 20 8"/><line x1="16" y1="13" x2="8" y2="13"/><line x1="16" y1="17" x2="8" y2="17"/><polyline points="10 9 9 9 8 9"/></svg>
        PWD List
      </a>
      <a class="nav-link active" href="<?= htmlspecialchars(app_url('/barangay-senior'), ENT_QUOTES, 'UTF-8') ?>">
        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/><path d="M23 21v-2a4 4 0 0 0-3-3.87"/><path d="M16 3.13a4 4 0 0 1 0 7.75"/></svg>
        Senior Citizens List
      </a>
      <a class="nav-link logout-link" style="margin-top:auto !important;" href="<?= htmlspecialchars(app_url('/logout'), ENT_QUOTES, 'UTF-8') ?>">
        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4"/><polyline points="16 17 21 12 16 7"/><line x1="21" y1="12" x2="9" y2="12"/></svg>
        Logout
      </a>
    </aside>

    <main class="main">
      <div class="top-header" style="display:flex;align-items:center;justify-content:center;position:relative;margin-bottom:24px;">
        <div style="text-align:center;">
          <h1 class="h4" style="margin:0;font-weight:700;color:#1e293b;">Barangay — Senior Citizens List</h1>
          <?php if (!empty($assignedBarangayName)): ?>
          <p class="text-muted mb-0" style="font-size: 13px;"><strong>Barangay:</strong> <?= htmlspecialchars((string) $assignedBarangayName, ENT_QUOTES, 'UTF-8') ?></p>
          <?php endif; ?>
        </div>
        <div style="position:absolute;right:0;top:0;display:flex;align-items:center;gap:20px;">
          <div id="liveClock" style="font-size:14px;font-weight:700;color:#1e293b;text-align:left;line-height:1.1;border-left:3px solid #3b82f6;padding-left:20px;margin-left:5px;">
            <div id="clockDate" style="font-size:15px;color:#64748b;font-weight:600;margin-bottom:4px;"></div>
            <div id="clockTime" style="color:#2563eb;font-size:28px;font-weight:800;font-variant-numeric: tabular-nums;"></div>
          </div>
        </div>
      </div>
      <?php
        $totalCount = count($seniors ?? []);
        $activeCount = 0;
        $archivedCount = 0;
        foreach (($seniors ?? []) as $s) {
          if (($s['status'] ?? 'Active') === 'Archived') $archivedCount++;
          else $activeCount++;
        }
      ?>
      <div class="stats">
        <div class="stat-card">
          <div class="stat-icon blue">
            <svg width="26" height="26" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/><path d="M23 21v-2a4 4 0 0 0-3-3.87"/><path d="M16 3.13a4 4 0 0 1 0 7.75"/></svg>
          </div>
          <div class="stat-content">
            <div class="stat-top-label">Active Seniors</div>
            <div class="stat-number"><?= $activeCount ?></div>
            <div class="stat-bottom-label" style="color: #16a34a; font-weight: 700;">↑ 1.8% Increase</div>
          </div>
        </div>
        <div class="stat-card">
          <div class="stat-icon slate">
            <svg width="26" height="26" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><polyline points="21 8 21 21 3 21 3 8"/><rect x="1" y="3" width="22" height="5"/><line x1="10" y1="12" x2="14" y2="12"/></svg>
          </div>
          <div class="stat-content">
            <div class="stat-top-label">Archived</div>
            <div class="stat-number"><?= $archivedCount ?></div>
            <div class="stat-bottom-label">Inactive Records</div>
          </div>
        </div>
        <div class="stat-card" style="border-color: #3b82f6; background: rgba(59, 130, 246, 0.02);">
          <div class="stat-icon blue" style="background: linear-gradient(135deg, #3b82f6, #1d4ed8);">
            <svg width="26" height="26" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/><polyline points="14 2 14 8 20 8"/><line x1="16" y1="13" x2="8" y2="13"/><line x1="16" y1="17" x2="8" y2="17"/><polyline points="10 9 9 9 8 9"/></svg>
          </div>
          <div class="stat-content">
            <div class="stat-top-label" style="color: #1e40af;">Total Records</div>
            <div class="stat-number" style="color: #1e3a8a;"><?= $totalCount ?></div>
            <div class="stat-bottom-label">Global Registry</div>
          </div>
        </div>
      </div>

      <div class="card">
        <div style="display:flex;justify-content:center;align-items:center;position:relative;">
          <h2 style="margin:0;text-align:center;">Senior — <?= htmlspecialchars((string)($assignedBarangayName ?? ''), ENT_QUOTES, 'UTF-8') ?></h2>
          <div style="display:flex;align-items:center;gap:8px;position:absolute;right:0;">
            <button type="button" class="btn btn-sm" id="birthdaysBtn" style="border:1px solid #d1d5db;border-radius:8px;padding:9px 12px;background:#fff;font-weight:700;">
              🎂 Birthdays <span id="birthdaysBadge" style="display:none;margin-left:6px;background:#3b82f6;color:#fff;border-radius:999px;padding:2px 8px;font-size:12px;">0</span>
            </button>
            <a href="<?= htmlspecialchars(app_url('/add_senior'), ENT_QUOTES, 'UTF-8') ?>" target="_blank" rel="noopener noreferrer" class="add-record-btn">ADD SENIOR</a>
          </div>
        </div>

        <div style="margin-top:16px; background:#fff; padding:14px; border-radius:8px; border:1px solid #eef2f6; display:flex; gap:12px; align-items:center;">
          <div style="flex:1">
            <label style="font-size:12px; color:#6b7280; display:block; margin-bottom:6px;">Barangay:</label>
            <input type="text" value="<?= htmlspecialchars((string)($assignedBarangayName ?? ''), ENT_QUOTES, 'UTF-8') ?>" disabled style="width:100%; padding:8px; border-radius:6px; border:1px solid #e5e7eb; background:#f8fafc;">
          </div>
          <div style="flex:1">
            <label style="font-size:12px; color:#6b7280; display:block; margin-bottom:6px;">Purok:</label>
            <select id="purokFilter" style="width:100%; padding:8px; border-radius:6px; border:1px solid #e5e7eb;">
              <option value="">Select Purok</option>
              <?php foreach (($puroks ?? []) as $pr): ?>
                <option value="<?= htmlspecialchars((string)$pr, ENT_QUOTES, 'UTF-8') ?>"><?= htmlspecialchars((string)$pr, ENT_QUOTES, 'UTF-8') ?></option>
              <?php endforeach; ?>
            </select>
          </div>
          <div style="width:160px">
            <label style="font-size:12px; color:#6b7280; display:block; margin-bottom:6px;">Status:</label>
            <select id="statusFilter" style="width:100%; padding:8px; border-radius:6px; border:1px solid #e5e7eb;">
              <option value="">Active Only</option>
              <option value="Active">Active</option>
              <option value="Archived">Archived</option>
              <option value="all">All</option>
            </select>
          </div>
          <div style="width:220px">
            <label style="font-size:12px; color:#6b7280; display:block; margin-bottom:6px;">Search:</label>
            <input id="searchInput" type="text" placeholder="Search..." style="width:100%; padding:8px; border-radius:6px; border:1px solid #e5e7eb;">
          </div>
        </div>

        <div style="margin-top:16px; overflow:auto">
          <table id="seniorTable" class="table table-striped">
            <thead class="thead-dark">
              <tr>
                <th>FULL NAME</th>
                <th>AGE</th>
                <th>BARANGAY</th>
                <th>PUROK</th>
                <th>GENDER</th>
                <th>STATUS</th>
                <th>ACTIONS</th>
              </tr>
            </thead>
            <tbody>
              <?php foreach (($seniors ?? []) as $s): ?>
                <?php 
                  $parts = [
                    (string)($s['first_name'] ?? ''),
                    (string)($s['middle_name'] ?? ''),
                    (string)($s['last_name'] ?? ''),
                    (string)($s['extension'] ?? '')
                  ];
                  $full = implode(' ', array_filter(array_map('trim', $parts)));
                ?>
                <?php
                  $status = (string)($s['status'] ?? 'Active');
                  $statusClass = $status === 'Archived' ? 'status-archived' : 'status-active';
                ?>
                <tr data-senior-id="<?= (int)($s['id'] ?? 0) ?>" data-purok="<?= htmlspecialchars((string)($s['purok'] ?? ''), ENT_QUOTES, 'UTF-8') ?>" data-status="<?= htmlspecialchars($status, ENT_QUOTES, 'UTF-8') ?>" data-senior='<?= htmlspecialchars(json_encode($s, JSON_UNESCAPED_UNICODE), ENT_QUOTES, 'UTF-8') ?>'>
                  <td><?= htmlspecialchars(strtoupper($full), ENT_QUOTES, 'UTF-8') ?></td>
                  <td><?= htmlspecialchars((string)($s['age'] ?? ''), ENT_QUOTES, 'UTF-8') ?></td>
                  <td><?= htmlspecialchars((string)($s['barangay'] ?? ''), ENT_QUOTES, 'UTF-8') ?></td>
                  <td><?= htmlspecialchars((string)($s['purok'] ?? ''), ENT_QUOTES, 'UTF-8') ?></td>
                  <td><?= htmlspecialchars((string)($s['gender'] ?? ''), ENT_QUOTES, 'UTF-8') ?></td>
                  <td><span class="status-badge <?= $statusClass ?>"><?= htmlspecialchars($status, ENT_QUOTES, 'UTF-8') ?></span></td>
                  <td>
                    <div class="actions">
                      <button type="button" class="btn-sm btn-view view-btn" title="View" aria-label="View"><span class="icon-only"><svg width="16" height="16" viewBox="0 0 24 24" fill="none" aria-hidden="true"><path d="M1 12C2.9 8.4 6.1 6 12 6C17.9 6 21.1 8.4 23 12C21.1 15.6 17.9 18 12 18C6.1 18 2.9 15.6 1 12Z" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"></path><circle cx="12" cy="12" r="3" stroke="currentColor" stroke-width="2"></circle></svg></span></button>
                      <button type="button" class="btn-sm btn-edit edit-btn" title="Edit" aria-label="Edit"><span class="icon-only"><svg width="16" height="16" viewBox="0 0 24 24" fill="none" aria-hidden="true"><path d="M12 20H21" stroke="currentColor" stroke-width="2" stroke-linecap="round"></path><path d="M16.5 3.5C17.3 2.7 18.7 2.7 19.5 3.5L20.5 4.5C21.3 5.3 21.3 6.7 20.5 7.5L9 19L4 20L5 15L16.5 3.5Z" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"></path></svg></span></button>
                      <button type="button" class="btn-sm btn-archive archive-btn" data-status="<?= htmlspecialchars($status, ENT_QUOTES, 'UTF-8') ?>" title="<?= $status === 'Archived' ? 'Unarchive' : 'Archive' ?>" aria-label="<?= $status === 'Archived' ? 'Unarchive' : 'Archive' ?>"><span class="icon-only"><?= $status === 'Archived' ? '<svg width="16" height="16" viewBox="0 0 24 24" fill="none" aria-hidden="true"><path d="M3 12C3 7 7 3 12 3C15 3 17.6 4.2 19.3 6.2" stroke="currentColor" stroke-width="2" stroke-linecap="round"></path><path d="M21 3V7H17" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"></path><path d="M21 12C21 17 17 21 12 21C9 21 6.4 19.8 4.7 17.8" stroke="currentColor" stroke-width="2" stroke-linecap="round"></path><path d="M3 21V17H7" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"></path></svg>' : '<svg width="16" height="16" viewBox="0 0 24 24" fill="none" aria-hidden="true"><path d="M3 7H21" stroke="currentColor" stroke-width="2" stroke-linecap="round"></path><path d="M5 7L6 20H18L19 7" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"></path><path d="M9 11H15" stroke="currentColor" stroke-width="2" stroke-linecap="round"></path><path d="M9 15H15" stroke="currentColor" stroke-width="2" stroke-linecap="round"></path><path d="M4 4H20V7H4V4Z" stroke="currentColor" stroke-width="2" stroke-linejoin="round"></path></svg>' ?></span></button>
                    </div>
                  </td>
                </tr>
              <?php endforeach; ?>
            </tbody>
          </table>
        </div>
        <div class="pagination-container">
          <div class="pagination-info" id="paginationInfo">Showing 0 to 0 of 0 entries</div>
          <div class="pagination-controls">
            <div style="display:flex; align-items:center; gap:12px; margin-right:16px;">
              <span style="font-size:12px; color:#64748b; font-weight:600;">Show:</span>
              <select id="itemsPerPage" class="items-per-page">
                <option value="5">5</option>
                <option value="10">10</option>
                <option value="25">25</option>
                <option value="50">50</option>
                <option value="100">100</option>
              </select>
            </div>
            <button class="page-btn" id="prevPage" disabled>&laquo; Prev</button>
            <div id="pageNumbers" style="display:flex; gap:5px;"></div>
            <button class="page-btn" id="nextPage" disabled>Next &raquo;</button>
          </div>
        </div>
      </div>
      </div>
    </main>
  </div>
  <div id="viewModal" class="modal-overlay" role="dialog" aria-modal="true">
    <div class="modal-card">
      <div class="modal-header">
        <h5 style="margin:0;">View Senior Record</h5>
      </div>
      <div class="modal-body modal-body-view-inner">
        <div id="viewEditLogsSection" class="view-edit-log-panel">
          <h6>Edit Logs</h6>
          <div id="viewEditLogsBody" class="muted" style="font-size:12px;">Open a record to see changes.</div>
        </div>
        <div class="edit-frame-body">
          <iframe id="viewSeniorFrame" title="View Senior Form" loading="lazy" src="about:blank"></iframe>
        </div>
      </div>
      <div class="modal-actions">
        <button type="button" id="printApplicationBtn" class="btn-print-app">Print Application</button>
        <button type="button" data-close="viewModal">Close</button>
      </div>
    </div>
  </div>
  <div id="editModal" class="modal-overlay" role="dialog" aria-modal="true">
    <div class="modal-card">
      <div class="modal-header">
        <h5 style="margin:0;">Edit Senior Record</h5>
      </div>
      <div class="modal-body edit-frame-body">
        <iframe id="editSeniorFrame" title="Edit Senior Form" loading="lazy" src="about:blank"></iframe>
      </div>
      <div class="modal-actions">
        <button type="button" data-close="editModal">Close</button>
      </div>
    </div>
  </div>
  <div id="birthdaysModal" class="modal-overlay" role="dialog" aria-modal="true">
    <div class="modal-card" style="width:min(1120px,100%);">
      <div class="modal-header">
        <h5 style="margin:0;">Senior Birthdays</h5>
        <button type="button" class="modal-close" data-close="birthdaysModal">&times;</button>
      </div>
      <div class="modal-body">
        <div style="display:flex;flex-wrap:wrap;gap:8px;align-items:center;margin-bottom:10px;">
          <input id="bdaySearch" class="toolbar-search" placeholder="Search name/barangay/purok...">
          <select id="bdayRange" style="padding:8px;border:1px solid #d1d5db;border-radius:8px;">
            <option value="today">Today's birthdays</option>
            <option value="month" selected>This month's birthdays</option>
          </select>
          <input id="bdayPurok" placeholder="Purok (optional)" style="padding:8px;border:1px solid #d1d5db;border-radius:8px;min-width:180px;">
        </div>
        <div style="overflow:auto;max-height:420px;border:1px solid #e5e7eb;border-radius:8px;">
          <table class="birthday-modal-table">
            <thead>
              <tr>
                <th style="width:34px;"><input type="checkbox" id="birthdaysSelectAll"></th>
                <th>Full Name</th>
                <th>Birth Date</th>
                <th>Barangay</th>
                <th>Purok</th>
              </tr>
            </thead>
            <tbody id="birthdaysTableBody"><tr><td colspan="5" class="text-center muted">Loading...</td></tr></tbody>
          </table>
        </div>
        <small id="birthdaysCount" class="muted" style="display:block;margin-top:8px;">0 results</small>
      </div>
      <div class="modal-actions">
        <button type="button" id="birthdaysSendSmsBtn" class="primary" disabled>Send SMS</button>
        <button type="button" id="birthdaysViewHistoryBtn">View SMS History</button>
        <button type="button" data-close="birthdaysModal">Close</button>
      </div>
    </div>
  </div>
  <div id="smsModal" class="modal-overlay" role="dialog" aria-modal="true">
    <div class="modal-card">
      <div class="modal-header">
        <h5 style="margin:0;">Send SMS Assistance</h5>
        <button type="button" class="modal-close" data-close="smsModal">&times;</button>
      </div>
      <div class="modal-body">
        <div id="recipientsList" style="max-height:160px; overflow:auto; border:1px solid #e5e7eb; border-radius:8px; padding:8px; margin-bottom:10px;"></div>
        <label for="smsMessage" class="muted" style="font-weight:700;">Message</label>
        <textarea id="smsMessage" rows="4" style="width:100%; border:1px solid #d1d5db; border-radius:8px; padding:8px;"></textarea>
        <div class="muted" style="margin-top:6px;">Characters: <span id="charCount">0</span></div>
      </div>
      <div class="modal-actions">
        <button type="button" id="sendSmsModalBtn" class="primary">Send SMS</button>
        <button type="button" data-close="smsModal">Close</button>
      </div>
    </div>
  </div>
  <div id="smsHistoryModal" class="modal-overlay" role="dialog" aria-modal="true">
    <div class="modal-card" style="width:min(1100px,100%);">
      <div class="modal-header">
        <h5 style="margin:0;">SMS History</h5>
        <button type="button" class="modal-close" data-close="smsHistoryModal">&times;</button>
      </div>
      <div class="modal-body">
        <div style="overflow:auto;">
          <table class="birthday-modal-table">
            <thead><tr><th>Sent At</th><th>Phone</th><th>Name</th><th>Barangay</th><th>Purok</th><th>Message</th><th>Status</th></tr></thead>
            <tbody id="smsHistoryTableBody"><tr><td colspan="7" class="text-center muted">No SMS history yet.</td></tr></tbody>
          </table>
        </div>
      </div>
      <div class="modal-actions"><button type="button" data-close="smsHistoryModal">Close</button></div>
    </div>
  </div>
  <div id="archiveConfirmModal" class="modal-overlay" role="dialog" aria-modal="true">
    <div class="modal-card" style="width:min(520px,100%);">
      <div class="modal-header">
        <h5 id="archiveModalTitle" style="margin:0;">Archive Senior Record</h5>
        <button type="button" class="modal-close" data-close="archiveConfirmModal">&times;</button>
      </div>
      <div class="modal-body">
        <p id="archiveModalMessage" class="muted" style="margin:0 0 10px 0;">Please provide a reason for archiving this record.</p>
        <label id="archiveReasonLabel" for="archiveReasonInput" style="display:block;font-size:12px;color:#6b7280;margin-bottom:6px;">Reason (optional)</label>
        <textarea id="archiveReasonInput" rows="4" style="width:100%;padding:10px;border:1px solid #d1d5db;border-radius:8px;resize:vertical;" placeholder="Enter archive reason..."></textarea>
      </div>
      <div class="modal-actions">
        <button type="button" id="archiveConfirmBtn" class="primary">Archive</button>
        <button type="button" data-close="archiveConfirmModal">Cancel</button>
      </div>
    </div>
  </div>
  <script>
    (function updateClock() {
      const now = new Date();
      const dateEl = document.getElementById('clockDate');
      const timeEl = document.getElementById('clockTime');
      if (dateEl) dateEl.textContent = now.toLocaleDateString('en-US', { weekday: 'long', month: 'short', day: 'numeric', year: 'numeric' });
      if (timeEl) timeEl.textContent = now.toLocaleTimeString('en-US', { hour: '2-digit', minute: '2-digit', second: '2-digit' });
      setTimeout(updateClock, 1000);
    })();
  </script>
</body>
</html>
<script>
  (function(){
    function appPath(p) {
      var b = window.__APP_BASE__ || '';
      return b + (p.charAt(0) === '/' ? p : '/' + p);
    }
    const purokFilter = document.getElementById('purokFilter');
    const statusFilter = document.getElementById('statusFilter');
    const searchInput = document.getElementById('searchInput');
    const viewSeniorFrame = document.getElementById('viewSeniorFrame');
    const editSeniorFrame = document.getElementById('editSeniorFrame');
    const assignedBarangay = <?= json_encode((string)($assignedBarangayName ?? ''), JSON_UNESCAPED_UNICODE) ?>;
    const birthdayGreetingMessage = 'Happy Birthday! Greetings from Mayor Matthew Louis P. Malacon and Vice Mayor Marvin M. Malacon.';
    let currentViewSeniorId = null;
    let birthdaysCache = [];

    function openModal(id) {
      const modal = document.getElementById(id);
      if (modal) modal.classList.add('show');
    }

    function closeModal(id) {
      const modal = document.getElementById(id);
      if (!modal) return;
      modal.classList.remove('show');
      if (id === 'viewModal' && viewSeniorFrame) viewSeniorFrame.src = 'about:blank';
      if (id === 'viewModal') {
        const logsEl = document.getElementById('viewEditLogsBody');
        if (logsEl) logsEl.innerHTML = 'Open a record to see changes.';
        currentViewSeniorId = null;
      }
      if (id === 'editModal' && editSeniorFrame) editSeniorFrame.src = 'about:blank';
      if (id === 'archiveConfirmModal' && archiveModalResolve) {
        const resolve = archiveModalResolve;
        archiveModalResolve = null;
        resolve(null);
      }
    }

    function getSeniorFromRow(row) {
      if (!row) return {};
      const raw = row.getAttribute('data-senior');
      if (!raw) return {};
      try { return JSON.parse(raw) || {}; } catch (_) { return {}; }
    }

    function getSeniorIdFromRow(row) {
      if (!row) return 0;
      const ds = Number(row.getAttribute('data-senior-id') || 0);
      if (Number.isFinite(ds) && ds > 0) return ds;
      const s = getSeniorFromRow(row);
      const nid = Number(s.id ?? s._id ?? 0);
      return Number.isFinite(nid) ? nid : 0;
    }

    function escapeHtml(text) {
      const d = document.createElement('div');
      d.textContent = text === null || text === undefined ? '' : String(text);
      return d.innerHTML;
    }

    function formatLogDateTime(value) {
      if (!value) return '—';
      const d = new Date(value);
      if (!Number.isNaN(d.getTime())) {
        return d.toLocaleString('en-PH', {
          month: 'short',
          day: '2-digit',
          year: 'numeric',
          hour: '2-digit',
          minute: '2-digit',
          second: '2-digit',
          hour12: true
        });
      }
      return escapeHtml(String(value));
    }

    function renderViewEditLogs(rows) {
      const el = document.getElementById('viewEditLogsBody');
      if (!el) return;
      if (!Array.isArray(rows) || rows.length === 0) {
        el.innerHTML = '<span class="muted">No edit history logged yet.</span>';
        return;
      }
      const body = rows.map(function (log) {
        return '<tr>'
          + '<td>' + escapeHtml(log.field || '—') + '</td>'
          + '<td>' + escapeHtml(log.old_value ?? '—') + '</td>'
          + '<td>' + escapeHtml(log.new_value ?? '—') + '</td>'
          + '<td>' + escapeHtml(log.edited_by || '—') + '</td>'
          + '<td>' + formatLogDateTime(log.edited_at || '') + '</td>'
          + '</tr>';
      }).join('');
      el.innerHTML = '<div class="view-edit-log-table-wrap"><div style="overflow:auto;max-height:140px;"><table class="view-edit-log-table"><thead><tr>'
        + '<th>Field</th><th>Old Value</th><th>New Value</th><th>Editor</th><th>Date and Time</th>'
        + '</tr></thead><tbody>' + body + '</tbody></table></div></div>';
    }

    async function loadViewSeniorEditLogs(residentId) {
      const el = document.getElementById('viewEditLogsBody');
      if (!el) return;
      if (!residentId) {
        el.innerHTML = '<span class="muted">Unable to load edit history.</span>';
        return;
      }
      el.innerHTML = '<span class="muted">Loading edit history...</span>';
      try {
        const res = await fetch(appPath('/api/senior-edit-logs/' + encodeURIComponent(String(residentId))), { credentials: 'same-origin' });
        const json = await res.json();
        if (!res.ok || !json || json.success !== true) {
          throw new Error((json && json.message) ? json.message : 'Failed to load edit history.');
        }
        renderViewEditLogs(json.data || []);
      } catch (e) {
        el.innerHTML = '<span style="color:#b91c1c;font-size:12px;">' + escapeHtml(e.message || 'Failed to load.') + '</span>';
      }
    }

    function formatBirthDate(value) {
      const d = new Date(value);
      if (Number.isNaN(d.getTime())) return value || 'N/A';
      return d.toLocaleDateString('en-PH', { year: 'numeric', month: 'long', day: 'numeric' });
    }

    function getBirthdayRowById(id) {
      return birthdaysCache.find(function (row) { return String(row.id || '') === String(id || ''); }) || null;
    }

    function updateRowStatus(row, status) {
      const badge = row.querySelector('.status-badge');
      const archiveBtn = row.querySelector('.archive-btn');
      if (badge) {
        badge.textContent = status;
        badge.classList.remove('status-active', 'status-archived');
        badge.classList.add(status === 'Archived' ? 'status-archived' : 'status-active');
      }
      row.dataset.status = status;
      if (archiveBtn) {
        const label = status === 'Archived' ? 'Unarchive' : 'Archive';
        archiveBtn.dataset.status = status;
        archiveBtn.title = label;
        archiveBtn.setAttribute('aria-label', label);
        archiveBtn.innerHTML = '<span class="icon-only">' + (status === 'Archived'
          ? '<svg width="16" height="16" viewBox="0 0 24 24" fill="none" aria-hidden="true"><path d="M3 12C3 7 7 3 12 3C15 3 17.6 4.2 19.3 6.2" stroke="currentColor" stroke-width="2" stroke-linecap="round"></path><path d="M21 3V7H17" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"></path><path d="M21 12C21 17 17 21 12 21C9 21 6.4 19.8 4.7 17.8" stroke="currentColor" stroke-width="2" stroke-linecap="round"></path><path d="M3 21V17H7" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"></path></svg>'
          : '<svg width="16" height="16" viewBox="0 0 24 24" fill="none" aria-hidden="true"><path d="M3 7H21" stroke="currentColor" stroke-width="2" stroke-linecap="round"></path><path d="M5 7L6 20H18L19 7" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"></path><path d="M9 11H15" stroke="currentColor" stroke-width="2" stroke-linecap="round"></path><path d="M9 15H15" stroke="currentColor" stroke-width="2" stroke-linecap="round"></path><path d="M4 4H20V7H4V4Z" stroke="currentColor" stroke-width="2" stroke-linejoin="round"></path></svg>') + '</span>';
      }
      const senior = getSeniorFromRow(row);
      senior.status = status;
      row.setAttribute('data-senior', JSON.stringify(senior));
    }

    function applyFilters(){
      const purok = purokFilter ? purokFilter.value.trim().toLowerCase() : '';
      const filterVal = statusFilter ? statusFilter.value : '';
      const search = searchInput ? searchInput.value.trim().toLowerCase() : '';
      filteredRows = [];

      document.querySelectorAll('#seniorTable tbody tr').forEach(function(row){
        const rowPurok = (row.dataset.purok || '').toLowerCase();
        const rowStatus = (row.dataset.status || 'Active');
        const cols = row.querySelectorAll('td');
        const name = (cols[0] ? cols[0].textContent : '').toLowerCase();

        let show = true;
        if (purok && rowPurok !== purok) show = false;
        
        // Filter logic: 
        // "" (Active Only) -> hide Archived
        // "all" -> show everything
        // "Active" or "Archived" -> match exact status
        if (filterVal === '') {
          if (rowStatus === 'Archived') show = false;
        } else if (filterVal !== 'all') {
          if (rowStatus !== filterVal) show = false;
        }

        if (search && name.indexOf(search) === -1 && rowPurok.indexOf(search) === -1) show = false;

        row.style.display = show ? '' : 'none';
        if (show) filteredRows.push(row);
      });

      currentPage = 1;
      updatePagination();
    }

    let currentPage = 1;
    let itemsPerPage = 5;
    let filteredRows = [];

    function updatePagination() {
      const totalItems = filteredRows.length;
      const totalPages = Math.ceil(totalItems / itemsPerPage);
      
      if (currentPage > totalPages && totalPages > 0) currentPage = totalPages;
      if (currentPage < 1) currentPage = 1;

      const start = (currentPage - 1) * itemsPerPage;
      const end = Math.min(start + itemsPerPage, totalItems);

      // Hide all filtered rows first
      filteredRows.forEach(row => row.style.display = 'none');
      
      // Show only rows for current page
      for (let i = start; i < end; i++) {
        if (filteredRows[i]) filteredRows[i].style.display = '';
      }

      // Update info
      const info = document.getElementById('paginationInfo');
      if (info) info.textContent = `Showing ${totalItems > 0 ? start + 1 : 0} to ${end} of ${totalItems} entries`;

      // Update buttons
      const prevBtn = document.getElementById('prevPage');
      const nextBtn = document.getElementById('nextPage');
      if (prevBtn) prevBtn.disabled = currentPage <= 1;
      if (nextBtn) nextBtn.disabled = currentPage >= totalPages;

      renderPageNumbers(totalPages);
    }

    function renderPageNumbers(totalPages) {
      const container = document.getElementById('pageNumbers');
      if (!container) return;
      container.innerHTML = '';
      
      let startPage = Math.max(1, currentPage - 2);
      let endPage = Math.min(totalPages, startPage + 4);
      if (endPage - startPage < 4) startPage = Math.max(1, endPage - 4);

      for (let i = startPage; i <= endPage; i++) {
        const btn = document.createElement('button');
        btn.className = `page-btn ${i === currentPage ? 'active' : ''}`;
        btn.textContent = i;
        btn.onclick = () => { currentPage = i; updatePagination(); };
        container.appendChild(btn);
      }
    }

    const prevBtn = document.getElementById('prevPage');
    const nextBtn = document.getElementById('nextPage');
    const itemsSelect = document.getElementById('itemsPerPage');

    if (prevBtn) prevBtn.onclick = () => { if (currentPage > 1) { currentPage--; updatePagination(); } };
    if (nextBtn) nextBtn.onclick = () => { if (currentPage < Math.ceil(filteredRows.length / itemsPerPage)) { currentPage++; updatePagination(); } };
    if (itemsSelect) itemsSelect.onchange = (e) => { itemsPerPage = parseInt(e.target.value); currentPage = 1; updatePagination(); };

    if (purokFilter) purokFilter.addEventListener('change', applyFilters);
    if (statusFilter) statusFilter.addEventListener('change', applyFilters);
    if (searchInput) searchInput.addEventListener('input', applyFilters);
    document.querySelectorAll('[data-close]').forEach(function (button) {
      button.addEventListener('click', function () {
        closeModal(button.dataset.close);
      });
    });
    document.querySelectorAll('.modal-overlay').forEach(function (modal) {
      modal.addEventListener('click', function (event) {
        if (event.target === modal) closeModal(modal.id);
      });
    });
    const birthdaysBtn = document.getElementById('birthdaysBtn');
    const birthdaysBadge = document.getElementById('birthdaysBadge');
    const birthdaysBody = document.getElementById('birthdaysTableBody');
    const birthdaysCount = document.getElementById('birthdaysCount');
    const bdaySearch = document.getElementById('bdaySearch');
    const bdayRange = document.getElementById('bdayRange');
    const bdayPurok = document.getElementById('bdayPurok');
    const birthdaysSelectAll = document.getElementById('birthdaysSelectAll');
    const birthdaysSendSmsBtn = document.getElementById('birthdaysSendSmsBtn');
    const birthdaysViewHistoryBtn = document.getElementById('birthdaysViewHistoryBtn');
    const archiveModalTitle = document.getElementById('archiveModalTitle');
    const archiveModalMessage = document.getElementById('archiveModalMessage');
    const archiveReasonLabel = document.getElementById('archiveReasonLabel');
    const archiveReasonInput = document.getElementById('archiveReasonInput');
    const archiveConfirmBtn = document.getElementById('archiveConfirmBtn');
    let archiveModalResolve = null;

    function requestStatusChange(options) {
      if (!archiveReasonInput || !archiveConfirmBtn) return Promise.resolve('');
      const isUnarchive = !!(options && options.isUnarchive);
      if (archiveModalTitle) archiveModalTitle.textContent = isUnarchive ? 'Unarchive Senior Record' : 'Archive Senior Record';
      if (archiveModalMessage) archiveModalMessage.textContent = isUnarchive
        ? 'Are you sure you want to unarchive this record?'
        : 'Please provide a reason for archiving this record.';
      archiveConfirmBtn.textContent = isUnarchive ? 'Unarchive' : 'Archive';
      if (archiveReasonLabel) archiveReasonLabel.style.display = isUnarchive ? 'none' : 'block';
      archiveReasonInput.style.display = isUnarchive ? 'none' : 'block';
      archiveReasonInput.value = '';
      openModal('archiveConfirmModal');
      if (!isUnarchive) setTimeout(function () { archiveReasonInput.focus(); }, 0);
      return new Promise(function (resolve) {
        archiveModalResolve = resolve;
      });
    }

    function renderBirthdaysTable() {
      if (!birthdaysBody) return;
      const q = (bdaySearch && bdaySearch.value ? bdaySearch.value : '').trim().toLowerCase();
      const purokQ = (bdayPurok && bdayPurok.value ? bdayPurok.value : '').trim().toLowerCase();
      const filtered = birthdaysCache.filter(function (row) {
        const fullName = String(row.full_name || '').toLowerCase();
        const barangay = String(row.barangay || '').toLowerCase();
        const purok = String(row.purok || '').toLowerCase();
        if (assignedBarangay && barangay !== String(assignedBarangay).toLowerCase()) return false;
        if (purokQ && purok.indexOf(purokQ) === -1) return false;
        if (q && fullName.indexOf(q) === -1 && barangay.indexOf(q) === -1 && purok.indexOf(q) === -1) return false;
        return true;
      });

      if (!filtered.length) {
        birthdaysBody.innerHTML = '<tr><td colspan="5" class="text-center muted">No birthdays found.</td></tr>';
      } else {
        birthdaysBody.innerHTML = filtered.map(function (row) {
          return '<tr>'
            + '<td><input type="checkbox" class="birthday-row-checkbox" value="' + String(row.id || '') + '"></td>'
            + '<td>' + String(row.full_name || 'Unnamed') + '</td>'
            + '<td>' + formatBirthDate(row.birth_date || row.birthday || '') + '</td>'
            + '<td>' + String(row.barangay || 'N/A') + '</td>'
            + '<td>' + String(row.purok || 'N/A') + '</td>'
            + '</tr>';
        }).join('');
      }

      birthdaysCount.textContent = filtered.length + ' results';
      birthdaysSendSmsBtn.disabled = true;
      birthdaysSelectAll.checked = false;
      birthdaysBody.querySelectorAll('.birthday-row-checkbox').forEach(function (cb) {
        cb.addEventListener('change', function () {
          const all = birthdaysBody.querySelectorAll('.birthday-row-checkbox');
          const checked = birthdaysBody.querySelectorAll('.birthday-row-checkbox:checked');
          birthdaysSelectAll.checked = all.length > 0 && all.length === checked.length;
          birthdaysSendSmsBtn.disabled = checked.length === 0;
        });
      });
    }

    async function loadBirthdays(range) {
      birthdaysBody.innerHTML = '<tr><td colspan="5" class="text-center muted">Loading...</td></tr>';
      try {
        const res = await fetch(appPath('/api/birthdays?type=senior&range=' + encodeURIComponent(range || 'month')), { credentials: 'same-origin' });
        const json = await res.json();
        birthdaysCache = (json && json.success && Array.isArray(json.data)) ? json.data : [];
        renderBirthdaysTable();
      } catch (_) {
        birthdaysCache = [];
        birthdaysBody.innerHTML = '<tr><td colspan="5" class="text-center text-danger">Failed to load birthdays.</td></tr>';
        birthdaysCount.textContent = '0 results';
      }
    }

    async function updateBirthdaysBadge() {
      try {
        const res = await fetch(appPath('/api/birthdays?type=senior&range=today'), { credentials: 'same-origin' });
        const json = await res.json();
        const data = (json && json.success && Array.isArray(json.data)) ? json.data : [];
        const todayCount = data.filter(function (row) {
          return !assignedBarangay || String(row.barangay || '').toLowerCase() === String(assignedBarangay).toLowerCase();
        }).length;
        birthdaysBadge.style.display = todayCount > 0 ? '' : 'none';
        birthdaysBadge.textContent = String(todayCount);
      } catch (_) {
        birthdaysBadge.style.display = 'none';
      }
    }

    document.querySelectorAll('.view-btn').forEach(function (button) {
      button.addEventListener('click', function () {
        const row = button.closest('tr');
        const id = getSeniorIdFromRow(row);
        if (!id) return;
        if (viewSeniorFrame) viewSeniorFrame.src = appPath('/add_senior') + '?edit=' + encodeURIComponent(String(id)) + '&modal=1&view=1';
        currentViewSeniorId = String(id);
        loadViewSeniorEditLogs(id);
        openModal('viewModal');
      });
    });

    const printApplicationBtn = document.getElementById('printApplicationBtn');
    if (printApplicationBtn) {
      printApplicationBtn.addEventListener('click', function () {
        if (!currentViewSeniorId) return;
        window.open(appPath('/senior/' + encodeURIComponent(String(currentViewSeniorId)) + '/application-pdf'), '_blank');
      });
    }

    document.querySelectorAll('.edit-btn').forEach(function (button) {
      button.addEventListener('click', function () {
        const row = button.closest('tr');
        const id = getSeniorIdFromRow(row);
        if (!id) return;
        if (editSeniorFrame) {
          editSeniorFrame.src = appPath('/add_senior') + '?edit=' + encodeURIComponent(String(id)) + '&modal=1&nocache=' + String(Date.now());
        }
        openModal('editModal');
      });
    });

    window.addEventListener('message', function (event) {
      if (!event || event.origin !== window.location.origin || !event.data) return;
      if (event.data.type === 'senior-edit-saved') window.location.reload();
      if (event.data.type === 'senior-edit-cancel') closeModal('editModal');
      if (event.data.type === 'senior-view-close') closeModal('viewModal');
    });

    if (birthdaysBtn) {
      birthdaysBtn.addEventListener('click', async function () {
        openModal('birthdaysModal');
        await loadBirthdays(bdayRange ? bdayRange.value : 'month');
      });
    }
    if (bdayRange) bdayRange.addEventListener('change', function () { loadBirthdays(bdayRange.value); });
    if (bdaySearch) bdaySearch.addEventListener('input', renderBirthdaysTable);
    if (bdayPurok) bdayPurok.addEventListener('input', renderBirthdaysTable);
    if (birthdaysSelectAll) {
      birthdaysSelectAll.addEventListener('change', function () {
        birthdaysBody.querySelectorAll('.birthday-row-checkbox').forEach(function (cb) { cb.checked = birthdaysSelectAll.checked; });
        birthdaysSendSmsBtn.disabled = birthdaysBody.querySelectorAll('.birthday-row-checkbox:checked').length === 0;
      });
    }
    if (birthdaysSendSmsBtn) {
      birthdaysSendSmsBtn.addEventListener('click', function () {
        const selected = Array.from(birthdaysBody.querySelectorAll('.birthday-row-checkbox:checked'));
        const recipientsList = document.getElementById('recipientsList');
        recipientsList.innerHTML = '';
        selected.forEach(function (checkbox) {
          const row = getBirthdayRowById(checkbox.value);
          const phone = String((row && row.mobile_number) || '').trim();
          if (!phone) return;
          const div = document.createElement('div');
          div.textContent = (row.full_name || 'Unnamed') + ' — ' + phone;
          div.dataset.phone = phone;
          div.dataset.firstName = row.first_name || '';
          div.dataset.middleName = row.middle_name || '';
          div.dataset.lastName = row.last_name || '';
          div.dataset.barangay = row.barangay || '';
          div.dataset.purok = row.purok || '';
          div.dataset.recordId = String(row.id || '');
          recipientsList.appendChild(div);
        });
        if (!recipientsList.children.length) {
          alert('No selected birthday records have a mobile number.');
          return;
        }
        const smsMessageEl = document.getElementById('smsMessage');
        const charCountEl = document.getElementById('charCount');
        smsMessageEl.value = birthdayGreetingMessage;
        charCountEl.textContent = String(birthdayGreetingMessage.length);
        closeModal('birthdaysModal');
        openModal('smsModal');
      });
    }
    if (birthdaysViewHistoryBtn) {
      birthdaysViewHistoryBtn.addEventListener('click', function () {
        closeModal('birthdaysModal');
        loadSmsHistory();
        openModal('smsHistoryModal');
      });
    }

    const smsMessage = document.getElementById('smsMessage');
    if (smsMessage) smsMessage.addEventListener('input', function () { document.getElementById('charCount').textContent = String(smsMessage.value.length); });
    const sendSmsModalBtn = document.getElementById('sendSmsModalBtn');
    if (sendSmsModalBtn) {
      sendSmsModalBtn.addEventListener('click', async function () {
        const message = (document.getElementById('smsMessage').value || '').trim();
        const recipients = Array.from(document.getElementById('recipientsList').children).map(function (div) {
          return {
            phone: div.dataset.phone || '',
            first_name: div.dataset.firstName || '',
            middle_name: div.dataset.middleName || '',
            last_name: div.dataset.lastName || '',
            barangay: div.dataset.barangay || '',
            purok: div.dataset.purok || '',
            record_id: div.dataset.recordId || ''
          };
        }).filter(function (r) { return r.phone; });
        if (!message || !recipients.length) return;
        sendSmsModalBtn.disabled = true;
        const oldText = sendSmsModalBtn.textContent;
        sendSmsModalBtn.textContent = 'Sending...';
        try {
          const response = await fetch(appPath('/send-sms'), {
            method: 'POST',
            headers: { 'Content-Type': 'application/json', 'Accept': 'application/json' },
            body: JSON.stringify({ message: message, recipients: recipients, recipient_type: 'Senior' })
          });
          const payload = await response.json();
          if (!response.ok || !payload || payload.success === false) throw new Error((payload && payload.message) ? payload.message : 'SMS send failed.');
          alert(payload.message || 'SMS sent successfully.');
          closeModal('smsModal');
        } catch (error) {
          alert(error.message || 'Failed to send SMS.');
        } finally {
          sendSmsModalBtn.disabled = false;
          sendSmsModalBtn.textContent = oldText;
        }
      });
    }

    async function loadSmsHistory() {
      const tableBody = document.getElementById('smsHistoryTableBody');
      tableBody.innerHTML = '<tr><td colspan="7" class="text-center muted">Loading...</td></tr>';
      try {
        const response = await fetch(appPath('/sms-history?recipient_type=Senior&limit=200'), { credentials: 'same-origin' });
        const payload = await response.json();
        const rows = (payload && payload.success && Array.isArray(payload.data)) ? payload.data : [];
        if (!rows.length) {
          tableBody.innerHTML = '<tr><td colspan="7" class="text-center muted">No SMS history yet.</td></tr>';
          return;
        }
        tableBody.innerHTML = rows.map(function (row) {
          return '<tr>'
            + '<td>' + String(row.created_at || row.sent_at || '') + '</td>'
            + '<td>' + String(row.phone || '') + '</td>'
            + '<td>' + String(row.name || [row.first_name, row.middle_name, row.last_name].filter(Boolean).join(' ') || '') + '</td>'
            + '<td>' + String(row.barangay || '') + '</td>'
            + '<td>' + String(row.purok || '') + '</td>'
            + '<td>' + String(row.message || '') + '</td>'
            + '<td>' + String(row.status || '') + '</td>'
            + '</tr>';
        }).join('');
      } catch (_) {
        tableBody.innerHTML = '<tr><td colspan="7" class="text-center text-danger">Failed to load SMS history.</td></tr>';
      }
    }

    document.querySelectorAll('.archive-btn').forEach(function (button) {
      button.addEventListener('click', async function () {
        const row = button.closest('tr');
        const senior = getSeniorFromRow(row);
        const rid = getSeniorIdFromRow(row);
        const isArchived = (button.dataset.status || senior.status || '') === 'Archived';
        const endpoint = appPath(isArchived ? '/unarchive-senior' : '/archive-senior');
        const payload = { senior_id: rid || senior.id };

        const reason = await requestStatusChange({ isUnarchive: isArchived });
        if (reason === null) return;
        if (!isArchived) payload.reason = reason.trim();

        try {
          const response = await fetch(endpoint, {
            method: 'POST',
            headers: { 'Content-Type': 'application/json', 'Accept': 'application/json' },
            body: JSON.stringify(payload)
          });
          const data = await response.json();
          if (!response.ok || !data || data.success === false) {
            throw new Error((data && data.message) ? data.message : 'Failed to update status.');
          }
          updateRowStatus(row, isArchived ? 'Active' : 'Archived');
          applyFilters(); // Re-apply filters to hide the row if we are in "Active Only" view
        } catch (error) {
          alert(error.message || 'Failed to update status.');
        }
      });
    });
    if (archiveConfirmBtn) {
      archiveConfirmBtn.addEventListener('click', function () {
        if (!archiveModalResolve) return;
        const resolve = archiveModalResolve;
        archiveModalResolve = null;
        const reason = archiveReasonInput ? archiveReasonInput.value : '';
        closeModal('archiveConfirmModal');
        resolve(reason || '');
      });
    }
    updateBirthdaysBadge();
    applyFilters(); // Initial filter on load
  })();

  (function monitorSessionReplacement() {
    const expectedUserId = <?= json_encode((int) ($user['_id'] ?? 0), JSON_UNESCAPED_UNICODE) ?>;
    if (!expectedUserId) return;
    const storageKey = 'swsActiveUserId';

    try { localStorage.setItem(storageKey, String(expectedUserId)); } catch (_) {}

    function forceLogout() {
      window.location.replace(appPath('/?session_replaced=1'));
    }

    function checkLocalActiveUser() {
      try {
        const active = Number(localStorage.getItem(storageKey) || 0);
        if (active && active !== expectedUserId) forceLogout();
      } catch (_) {}
    }

    window.addEventListener('storage', function (event) {
      if (event.key !== storageKey) return;
      const active = Number(event.newValue || 0);
      if (active && active !== expectedUserId) forceLogout();
    });

    async function checkSession() {
      try {
        const res = await fetch(appPath('/api/session-state'), { credentials: 'same-origin', cache: 'no-store' });
        if (!res.ok) {
          forceLogout();
          return;
        }
        const data = await res.json();
        const activeUserId = Number((data && data.user && data.user._id) || 0);
        if (!data || data.success !== true || activeUserId !== expectedUserId) {
          forceLogout();
        }
      } catch (_) {
        forceLogout();
      }
    }

    checkLocalActiveUser();
    setInterval(checkSession, 3000);
    setInterval(checkLocalActiveUser, 1000);
    document.addEventListener('visibilitychange', function () {
      if (!document.hidden) {
        checkLocalActiveUser();
        checkSession();
      }
    });
  })();
</script>
