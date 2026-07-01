<!DOCTYPE html>
<html lang="id" data-bs-theme="dark">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Kelola User — ISO Platform Askara Group</title>
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

  <!-- MAIN -->
  <main class="container-xl py-4">

    <div class="d-flex align-items-center justify-content-between mb-4">
      <h4 class="mb-0" style="color:var(--text-primary);font-weight:700;">
        <i class="bi bi-people-fill me-2 text-info"></i>Kelola User
      </h4>
      <button class="btn-primary-custom" data-bs-toggle="modal" data-bs-target="#modalUser" onclick="resetUserModal()">
        <i class="bi bi-plus-lg me-1"></i>Tambah User
      </button>
    </div>

    <!-- Users Table -->
    <div class="table-responsive" style="background:var(--bg-elevated);border-radius:12px;border:1px solid var(--border-color);overflow:hidden;">
      <table class="table table-hover mb-0" style="color:var(--text-primary);">
        <thead style="background:var(--bg-card);">
          <tr>
            <th style="padding:12px 16px;font-weight:600;font-size:.85rem;">#</th>
            <th style="padding:12px 16px;font-weight:600;font-size:.85rem;">Username</th>
            <th style="padding:12px 16px;font-weight:600;font-size:.85rem;">Nama Lengkap</th>
            <th style="padding:12px 16px;font-weight:600;font-size:.85rem;">Dibuat</th>
            <th style="padding:12px 16px;font-weight:600;font-size:.85rem;text-align:center;">Aksi</th>
          </tr>
        </thead>
        <tbody>
          <?php if (!empty($users)): ?>
            <?php foreach ($users as $i => $user): ?>
              <tr>
                <td style="padding:12px 16px;"><?= $i + 1 ?></td>
                <td style="padding:12px 16px;font-weight:500;"><?= htmlspecialchars($user->username) ?></td>
                <td style="padding:12px 16px;"><?= htmlspecialchars($user->full_name) ?></td>
                <td style="padding:12px 16px;font-size:.85rem;color:var(--text-muted);"><?= $user->created_at ?></td>
                <td style="padding:12px 16px;text-align:center;">
                  <button class="btn btn-sm btn-outline-warning me-1" title="Edit" onclick='openEditUser(<?= json_encode($user) ?>)'>
                    <i class="bi bi-pencil"></i>
                  </button>
                  <?php if ($user->id != $this->session->userdata('user_id')): ?>
                  <button class="btn btn-sm btn-outline-danger" title="Hapus" onclick="openDeleteUser(<?= $user->id ?>, '<?= addslashes($user->username) ?>')">
                    <i class="bi bi-trash"></i>
                  </button>
                  <?php endif; ?>
                </td>
              </tr>
            <?php endforeach; ?>
          <?php else: ?>
            <tr>
              <td colspan="5" class="text-center py-4" style="color:var(--text-muted);">Belum ada user.</td>
            </tr>
          <?php endif; ?>
        </tbody>
      </table>
    </div>

  </main>


  <!-- MODAL — TAMBAH/EDIT USER -->
  <div class="modal fade" id="modalUser" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="modalUserTitle"><i class="bi bi-person-plus me-2 text-info"></i>Tambah User</h5>
          <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
        </div>
        <form id="userForm" method="POST" action="<?= site_url('index.php/users/store') ?>">
          <input type="hidden" name="id" id="userFormId" value="" />
          <div class="modal-body">
            <div class="mb-3">
              <label class="form-label">Username <span class="text-danger">*</span></label>
              <input type="text" name="username" id="userInputUsername" class="form-control" required autocomplete="off" />
            </div>
            <div class="mb-3">
              <label class="form-label">Nama Lengkap</label>
              <input type="text" name="full_name" id="userInputFullName" class="form-control" />
            </div>
            <div class="mb-3">
              <label class="form-label" id="userPasswordLabel">Password <span class="text-danger">*</span></label>
              <input type="password" name="password" id="userInputPassword" class="form-control" autocomplete="new-password" />
              <small class="text-muted" id="userPasswordHint" style="display:none;">Kosongkan jika tidak ingin mengubah password.</small>
            </div>
          </div>
          <div class="modal-footer gap-2">
            <button type="button" class="btn-ghost" data-bs-dismiss="modal">Batal</button>
            <button type="submit" class="btn-primary-custom">
              <i class="bi bi-check-lg me-1"></i><span id="btnUserSubmitText">Simpan User</span>
            </button>
          </div>
        </form>
      </div>
    </div>
  </div>


  <!-- MODAL — HAPUS USER -->
  <div class="modal fade" id="modalDeleteUser" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-sm">
      <div class="modal-content">
        <div class="modal-header border-0 pb-0">
          <h5 class="modal-title text-danger"><i class="bi bi-trash me-2"></i>Hapus User</h5>
          <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
        </div>
        <div class="modal-body pt-2">
          <p class="mb-0" style="font-size:.9rem;color:var(--text-secondary);">
            Hapus user <strong id="deleteUserName" style="color:var(--text-primary);"></strong>?
          </p>
        </div>
        <div class="modal-footer gap-2">
          <form id="deleteUserForm" method="POST" action="<?= site_url('index.php/users/delete') ?>">
            <input type="hidden" name="id" id="deleteUserId" />
            <button type="button" class="btn-ghost" data-bs-dismiss="modal">Batal</button>
            <button type="submit" class="btn-danger-custom">
              <i class="bi bi-trash me-1"></i>Hapus
            </button>
          </form>
        </div>
      </div>
    </div>
  </div>


  <!-- TOAST CONTAINER -->
  <div class="toast-container-custom" id="toastContainer"></div>


  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
  <script>
    function resetUserModal() {
      const form = document.getElementById('userForm');
      form.action = '<?= site_url('index.php/users/store') ?>';
      form.reset();
      document.getElementById('userFormId').value = '';
      document.getElementById('modalUserTitle').innerHTML = '<i class="bi bi-person-plus me-2 text-info"></i>Tambah User';
      document.getElementById('btnUserSubmitText').textContent = 'Simpan User';
      document.getElementById('userInputPassword').required = true;
      document.getElementById('userPasswordLabel').innerHTML = 'Password <span class="text-danger">*</span>';
      document.getElementById('userPasswordHint').style.display = 'none';
    }

    function openEditUser(user) {
      const form = document.getElementById('userForm');
      form.action = '<?= site_url('index.php/users/update') ?>';
      document.getElementById('userFormId').value = user.id;
      document.getElementById('userInputUsername').value = user.username;
      document.getElementById('userInputFullName').value = user.full_name || '';
      document.getElementById('userInputPassword').value = '';
      document.getElementById('userInputPassword').required = false;
      document.getElementById('modalUserTitle').innerHTML = '<i class="bi bi-pencil me-2 text-warning"></i>Edit User';
      document.getElementById('btnUserSubmitText').textContent = 'Perbarui User';
      document.getElementById('userPasswordLabel').innerHTML = 'Password Baru';
      document.getElementById('userPasswordHint').style.display = 'block';
      new bootstrap.Modal(document.getElementById('modalUser')).show();
    }

    function openDeleteUser(id, name) {
      document.getElementById('deleteUserId').value = id;
      document.getElementById('deleteUserName').textContent = name;
      new bootstrap.Modal(document.getElementById('modalDeleteUser')).show();
    }

    // Toast
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

    // Flash messages
    <?php $success = $this->session->flashdata('success'); $error = $this->session->flashdata('error'); ?>
    <?php if ($success): ?>showToast(<?= json_encode($success) ?>, 'success');<?php endif; ?>
    <?php if ($error): ?>showToast(<?= json_encode($error) ?>, 'error');<?php endif; ?>

    // AJAX submit user form
    document.getElementById('userForm').addEventListener('submit', async function(event) {
      event.preventDefault();
      const form = event.currentTarget;
      const btn = form.querySelector('[type="submit"]');
      btn.disabled = true;

      try {
        const response = await fetch(form.action, {
          method: 'POST',
          body: new FormData(form),
          headers: { 'X-Requested-With': 'XMLHttpRequest' },
        });
        const data = await response.json();

        if (data.status) {
          showToast(data.message, 'success');
          setTimeout(() => { window.location.href = data.redirect || window.location.href; }, 800);
        } else {
          showToast(data.message || 'Gagal menyimpan.', 'error');
        }
      } catch (e) {
        showToast('Terjadi kesalahan.', 'error');
      } finally {
        btn.disabled = false;
      }
    });

    // Theme toggle
    document.addEventListener('DOMContentLoaded', function() {
      const themeToggle = document.getElementById('themeToggle');
      const themeIcon = document.getElementById('themeIcon');
      const html = document.documentElement;
      const savedTheme = localStorage.getItem('theme') || 'dark';
      html.setAttribute('data-bs-theme', savedTheme);
      updateThemeIcon(savedTheme);

      themeToggle.addEventListener('click', () => {
        const current = html.getAttribute('data-bs-theme');
        const next = current === 'dark' ? 'light' : 'dark';
        html.setAttribute('data-bs-theme', next);
        localStorage.setItem('theme', next);
        updateThemeIcon(next);
      });

      function updateThemeIcon(theme) {
        themeIcon.className = theme === 'dark' ? 'bi bi-moon-fill' : 'bi bi-sun-fill';
      }
    });
  </script>

</body>
</html>
