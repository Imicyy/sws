<!doctype html>
<html lang="en">
<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width,initial-scale=1">
	<link rel="icon" type="image/png" href="/php/assets/images/logo-ebmag.png">
	<title>Social Welfare System - Senior Citizens List</title>
	<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/css/bootstrap.min.css">
	<style>
		body { font-family: Open Sans, Segoe UI, Arial, sans-serif; margin: 0; background: #f3f6fb; color: #1f2937; }
		.layout { display: grid; grid-template-columns: 260px 1fr; min-height: 100vh; }
		.sidebar { background: #fff; border-right: 1px solid #e5e7eb; padding: 20px 14px; position: fixed; height: 100vh; width: 260px; overflow-y: auto; }
		.brand { font-weight: 800; font-size: 13px; letter-spacing: 0.4px; margin-bottom: 18px; }
		.nav-title { font-size: 12px; color: #6b7280; text-transform: uppercase; margin: 8px 10px; margin-top: 16px; }
		.nav-link { display: block; padding: 10px 12px; margin-bottom: 6px; border-radius: 8px; color: #1f2937; text-decoration: none; font-size: 14px; }
		.nav-link.active { background: #0f766e; color: #fff; }
		.nav-link:hover { background: #edf2f7; }
		.main { margin-left: 260px; padding: 20px; }
		.top { display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px; }
		.top h1 { margin: 0; font-size: 28px; font-weight: 600; }
		.filters { background: #fff; padding: 16px; border-radius: 8px; margin-bottom: 16px; border: 1px solid #e5e7eb; display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 12px; align-items: end; }
		.filter-group { display: flex; flex-direction: column; }
		.filter-group label { font-size: 12px; font-weight: 600; margin-bottom: 6px; color: #6b7280; }
		.filter-group select { padding: 8px; border: 1px solid #d1d5db; border-radius: 6px; font-size: 14px; }
		.table-container { background: #fff; border-radius: 8px; border: 1px solid #e5e7eb; overflow-x: auto; }
		table { margin: 0; }
		table thead { background: #374151; color: #fff; font-weight: 600; font-size: 12px; }
		table th { padding: 12px; text-align: left; border: none; }
		table td { padding: 12px; border-top: 1px solid #e5e7eb; }
		table tbody tr:hover { background: #f9fafb; }
		.status-badge { display: inline-block; padding: 4px 8px; border-radius: 4px; font-size: 12px; font-weight: 600; }
		.status-active { background: #d1fae5; color: #065f46; }
		.status-archived { background: #f3f4f6; color: #374151; }
		.actions { display: flex; gap: 8px; }
		.btn-sm { padding: 6px 10px; font-size: 12px; border-radius: 4px; border: 1px solid #d1d5db; background: none; cursor: pointer; }
		.btn-view { color: #0f766e; }
		.btn-edit { color: #2563eb; }
		.btn-archive { color: #dc2626; }
		.btn-sm:hover { background: #f3f4f6; }
		.action-bar { margin-top: 16px; display: flex; gap: 12px; }
		.action-bar button { padding: 10px 16px; border: none; border-radius: 6px; font-weight: 600; cursor: pointer; font-size: 14px; }
		.btn-sms { background: #059669; color: white; }
		.btn-sms:disabled { background: #d1d5db; color: #6b7280; cursor: not-allowed; }
		.btn-history { background: #0f766e; color: white; }
		.pagination { margin-top: 16px; text-align: right; }
		.entries-info { font-size: 14px; color: #6b7280; margin: 16px 0; }
		@media (max-width: 768px) {
			.layout { grid-template-columns: 1fr; }
			.sidebar { width: 100%; height: auto; position: relative; margin-bottom: 16px; }
			.main { margin-left: 0; }
			.filters { grid-template-columns: 1fr; }
		}
	</style>
</head>
<body>
	<div class="layout">
		<aside class="sidebar">
			<div class="brand">ENRIQUE B. MAGALONA</div>
			<div style="font-size: 11px; color: #6b7280; margin-bottom: 14px;">
				<span style="display: inline-block; padding: 4px 10px; background: #d1fae5; color: #065f46; border-radius: 6px; font-weight: 600;">OSCA Department</span>
			</div>
			<div class="nav-title">Navigation</div>
			<a class="nav-link" href="/osca-dashboard">Dashboard</a>
			<a class="nav-link active" href="/Senior-form">Senior List</a>
			<a class="nav-link" href="/add_senior">Register New Senior</a>
			<a class="nav-link" href="/logout">Logout</a>
		</aside>

		<main class="main">
			<div class="top">
				<h1>SENIOR CITIZENS</h1>
				<div style="color: #6b7280; font-size: 14px;">
					Signed in as <?= htmlspecialchars((string) ($user['email'] ?? 'staff'), ENT_QUOTES, 'UTF-8') ?>
				</div>
			</div>

			<div class="filters">
				<div class="filter-group">
					<label>Barangay:</label>
					<select id="barangayFilter">
						<option value="">All Barangays</option>
						<?php foreach ($barangays as $brgy => $puroks): ?>
							<option value="<?= htmlspecialchars($brgy, ENT_QUOTES, 'UTF-8') ?>"><?= htmlspecialchars($brgy, ENT_QUOTES, 'UTF-8') ?></option>
						<?php endforeach; ?>
					</select>
				</div>
				<div class="filter-group">
					<label>Purok:</label>
					<select id="purokFilter">
						<option value="">First select a Barangay</option>
					</select>
				</div>
				<div class="filter-group">
					<label>Status:</label>
					<select id="statusFilter" onchange="filterTable()">
						<option value="Active Only">Active Only</option>
						<option value="Archived">Archived Only</option>
						<option value="">All Records</option>
					</select>
				</div>
			</div>

			<div class="entries-info">
				Showing <span id="entryCount"><?= count($seniors ?? []) ?></span> entries
			</div>

			<div class="table-container">
				<table id="dataTable" style="width: 100%;">
					<thead>
						<tr>
							<th style="width: 30px;"><input type="checkbox" id="selectAll"></th>
							<th>Full Name</th>
							<th>Age</th>
							<th>Age When Added</th>
							<th>Barangay</th>
							<th>Purok</th>
							<th>Civil Status</th>
							<th>Status</th>
							<th>Actions</th>
						</tr>
					</thead>
					<tbody>
						<?php foreach ($seniors ?? [] as $senior): 
							$firstName = $senior['first_name'] ?? '';
							$middleName = $senior['middle_name'] ?? '';
							$lastName = $senior['last_name'] ?? '';
							$fullName = trim("$firstName $middleName $lastName");
							$barangay = $senior['barangay'] ?? 'N/A';
							$purok = $senior['purok'] ?? 'N/A';
							$status = $senior['status'] ?? 'Active';
							$statusClass = $status === 'Archived' ? 'status-archived' : 'status-active';
							$id = $senior['id'];
						?>
							<tr data-status="<?= htmlspecialchars($status, ENT_QUOTES, 'UTF-8') ?>">
								<td><input type="checkbox" class="rowCheckbox" value="<?= $id ?>"></td>
								<td><?= htmlspecialchars($fullName, ENT_QUOTES, 'UTF-8') ?></td>
								<td><?= isset($senior['age']) ? (int)$senior['age'] : 'N/A' ?></td>
								<td>N/A</td>
								<td><?= htmlspecialchars($barangay, ENT_QUOTES, 'UTF-8') ?></td>
								<td><?= htmlspecialchars($purok, ENT_QUOTES, 'UTF-8') ?></td>
								<td>N/A</td>
								<td><span class="status-badge <?= $statusClass ?>"><?= htmlspecialchars($status, ENT_QUOTES, 'UTF-8') ?></span></td>
								<td>
									<div class="actions">
										<button class="btn-sm btn-view" title="View">👁️</button>
										<button class="btn-sm btn-edit" title="Edit">✎</button>
										<button class="btn-sm btn-archive" title="Archive">📦</button>
									</div>
								</td>
							</tr>
						<?php endforeach; ?>
					</tbody>
				</table>
			</div>

			<div class="action-bar">
				<button class="btn-sms" id="sendSmsBtn" disabled>📨 Send SMS to Selected</button>
				<button class="btn-history" id="viewHistoryBtn">⏱️ View SMS History</button>
			</div>
		</main>
	</div>

	<script>
		const barangaySelect = document.getElementById('barangayFilter');
		const purokSelect = document.getElementById('purokFilter');
		const barangays = <?= json_encode($barangays ?? [], JSON_UNESCAPED_UNICODE) ?>;

		barangaySelect.addEventListener('change', function() {
			const selected = this.value;
			purokSelect.innerHTML = '<option value="">First select a Barangay</option>';
      
			if (selected && barangays[selected]) {
				barangays[selected].forEach(purok => {
					const opt = document.createElement('option');
					opt.value = purok;
					opt.textContent = purok;
					purokSelect.appendChild(opt);
				});
				purokSelect.disabled = false;
			} else {
				purokSelect.disabled = true;
			}
		});

		function filterTable() {
			const statusFilter = document.getElementById('statusFilter').value;
			const rows = document.querySelectorAll('#dataTable tbody tr');
			let visibleCount = 0;

			rows.forEach(row => {
				const status = row.dataset.status;
				if (statusFilter === '' || status === statusFilter) {
					row.style.display = '';
					visibleCount++;
				} else {
					row.style.display = 'none';
				}
			});

			document.getElementById('entryCount').textContent = visibleCount;
			updateSelectButtons();
		}

		function updateSelectButtons() {
			const smsBtn = document.getElementById('sendSmsBtn');
			const checked = document.querySelectorAll('.rowCheckbox:checked').length;
			smsBtn.disabled = checked === 0;
		}

		document.getElementById('selectAll').addEventListener('change', function() {
			document.querySelectorAll('.rowCheckbox').forEach(cb => cb.checked = this.checked);
			updateSelectButtons();
		});

		document.querySelectorAll('.rowCheckbox').forEach(cb => {
			cb.addEventListener('change', updateSelectButtons);
		});

		document.getElementById('sendSmsBtn').addEventListener('click', function() {
			const selected = Array.from(document.querySelectorAll('.rowCheckbox:checked')).map(cb => cb.value);
			alert('SMS to ' + selected.length + ' recipient(s): ' + selected.join(', '));
		});

		document.getElementById('viewHistoryBtn').addEventListener('click', function() {
			alert('SMS History view would open here');
		});
	</script>
</body>
</html>

