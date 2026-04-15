<!DOCTYPE html>
<html lang="id" data-bs-theme="dark">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>App Portal</title>

  <!-- Bootstrap 5 -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" />
  <!-- Bootstrap Icons -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet" />
  <!-- Google Fonts -->
  <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet" />

  <style>
    /* ==========================================
       ROOT & BASE
    ========================================== */
    :root {
      --bg-body: #0d1117;
      --bg-surface: #161b22;
      --bg-elevated: #1c2333;
      --bg-hover: #21262d;
      --border-color: #30363d;
      --border-muted: #21262d;
      --text-primary: #e6edf3;
      --text-secondary: #8b949e;
      --text-muted: #484f58;
      --accent: #58a6ff;
      --accent-green: #3fb950;
      --accent-red: #f85149;
      --accent-orange: #d29922;
      --radius-sm: 8px;
      --radius-md: 12px;
      --radius-lg: 16px;
      --shadow-card: 0 0 0 1px var(--border-color), 0 4px 24px rgba(0, 0, 0, .4);
      --shadow-hover: 0 0 0 1px var(--accent), 0 8px 32px rgba(88, 166, 255, .15);
      --transition: all .2s cubic-bezier(.4, 0, .2, 1);
    }

    /* Light mode variables */
    [data-bs-theme="light"] {
      --bg-body: #ffffff;
      --bg-surface: #f8f9fa;
      --bg-elevated: #ffffff;
      --bg-hover: #f1f3f4;
      --border-color: #dadce0;
      --border-muted: #e8eaed;
      --text-primary: #202124;
      --text-secondary: #5f6368;
      --text-muted: #9aa0a6;
      --accent: #1a73e8;
      --accent-green: #34a853;
      --accent-red: #ea4335;
      --accent-orange: #fbbc04;
      --shadow-card: 0 0 0 1px var(--border-color), 0 4px 24px rgba(0, 0, 0, .1);
      --shadow-hover: 0 0 0 1px var(--accent), 0 8px 32px rgba(26, 115, 232, .15);
    }

    *,
    *::before,
    *::after {
      box-sizing: border-box;
    }

    html,
    body {
      background-color: var(--bg-body);
      color: var(--text-primary);
      font-family: 'Plus Jakarta Sans', sans-serif;
      min-height: 100vh;
      background-image:
        url('https://images.unsplash.com/photo-1500530855697-b586d89ba3ee?auto=format&fit=crop&w=800&q=40'),
        radial-gradient(circle at 15% 15%, rgba(88, 166, 255, .15), transparent 18%),
        radial-gradient(circle at 85% 25%, rgba(63, 185, 80, .10), transparent 16%);
      background-size: cover, cover, cover;
      /* background-position: center center; */
      background-repeat: no-repeat;
      background-attachment: fixed;
      /* background-blend-mode: overlay, overlay, normal; */
    }

    /* ==========================================
       SCROLLBAR
    ========================================== */
    ::-webkit-scrollbar {
      width: 6px;
    }

    ::-webkit-scrollbar-track {
      background: var(--bg-body);
    }

    ::-webkit-scrollbar-thumb {
      background: var(--border-color);
      border-radius: 99px;
    }

    /* ==========================================
       NAVBAR
    ========================================== */
    .portal-navbar {
      background: rgba(13, 17, 23, .9);
      backdrop-filter: blur(20px);
      border-bottom: 1px solid var(--border-color);
      padding: 14px 0;
      position: sticky;
      top: 0;
      z-index: 1000;
    }

    [data-bs-theme="light"] .portal-navbar {
      background: rgba(255, 255, 255, .95);
      border-color: var(--border-color);
    }

    .portal-brand {
      font-weight: 800;
      font-size: 1.25rem;
      color: var(--text-primary) !important;
      text-decoration: none;
      display: flex;
      align-items: center;
      gap: 10px;
      letter-spacing: -.3px;
    }

    [data-bs-theme="light"] .portal-brand {
      color: var(--text-primary) !important;
    }

    .brand-icon {
      width: 36px;
      height: 36px;
      background: linear-gradient(135deg, #58a6ff 0%, #3fb950 100%);
      border-radius: 10px;
      display: flex;
      align-items: center;
      justify-content: center;
      font-size: 18px;
      flex-shrink: 0;
    }

    /* ==========================================
       SEARCH
    ========================================== */
    .search-wrapper {
      position: relative;
      max-width: 380px;
      width: 100%;
    }

    .search-wrapper .bi-search {
      position: absolute;
      left: 14px;
      top: 50%;
      transform: translateY(-50%);
      color: var(--text-secondary);
      font-size: .9rem;
      pointer-events: none;
    }

    .search-input {
      background: var(--bg-elevated) !important;
      border: 1px solid var(--border-color) !important;
      color: var(--text-primary) !important;
      border-radius: 10px !important;
      padding: 9px 16px 9px 38px !important;
      font-size: .9rem !important;
      transition: var(--transition) !important;
      width: 100%;
    }

    .search-input:focus {
      border-color: var(--accent) !important;
      box-shadow: 0 0 0 3px rgba(88, 166, 255, .15) !important;
      outline: none !important;
    }

    .search-input::placeholder {
      color: var(--text-muted) !important;
    }

    /* ==========================================
       HERO STATS BAR
    ========================================== */
    .stats-bar {
      background: var(--bg-surface);
      border-bottom: 1px solid var(--border-muted);
      padding: 12px 0;
    }

    .stat-pill {
      display: inline-flex;
      align-items: center;
      gap: 6px;
      background: var(--bg-elevated);
      border: 1px solid var(--border-color);
      border-radius: 20px;
      padding: 5px 14px;
      font-size: .8rem;
      color: var(--text-secondary);
    }

    .stat-pill strong {
      color: var(--text-primary);
    }

    /* ==========================================
       CATEGORY TABS
    ========================================== */
    .cat-tabs {
      display: flex;
      gap: 6px;
      flex-wrap: wrap;
      align-items: center;
    }

    .cat-tab {
      background: transparent;
      border: 1px solid var(--border-color);
      color: var(--text-secondary);
      border-radius: 8px;
      padding: 5px 14px;
      font-size: .82rem;
      font-weight: 500;
      cursor: pointer;
      transition: var(--transition);
      text-decoration: none;
      white-space: nowrap;
    }

    .cat-tab:hover {
      background: var(--bg-hover);
      color: var(--text-primary);
      border-color: var(--border-color);
    }

    .cat-tab.active {
      background: var(--accent);
      color: #0d1117;
      border-color: var(--accent);
      font-weight: 600;
    }

    /* ==========================================
       SECTION HEADER
    ========================================== */
    .section-header {
      padding: 28px 0 16px;
    }

    .section-title {
      font-size: 1rem;
      font-weight: 700;
      color: var(--text-secondary);
      text-transform: uppercase;
      letter-spacing: 1px;
      margin: 0;
      display: flex;
      align-items: center;
      gap: 8px;
    }

    .section-title::after {
      content: '';
      flex: 1;
      height: 1px;
      background: var(--border-muted);
    }

    /* ==========================================
       APP CARDS
    ========================================== */
    .apps-grid {
      display: grid;
      grid-template-columns: repeat(5, 1fr);
      gap: 28px;
      padding-bottom: 40px;
    }

    .app-card {
      background: var(--bg-surface);
      border: 1px solid var(--border-color);
      border-radius: var(--radius-lg);
      padding: 16px 12px 12px;
      position: relative;
      transition: var(--transition);
      display: flex;
      flex-direction: column;
      align-items: center;
      text-align: center;
      cursor: pointer;
      text-decoration: none !important;
      color: inherit !important;
      overflow: hidden;
    }

    .app-card::before {
      content: '';
      position: absolute;
      top: 0;
      left: 0;
      right: 0;
      height: 4px;
      background: var(--card-accent, #58a6ff);
      opacity: 0;
      transition: var(--transition);
    }

    .app-card:hover {
      box-shadow: var(--shadow-hover);
      transform: translateY(-4px);
      background: var(--bg-elevated);
    }

    .app-card.inactive {
      opacity: .5;
      filter: grayscale(100%);
    }

    /* Card Actions */
    .card-actions {
      position: absolute;
      top: 12px;
      right: 12px;
      display: flex;
      gap: 6px;
      opacity: 0;
      transition: var(--transition);
    }

    .app-card:hover .card-actions {
      opacity: 1;
    }

    .card-action-btn {
      width: 32px;
      height: 32px;
      border: none;
      border-radius: 8px;
      display: flex;
      align-items: center;
      justify-content: center;
      font-size: .85rem;
      cursor: pointer;
      transition: var(--transition);
    }

    .card-action-btn.btn-edit {
      background: rgba(88, 166, 255, .15);
      color: var(--accent);
    }

    .card-action-btn.btn-edit:hover {
      background: rgba(88, 166, 255, .3);
    }

    .card-action-btn.btn-delete {
      background: rgba(248, 81, 73, .15);
      color: var(--accent-red);
    }

    .card-action-btn.btn-delete:hover {
      background: rgba(248, 81, 73, .3);
    }

    /* Logo / Icon */
    .app-logo-wrap {
      width: 100px;
      height: 100px;
      border-radius: var(--radius-md);
      overflow: hidden;
      display: flex;
      align-items: center;
      justify-content: center;
      margin-bottom: 12px;
      position: relative;
      flex-shrink: 0;
    }

    .app-logo-wrap img {
      width: 100%;
      height: 100%;
      object-fit: contain;
    }

    .app-logo-initials {
      width: 100%;
      height: 100%;
      display: flex;
      align-items: center;
      justify-content: center;
      font-size: 2rem;
      font-weight: 800;
      color: #fff;
      background: var(--card-accent, #58a6ff);
      letter-spacing: -.5px;
    }

    .app-name {
      font-size: 1.05rem;
      font-weight: 700;
      color: var(--text-primary);
      margin: 0 0 4px;
      white-space: nowrap;
      overflow: hidden;
      text-overflow: ellipsis;
      max-width: 100%;
    }

    .app-desc {
      font-size: .8rem;
      color: var(--text-secondary);
      margin: 0 0 10px;
      overflow: hidden;
      display: -webkit-box;
      -webkit-line-clamp: 2;
      -webkit-box-orient: vertical;
      line-height: 1.5;
      flex: 1;
    }

    .app-meta {
      display: flex;
      align-items: center;
      justify-content: center;
      margin-top: auto;
      width: 100%;
    }

    .app-category-badge {
      font-size: .75rem;
      padding: 4px 12px;
      border-radius: 6px;
      background: var(--bg-hover);
      color: var(--text-secondary);
      border: 1px solid var(--border-muted);
      font-weight: 500;
      text-align: center;
    }

    .app-open-link {
      font-size: .78rem;
      color: var(--accent);
      font-weight: 600;
      display: flex;
      align-items: center;
      gap: 4px;
      opacity: 0;
      transition: var(--transition);
    }

    .app-card:hover .app-open-link {
      opacity: 1;
    }

    /* ==========================================
       ADD APP BUTTON (Dashed card)
    ========================================== */
    .add-card {
      background: transparent;
      border: 2px dashed var(--border-color);
      border-radius: var(--radius-lg);
      min-height: 200px;
      display: flex;
      flex-direction: column;
      align-items: center;
      justify-content: center;
      gap: 12px;
      cursor: pointer;
      transition: var(--transition);
      color: var(--text-muted);
      font-size: .9rem;
      font-weight: 600;
    }

    .add-card:hover {
      border-color: var(--accent);
      color: var(--accent);
      background: rgba(88, 166, 255, .05);
    }

    .add-card-icon {
      width: 50px;
      height: 50px;
      border-radius: 12px;
      background: var(--bg-elevated);
      display: flex;
      align-items: center;
      justify-content: center;
      font-size: 1.5rem;
      transition: var(--transition);
    }

    .add-card:hover .add-card-icon {
      background: rgba(88, 166, 255, .15);
    }

    /* ==========================================
       EMPTY STATE
    ========================================== */
    .empty-state {
      text-align: center;
      padding: 80px 20px;
      color: var(--text-secondary);
    }

    .empty-state i {
      font-size: 3rem;
      margin-bottom: 16px;
      opacity: .4;
    }

    .empty-state h5 {
      font-weight: 700;
      color: var(--text-primary);
    }

    /* ==========================================
       MODAL OVERRIDES
    ========================================== */
    .modal-content {
      background: var(--bg-surface) !important;
      border: 1px solid var(--border-color) !important;
      border-radius: var(--radius-lg) !important;
    }

    .modal-header {
      border-bottom: 1px solid var(--border-muted) !important;
      padding: 20px 24px !important;
    }

    .modal-footer {
      border-top: 1px solid var(--border-muted) !important;
      padding: 16px 24px !important;
    }

    .modal-body {
      padding: 24px !important;
    }

    .modal-title {
      font-weight: 700;
      font-size: 1.05rem;
    }

    .form-label {
      font-size: .83rem;
      font-weight: 600;
      color: var(--text-secondary);
      margin-bottom: 6px;
      text-transform: uppercase;
      letter-spacing: .5px;
    }

    .form-control,
    .form-select {
      background: var(--bg-elevated) !important;
      border: 1px solid var(--border-color) !important;
      color: var(--text-primary) !important;
      border-radius: 10px !important;
      font-size: .9rem !important;
      padding: 10px 14px !important;
    }

    .form-control:focus,
    .form-select:focus {
      border-color: var(--accent) !important;
      box-shadow: 0 0 0 3px rgba(88, 166, 255, .15) !important;
    }

    .form-control::placeholder {
      color: var(--text-muted) !important;
    }

    .form-select option {
      background: var(--bg-elevated);
    }

    /* Logo Preview in Modal */
    .logo-preview-wrap {
      width: 72px;
      height: 72px;
      border-radius: 14px;
      overflow: hidden;
      border: 2px solid var(--border-color);
      display: flex;
      align-items: center;
      justify-content: center;
      background: var(--bg-elevated);
      flex-shrink: 0;
    }

    .logo-preview-wrap img {
      width: 100%;
      height: 100%;
      object-fit: contain;
    }

    .logo-placeholder {
      color: var(--text-muted);
      font-size: 1.6rem;
    }

    .upload-zone {
      border: 2px dashed var(--border-color);
      border-radius: 10px;
      padding: 16px;
      text-align: center;
      cursor: pointer;
      transition: var(--transition);
      position: relative;
    }

    .upload-zone:hover,
    .upload-zone.dragover {
      border-color: var(--accent);
      background: rgba(88, 166, 255, .05);
    }

    .upload-zone input[type=file] {
      position: absolute;
      inset: 0;
      opacity: 0;
      cursor: pointer;
    }

    .btn-primary-custom {
      background: var(--accent);
      border: none;
      color: #0d1117;
      font-weight: 700;
      border-radius: 10px;
      padding: 10px 22px;
      font-size: .9rem;
      transition: var(--transition);
    }

    .btn-primary-custom:hover {
      background: #79baff;
      color: #0d1117;
    }

    .btn-ghost {
      background: transparent;
      border: 1px solid var(--border-color);
      color: var(--text-secondary);
      border-radius: 10px;
      padding: 10px 18px;
      font-size: .9rem;
      font-weight: 600;
      transition: var(--transition);
    }

    .btn-ghost:hover {
      background: var(--bg-hover);
      color: var(--text-primary);
    }

    .btn-danger-custom {
      background: rgba(248, 81, 73, .15);
      border: 1px solid rgba(248, 81, 73, .3);
      color: var(--accent-red);
      border-radius: 10px;
      padding: 10px 18px;
      font-size: .9rem;
      font-weight: 600;
      transition: var(--transition);
    }

    .btn-danger-custom:hover {
      background: var(--accent-red);
      color: #fff;
    }

    /* ==========================================
       COLOR SWATCHES
    ========================================== */
    .color-swatches {
      display: flex;
      gap: 8px;
      flex-wrap: wrap;
    }

    .color-swatch {
      width: 28px;
      height: 28px;
      border-radius: 8px;
      cursor: pointer;
      border: 2px solid transparent;
      transition: var(--transition);
    }

    .color-swatch:hover,
    .color-swatch.active {
      border-color: white;
      transform: scale(1.15);
    }

    /* ==========================================
       TOAST
    ========================================== */
    .toast-container-custom {
      position: fixed;
      bottom: 24px;
      right: 24px;
      z-index: 9999;
    }

    .toast-custom {
      background: var(--bg-elevated);
      border: 1px solid var(--border-color);
      border-radius: 12px;
      padding: 14px 20px;
      display: flex;
      align-items: center;
      gap: 12px;
      box-shadow: 0 8px 32px rgba(0, 0, 0, .5);
      font-size: .9rem;
      font-weight: 500;
      animation: slideInRight .3s ease;
    }

    @keyframes slideInRight {
      from {
        transform: translateX(100%);
        opacity: 0;
      }

      to {
        transform: translateX(0);
        opacity: 1;
      }
    }

    .toast-custom.success {
      border-left: 3px solid var(--accent-green);
    }

    .toast-custom.error {
      border-left: 3px solid var(--accent-red);
    }

    /* ==========================================
       THEME TOGGLE BUTTON
    ========================================== */
    #themeToggle {
      transition: var(--transition);
      color: var(--text-primary);
      border-color: var(--border-color);
      background: var(--bg-elevated);
    }

    #themeToggle:hover {
      background: var(--bg-hover) !important;
      border-color: var(--accent) !important;
      color: var(--accent) !important;
    }

    #themeToggle i {
      transition: var(--transition);
    }

    /* ==========================================
       RESPONSIVE
    ========================================== */
    @media (max-width: 1400px) {
      .apps-grid {
        grid-template-columns: repeat(4, 1fr);
        gap: 24px;
      }
    }

    @media (max-width: 1024px) {
      .apps-grid {
        grid-template-columns: repeat(3, 1fr);
        gap: 20px;
      }
    }

    @media (max-width: 768px) {
      .apps-grid {
        grid-template-columns: repeat(2, 1fr);
        gap: 16px;
      }
    }

    @media (max-width: 576px) {
      .apps-grid {
        grid-template-columns: 1fr;
        gap: 12px;
      }

      .search-wrapper {
        max-width: 100%;
      }

      .app-logo-wrap {
        width: 80px;
        height: 80px;
      }

      .app-logo-initials {
        font-size: 1.5rem;
      }
    }
  </style>
</head>

<body>

  <!-- =======================================
     NAVBAR
======================================= -->
  <nav class="portal-navbar">
    <div class="container-xl">
      <div class="d-flex align-items-center justify-content-between gap-3 flex-wrap">

        <a href="<?= site_url('/') ?>" class="portal-brand">
          <div class="brand-icon">🚀</div>
          App Portal Askara
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
          <?php $active_count = count(array_filter($apps, function ($a) {
            $a->is_active;
          })); ?>
          <span class="stat-pill">
            <i class="bi bi-circle-fill text-success" style="font-size:.55rem;"></i>
            <strong><?= $active_count ?></strong> aktif
          </span>
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
              <!-- Action Buttons -->
              <div class="card-actions" onclick="event.preventDefault();event.stopPropagation();">
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
            </a>

          </div>

        <?php endforeach; ?>

        <!-- Add app card at the end of the grid -->
        <div class="add-card" role="button" data-bs-toggle="modal" data-bs-target="#modalApp" onclick="resetModalForCreate()">
          <div class="add-card-icon">
            <i class="bi bi-plus-lg"></i>
          </div>
          <div>Tambah Aplikasi</div>
        </div>
      </div>

    <?php else: ?>
      <div class="empty-state">
        <i class="bi bi-grid-3x3-gap d-block"></i>
        <h5>Belum ada aplikasi</h5>
        <p class="mb-4">Mulai tambahkan aplikasi ke portal Anda.</p>
        <button class="btn-primary-custom" data-bs-toggle="modal" data-bs-target="#modalApp">
          <i class="bi bi-plus-lg me-2"></i>Tambah Aplikasi Pertama
        </button>
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

              <!-- Name -->
              <div class="col-12">
                <label class="form-label" for="inputName">Nama Aplikasi <span class="text-danger">*</span></label>
                <input type="text" id="inputName" name="name" class="form-control" placeholder="Contoh: Slack, Jira, Figma..." required />
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

</body>

</html>