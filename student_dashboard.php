<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <title>Student Library Dashboard</title>
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <style>
    :root {
      --bg: #f7f8fc;
      --panel: #ffffff;
      --text: #1f2937;
      --muted: #6b7280;
      --primary: #2563eb;
      --accent: #10b981;
      --border: #e5e7eb;
      --warning: #f59e0b;
    }
    * { box-sizing: border-box; }
    body {
      margin: 0;
      font-family: system-ui, -apple-system, Segoe UI, Roboto, "Helvetica Neue", Arial, "Noto Sans", "Liberation Sans", sans-serif;
      background: var(--bg);
      color: var(--text);
    }
    .app {
    display: grid;
    grid-template-columns: 1fr 350px;
    grid-template-rows: auto;
    grid-template-areas: "main sidebar";
    gap: 20px;
    height: 100%;
    }
    main {
    grid-area: main;
        
    }
    .sidebar {
    grid-area: sidebar;
    position: sticky;
    top: 0;
    height: 100vh;
    background: #0f172a;
    color: #e5e7eb;
    padding: 24px;
    }
    .brand {
      display: flex;
      align-items: center;
      gap: 12px;
    }
    .brand-logo {
      width: 36px; height: 36px; border-radius: 8px;
      background: linear-gradient(135deg, #2563eb, #10b981);
    }
    .brand h1 {
      font-size: 18px; margin: 0;
      letter-spacing: 0.3px;
    }
    .user {
      background: rgba(255,255,255,0.06);
      border: 1px solid rgba(255,255,255,0.08);
      border-radius: 12px;
      padding: 16px;
    }
    .user h2 {
      font-size: 16px; margin: 0 0 4px;
      color: #fff;
    }
    .user p {
      margin: 0; color: #cbd5e1; font-size: 13px;
    }
    .nav {
      display: grid; gap: 8px;
    }
    .nav a {
      display: flex; align-items: center; gap: 10px;
      padding: 10px 12px;
      color: #e5e7eb; text-decoration: none;
      border-radius: 8px;
    }
    .nav a:hover { background: rgba(255,255,255,0.08); }
    .nav .signout { color: #fecaca; }
    .header {
      background: var(--panel);
      border-bottom: 1px solid var(--border);
      padding: 16px 24px;
      display: flex;
      align-items: center;
      justify-content: space-between;
      top: 0; z-index: 10;
      width: 100%;
    }
    .header h2 { margin: 0; font-size: 20px; }
    .help-btn {
      border: 1px solid var(--border);
      background: var(--panel);
      padding: 8px 12px;
      border-radius: 8px;
      color: var(--text);
      cursor: pointer;
    }
    .help-btn:hover { background: #f3f4f6; }
    .content {
      padding: 24px;
      display: grid;
      gap: 20px;
    }
    .metrics {
      display: grid;
      grid-template-columns: repeat(3, minmax(0,1fr));
      gap: 16px;
    }
    .card {
      background: var(--panel);
      border: 1px solid var(--border);
      border-radius: 12px;
      padding: 16px;
      display: grid;
      gap: 8px;
    }
    .card .label { color: var(--muted); font-size: 13px; }
    .card .value { font-size: 24px; font-weight: 700; }
    .card .note { font-size: 12px; color: var(--muted); }
    .card.primary .value { color: var(--primary); }
    .card.success .value { color: var(--accent); }
    .card.warning .value { color: var(--warning); }

    /* Panels */
    .panels {
      display: grid;
      grid-template-columns: 2fr 1fr;
      gap: 16px;
    }

    /* Books table */
    .panel {
      background: var(--panel);
      border: 1px solid var(--border);
      border-radius: 12px;
      overflow: hidden;
      display: flex; flex-direction: column;
    }
    .panel-header {
      padding: 14px 16px;
      border-bottom: 1px solid var(--border);
      display: flex; align-items: center; justify-content: space-between;
    }
    .panel-header h3 {
      margin: 0; font-size: 16px;
    }
    .table-wrap { overflow-x: auto; }
    table {
      width: 100%;
      border-collapse: collapse;
      font-size: 14px;
    }
    thead th {
      text-align: left;
      padding: 12px 16px;
      color: var(--muted);
      background: #f9fafb;
      border-bottom: 1px solid var(--border);
      white-space: nowrap;
    }
    tbody td {
      padding: 12px 16px;
      border-bottom: 1px solid var(--border);
    }
    tbody tr:hover { background: #f8fafc; }
    .empty {
      padding: 20px 16px;
      color: var(--muted);
      text-align: center;
    }

    /* Notifications */
    .panel-body { padding: 16px; }
    .btn {
      display: inline-flex; align-items: center; gap: 8px;
      padding: 8px 12px;
      background: var(--primary);
      color: #fff; border: none; border-radius: 8px;
      cursor: pointer;
      font-size: 14px;
    }
    .btn.secondary {
      background: #fff; color: var(--text);
      border: 1px solid var(--border);
    }
    .btn:hover { filter: brightness(0.95); }

    /* Footer help */
    .floating-help {
      position: fixed;
      right: 24px; bottom: 24px;
      padding: 12px 14px;
      background: var(--primary);
      color: #fff;
      border: none; border-radius: 999px;
      box-shadow: 0 8px 24px rgba(0,0,0,0.15);
      cursor: pointer;
    }

    /* Responsive */
    @media (max-width: 1024px) {
      .app { grid-template-columns: 1fr; }
      .sidebar { display: none; }
      .panels { grid-template-columns: 1fr; }
      .metrics { grid-template-columns: 1fr 1fr; }
    }
    @media (max-width: 640px) {
      .metrics { grid-template-columns: 1fr; }
    }
  </style>
</head>
<body>
  <div class="app">
    <!-- Sidebar -->
    <aside class="sidebar" aria-label="Sidebar navigation">
      <section class="user" aria-label="User summary">
        <div class="brand">
            <div class="brand-logo" aria-hidden="true"></div>
        </div>
        <br>
        <h2>Claudia Alves</h2>
        <p>Semester: 2 • Status: Active</p>
      </section>

      <nav class="nav" aria-label="Primary">
        <a href="#profile" aria-label="My profile">👤 My Profile</a>
        <a href="#settings" aria-label="Settings">⚙️ Settings</a>
        <a href="#signout" class="signout" aria-label="Sign out">🚪 Sign Out</a>
      </nav>
    </aside>

    <!-- Main -->
    <main>
        <header class="header">
            <h2>Student Library</h2>
        </header>
      <div class="content">
        <!-- Metrics -->
        <section class="metrics" aria-label="Key metrics">
          <div class="card primary" aria-live="polite">
            <div class="label">Books borrowed</div>
            <div class="value">0</div>
            <div class="note">3 books allowed per semester</div>
          </div>

          <div class="card success" aria-live="polite">
            <div class="label">Books reserved</div>
            <div class="value">0</div>
          </div>

          <div class="card warning" aria-live="polite">
            <div class="label">Outstanding fees</div>
            <div class="value">₱0.00</div>
          </div>
        </section>

        <!-- Panels -->
        <section class="panels">
          <!-- Books -->
          <div class="panel" aria-label="Books list">
            <div class="panel-header">
              <h3>Books</h3>
              <div class="actions">
                <button class="btn secondary" type="button">Add book</button>
              </div>
            </div>
            <div class="table-wrap">
              <table aria-describedby="books-empty">
                <thead>
                  <tr>
                    <th>Title</th>
                    <th>Author</th>
                    <th>Category</th>
                    <th>Status</th>
                    <th>Availability</th>
                  </tr>
                </thead>
                <tbody>
                  <!-- Empty state -->
                  <tr>
                    <td colspan="5" class="empty" id="books-empty">
                      No books to display.
                    </td>
                  </tr>
                </tbody>
              </table>
            </div>
          </div>

          <!-- Notifications -->
          <div class="panel" aria-label="Notifications">
            <div class="panel-header">
              <h3>Notifications</h3>
            </div>
            <div class="panel-body">
              <p class="empty">No notifications at the moment.</p>
              <button class="btn" type="button">View all notifications</button>
            </div>
          </div>
        </section>
      </div>
    </main>
  </div>

  <!-- Floating Help -->
  <button class="floating-help" type="button" aria-label="Help">
    Help
  </button>

  <script>
    // Optional: basic interactions to make the template feel alive
    document.querySelectorAll('.help-btn, .floating-help').forEach(btn => {
      btn.addEventListener('click', () => {
        alert('How can we help you today?');
      });
    });

    document.querySelector('.actions .secondary')?.addEventListener('click', () => {
      alert('Add book form goes here.');
    });
  </script>
</body>
</html>