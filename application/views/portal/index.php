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
  <link rel="stylesheet" href="<?= base_url('assets/css/portal.css') ?>" />
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