<!DOCTYPE html>
<html lang="id" data-bs-theme="dark">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>ISO Platform Askara Group</title>
  <link rel="icon" type="image/png" href="assets/portal-icon.png" />

  <!-- Bootstrap 5 -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" />
  <!-- Bootstrap Icons -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet" />
  <!-- Google Fonts -->
  <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet" />
  <link rel="stylesheet" href="<?= base_url('assets/css/portal.css?v=' . filemtime(FCPATH . 'assets/css/portal.css')) ?>" />
</head>

<body>

  <!-- =======================================
     NAVBAR
======================================= -->
  <nav class="portal-navbar">
    <div class="container-xl">
      <div class="d-flex align-items-center justify-content-between gap-3 flex-wrap">
        <a href="<?= site_url('/') ?>" class="portal-brand">
          <div class="brand-icon overflow-hidden">
            <img src="<?= base_url('assets/portal-icon.png') ?>" alt="logo_portal" width="40" height="40" />
          </div>
          ISO Platform Askara Group
        </a>

        <!-- Search -->
        <div class="search-wrapper">
          <i class="bi bi-search"></i>
          <input
            type="text"
            id="searchInput"
            class="search-input"
            placeholder="Cari aplikasi..."
            value="<?= htmlspecialchars($search ?: '') ?>"
            autocomplete="off" />
        </div>

        <!-- Theme Toggle -->
        <div class="d-flex align-items-center gap-2">
          <?php if (!empty($logged_in)): ?>
            <a href="<?= site_url('index.php/analytics') ?>" class="btn btn-sm" style="border-radius:8px;padding:6px 10px;background:var(--bg-elevated);border:1px solid var(--border-color);color:var(--text-primary);" title="Statistik Login">
              <i class="bi bi-bar-chart-line-fill me-1 text-info"></i><span class="d-none d-sm-inline">Statistik</span>
            </a>
            <a href="<?= site_url('index.php/api_settings') ?>" class="btn btn-sm" style="border-radius:8px;padding:6px 10px;background:var(--bg-elevated);border:1px solid var(--border-color);color:var(--text-primary);" title="Pengaturan Link API">
              <i class="bi bi-link-45deg me-1 text-success"></i><span class="d-none d-sm-inline">Link API</span>
            </a>
            <a href="<?= site_url('index.php/users') ?>" class="btn btn-sm" style="border-radius:8px;padding:6px 10px;background:var(--bg-elevated);border:1px solid var(--border-color);color:var(--text-primary);" title="Kelola User">
              <i class="bi bi-people-fill"></i>
            </a>
            <div class="dropdown">
              <button class="btn btn-sm dropdown-toggle" style="border-radius:8px;padding:6px 10px;background:var(--bg-elevated);border:1px solid var(--border-color);color:var(--text-primary);" data-bs-toggle="dropdown" title="<?= htmlspecialchars($current_user) ?>">
                <i class="bi bi-person-circle me-1"></i><?= htmlspecialchars($current_user) ?>
              </button>
              <ul class="dropdown-menu dropdown-menu-end">
                <li><a class="dropdown-item" href="<?= site_url('index.php/auth/logout') ?>"><i class="bi bi-box-arrow-right me-2"></i>Logout</a></li>
              </ul>
            </div>
          <?php else: ?>
            <button class="btn btn-sm" style="border-radius:8px;padding:6px 10px;background:var(--bg-elevated);border:1px solid var(--border-color);color:var(--text-primary);" data-bs-toggle="modal" data-bs-target="#modalLogin" title="Admin Login">
              <i class="bi bi-box-arrow-in-right me-1"></i>Login
            </button>
          <?php endif; ?>
          <button id="themeToggle" class="btn btn-sm" style="border-radius:8px;padding:6px 10px;background:var(--bg-elevated);border:1px solid var(--border-color);color:var(--text-primary);" title="Dark Mode">
            <i class="bi bi-moon-fill" id="themeIcon"></i>
          </button>
        </div>

      </div>
    </div>
  </nav>

  <!-- =======================================
     STATS BAR
======================================= -->
  <div class="stats-bar">
    <div class="container-xl">
      <div class="d-flex align-items-center justify-content-start flex-wrap gap-3">
        <!-- Stats -->
        <div class="d-flex gap-2 flex-wrap">
          <span class="stat-pill">
            <i class="bi bi-app-indicator text-info"></i>
            <strong><?= count($apps) ?></strong> aplikasi
          </span>
          <?php
          $is_active = function ($a) {
            return $a->is_active;
          };
          ?>
          <?php $active_count = count(array_filter($apps, $is_active)); ?>
          <span class="stat-pill">
            <i class="bi bi-circle-fill text-success" style="font-size:.55rem;"></i>
            <strong><?= $active_count ?></strong> aktif
          </span>
          <?php if (!empty($logged_in) && !empty($portal_summary)): ?>
            <a href="<?= site_url('index.php/analytics?period=week') ?>" class="stat-pill text-decoration-none" title="Lihat detail statistik login minggu ini">
              <i class="bi bi-calendar-week text-info"></i>
              Minggu ini: <strong><?= number_format($portal_summary->week_count, 0, ',', '.') ?></strong> login
            </a>
            <a href="<?= site_url('index.php/analytics?period=month') ?>" class="stat-pill text-decoration-none" title="Lihat detail statistik login bulan ini">
              <i class="bi bi-calendar-month text-primary"></i>
              Bulan ini: <strong><?= number_format($portal_summary->month_count, 0, ',', '.') ?></strong> login
            </a>
            <a href="<?= site_url('index.php/analytics?period=year') ?>" class="stat-pill text-decoration-none" title="Lihat detail statistik login tahun ini">
              <i class="bi bi-calendar-check text-warning"></i>
              Tahun ini: <strong><?= number_format($portal_summary->year_count, 0, ',', '.') ?></strong> login
            </a>
          <?php endif; ?>
        </div>
      </div>
    </div>
  </div>


  <!-- =======================================
     MAIN CONTENT
======================================= -->
  <main class="container-xl py-3">

    <?php if (!empty($apps)): ?>

      <div class="apps-grid mb-4">
        <?php foreach ($apps as $app): ?>

          <?php
          $initials = strtoupper(mb_substr($app->name, 0, 1));
          if (strpos($app->name, ' ') !== false) {
            $parts = explode(' ', $app->name);
            $initials = strtoupper(mb_substr($parts[0], 0, 1) . mb_substr($parts[1], 0, 1));
          }
          $accent_color = $app->color ?: '#58a6ff';
          ?>

          <div style="position:relative;">

            <!-- App Card (clickable to open URL) -->
            <a
              href="<?= htmlspecialchars($app->url) ?>"
              target="_blank"
              rel="noopener noreferrer"
              class="app-card <?= $app->is_active ? '' : 'inactive' ?>"
              style="--card-accent: <?= htmlspecialchars($accent_color) ?>;">
              <!-- Action Buttons (hanya tampil jika login) -->
              <?php if (!empty($logged_in)): ?>
              <div class="card-actions" onclick="event.preventDefault();event.stopPropagation();">
                <button
                  class="card-action-btn btn-stats"
                  title="Statistik Login"
                  onclick="openStatsModal(<?= (int)$app->id ?>, <?= htmlspecialchars(json_encode($app->name), ENT_QUOTES, 'UTF-8') ?>)">
                  <i class="bi bi-bar-chart-line"></i>
                </button>
                <button
                  class="card-action-btn btn-edit"
                  title="Edit"
                  onclick="openEditModal(<?= htmlspecialchars(json_encode($app)) ?>)">
                  <i class="bi bi-pencil"></i>
                </button>
                <button
                  class="card-action-btn btn-delete"
                  title="Hapus"
                  onclick="openDeleteModal(<?= $app->id ?>, '<?= addslashes($app->name) ?>')">
                  <i class="bi bi-trash"></i>
                </button>
              </div>
              <?php endif; ?>

              <!-- Logo -->
              <?php if ($app->logo && file_exists(FCPATH . $app->logo)): ?>
                <div class="app-logo-wrap">
                  <img src="<?= base_url($app->logo) ?>" alt="<?= htmlspecialchars($app->name) ?>" />
                </div>
              <?php else: ?>
                <div class="app-logo-wrap" style="background: <?= htmlspecialchars($accent_color) ?>1a;">
                  <div class="app-logo-initials" style="background: <?= htmlspecialchars($accent_color) ?>;">
                    <?= $initials ?>
                  </div>
                </div>
              <?php endif; ?>

              <h6 class="app-name"><?= htmlspecialchars($app->name) ?></h6>
              <p class="app-desc"><?= htmlspecialchars($app->description ?: '—') ?></p>

              <div class="app-meta">
                <span class="app-category-badge"><?= htmlspecialchars($app->category) ?></span>
              </div>

              <?php if (!empty($logged_in)): ?>
                <?php
                $cnt = isset($app_counters[(int)$app->id]) ? $app_counters[(int)$app->id] : null;
                $w_cnt = $cnt ? (int)$cnt->week_count : 0;
                $m_cnt = $cnt ? (int)$cnt->month_count : 0;
                $y_cnt = $cnt ? (int)$cnt->year_count : 0;
                ?>
                <div
                  class="app-login-counter"
                  title="Klik untuk melihat grafik & list user login"
                  onclick="event.preventDefault();event.stopPropagation();openStatsModal(<?= (int)$app->id ?>, <?= htmlspecialchars(json_encode($app->name), ENT_QUOTES, 'UTF-8') ?>);">
                  <i class="bi bi-bar-chart-line-fill"></i>
                  <span>M: <strong><?= number_format($w_cnt, 0, ',', '.') ?></strong></span>
                  <span class="counter-sep">&middot;</span>
                  <span>B: <strong><?= number_format($m_cnt, 0, ',', '.') ?></strong></span>
                  <span class="counter-sep">&middot;</span>
                  <span>T: <strong><?= number_format($y_cnt, 0, ',', '.') ?></strong></span>
                </div>
              <?php endif; ?>
            </a>

          </div>

        <?php endforeach; ?>

        <!-- Add app card (hanya tampil jika login) -->
        <?php if (!empty($logged_in)): ?>
        <div class="add-card" role="button" data-bs-toggle="modal" data-bs-target="#modalApp" onclick="resetModalForCreate()">
          <div class="add-card-icon">
            <i class="bi bi-plus-lg"></i>
          </div>
          <div>Tambah Aplikasi</div>
        </div>
        <?php endif; ?>
      </div>

    <?php else: ?>
      <div class="empty-state">
        <i class="bi bi-grid-3x3-gap d-block"></i>
        <h5>Belum ada aplikasi</h5>
        <p class="mb-4">Mulai tambahkan aplikasi ke portal Anda.</p>
        <?php if (!empty($logged_in)): ?>
        <button class="btn-primary-custom" data-bs-toggle="modal" data-bs-target="#modalApp">
          <i class="bi bi-plus-lg me-2"></i>Tambah Aplikasi Pertama
        </button>
        <?php else: ?>
        <p class="text-muted">Login sebagai admin untuk mengelola aplikasi.</p>
        <?php endif; ?>
      </div>
    <?php endif; ?>

  </main>


  <!-- =======================================
     MODAL — TAMBAH / EDIT
======================================= -->
  <div class="modal fade" id="modalApp" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
      <div class="modal-content">

        <div class="modal-header">
          <h5 class="modal-title" id="modalAppTitle">
            <i class="bi bi-plus-circle me-2 text-info"></i>Tambah Aplikasi
          </h5>
          <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
        </div>

        <form id="appForm" method="POST" action="<?= site_url('index.php/apps/store') ?>" enctype="multipart/form-data">
          <input type="hidden" name="id" id="formAppId" value="" />
          <input type="hidden" name="remove_logo" id="removeLogoInput" value="0" />

          <div class="modal-body">
            <div class="row g-3">

              <!-- Logo Upload -->
              <div class="col-12">
                <label class="form-label">Logo Aplikasi</label>
                <div class="d-flex align-items-center gap-3">

                  <!-- Preview -->
                  <div class="logo-preview-wrap" id="logoPreviewWrap">
                    <img id="logoPreviewImg" src="" alt="" style="display:none;" />
                    <i class="bi bi-image logo-placeholder" id="logoPlaceholderIcon"></i>
                  </div>

                  <!-- Upload Zone -->
                  <div class="flex-fill">
                    <div class="upload-zone" id="uploadZone">
                      <input type="file" name="logo" id="logoFileInput" accept="image/*" onchange="previewLogo(this)" />
                      <i class="bi bi-cloud-upload mb-1 d-block" style="font-size:1.3rem;color:var(--text-muted);"></i>
                      <div style="font-size:.82rem;color:var(--text-muted);">Klik atau seret gambar ke sini</div>
                      <div style="font-size:.75rem;color:var(--text-muted);margin-top:2px;">PNG, JPG, SVG — maks 2MB</div>
                    </div>
                    <div id="removeLogoWrap" class="mt-2" style="display:none;">
                      <button type="button" class="btn btn-sm btn-outline-danger" onclick="removeLogo()">
                        <i class="bi bi-x me-1"></i>Hapus Logo
                      </button>
                    </div>
                  </div>

                </div>
              </div>

              <!-- Name + App Code -->
              <div class="col-7">
                <label class="form-label" for="inputName">Nama Aplikasi <span class="text-danger">*</span></label>
                <input type="text" id="inputName" name="name" class="form-control" placeholder="Contoh: Slack, Jira, Figma..." required />
              </div>

              <div class="col-5">
                <label class="form-label" for="inputAppCode">Kode Aplikasi</label>
                <input type="text" id="inputAppCode" name="app_code" class="form-control" placeholder="Otomatis dari URL" />
              </div>

              <!-- URL -->
              <div class="col-12">
                <label class="form-label" for="inputUrl">URL Aplikasi <span class="text-danger">*</span></label>
                <input type="url" id="inputUrl" name="url" class="form-control" placeholder="https://..." required />
              </div>

              <!-- Description -->
              <div class="col-12">
                <label class="form-label" for="inputDesc">Deskripsi</label>
                <input type="text" id="inputDesc" name="description" class="form-control" placeholder="Deskripsi singkat aplikasi..." />
              </div>

              <!-- Category + Sort -->
              <div class="col-7">
                <label class="form-label" for="inputCategory">Kategori</label>
                <input type="text" id="inputCategory" name="category" class="form-control" placeholder="Produktivitas, Komunikasi..." list="catList" />
                <datalist id="catList">
                  <?php foreach ($categories as $cat): ?>
                    <option value="<?= htmlspecialchars($cat) ?>">
                    <?php endforeach; ?>
                </datalist>
              </div>

              <div class="col-5">
                <label class="form-label" for="inputSort">Urutan</label>
                <input type="number" id="inputSort" name="sort_order" class="form-control" value="0" min="0" />
              </div>

              <!-- Color -->
              <div class="col-12">
                <label class="form-label">Warna Aksen</label>
                <div class="d-flex align-items-center gap-3">
                  <div class="color-swatches" id="colorSwatches">
                    <?php
                    $palette = ['#58a6ff', '#3fb950', '#f85149', '#d29922', '#a371f7', '#39d353', '#F24E1E', '#EA4335', '#4285F4', '#25D366', '#FF6B6B', '#C9CBFF'];
                    foreach ($palette as $c):
                    ?>
                      <div
                        class="color-swatch"
                        style="background:<?= $c ?>;"
                        title="<?= $c ?>"
                        onclick="selectColor('<?= $c ?>')"></div>
                    <?php endforeach; ?>
                  </div>
                  <input type="color" id="inputColor" name="color" value="#58a6ff" style="width:38px;height:38px;border:none;border-radius:8px;background:transparent;cursor:pointer;" title="Pilih warna" onchange="onColorChange(this.value)" />
                </div>
                <small class="text-muted mt-1 d-block" id="colorDisplay">#58a6ff</small>
              </div>

            </div>
          </div>

          <div class="modal-footer gap-2">
            <button type="button" class="btn-ghost" data-bs-dismiss="modal">Batal</button>
            <button type="submit" class="btn-primary-custom">
              <i class="bi bi-check-lg me-1"></i>
              <span id="btnSubmitText">Simpan Aplikasi</span>
            </button>
          </div>
        </form>
      </div>
    </div>
  </div>


  <!-- =======================================
     MODAL — HAPUS
======================================= -->
  <div class="modal fade" id="modalDelete" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-sm">
      <div class="modal-content">
        <div class="modal-header border-0 pb-0">
          <h5 class="modal-title text-danger"><i class="bi bi-trash me-2"></i>Hapus Aplikasi</h5>
          <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
        </div>
        <div class="modal-body pt-2">
          <p class="mb-0" style="font-size:.9rem;color:var(--text-secondary);">
            Hapus <strong id="deleteAppName" style="color:var(--text-primary);"></strong>? Tindakan ini tidak dapat diurungkan.
          </p>
        </div>
        <div class="modal-footer gap-2">
          <form id="deleteForm" method="POST" action="<?= site_url('index.php/apps/delete') ?>">
            <input type="hidden" name="id" id="deleteAppId" />
            <button type="button" class="btn-ghost" data-bs-dismiss="modal">Batal</button>
            <button type="submit" class="btn-danger-custom">
              <i class="bi bi-trash me-1"></i>Hapus
            </button>
          </form>
        </div>
      </div>
    </div>
  </div>


  <!-- =======================================
     MODAL — LOGIN
======================================= -->
  <div class="modal fade" id="modalLogin" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-sm">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title"><i class="bi bi-shield-lock me-2 text-info"></i>Admin Login</h5>
          <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
        </div>
        <form id="loginForm" method="POST" action="<?= site_url('index.php/auth/login') ?>">
          <div class="modal-body">
            <div class="mb-3">
              <label class="form-label" for="loginUsername">Username</label>
              <input type="text" id="loginUsername" name="username" class="form-control" placeholder="Username" required autocomplete="username" />
            </div>
            <div class="mb-3">
              <label class="form-label" for="loginPassword">Password</label>
              <input type="password" id="loginPassword" name="password" class="form-control" placeholder="Password" required autocomplete="current-password" />
            </div>
            <div id="loginError" class="text-danger small" style="display:none;"></div>
          </div>
          <div class="modal-footer gap-2">
            <button type="button" class="btn-ghost" data-bs-dismiss="modal">Batal</button>
            <button type="submit" class="btn-primary-custom" id="btnLogin">
              <i class="bi bi-box-arrow-in-right me-1"></i>
              <span id="btnLoginText">Login</span>
            </button>
          </div>
        </form>
      </div>
    </div>
  </div>


  <?php if (!empty($logged_in)): ?>
  <!-- =======================================
     MODAL — STATISTIK LOGIN APLIKASI (ADMIN ONLY)
======================================= -->
  <div class="modal fade" id="modalAppStats" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-xl modal-dialog-centered modal-dialog-scrollable">
      <div class="modal-content">
        <div class="modal-header">
          <div class="d-flex align-items-center gap-2">
            <i class="bi bi-bar-chart-line-fill text-info fs-5"></i>
            <div>
              <h5 class="modal-title mb-0" id="statsModalTitle">Statistik Login Aplikasi</h5>
              <small class="text-muted" id="statsModalSubtitle">Rekap aktivitas login user</small>
            </div>
          </div>
          <div class="d-flex align-items-center gap-2">
            <a href="<?= site_url('index.php/analytics') ?>" id="statsFullLink" class="btn btn-sm btn-outline-info">
              <i class="bi bi-box-arrow-up-right me-1"></i>Buka Halaman Analitik
            </a>
            <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
          </div>
        </div>

        <div class="modal-body">
          <!-- Counter Summary Cards -->
          <div class="row g-3 mb-4">
            <div class="col-6 col-lg-3">
              <div class="stats-metric-card">
                <div class="stats-metric-label">
                  <span>Minggu Ini</span>
                  <i class="bi bi-calendar-week text-info"></i>
                </div>
                <div class="stats-metric-value" id="statWeekCount">0</div>
                <div class="stats-metric-sub" id="statWeekUsers">0 user unik</div>
              </div>
            </div>
            <div class="col-6 col-lg-3">
              <div class="stats-metric-card">
                <div class="stats-metric-label">
                  <span>Bulan Ini</span>
                  <i class="bi bi-calendar-month text-primary"></i>
                </div>
                <div class="stats-metric-value" id="statMonthCount">0</div>
                <div class="stats-metric-sub" id="statMonthUsers">0 user unik</div>
              </div>
            </div>
            <div class="col-6 col-lg-3">
              <div class="stats-metric-card">
                <div class="stats-metric-label">
                  <span>Tahun Ini</span>
                  <i class="bi bi-calendar-check text-warning"></i>
                </div>
                <div class="stats-metric-value" id="statYearCount">0</div>
                <div class="stats-metric-sub" id="statYearUsers">0 user unik</div>
              </div>
            </div>
            <div class="col-6 col-lg-3">
              <div class="stats-metric-card">
                <div class="stats-metric-label">
                  <span>Total Semua Waktu</span>
                  <i class="bi bi-people-fill text-success"></i>
                </div>
                <div class="stats-metric-value" id="statTotalCount">0</div>
                <div class="stats-metric-sub" id="statTotalUsers">0 user unik</div>
              </div>
            </div>
          </div>

          <!-- Period Filter -->
          <div class="d-flex align-items-center justify-content-between flex-wrap gap-2 mb-3">
            <h6 class="mb-0 fw-bold" style="color:var(--text-primary);">
              <i class="bi bi-graph-up me-2 text-info"></i>Grafik &amp; Daftar Login per User
            </h6>
            <div class="period-filter-group" id="statsPeriodFilter">
              <button type="button" class="period-btn" data-period="week" onclick="changeStatsPeriod('week')">Minggu Ini</button>
              <button type="button" class="period-btn active" data-period="month" onclick="changeStatsPeriod('month')">Bulan Ini</button>
              <button type="button" class="period-btn" data-period="year" onclick="changeStatsPeriod('year')">Tahun Ini</button>
              <button type="button" class="period-btn" data-period="all" onclick="changeStatsPeriod('all')">Semua</button>
            </div>
          </div>

          <!-- Chart -->
          <div class="analytics-panel mb-4">
            <div id="statsChartLoading" class="text-center py-5 text-muted" style="display:none;">
              <div class="spinner-border spinner-border-sm me-2" role="status"></div>Memuat data statistik...
            </div>
            <div id="statsChartEmpty" class="text-center py-4 text-muted" style="display:none;">
              <i class="bi bi-bar-chart d-block fs-3 mb-2 opacity-50"></i>
              <span>Belum ada data login pada periode yang dipilih.</span>
            </div>
            <div class="chart-container-box" id="statsChartWrapper">
              <canvas id="userLoginChart"></canvas>
            </div>
          </div>

          <!-- List Tabs -->
          <ul class="nav nav-pills analytics-nav-tabs gap-2 mb-3" role="tablist">
            <li class="nav-item" role="presentation">
              <button class="nav-link active" data-bs-toggle="tab" data-bs-target="#tabUserStats" type="button" role="tab">
                <i class="bi bi-person-lines-fill me-1"></i>Rekap per User
              </button>
            </li>
            <li class="nav-item" role="presentation">
              <button class="nav-link" data-bs-toggle="tab" data-bs-target="#tabLoginHistory" type="button" role="tab">
                <i class="bi bi-clock-history me-1"></i>Riwayat Waktu Login
              </button>
            </li>
          </ul>

          <div class="tab-content">
            <div class="tab-pane fade show active" id="tabUserStats" role="tabpanel">
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
                      <th class="text-end">Total</th>
                      <th class="pe-3">Login Terakhir</th>
                    </tr>
                  </thead>
                  <tbody id="statsUserTableBody">
                    <tr>
                      <td colspan="8" class="text-center py-4 text-muted">Memuat data...</td>
                    </tr>
                  </tbody>
                </table>
              </div>
            </div>

            <div class="tab-pane fade" id="tabLoginHistory" role="tabpanel">
              <div class="table-responsive" style="border:1px solid var(--border-color);border-radius:10px;">
                <table class="table table-hover analytics-table mb-0 align-middle">
                  <thead>
                    <tr>
                      <th class="ps-3">#</th>
                      <th>Waktu Login</th>
                      <th>Username</th>
                      <th>Nama Lengkap</th>
                      <th class="pe-3">IP Address</th>
                    </tr>
                  </thead>
                  <tbody id="statsHistoryTableBody">
                    <tr>
                      <td colspan="5" class="text-center py-4 text-muted">Memuat data...</td>
                    </tr>
                  </tbody>
                </table>
              </div>
            </div>
          </div>
        </div>

        <div class="modal-footer">
          <button type="button" class="btn-ghost" data-bs-dismiss="modal">Tutup</button>
        </div>
      </div>
    </div>
  </div>
  <?php endif; ?>


  <!-- =======================================
     TOAST CONTAINER
======================================= -->
  <div class="toast-container-custom" id="toastContainer"></div>


  <!-- Scripts -->
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
  <script>
    // ============================================
    // LOGO PREVIEW
    // ============================================
    function previewLogo(input) {
      if (input.files && input.files[0]) {
        const reader = new FileReader();
        reader.onload = e => {
          document.getElementById('logoPreviewImg').src = e.target.result;
          document.getElementById('logoPreviewImg').style.display = 'block';
          document.getElementById('logoPlaceholderIcon').style.display = 'none';
          document.getElementById('removeLogoWrap').style.display = 'block';
        };
        reader.readAsDataURL(input.files[0]);
      }
    }

    function removeLogo() {
      document.getElementById('logoPreviewImg').style.display = 'none';
      document.getElementById('logoPlaceholderIcon').style.display = '';
      document.getElementById('removeLogoWrap').style.display = 'none';
      document.getElementById('logoFileInput').value = '';
      document.getElementById('removeLogoInput').value = '1';
    }

    // ============================================
    // COLOR PICKER
    // ============================================
    function selectColor(hex) {
      document.getElementById('inputColor').value = hex;
      document.getElementById('colorDisplay').textContent = hex;
      document.querySelectorAll('.color-swatch').forEach(el => {
        el.classList.toggle('active', el.style.background === hex || el.getAttribute('title') === hex);
      });
    }

    function onColorChange(hex) {
      document.getElementById('colorDisplay').textContent = hex;
      document.querySelectorAll('.color-swatch').forEach(el => el.classList.remove('active'));
    }

    function resetModalForCreate() {
      const form = document.getElementById('appForm');
      form.action = '<?= site_url('index.php/apps/store') ?>';
      form.reset();
      document.getElementById('formAppId').value = '';
      document.getElementById('inputAppCode').value = '';
      document.getElementById('logoPreviewImg').style.display = 'none';
      document.getElementById('logoPlaceholderIcon').style.display = '';
      document.getElementById('removeLogoWrap').style.display = 'none';
      document.getElementById('removeLogoInput').value = '0';
      document.getElementById('modalAppTitle').innerHTML =
        '<i class="bi bi-plus-circle me-2 text-info"></i>Tambah Aplikasi';
      document.getElementById('btnSubmitText').textContent = 'Simpan Aplikasi';
      selectColor('#58a6ff');
    }

    // ============================================
    // OPEN EDIT MODAL
    // ============================================
    function openEditModal(app) {
      const form = document.getElementById('appForm');
      form.action = '<?= site_url('index.php/apps/update') ?>';

      document.getElementById('formAppId').value = app.id;
      document.getElementById('inputName').value = app.name;
      document.getElementById('inputAppCode').value = app.app_code || '';
      document.getElementById('inputUrl').value = app.url;
      document.getElementById('inputDesc').value = app.description || '';
      document.getElementById('inputCategory').value = app.category || '';
      document.getElementById('inputSort').value = app.sort_order || 0;
      document.getElementById('modalAppTitle').innerHTML =
        '<i class="bi bi-pencil me-2 text-warning"></i>Edit Aplikasi';
      document.getElementById('btnSubmitText').textContent = 'Perbarui Aplikasi';
      document.getElementById('removeLogoInput').value = '0';

      // Color
      const color = app.color || '#58a6ff';
      selectColor(color);
      document.getElementById('inputColor').value = color;

      // Logo preview
      if (app.logo) {
        document.getElementById('logoPreviewImg').src = '<?= base_url() ?>' + app.logo;
        document.getElementById('logoPreviewImg').style.display = 'block';
        document.getElementById('logoPlaceholderIcon').style.display = 'none';
        document.getElementById('removeLogoWrap').style.display = 'block';
      } else {
        document.getElementById('logoPreviewImg').style.display = 'none';
        document.getElementById('logoPlaceholderIcon').style.display = '';
        document.getElementById('removeLogoWrap').style.display = 'none';
      }

      new bootstrap.Modal(document.getElementById('modalApp')).show();
    }

    // ============================================
    // OPEN DELETE MODAL
    // ============================================
    function openDeleteModal(id, name) {
      document.getElementById('deleteAppId').value = id;
      document.getElementById('deleteAppName').textContent = name;
      new bootstrap.Modal(document.getElementById('modalDelete')).show();
    }

    // ============================================
    // RESET MODAL ON CLOSE
    // ============================================
    document.getElementById('modalApp').addEventListener('hidden.bs.modal', () => {
      const form = document.getElementById('appForm');
      form.action = '<?= site_url('index.php/apps/store') ?>';
      form.reset();
      document.getElementById('formAppId').value = '';
      document.getElementById('inputAppCode').value = '';
      document.getElementById('logoPreviewImg').style.display = 'none';
      document.getElementById('logoPlaceholderIcon').style.display = '';
      document.getElementById('removeLogoWrap').style.display = 'none';
      document.getElementById('removeLogoInput').value = '0';
      document.getElementById('modalAppTitle').innerHTML =
        '<i class="bi bi-plus-circle me-2 text-info"></i>Tambah Aplikasi';
      document.getElementById('btnSubmitText').textContent = 'Simpan Aplikasi';
      selectColor('#58a6ff');
    });

    // ============================================
    // DRAG & DROP UPLOAD ZONE
    // ============================================
    const uploadZone = document.getElementById('uploadZone');
    ['dragenter', 'dragover'].forEach(e => uploadZone.addEventListener(e, ev => {
      ev.preventDefault();
      uploadZone.classList.add('dragover');
    }));
    ['dragleave', 'drop'].forEach(e => uploadZone.addEventListener(e, ev => {
      ev.preventDefault();
      uploadZone.classList.remove('dragover');
    }));
    uploadZone.addEventListener('drop', ev => {
      const files = ev.dataTransfer.files;
      if (files.length) {
        document.getElementById('logoFileInput').files = files;
        previewLogo(document.getElementById('logoFileInput'));
      }
    });

    // ============================================
    // TOAST (Flash Messages)
    // ============================================
    <?php $success = $this->session->flashdata('success');
    $error = $this->session->flashdata('error'); ?>
    <?php if ($success): ?>
      showToast(<?= json_encode($success) ?>, 'success');
    <?php endif; ?>
    <?php if ($error): ?>
      showToast(<?= json_encode($error) ?>, 'error');
    <?php endif; ?>

    function showToast(msg, type = 'success') {
      const icon = type === 'success' ?
        '<i class="bi bi-check-circle-fill text-success me-2"></i>' :
        '<i class="bi bi-exclamation-circle-fill text-danger me-2"></i>';
      const toast = document.createElement('div');
      toast.className = `toast-custom ${type}`;
      toast.innerHTML = icon + msg;
      document.getElementById('toastContainer').appendChild(toast);
      setTimeout(() => toast.remove(), 4000);
    }

    // ============================================
    // AJAX SUBMIT FOR CREATE/UPDATE
    // ============================================
    document.getElementById('appForm').addEventListener('submit', async function(event) {
      event.preventDefault();

      const form = event.currentTarget;
      const submitButton = form.querySelector('[type="submit"]');
      const submitText = document.getElementById('btnSubmitText');
      const originalSubmitText = submitText.textContent;

      submitButton.disabled = true;
      submitText.textContent = 'Menyimpan...';

      const formData = new FormData(form);

      try {
        const response = await fetch(form.action, {
          method: 'POST',
          body: formData,
          headers: {
            'X-Requested-With': 'XMLHttpRequest',
          },
        });

        const data = await response.json();

        if (data.status) {
          showToast(data.message || 'Berhasil disimpan', 'success');
          setTimeout(() => {
            window.location.href = data.redirect || window.location.href;
          }, 800);
        } else {
          showToast(data.message || 'Gagal menyimpan data', 'error');
        }
      } catch (error) {
        showToast('Terjadi kesalahan saat menyimpan', 'error');
        console.error(error);
      } finally {
        submitButton.disabled = false;
        submitText.textContent = originalSubmitText;
      }
    });

    // ============================================
    // AJAX SEARCH
    // ============================================
    let searchTimeout;
    document.getElementById('searchInput').addEventListener('input', function(event) {
      const query = event.target.value.trim();

      clearTimeout(searchTimeout);

      searchTimeout = setTimeout(async () => {
        try {
          const url = query.length === 0 ?
            '<?= site_url('index.php/search_ajax') ?>' :
            '<?= site_url('index.php/search_ajax') ?>?q=' + encodeURIComponent(query);

          const response = await fetch(url, {
            headers: {
              'X-Requested-With': 'XMLHttpRequest',
            },
          });

          const data = await response.json();

          if (data.status) {
            const gridContainer = document.querySelector('.apps-grid');
            if (gridContainer) {
              gridContainer.innerHTML = data.html;
            }
            // Update stats
            document.querySelector('.stat-pill strong').textContent = data.count;
          }
        } catch (error) {
          console.error('Search error:', error);
        }
      }, 300); // Debounce 300ms
    });

    // ============================================
    // AJAX LOGIN
    // ============================================
    document.getElementById('loginForm').addEventListener('submit', async function(event) {
      event.preventDefault();
      const form = event.currentTarget;
      const btn = document.getElementById('btnLogin');
      const btnText = document.getElementById('btnLoginText');
      const errorDiv = document.getElementById('loginError');
      const originalText = btnText.textContent;

      btn.disabled = true;
      btnText.textContent = 'Memproses...';
      errorDiv.style.display = 'none';

      const formData = new FormData(form);

      try {
        const response = await fetch(form.action, {
          method: 'POST',
          body: formData,
          headers: { 'X-Requested-With': 'XMLHttpRequest' },
        });
        const data = await response.json();

        if (data.status) {
          showToast(data.message || 'Login berhasil!', 'success');
          setTimeout(() => window.location.reload(), 800);
        } else {
          errorDiv.textContent = data.message || 'Login gagal.';
          errorDiv.style.display = 'block';
        }
      } catch (error) {
        errorDiv.textContent = 'Terjadi kesalahan.';
        errorDiv.style.display = 'block';
      } finally {
        btn.disabled = false;
        btnText.textContent = originalText;
      }
    });

    // ============================================
    // AJAX SUBMIT — handle require_login response
    // ============================================
    const originalFetch = window.fetch;
    window.fetch = async function(...args) {
      const response = await originalFetch.apply(this, args);
      // Clone so we can read it again later
      const clone = response.clone();
      try {
        const data = await clone.json();
        if (data && data.require_login) {
          showToast(data.message || 'Silakan login terlebih dahulu.', 'error');
          new bootstrap.Modal(document.getElementById('modalLogin')).show();
        }
      } catch(e) { /* not json, ignore */ }
      return response;
    };

    // ============================================
    // DARK MODE / LIGHT MODE TOGGLE
    // ============================================
    document.addEventListener('DOMContentLoaded', function() {
      const themeToggle = document.getElementById('themeToggle');
      const themeIcon = document.getElementById('themeIcon');
      const html = document.documentElement;

      // Load saved theme from localStorage
      const savedTheme = localStorage.getItem('theme') || 'dark';
      html.setAttribute('data-bs-theme', savedTheme);
      updateThemeIcon(savedTheme);

      themeToggle.addEventListener('click', () => {
        const currentTheme = html.getAttribute('data-bs-theme');
        const newTheme = currentTheme === 'dark' ? 'light' : 'dark';
        html.setAttribute('data-bs-theme', newTheme);
        localStorage.setItem('theme', newTheme);
        updateThemeIcon(newTheme);
        if (typeof refreshStatsChartTheme === 'function') {
          refreshStatsChartTheme();
        }
      });

      function updateThemeIcon(theme) {
        if (theme === 'dark') {
          themeIcon.className = 'bi bi-moon-fill';
          themeToggle.title = 'Switch to Light Mode';
        } else {
          themeIcon.className = 'bi bi-sun-fill';
          themeToggle.title = 'Switch to Dark Mode';
        }
      }
    });
  </script>

  <?php if (!empty($logged_in)): ?>
  <script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.1/dist/chart.umd.min.js"></script>
  <script>
    let currentStatsAppId = 0;
    let currentStatsPeriod = 'month';
    let currentStatsData = null;
    let userLoginChartInstance = null;

    function formatIdNumber(val) {
      return new Intl.NumberFormat('id-ID').format(Number(val) || 0);
    }

    function escapeHtml(str) {
      if (str === null || str === undefined) return '';
      return String(str)
        .replace(/&/g, '&amp;')
        .replace(/</g, '&lt;')
        .replace(/>/g, '&gt;')
        .replace(/"/g, '&quot;')
        .replace(/'/g, '&#39;');
    }

    function openStatsModal(appId, appName) {
      currentStatsAppId = appId;
      currentStatsPeriod = 'month';

      document.getElementById('statsModalTitle').textContent = 'Statistik Login — ' + appName;
      document.getElementById('statsModalSubtitle').textContent = 'Counter per minggu, bulan, tahun, dan grafik per user';
      document.getElementById('statsFullLink').href = '<?= site_url('index.php/analytics') ?>?app_id=' + encodeURIComponent(appId);

      updatePeriodFilterButtons('month');
      const modalEl = document.getElementById('modalAppStats');
      bootstrap.Modal.getOrCreateInstance(modalEl).show();
      loadAppStats(appId, 'month');
    }

    function changeStatsPeriod(period) {
      currentStatsPeriod = period;
      updatePeriodFilterButtons(period);
      if (currentStatsAppId > 0) {
        loadAppStats(currentStatsAppId, period);
      }
    }

    function updatePeriodFilterButtons(period) {
      document.querySelectorAll('#statsPeriodFilter .period-btn').forEach(btn => {
        btn.classList.toggle('active', btn.dataset.period === period);
      });
    }

    async function loadAppStats(appId, period) {
      const loadingEl = document.getElementById('statsChartLoading');
      const emptyEl = document.getElementById('statsChartEmpty');
      const wrapperEl = document.getElementById('statsChartWrapper');

      loadingEl.style.display = 'block';
      emptyEl.style.display = 'none';
      wrapperEl.style.display = 'none';

      try {
        const url = '<?= site_url('index.php/stats_ajax') ?>?app_id=' + encodeURIComponent(appId) + '&period=' + encodeURIComponent(period);
        const response = await fetch(url, {
          headers: { 'X-Requested-With': 'XMLHttpRequest' }
        });
        const data = await response.json();

        loadingEl.style.display = 'none';

        if (!data.status) {
          showToast(data.message || 'Gagal memuat statistik.', 'error');
          return;
        }

        currentStatsData = data;
        const summary = data.summary || {};

        document.getElementById('statWeekCount').textContent = formatIdNumber(summary.week_count);
        document.getElementById('statMonthCount').textContent = formatIdNumber(summary.month_count);
        document.getElementById('statYearCount').textContent = formatIdNumber(summary.year_count);
        document.getElementById('statTotalCount').textContent = formatIdNumber(summary.total_count);

        document.getElementById('statWeekUsers').textContent = formatIdNumber(summary.unique_users_week) + ' user unik';
        document.getElementById('statMonthUsers').textContent = formatIdNumber(summary.unique_users_month) + ' user unik';
        document.getElementById('statYearUsers').textContent = formatIdNumber(summary.unique_users_year) + ' user unik';
        document.getElementById('statTotalUsers').textContent = formatIdNumber(summary.unique_users_total) + ' user unik';

        renderUserLoginChart(data.user_stats || [], period);
        renderUserStatsTable(data.user_stats || []);
        renderLoginHistoryTable(data.history || []);
      } catch (err) {
        loadingEl.style.display = 'none';
        emptyEl.style.display = 'block';
        showToast('Gagal memuat statistik login.', 'error');
        console.error(err);
      }
    }

    function getChartPalette() {
      const isLight = document.documentElement.getAttribute('data-bs-theme') === 'light';
      return {
        text: isLight ? '#5f6368' : '#8b949e',
        grid: isLight ? 'rgba(0,0,0,0.08)' : 'rgba(255,255,255,0.08)',
        barBg: isLight ? 'rgba(26, 115, 232, 0.78)' : 'rgba(88, 166, 255, 0.78)',
        barBorder: isLight ? '#1a73e8' : '#58a6ff'
      };
    }

    function renderUserLoginChart(userStats, period) {
      const emptyEl = document.getElementById('statsChartEmpty');
      const wrapperEl = document.getElementById('statsChartWrapper');
      const activeUsers = userStats.filter(u => (Number(u.period_count) || 0) > 0).slice(0, 15);

      if (userLoginChartInstance) {
        userLoginChartInstance.destroy();
        userLoginChartInstance = null;
      }

      if (activeUsers.length === 0) {
        wrapperEl.style.display = 'none';
        emptyEl.style.display = 'block';
        return;
      }

      emptyEl.style.display = 'none';
      wrapperEl.style.display = 'block';

      const labels = activeUsers.map(u => {
        const display = u.full_name && u.full_name !== u.username ? u.full_name + ' (' + u.username + ')' : u.username;
        return display;
      });
      const counts = activeUsers.map(u => Number(u.period_count) || 0);
      const palette = getChartPalette();
      const periodLabels = { week: 'Minggu Ini', month: 'Bulan Ini', year: 'Tahun Ini', all: 'Semua Waktu' };

      const ctx = document.getElementById('userLoginChart').getContext('2d');
      userLoginChartInstance = new Chart(ctx, {
        type: 'bar',
        data: {
          labels: labels,
          datasets: [{
            label: 'Jumlah Login (' + (periodLabels[period] || 'Bulan Ini') + ')',
            data: counts,
            backgroundColor: palette.barBg,
            borderColor: palette.barBorder,
            borderWidth: 1,
            borderRadius: 6,
            maxBarThickness: 44
          }]
        },
        options: {
          responsive: true,
          maintainAspectRatio: false,
          plugins: {
            legend: {
              labels: { color: palette.text }
            }
          },
          scales: {
            x: {
              ticks: { color: palette.text },
              grid: { display: false }
            },
            y: {
              beginAtZero: true,
              ticks: {
                color: palette.text,
                precision: 0
              },
              grid: { color: palette.grid }
            }
          }
        }
      });
    }

    function refreshStatsChartTheme() {
      if (currentStatsData && document.getElementById('modalAppStats').classList.contains('show')) {
        renderUserLoginChart(currentStatsData.user_stats || [], currentStatsPeriod);
      }
    }

    function renderUserStatsTable(rows) {
      const tbody = document.getElementById('statsUserTableBody');
      if (!rows.length) {
        tbody.innerHTML = '<tr><td colspan="8" class="text-center py-4 text-muted">Belum ada data user yang login.</td></tr>';
        return;
      }

      tbody.innerHTML = rows.map((u, idx) => `
        <tr>
          <td class="ps-3">${idx + 1}</td>
          <td class="fw-semibold">${escapeHtml(u.username)}</td>
          <td>${escapeHtml(u.full_name || '-')}</td>
          <td class="text-end">${formatIdNumber(u.week_count)}</td>
          <td class="text-end">${formatIdNumber(u.month_count)}</td>
          <td class="text-end">${formatIdNumber(u.year_count)}</td>
          <td class="text-end fw-bold">${formatIdNumber(u.total_count)}</td>
          <td class="pe-3 text-muted small">${escapeHtml(u.last_login_at || '-')}</td>
        </tr>
      `).join('');
    }

    function renderLoginHistoryTable(rows) {
      const tbody = document.getElementById('statsHistoryTableBody');
      if (!rows.length) {
        tbody.innerHTML = '<tr><td colspan="5" class="text-center py-4 text-muted">Belum ada riwayat login pada periode ini.</td></tr>';
        return;
      }

      tbody.innerHTML = rows.map((h, idx) => `
        <tr>
          <td class="ps-3">${idx + 1}</td>
          <td class="small">${escapeHtml(h.login_at)}</td>
          <td class="fw-semibold">${escapeHtml(h.username)}</td>
          <td>${escapeHtml(h.full_name || '-')}</td>
          <td class="pe-3 small text-muted">${escapeHtml(h.ip_address || '-')}</td>
        </tr>
      `).join('');
    }
  </script>
  <?php endif; ?>

</body>

</html>