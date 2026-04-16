<!doctype html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width,initial-scale=1">
  <title>Admin Alert Management</title>
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/css/bootstrap.min.css">
  <style>
    body { font-family: Open Sans, Segoe UI, Arial, sans-serif; margin: 0; background: #f3f6fb; color: #1f2937; }
    .layout { display: grid; grid-template-columns: 260px 1fr; min-height: 100vh; }
    .sidebar { background: #fff; border-right: 1px solid #e5e7eb; padding: 20px 14px; }
    .brand { font-weight: 800; font-size: 13px; letter-spacing: 0.4px; margin-bottom: 18px; }
    .nav-title { font-size: 12px; color: #6b7280; text-transform: uppercase; margin: 8px 10px; }
    .nav-link { display: block; padding: 10px 12px; margin-bottom: 6px; border-radius: 8px; color: #1f2937; text-decoration: none; }
    .nav-link.active { background: #0f766e; color: #fff; }
    .nav-link:hover { background: #edf2f7; }
    .main { padding: 20px; }
    .panel { background: #fff; border: 1px solid #e5e7eb; border-radius: 12px; padding: 16px; max-width: 700px; }
    .form-group { margin-bottom: 16px; }
    label { font-weight: 600; margin-bottom: 6px; display: block; }
    input, select, textarea { width: 100%; padding: 8px 12px; border: 1px solid #e5e7eb; border-radius: 6px; font-family: inherit; }
    textarea { resize: vertical; }
    button { padding: 10px 16px; border-radius: 6px; border: none; cursor: pointer; font-weight: 600; }
    button.btn-primary { background: #0f766e; color: #fff; }
    button.btn-primary:hover { background: #0d5f58; }
    pre { background: #f9fafb; border: 1px solid #e5e7eb; border-radius: 6px; padding: 12px; font-size: 12px; }
  </style>
</head>
<body>
  <div class="layout">
    <aside class="sidebar">
      <div class="brand">ENRIQUE B. MAGALONA</div>
      <div class="nav-title">Navigation</div>
      <a class="nav-link" href="/Index">Dashboard</a>
      <a class="nav-link" href="/Analytics">Senior Citizen Table</a>
      <a class="nav-link" href="/Map">PWD Map</a>
      <a class="nav-link" href="/Maps">Senior Map</a>
      <a class="nav-link active" href="/admin-alert">Alerts</a>
      <a class="nav-link" href="/User">User Management</a>
      <a class="nav-link" href="/logout">Logout</a>
    </aside>

    <main class="main">
      <h1 class="h4 mb-4">Send Alert</h1>

      <div class="panel">
        <form id="alertForm">
          <div class="form-group">
            <label for="room">Alert Channel</label>
            <select id="room" name="room" required>
              <option value="">-- Select Channel --</option>
              <option value="staff">Staff</option>
              <option value="youth">Youth</option>
              <option value="barangay">Barangay</option>
              <option value="all">All Users</option>
            </select>
          </div>

          <div class="form-group">
            <label for="messageType">Message Type</label>
            <select id="messageType" name="messageType" required>
              <option value="">-- Select Type --</option>
              <option value="info">Information</option>
              <option value="warning">Warning</option>
              <option value="error">Error</option>
              <option value="success">Success</option>
            </select>
          </div>

          <div class="form-group">
            <label for="subject">Subject (Optional)</label>
            <input type="text" id="subject" name="subject" placeholder="Alert subject line">
          </div>

          <div class="form-group">
            <label for="message">Message</label>
            <textarea id="message" name="message" rows="6" placeholder="Enter alert message..." required></textarea>
          </div>

          <div class="form-group">
            <button type="submit" class="btn btn-primary">Send Alert</button>
          </div>
        </form>

        <div id="response" style="margin-top: 16px; display: none;">
          <h3>Response:</h3>
          <pre id="responseOutput"></pre>
        </div>
      </div>

      <div class="panel" style="margin-top: 20px;">
        <h2 style="font-size: 14px; font-weight: 600; margin-bottom: 12px;">Alert History</h2>
        <table style="width: 100%; font-size: 13px;">
          <thead>
            <tr style="border-bottom: 2px solid #e5e7eb;">
              <th style="padding: 10px; text-align: left;">Channel</th>
              <th style="padding: 10px; text-align: left;">Type</th>
              <th style="padding: 10px; text-align: left;">Subject</th>
              <th style="padding: 10px; text-align: left;">Sent At</th>
            </tr>
          </thead>
          <tbody id="historyBody">
            <tr>
              <td colspan="4" style="padding: 20px; text-align: center; color: #999;">No alert history yet</td>
            </tr>
          </tbody>
        </table>
      </div>
    </main>
  </div>

  <script>
    document.getElementById('alertForm').addEventListener('submit', async (e) => {
      e.preventDefault();
      const room = document.getElementById('room').value;
      const message = document.getElementById('message').value;
      const subject = document.getElementById('subject').value;
      const messageType = document.getElementById('messageType').value;

      try {
        const response = await fetch('/send-alert', {
          method: 'POST',
          headers: { 'Content-Type': 'application/json' },
          body: JSON.stringify({ room, message, subject, messageType })
        });
        const data = await response.json();
        document.getElementById('responseOutput').textContent = JSON.stringify(data, null, 2);
        document.getElementById('response').style.display = 'block';
        if (data.success) {
          document.getElementById('alertForm').reset();
        }
      } catch (err) {
        document.getElementById('responseOutput').textContent = 'Error: ' + err.message;
        document.getElementById('response').style.display = 'block';
      }
    });
  </script>
</body>
</html>

