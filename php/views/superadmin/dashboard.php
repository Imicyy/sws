<!doctype html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width,initial-scale=1">
  <title><?= htmlspecialchars((string)($title ?? 'Super Admin'), ENT_QUOTES, 'UTF-8') ?></title>
  <style>
    body { font-family: Segoe UI, Arial, sans-serif; margin: 0; background: #f3f6fb; }
    .wrap { max-width: 1200px; margin: 24px auto; padding: 0 16px; }
    .card { background: #fff; border-radius: 12px; box-shadow: 0 10px 24px rgba(0,0,0,.08); padding: 20px; margin-bottom: 16px; }
    .tabs { display: flex; gap: 8px; margin-bottom: 16px; }
    .tab { cursor: pointer; padding: 10px 14px; border-radius: 10px; border: 1px solid #d8dee9; background: #f8fafc; }
    .tab.active { background: #0f766e; border-color: #0f766e; color: #fff; }
    .panel { display: none; }
    .panel.active { display: block; }
    .row { display: grid; grid-template-columns: 1fr 1fr; gap: 12px; }
    label { display: block; font-size: 13px; margin: 0 0 6px; color: #334155; }
    input, select { width: 100%; padding: 10px; border: 1px solid #cbd5e1; border-radius: 8px; box-sizing: border-box; }
    .btn { border: 0; background: #0f766e; color: #fff; border-radius: 8px; padding: 10px 14px; cursor: pointer; }
    .muted { color: #64748b; font-size: 13px; }
    .barangay-item { margin-bottom: 12px; padding: 12px; border: 1px solid #e2e8f0; border-radius: 10px; background: #f8fafc; }
    .name { font-weight: 600; color: #0f172a; margin-bottom: 8px; }
    .badge { display: inline-block; margin-right: 6px; margin-bottom: 6px; padding: 4px 8px; background: #e2e8f0; border-radius: 999px; font-size: 12px; }
    .links a { color: #0b4f8a; text-decoration: none; margin-right: 10px; }
    #statusMessage { margin-top: 10px; font-size: 13px; }
  </style>
</head>
<body>
  <div class="wrap">
    <div class="card">
      <h1>Super Admin Dashboard</h1>
      <p class="muted">Signed in as <?= htmlspecialchars((string)($user['email'] ?? 'unknown'), ENT_QUOTES, 'UTF-8') ?></p>
      <p class="links">
        <a href="/superadmin-users">Users</a>
        <a href="/superadmin-logs">Logs</a>
        <a href="/Senior-form">Senior Form</a>
        <a href="/Pwd-form">PWD Form</a>
      </p>
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
  </script>
</body>
</html>
