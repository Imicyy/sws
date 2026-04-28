<!doctype html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width,initial-scale=1">
  <link rel="icon" type="image/jpeg" href="<?= htmlspecialchars(asset_url('images/SilayLogo.jpg'), ENT_QUOTES) ?>">
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
    body { font-family: Segoe UI, Arial, sans-serif; background: var(--bg-page); display: flex; }
    
    /* Sidebar Styles */
    .sidebar { 
      width: 260px; 
      background: linear-gradient(180deg, #eff6ff 0%, #dbeafe 100%);
      color: #1e3a8a; 
      min-height: 100vh; 
      padding: 20px 0; 
      position: fixed; 
      left: 0; 
      top: 0; 
      overflow-y: auto; 
      display: flex;
      flex-direction: column;
    }
    .sidebar-header { 
      padding: 0 20px 24px; 
      border-bottom: 1px solid rgba(59,130,246,0.18); 
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
    .nav-center { display: flex; flex-direction: column; gap: 6px; margin: 10px 0; align-items: center; justify-content: center; }
    .sidebar-nav a, .nav-center a { 
      display: block; 
      padding: 12px 20px; 
      color: #1e3a8a; 
      text-decoration: none; 
      transition: all 0.3s ease; 
      border-left: 3px solid transparent; 
      width: 100%; max-width: 220px; text-align: center; border-radius: 8px;
    }
    .logout-btn { margin-top: auto; display: block; width: 100%; max-width: 220px; text-align: center; padding: 10px 12px; border-radius: 8px; background: linear-gradient(135deg, #ef4444, #dc2626); color: #fff; box-shadow: 0 6px 14px rgba(220,38,38,0.18); text-decoration: none; }
    .logout-btn:hover { background: linear-gradient(135deg, #dc2626, #b91c1c); color: #fff; }
    .sidebar-nav a:hover {
      background: #dbeafe;
      color: #1e3a8a;
      border-left-color: var(--blue-main); 
    }
    .sidebar-nav a.active { 
      background: linear-gradient(135deg, #60a5fa, var(--blue-main)); 
      color: #fff; 
      border-left-color: var(--yellow-main); 
    }
    .sidebar-nav-label {
      font-size: 12px;
      font-weight: 600;
      color: #64748b;
      text-transform: uppercase;
      padding: 16px 20px 8px;
      letter-spacing: 0.5px;
    }
    .user-name {
      font-size: 14px;
      font-weight: 600;
      color: #1e3a8a;
      padding: 0 20px;
      margin-bottom: 8px;
      word-break: break-word;
    }
    .sidebar-nav .nav-logout {
      margin-top: 24px;
      border-top: 1px solid rgba(59,130,246,0.18);
      padding-top: 16px;
    }
    
    /* Main Content */
    .main-content { 
      margin-left: 260px; 
      flex: 1; 
      padding: 24px; 
    }
    .wrap { max-width: 100%; }
    
    .card { background: #fff; border-radius: 14px; border: 1px solid var(--panel-border); box-shadow: 0 12px 24px rgba(59, 130, 246, .08); padding: 20px; margin-bottom: 16px; }
    .tabs { display: flex; gap: 8px; margin-bottom: 16px; }
    .tab { cursor: pointer; padding: 10px 14px; border-radius: 10px; border: 1px solid #d8dee9; background: #f8fafc; }
    .tab.active { background: linear-gradient(135deg, #60a5fa, var(--blue-main)); border-color: var(--blue-main); color: #fff; }
    .panel { display: none; }
    .panel.active { display: block; }
    .row { display: grid; grid-template-columns: 1fr 1fr; gap: 12px; }
    label { display: block; font-size: 13px; margin: 0 0 6px; color: #334155; }
    input, select { width: 100%; padding: 10px; border: 1px solid #cbd5e1; border-radius: 8px; box-sizing: border-box; }
    .btn { border: 0; background: linear-gradient(135deg, #60a5fa, var(--blue-main)); color: #fff; border-radius: 8px; padding: 10px 14px; cursor: pointer; }
    .muted { color: #64748b; font-size: 13px; }
    .barangay-item { margin-bottom: 12px; padding: 12px; border: 1px solid var(--panel-border); border-radius: 10px; background: #fffbeb; }
    .name { font-weight: 600; color: #0f172a; margin-bottom: 8px; }
    .badge { display: inline-block; margin-right: 6px; margin-bottom: 6px; padding: 4px 8px; background: #dbeafe; color: #1e3a8a; border-radius: 999px; font-size: 12px; }
    #statusMessage { margin-top: 10px; font-size: 13px; }
  </style>
</head>
<body>
  <!-- Sidebar Navigation -->
  <div class="sidebar">
    <div class="sidebar-header">
      <div class="user-name"><?= htmlspecialchars((string)($user['name'] ?? 'Super Admin'), ENT_QUOTES, 'UTF-8') ?></div>
      <div class="sidebar-nav-label">Navigation</div>
    </div>
    <div class="nav-center">
      <a href="/index-superadmin" class="<?= strpos($_SERVER['REQUEST_URI'], 'index-superadmin') !== false ? 'active' : '' ?>">Dashboard</a>
      <a href="/superadmin-users" class="<?= strpos($_SERVER['REQUEST_URI'], 'superadmin-users') !== false ? 'active' : '' ?>">User Management</a>
      <a href="/superadmin-logs" class="<?= strpos($_SERVER['REQUEST_URI'], 'superadmin-logs') !== false ? 'active' : '' ?>">System Logs</a>
    </div>
    <a class="logout-btn" href="/logout">Logout</a>
  </div>

  <!-- Main Content -->
  <div class="main-content">
  <div class="wrap">
    <div class="card">
      <h1>Super Admin Dashboard</h1>
      <p class="muted">Signed in as <?= htmlspecialchars((string)($user['email'] ?? 'unknown'), ENT_QUOTES, 'UTF-8') ?></p>
    </div>

    <div class="card">
      <h2>Barangay and Purok Registration</h2>
      <p class="muted">Maintenance section for managing barangays and puroks used across registration forms.</p>

      <div class="tabs">
        <button type="button" class="tab active" id="tab-barangay">New Barangay</button>
        <button type="button" class="tab" id="tab-purok">Add Purok</button>
      </div>

      <div class="panel active" id="panel-barangay">
        <form id="registerBarangayForm">
          <label for="barangayName">Barangay Name</label>
          <input type="text" id="barangayName" name="barangayName" placeholder="Enter barangay name" required>
          <button type="submit" class="btn" style="margin-top:10px;">Register Barangay</button>
        </form>
      </div>

      <div class="panel" id="panel-purok">
        <form id="registerPurokForm">
          <div class="row">
            <div>
              <label for="selectBarangay">Select Barangay</label>
              <select id="selectBarangay" name="barangayId" required>
                <option value="" selected disabled>-- Select Barangay --</option>
                <?php if (!empty($barangays) && is_array($barangays)): ?>
                  <?php $index = 1; foreach ($barangays as $barangayName => $purokList): ?>
                    <option value="<?= $index ?>"><?= htmlspecialchars((string) $barangayName, ENT_QUOTES, 'UTF-8') ?></option>
                  <?php $index++; endforeach; ?>
                <?php endif; ?>
              </select>
            </div>
            <div>
              <label for="purokName">Purok Name</label>
              <input type="text" id="purokName" name="purokName" placeholder="Enter purok name" required>
            </div>
          </div>
          <button type="submit" class="btn" style="margin-top:10px;">Add Purok</button>
        </form>
      </div>

      <div id="statusMessage" class="muted"></div>
    </div>

    <div class="card">
      <h3>Current Barangays and Puroks</h3>
      <?php if (!empty($barangays) && is_array($barangays)): ?>
        <div id="barangayList">
          <?php foreach ($barangays as $barangayName => $purokList): ?>
            <div class="barangay-item">
              <div class="name"><?= htmlspecialchars((string) $barangayName, ENT_QUOTES, 'UTF-8') ?></div>
              <?php if (!empty($purokList) && is_array($purokList)): ?>
                <?php foreach ($purokList as $purok): ?>
                  <span class="badge"><?= htmlspecialchars((string) $purok, ENT_QUOTES, 'UTF-8') ?></span>
                <?php endforeach; ?>
              <?php else: ?>
                <span class="muted">No puroks added yet</span>
              <?php endif; ?>
            </div>
          <?php endforeach; ?>
        </div>
        <div style="margin-top: 12px; display: flex; justify-content: space-between; align-items: center;">
          <small id="barangayPaginationInfo">Showing 0-0 of 0 records</small>
          <div id="barangayPaginationControls" class="btn-group btn-group-sm" style="display: flex; gap: 4px;"></div>
        </div>
      <?php else: ?>
        <p class="muted">No barangays registered yet.</p>
      <?php endif; ?>
    </div>
  </div>

  <script>
    (function () {
      const tabBarangay = document.getElementById('tab-barangay');
      const tabPurok = document.getElementById('tab-purok');
      const panelBarangay = document.getElementById('panel-barangay');
      const panelPurok = document.getElementById('panel-purok');
      const statusMessage = document.getElementById('statusMessage');

      function activate(which) {
        const isBarangay = which === 'barangay';
        tabBarangay.classList.toggle('active', isBarangay);
        tabPurok.classList.toggle('active', !isBarangay);
        panelBarangay.classList.toggle('active', isBarangay);
        panelPurok.classList.toggle('active', !isBarangay);
      }

      tabBarangay.addEventListener('click', function () { activate('barangay'); });
      tabPurok.addEventListener('click', function () { activate('purok'); });

      document.getElementById('registerBarangayForm').addEventListener('submit', async function (e) {
        e.preventDefault();
        const payload = new URLSearchParams();
        payload.set('barangayName', document.getElementById('barangayName').value);
        const res = await fetch('/api/barangay', {
          method: 'POST',
          headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
          body: payload.toString(),
        });
        const json = await res.json();
        statusMessage.textContent = json.message || (json.success ? 'Barangay registered.' : 'Failed to register barangay.');
        if (json.success) { window.location.reload(); }
      });

      document.getElementById('registerPurokForm').addEventListener('submit', async function (e) {
        e.preventDefault();
        const payload = new URLSearchParams();
        payload.set('barangayId', document.getElementById('selectBarangay').value);
        payload.set('purokName', document.getElementById('purokName').value);
        const res = await fetch('/api/purok', {
          method: 'POST',
          headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
          body: payload.toString(),
        });
        const json = await res.json();
        statusMessage.textContent = json.message || (json.success ? 'Purok added.' : 'Failed to add purok.');
        if (json.success) { window.location.reload(); }
      });
    })();

    // Pagination for barangay list
    let barangayCurrentPage = 1;
    const barangayItemsPerPage = 5;

    function getBarangayItems() {
      return Array.from(document.querySelectorAll('#barangayList .barangay-item'));
    }

    function updateBarangayPagination() {
      const items = getBarangayItems();
      const totalItems = items.length;
      const totalPages = Math.ceil(totalItems / barangayItemsPerPage);
      
      // Reset to first page if current page is out of bounds
      if (barangayCurrentPage > totalPages && totalPages > 0) {
        barangayCurrentPage = totalPages;
      } else if (totalPages === 0) {
        barangayCurrentPage = 1;
      }
      
      // Update pagination info
      const startItem = totalItems === 0 ? 0 : (barangayCurrentPage - 1) * barangayItemsPerPage + 1;
      const endItem = Math.min(barangayCurrentPage * barangayItemsPerPage, totalItems);
      document.getElementById('barangayPaginationInfo').textContent = `Showing ${startItem}-${endItem} of ${totalItems} records`;
      
      // Show/hide items based on current page
      items.forEach((item, index) => {
        const itemPage = Math.floor(index / barangayItemsPerPage) + 1;
        item.style.display = itemPage === barangayCurrentPage ? '' : 'none';
      });
      
      // Update pagination controls
      renderBarangayPaginationControls(totalPages);
    }

    function renderBarangayPaginationControls(totalPages) {
      const controls = document.getElementById('barangayPaginationControls');
      controls.innerHTML = '';
      
      if (totalPages <= 1) return;
      
      // Previous button
      const prevBtn = document.createElement('button');
      prevBtn.className = 'btn btn-sm btn-outline-secondary';
      prevBtn.textContent = 'Previous';
      prevBtn.disabled = barangayCurrentPage === 1;
      prevBtn.onclick = () => {
        if (barangayCurrentPage > 1) {
          barangayCurrentPage--;
          updateBarangayPagination();
        }
      };
      controls.appendChild(prevBtn);
      
      // Page numbers
      const maxVisiblePages = 5;
      let startPage = Math.max(1, barangayCurrentPage - Math.floor(maxVisiblePages / 2));
      let endPage = Math.min(totalPages, startPage + maxVisiblePages - 1);
      
      if (endPage - startPage + 1 < maxVisiblePages) {
        startPage = Math.max(1, endPage - maxVisiblePages + 1);
      }
      
      for (let i = startPage; i <= endPage; i++) {
        const pageBtn = document.createElement('button');
        pageBtn.className = `btn btn-sm ${i === barangayCurrentPage ? 'btn-primary' : 'btn-outline-secondary'}`;
        pageBtn.textContent = i;
        pageBtn.onclick = () => {
          barangayCurrentPage = i;
          updateBarangayPagination();
        };
        controls.appendChild(pageBtn);
      }
      
      // Next button
      const nextBtn = document.createElement('button');
      nextBtn.className = 'btn btn-sm btn-outline-secondary';
      nextBtn.textContent = 'Next';
      nextBtn.disabled = barangayCurrentPage === totalPages;
      nextBtn.onclick = () => {
        if (barangayCurrentPage < totalPages) {
          barangayCurrentPage++;
          updateBarangayPagination();
        }
      };
      controls.appendChild(nextBtn);
    }

    // Initialize barangay pagination
    updateBarangayPagination();
  </script>
  </div>
  </div>
</body>
</html>
