<!DOCTYPE html>
<html lang="id" data-bs-theme="dark">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Statistik Login Aplikasi — ISO Platform Askara Group</title>
  <link rel="icon" type="image/png" href="<?= base_url('assets/portal-icon.png') ?>" />
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" />
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet" />
  <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet" />
  <link rel="stylesheet" href="<?= base_url('assets/css/portal.css?v=' . filemtime(FCPATH . 'assets/css/portal.css')) ?>" />
</head>

<body>

  <!-- NAVBAR -->
  <nav class="portal-navbar">
    <div class="container-xl">
      <div class="d-flex align-items-center justify-content-between gap-3 flex-wrap">
        <a href="<?= site_url('/') ?>" class="portal-brand">
          <div class="brand-icon overflow-hidden">
            <img src="<?= base_url('assets/portal-icon.png') ?>" alt="logo_portal" width="40" height="40" />
          </div>
          ISO Platform Askara Group
        </a>

        <div class="d-flex align-items-center gap-2">
          <a href="<?= site_url('/') ?>" class="btn btn-sm" style="border-radius:8px;padding:6px 10px;background:var(--bg-elevated);border:1px solid var(--border-color);color:var(--text-primary);" title="Kembali ke Portal">
            <i class="bi bi-arrow-left me-1"></i>Portal
          </a>
          <a href="<?= site_url('index.php/api_settings') ?>" class="btn btn-sm" style="border-radius:8px;padding:6px 10px;background:var(--bg-elevated);border:1px solid var(--border-color);color:var(--text-primary);" title="Pengaturan Link API">
            <i class="bi bi-link-45deg me-1 text-success"></i><span class="d-none d-sm-inline">Link API</span>
          </a>
          <a href="<?= site_url('index.php/users') ?>" class="btn btn-sm" style="border-radius:8px;padding:6px 10px;background:var(--bg-elevated);border:1px solid var(--border-color);color:var(--text-primary);" title="Kelola User">
            <i class="bi bi-people-fill"></i>
          </a>
          <div class="dropdown">
            <button class="btn btn-sm dropdown-toggle" style="border-radius:8px;padding:6px 10px;background:var(--bg-elevated);border:1px solid var(--border-color);color:var(--text-primary);" data-bs-toggle="dropdown">
              <i class="bi bi-person-circle me-1"></i><?= htmlspecialchars($current_user) ?>
            </button>
            <ul class="dropdown-menu dropdown-menu-end">
              <li><a class="dropdown-item" href="<?= site_url('index.php/auth/logout') ?>"><i class="bi bi-box-arrow-right me-2"></i>Logout</a></li>
            </ul>
          </div>
          <button id="themeToggle" class="btn btn-sm" style="border-radius:8px;padding:6px 10px;background:var(--bg-elevated);border:1px solid var(--border-color);color:var(--text-primary);" title="Dark Mode">
            <i class="bi bi-moon-fill" id="themeIcon"></i>
          </button>
        </div>
      </div>
    </div>
  </nav>

  <?php
  $period_labels = [
    'week'  => 'Minggu Ini',
    'month' => 'Bulan Ini',
    'year'  => 'Tahun Ini',
    'all'   => 'Semua Waktu',
  ];
  $active_period_label = isset($period_labels[$selected_period]) ? $period_labels[$selected_period] : 'Bulan Ini';
  $active_app_label = $selected_app ? $selected_app->name : 'Semua Aplikasi';
  ?>

  <!-- MAIN -->
  <main class="container-xl py-4">

    <!-- Page Header + Filter -->
    <div class="d-flex align-items-start align-items-md-center justify-content-between flex-wrap gap-3 mb-4">
      <div>
        <h4 class="mb-1 fw-bold" style="color:var(--text-primary);">
          <i class="bi bi-bar-chart-line-fill me-2 text-info"></i>Statistik Login Aplikasi
        </h4>
        <div class="small" style="color:var(--text-secondary);">
          Filter aktif: <strong style="color:var(--text-primary);"><?= htmlspecialchars($active_app_label) ?></strong> &middot;
          <strong style="color:var(--text-primary);"><?= htmlspecialchars($active_period_label) ?></strong>
        </div>
      </div>

      <form method="GET" action="<?= site_url('index.php/analytics') ?>" class="d-flex align-items-center flex-wrap gap-2">
        <select name="app_id" class="form-select form-select-sm" style="width:auto;min-width:190px;" onchange="this.form.submit()">
          <option value="0" <?= $selected_app_id == 0 ? 'selected' : '' ?>>Semua Aplikasi</option>
          <?php foreach ($apps as $a): ?>
            <option value="<?= (int)$a->id ?>" <?= $selected_app_id == (int)$a->id ? 'selected' : '' ?>>
              <?= htmlspecialchars($a->name) ?><?= !empty($a->app_code) ? ' (' . htmlspecialchars($a->app_code) . ')' : '' ?>
            </option>
          <?php endforeach; ?>
        </select>

        <div class="period-filter-group">
          <?php foreach ($period_labels as $p_key => $p_label): ?>
            <a
              href="<?= site_url('index.php/analytics') ?>?app_id=<?= (int)$selected_app_id ?>&period=<?= $p_key ?>"
              class="period-btn <?= $selected_period === $p_key ? 'active' : '' ?>">
              <?= htmlspecialchars($p_label) ?>
            </a>
          <?php endforeach; ?>
        </div>
      </form>
    </div>

    <!-- Summary Cards -->
    <div class="row g-3 mb-4">
      <div class="col-6 col-lg-3">
        <div class="stats-metric-card">
          <div class="stats-metric-label">
            <span>Login Minggu Ini</span>
            <i class="bi bi-calendar-week text-info"></i>
          </div>
          <div class="stats-metric-value"><?= number_format($summary->week_count, 0, ',', '.') ?></div>
          <div class="stats-metric-sub"><?= number_format($summary->unique_users_week, 0, ',', '.') ?> user unik</div>
        </div>
      </div>
      <div class="col-6 col-lg-3">
        <div class="stats-metric-card">
          <div class="stats-metric-label">
            <span>Login Bulan Ini</span>
            <i class="bi bi-calendar-month text-primary"></i>
          </div>
          <div class="stats-metric-value"><?= number_format($summary->month_count, 0, ',', '.') ?></div>
          <div class="stats-metric-sub"><?= number_format($summary->unique_users_month, 0, ',', '.') ?> user unik</div>
        </div>
      </div>
      <div class="col-6 col-lg-3">
        <div class="stats-metric-card">
          <div class="stats-metric-label">
            <span>Login Tahun Ini</span>
            <i class="bi bi-calendar-check text-warning"></i>
          </div>
          <div class="stats-metric-value"><?= number_format($summary->year_count, 0, ',', '.') ?></div>
          <div class="stats-metric-sub"><?= number_format($summary->unique_users_year, 0, ',', '.') ?> user unik</div>
        </div>
      </div>
      <div class="col-6 col-lg-3">
        <div class="stats-metric-card">
          <div class="stats-metric-label">
            <span>Total Semua Waktu</span>
            <i class="bi bi-people-fill text-success"></i>
          </div>
          <div class="stats-metric-value"><?= number_format($summary->total_count, 0, ',', '.') ?></div>
          <div class="stats-metric-sub"><?= number_format($summary->unique_users_total, 0, ',', '.') ?> user unik</div>
        </div>
      </div>
    </div>

    <!-- Charts Row -->
    <div class="row g-3 mb-4">
      <div class="col-12 col-lg-6">
        <div class="analytics-panel h-100">
          <div class="d-flex align-items-center justify-content-between mb-3">
            <h6 class="mb-0 fw-bold" style="color:var(--text-primary);">
              <i class="bi bi-person-badge me-2 text-info"></i>Grafik Login per User (<?= htmlspecialchars($active_period_label) ?>)
            </h6>
            <span class="badge bg-secondary-subtle text-secondary-emphasis"><?= htmlspecialchars($active_app_label) ?></span>
          </div>
          <div id="userChartEmpty" class="text-center py-5 text-muted" style="display:none;">
            <i class="bi bi-bar-chart d-block fs-3 mb-2 opacity-50"></i>
            <span>Belum ada data login user pada periode ini.</span>
          </div>
          <div class="chart-container-box" id="userChartBox">
            <canvas id="analyticsUserChart"></canvas>
          </div>
        </div>
      </div>

      <div class="col-12 col-lg-6">
        <div class="analytics-panel h-100">
          <div class="d-flex align-items-center justify-content-between mb-3">
            <h6 class="mb-0 fw-bold" style="color:var(--text-primary);">
              <i class="bi bi-diagram-3 me-2 text-success"></i>Grafik Perbandingan Antar Aplikasi
            </h6>
            <span class="badge bg-secondary-subtle text-secondary-emphasis">Minggu / Bulan / Tahun</span>
          </div>
          <div class="chart-container-box">
            <canvas id="analyticsAppChart"></canvas>
          </div>
        </div>
      </div>
    </div>

    <!-- Per Application Breakdown Table -->
    <div class="analytics-panel mb-4">
      <div class="d-flex align-items-center justify-content-between flex-wrap gap-2 mb-3">
        <h6 class="mb-0 fw-bold" style="color:var(--text-primary);">
          <i class="bi bi-grid-fill me-2 text-primary"></i>Rekap Counter per Aplikasi
        </h6>
        <?php if ($selected_app_id > 0): ?>
          <a href="<?= site_url('index.php/analytics') ?>?app_id=0&period=<?= urlencode($selected_period) ?>" class="btn btn-sm btn-outline-secondary">
            <i class="bi bi-x-circle me-1"></i>Tampilkan Semua Aplikasi
          </a>
        <?php endif; ?>
      </div>
      <div class="table-responsive" style="border:1px solid var(--border-color);border-radius:10px;">
        <table class="table table-hover analytics-table mb-0 align-middle">
          <thead>
            <tr>
              <th class="ps-3">#</th>
              <th>Aplikasi</th>
              <th>Kode</th>
              <th class="text-end">Minggu Ini</th>
              <th class="text-end">Bulan Ini</th>
              <th class="text-end">Tahun Ini</th>
              <th class="text-end">Total</th>
              <th class="text-end">User Unik</th>
              <th>Login Terakhir</th>
              <th class="text-center pe-3">Detail</th>
            </tr>
          </thead>
          <tbody>
            <?php if (!empty($app_comparison)): ?>
              <?php foreach ($app_comparison as $i => $row): ?>
                <tr class="<?= $selected_app_id == $row->id ? 'table-active' : '' ?>">
                  <td class="ps-3"><?= $i + 1 ?></td>
                  <td class="fw-semibold">
                    <span class="d-inline-block rounded-circle me-2" style="width:9px;height:9px;background:<?= htmlspecialchars($row->color ?: '#58a6ff') ?>;"></span>
                    <?= htmlspecialchars($row->name) ?>
                  </td>
                  <td><code><?= htmlspecialchars($row->app_code ?: '-') ?></code></td>
                  <td class="text-end"><?= number_format($row->week_count, 0, ',', '.') ?></td>
                  <td class="text-end"><?= number_format($row->month_count, 0, ',', '.') ?></td>
                  <td class="text-end"><?= number_format($row->year_count, 0, ',', '.') ?></td>
                  <td class="text-end fw-bold"><?= number_format($row->total_count, 0, ',', '.') ?></td>
                  <td class="text-end"><?= number_format($row->unique_users, 0, ',', '.') ?></td>
                  <td class="small text-muted"><?= $row->last_login_at ? htmlspecialchars($row->last_login_at) : '-' ?></td>
                  <td class="text-center pe-3">
                    <a href="<?= site_url('index.php/analytics') ?>?app_id=<?= (int)$row->id ?>&period=<?= urlencode($selected_period) ?>" class="btn btn-sm btn-outline-info">
                      <i class="bi bi-funnel"></i>
                    </a>
                  </td>
                </tr>
              <?php endforeach; ?>
            <?php else: ?>
              <tr>
                <td colspan="10" class="text-center py-4 text-muted">Belum ada aplikasi terdaftar.</td>
              </tr>
            <?php endif; ?>
          </tbody>
        </table>
      </div>
    </div>

    <!-- User List, History & Integration Tabs -->
    <div class="analytics-panel">
      <ul class="nav nav-pills analytics-nav-tabs gap-2 mb-3" role="tablist">
        <li class="nav-item" role="presentation">
          <button class="nav-link active" data-bs-toggle="tab" data-bs-target="#paneUserList" type="button" role="tab">
            <i class="bi bi-person-lines-fill me-1"></i>List &amp; Counter per User
          </button>
        </li>
        <li class="nav-item" role="presentation">
          <button class="nav-link" data-bs-toggle="tab" data-bs-target="#paneLoginHistory" type="button" role="tab">
            <i class="bi bi-clock-history me-1"></i>Riwayat Waktu Login
          </button>
        </li>
        <li class="nav-item" role="presentation">
          <button class="nav-link" data-bs-toggle="tab" data-bs-target="#paneIntegration" type="button" role="tab">
            <i class="bi bi-code-slash me-1"></i>Panduan Integrasi Aplikasi
          </button>
        </li>
      </ul>

      <div class="tab-content">
        <!-- Tab 1: Rekap Per User -->
        <div class="tab-pane fade show active" id="paneUserList" role="tabpanel">
          <div class="table-responsive" style="border:1px solid var(--border-color);border-radius:10px;">
            <table class="table table-hover analytics-table mb-0 align-middle">
              <thead>
                <tr>
                  <th class="ps-3">#</th>
                  <th>Username</th>
                  <th>Nama Lengkap</th>
                  <th class="text-end">Minggu Ini</th>
                  <th class="text-end">Bulan Ini</th>
                  <th class="text-end">Tahun Ini</th>
                  <th class="text-end">Total Login</th>
                  <th class="pe-3">Login Terakhir</th>
                </tr>
              </thead>
              <tbody>
                <?php if (!empty($user_stats)): ?>
                  <?php foreach ($user_stats as $i => $u): ?>
                    <tr>
                      <td class="ps-3"><?= $i + 1 ?></td>
                      <td class="fw-semibold"><?= htmlspecialchars($u->username) ?></td>
                      <td><?= htmlspecialchars($u->full_name ?: '-') ?></td>
                      <td class="text-end"><?= number_format($u->week_count, 0, ',', '.') ?></td>
                      <td class="text-end"><?= number_format($u->month_count, 0, ',', '.') ?></td>
                      <td class="text-end"><?= number_format($u->year_count, 0, ',', '.') ?></td>
                      <td class="text-end fw-bold"><?= number_format($u->total_count, 0, ',', '.') ?></td>
                      <td class="pe-3 small text-muted"><?= htmlspecialchars($u->last_login_at ?: '-') ?></td>
                    </tr>
                  <?php endforeach; ?>
                <?php else: ?>
                  <tr>
                    <td colspan="8" class="text-center py-4 text-muted">Belum ada data user yang login.</td>
                  </tr>
                <?php endif; ?>
              </tbody>
            </table>
          </div>
        </div>

        <!-- Tab 2: Riwayat Detail -->
        <div class="tab-pane fade" id="paneLoginHistory" role="tabpanel">
          <div class="table-responsive" style="border:1px solid var(--border-color);border-radius:10px;">
            <table class="table table-hover analytics-table mb-0 align-middle">
              <thead>
                <tr>
                  <th class="ps-3">#</th>
                  <th>Waktu Login</th>
                  <th>Aplikasi</th>
                  <th>Username</th>
                  <th>Nama Lengkap</th>
                  <th class="pe-3">IP Address</th>
                </tr>
              </thead>
              <tbody>
                <?php if (!empty($history)): ?>
                  <?php foreach ($history as $i => $h): ?>
                    <tr>
                      <td class="ps-3"><?= $i + 1 ?></td>
                      <td class="small"><?= htmlspecialchars($h->login_at) ?></td>
                      <td>
                        <span class="badge bg-secondary-subtle text-secondary-emphasis">
                          <?= htmlspecialchars($h->app_name ?: ('App #' . $h->app_id)) ?>
                        </span>
                      </td>
                      <td class="fw-semibold"><?= htmlspecialchars($h->username) ?></td>
                      <td><?= htmlspecialchars($h->full_name ?: '-') ?></td>
                      <td class="pe-3 small text-muted"><?= htmlspecialchars($h->ip_address ?: '-') ?></td>
                    </tr>
                  <?php endforeach; ?>
                <?php else: ?>
                  <tr>
                    <td colspan="6" class="text-center py-4 text-muted">Belum ada riwayat login pada periode ini.</td>
                  </tr>
                <?php endif; ?>
              </tbody>
            </table>
          </div>
        </div>

        <!-- Tab 3: Integration Guide -->
        <div class="tab-pane fade" id="paneIntegration" role="tabpanel">
          <div class="row g-3">
            <div class="col-12 col-lg-6">
              <div class="stats-metric-card">
                <div class="d-flex align-items-center justify-content-between flex-wrap gap-2 mb-2">
                  <div class="fw-bold" style="color:var(--text-primary);">
                    <i class="bi bi-cloud-upload me-2 text-info"></i>Opsi 1: REST API Endpoint (cURL PHP)
                  </div>
                  <a href="<?= site_url('index.php/api_settings') ?>" class="btn btn-sm btn-outline-info">
                    <i class="bi bi-sliders me-1"></i>Ubah Link API
                  </a>
                </div>
                <p class="small text-muted mb-2">
                  Gunakan cara ini apabila aplikasi berada di server atau database yang terpisah dari Portal.
                </p>
<pre class="code-snippet-box"><code>$payload = json_encode([
    'app_code'   => 'askara', // sesuai app_code di portal
    'user_id'    => $user->id_user,
    'username'   => $user->username,
    'full_name'  => $user->full_name,
    'ip_address' => $_SERVER['REMOTE_ADDR'] ?? null,
]);

$ch = curl_init('<?= htmlspecialchars($api_settings->api_endpoint_url) ?>');
curl_setopt_array($ch, [
    CURLOPT_POST           => true,
    CURLOPT_POSTFIELDS     => $payload,
    CURLOPT_RETURNTRANSFER => true,
    CURLOPT_TIMEOUT        => 2,
    CURLOPT_HTTPHEADER     => [
        'Content-Type: application/json',
        'X-API-KEY: <?= htmlspecialchars($api_settings->api_key) ?>',
    ],
]);
curl_exec($ch);
curl_close($ch);</code></pre>
              </div>
            </div>

            <div class="col-12 col-lg-6">
              <div class="stats-metric-card">
                <div class="fw-bold mb-2" style="color:var(--text-primary);">
                  <i class="bi bi-database-add me-2 text-success"></i>Opsi 2: Insert Langsung ke portal_db (Satu Server MySQL)
                </div>
                <p class="small text-muted mb-2">
                  Gunakan cara ini apabila aplikasi berada pada server database MySQL yang sama dengan <code>portal_db</code>.
                </p>
<pre class="code-snippet-box"><code>$portalDb = new mysqli('localhost', 'root', 'PASSWORD', 'portal_db');
if (!$portalDb->connect_error) {
    $appCode = 'askara';
    $stmtApp = $portalDb->prepare("SELECT id FROM apps WHERE app_code = ? LIMIT 1");
    $stmtApp->bind_param('s', $appCode);
    $stmtApp->execute();
    $resApp = $stmtApp->get_result()->fetch_assoc();
    $stmtApp->close();

    if ($resApp) {
        $stmtLog = $portalDb->prepare(
            "INSERT INTO app_login_logs (app_id, user_id, username, full_name, ip_address, login_at)
             VALUES (?, ?, ?, ?, ?, NOW())"
        );
        $stmtLog->bind_param('issss', $resApp['id'], $userId, $username, $fullName, $ipAddress);
        $stmtLog->execute();
        $stmtLog->close();
    }
    $portalDb->close();
}</code></pre>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>

  </main>

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.1/dist/chart.umd.min.js"></script>
  <script>
    const userStatsData = <?= json_encode($user_stats) ?>;
    const appStatsData = <?= json_encode($app_comparison) ?>;
    const activePeriodLabel = <?= json_encode($active_period_label) ?>;

    let userChart = null;
    let appChart = null;

    function getChartTheme() {
      const isLight = document.documentElement.getAttribute('data-bs-theme') === 'light';
      return {
        text: isLight ? '#5f6368' : '#8b949e',
        grid: isLight ? 'rgba(0,0,0,0.08)' : 'rgba(255,255,255,0.08)',
        blue: isLight ? 'rgba(26, 115, 232, 0.8)' : 'rgba(88, 166, 255, 0.8)',
        green: isLight ? 'rgba(52, 168, 83, 0.8)' : 'rgba(63, 185, 80, 0.8)',
        orange: isLight ? 'rgba(251, 188, 4, 0.8)' : 'rgba(210, 153, 34, 0.8)'
      };
    }

    function renderAnalyticsCharts() {
      const palette = getChartTheme();

      // 1. User Login Chart
      const activeUsers = (userStatsData || []).filter(u => (Number(u.period_count) || 0) > 0).slice(0, 15);
      const emptyUserEl = document.getElementById('userChartEmpty');
      const boxUserEl = document.getElementById('userChartBox');

      if (userChart) {
        userChart.destroy();
        userChart = null;
      }

      if (activeUsers.length === 0) {
        boxUserEl.style.display = 'none';
        emptyUserEl.style.display = 'block';
      } else {
        boxUserEl.style.display = 'block';
        emptyUserEl.style.display = 'none';

        userChart = new Chart(document.getElementById('analyticsUserChart').getContext('2d'), {
          type: 'bar',
          data: {
            labels: activeUsers.map(u => (u.full_name && u.full_name !== u.username) ? u.full_name + ' (' + u.username + ')' : u.username),
            datasets: [{
              label: 'Login (' + activePeriodLabel + ')',
              data: activeUsers.map(u => Number(u.period_count) || 0),
              backgroundColor: palette.blue,
              borderRadius: 6,
              maxBarThickness: 40
            }]
          },
          options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: { legend: { labels: { color: palette.text } } },
            scales: {
              x: { ticks: { color: palette.text }, grid: { display: false } },
              y: { beginAtZero: true, ticks: { color: palette.text, precision: 0 }, grid: { color: palette.grid } }
            }
          }
        });
      }

      // 2. Application Comparison Chart
      if (appChart) {
        appChart.destroy();
        appChart = null;
      }

      appChart = new Chart(document.getElementById('analyticsAppChart').getContext('2d'), {
        type: 'bar',
        data: {
          labels: (appStatsData || []).map(a => a.name),
          datasets: [
            {
              label: 'Minggu Ini',
              data: (appStatsData || []).map(a => Number(a.week_count) || 0),
              backgroundColor: palette.blue,
              borderRadius: 4
            },
            {
              label: 'Bulan Ini',
              data: (appStatsData || []).map(a => Number(a.month_count) || 0),
              backgroundColor: palette.green,
              borderRadius: 4
            },
            {
              label: 'Tahun Ini',
              data: (appStatsData || []).map(a => Number(a.year_count) || 0),
              backgroundColor: palette.orange,
              borderRadius: 4
            }
          ]
        },
        options: {
          responsive: true,
          maintainAspectRatio: false,
          plugins: { legend: { labels: { color: palette.text } } },
          scales: {
            x: { ticks: { color: palette.text }, grid: { display: false } },
            y: { beginAtZero: true, ticks: { color: palette.text, precision: 0 }, grid: { color: palette.grid } }
          }
        }
      });
    }

    document.addEventListener('DOMContentLoaded', function() {
      const themeToggle = document.getElementById('themeToggle');
      const themeIcon = document.getElementById('themeIcon');
      const html = document.documentElement;
      const savedTheme = localStorage.getItem('theme') || 'dark';
      html.setAttribute('data-bs-theme', savedTheme);
      updateThemeIcon(savedTheme);

      renderAnalyticsCharts();

      themeToggle.addEventListener('click', () => {
        const current = html.getAttribute('data-bs-theme');
        const next = current === 'dark' ? 'light' : 'dark';
        html.setAttribute('data-bs-theme', next);
        localStorage.setItem('theme', next);
        updateThemeIcon(next);
        renderAnalyticsCharts();
      });

      function updateThemeIcon(theme) {
        themeIcon.className = theme === 'dark' ? 'bi bi-moon-fill' : 'bi bi-sun-fill';
      }
    });
  </script>
</body>

</html>
