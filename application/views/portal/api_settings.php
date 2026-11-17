<!DOCTYPE html>
<html lang="id" data-bs-theme="dark">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Pengaturan Link API — ISO Platform Askara Group</title>
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
          <a href="<?= site_url('index.php/analytics') ?>" class="btn btn-sm" style="border-radius:8px;padding:6px 10px;background:var(--bg-elevated);border:1px solid var(--border-color);color:var(--text-primary);" title="Statistik Login">
            <i class="bi bi-bar-chart-line-fill me-1 text-info"></i><span class="d-none d-sm-inline">Statistik</span>
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

  <?php $current_server_endpoint = site_url('index.php/api/log_login'); ?>

  <!-- MAIN -->
  <main class="container-xl py-4">

    <div class="d-flex align-items-center justify-content-between flex-wrap gap-3 mb-4">
      <div>
        <h4 class="mb-1 fw-bold" style="color:var(--text-primary);">
          <i class="bi bi-link-45deg me-2 text-info"></i>Pengaturan Link API Login Counter
        </h4>
        <div class="small" style="color:var(--text-secondary);">
          Kelola URL endpoint API, API Key, dan kode integrasi untuk seluruh aplikasi terhubung.
        </div>
      </div>
      <a href="<?= site_url('index.php/analytics') ?>" class="btn-ghost text-decoration-none">
        <i class="bi bi-bar-chart-line me-1"></i>Lihat Statistik Login
      </a>
    </div>

    <div class="row g-4">
      <!-- Form Pengaturan Link API -->
      <div class="col-12 col-lg-6">
        <div class="analytics-panel h-100">
          <h6 class="fw-bold mb-3" style="color:var(--text-primary);">
            <i class="bi bi-sliders me-2 text-primary"></i>Konfigurasi Endpoint &amp; Keamanan
          </h6>

          <form id="apiSettingsForm" method="POST" action="<?= site_url('index.php/api_settings/save') ?>">
            <div class="mb-3">
              <div class="form-check form-switch">
                <input
                  class="form-check-input"
                  type="checkbox"
                  role="switch"
                  id="inputApiEnabled"
                  name="api_enabled"
                  value="1"
                  <?= $api_settings->api_enabled === '1' ? 'checked' : '' ?> />
                <label class="form-check-label fw-semibold" for="inputApiEnabled">
                  Aktifkan Pencatatan Login via API
                </label>
              </div>
              <small class="text-muted">Nonaktifkan sementara untuk menolak seluruh permintaan pencatatan dari luar.</small>
            </div>

            <div class="mb-3">
              <label class="form-label" for="inputApiEndpointUrl">Link API Endpoint <span class="text-danger">*</span></label>
              <div class="input-group">
                <input
                  type="url"
                  class="form-control"
                  id="inputApiEndpointUrl"
                  name="api_endpoint_url"
                  value="<?= htmlspecialchars($api_settings->api_endpoint_url) ?>"
                  placeholder="https://portal.domain.com/index.php/api/log_login"
                  required />
                <button type="button" class="btn btn-outline-info" onclick="useCurrentServerUrl()" title="Isi otomatis dengan URL server ini">
                  <i class="bi bi-magic me-1"></i>URL Server Ini
                </button>
              </div>
              <small class="text-muted">URL endpoint pada Portal yang dipanggil oleh aplikasi ketika user sukses login.</small>
            </div>

            <div class="mb-4">
              <label class="form-label" for="inputApiKey">API Key (Header <code>X-API-KEY</code>) <span class="text-danger">*</span></label>
              <div class="input-group">
                <input
                  type="text"
                  class="form-control"
                  id="inputApiKey"
                  name="api_key"
                  value="<?= htmlspecialchars($api_settings->api_key) ?>"
                  minlength="6"
                  required />
                <button type="button" class="btn btn-outline-warning" onclick="generateApiKey()" title="Buat API Key acak baru">
                  <i class="bi bi-key me-1"></i>Generate Baru
                </button>
              </div>
              <small class="text-muted">Minimal 6 karakter. Pastikan nilai ini sama dengan yang dipasang di kode <code>Auth.php</code> aplikasi.</small>
            </div>

            <div class="d-flex align-items-center justify-content-between flex-wrap gap-2 pt-2 border-top" style="border-color:var(--border-color) !important;">
              <button type="button" class="btn btn-outline-success" id="btnTestApi" onclick="testApiEndpoint()">
                <i class="bi bi-plug me-1"></i>Uji Koneksi API
              </button>
              <button type="submit" class="btn-primary-custom" id="btnSaveSettings">
                <i class="bi bi-check-lg me-1"></i>Simpan Pengaturan
              </button>
            </div>
          </form>

          <div id="apiTestResult" class="mt-3 small" style="display:none;"></div>
        </div>
      </div>

      <!-- Kode Auth.php Siap Copy-Paste -->
      <div class="col-12 col-lg-6">
        <div class="analytics-panel h-100">
          <div class="d-flex align-items-center justify-content-between flex-wrap gap-2 mb-3">
            <h6 class="mb-0 fw-bold" style="color:var(--text-primary);">
              <i class="bi bi-code-slash me-2 text-success"></i>Kode <code>Auth.php</code> Siap Copy-Paste
            </h6>
            <button type="button" class="btn btn-sm btn-outline-info" onclick="copyAuthSnippet()">
              <i class="bi bi-clipboard me-1"></i>Salin Kode
            </button>
          </div>
          <p class="small text-muted mb-2">
            Tempelkan method berikut ke dalam library <code>Auth.php</code> dan panggil <code>$this-&gt;record_portal_login($existUser, $dataCompany, $ip_address);</code> setelah login berhasil.
          </p>
          <pre class="code-snippet-box" style="max-height:360px;"><code id="authSnippetCode"></code></pre>
        </div>
      </div>

      <!-- Daftar Kode Aplikasi -->
      <div class="col-12">
        <div class="analytics-panel">
          <h6 class="fw-bold mb-3" style="color:var(--text-primary);">
            <i class="bi bi-tags me-2 text-warning"></i>Daftar Kode Aplikasi (<code>app_code</code>) Terdaftar
          </h6>
          <div class="table-responsive" style="border:1px solid var(--border-color);border-radius:10px;">
            <table class="table table-hover analytics-table mb-0 align-middle">
              <thead>
                <tr>
                  <th class="ps-3">#</th>
                  <th>Nama Aplikasi</th>
                  <th>Kode Aplikasi (<code>app_code</code>)</th>
                  <th>URL Aplikasi</th>
                </tr>
              </thead>
              <tbody>
                <?php if (!empty($apps)): ?>
                  <?php foreach ($apps as $i => $a): ?>
                    <tr>
                      <td class="ps-3"><?= $i + 1 ?></td>
                      <td class="fw-semibold"><?= htmlspecialchars($a->name) ?></td>
                      <td><code><?= htmlspecialchars($a->app_code ?: '-') ?></code></td>
                      <td class="small text-muted"><?= htmlspecialchars($a->url) ?></td>
                    </tr>
                  <?php endforeach; ?>
                <?php else: ?>
                  <tr>
                    <td colspan="4" class="text-center py-4 text-muted">Belum ada aplikasi terdaftar.</td>
                  </tr>
                <?php endif; ?>
              </tbody>
            </table>
          </div>
        </div>
      </div>
    </div>

  </main>

  <div class="toast-container-custom" id="toastContainer"></div>

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
  <script>
    const currentServerEndpoint = <?= json_encode($current_server_endpoint) ?>;

    function showToast(msg, type = 'success') {
      const icon = type === 'success'
        ? '<i class="bi bi-check-circle-fill text-success me-2"></i>'
        : '<i class="bi bi-exclamation-circle-fill text-danger me-2"></i>';
      const toast = document.createElement('div');
      toast.className = 'toast-custom ' + type;
      toast.innerHTML = icon + msg;
      document.getElementById('toastContainer').appendChild(toast);
      setTimeout(() => toast.remove(), 4000);
    }

    function escapePhpSingleQuote(val) {
      return String(val || '').replace(/\\/g, '\\\\').replace(/'/g, "\\'");
    }

    function updateAuthSnippet() {
      const url = escapePhpSingleQuote(document.getElementById('inputApiEndpointUrl').value.trim());
      const key = escapePhpSingleQuote(document.getElementById('inputApiKey').value.trim());

      const snippet = `private function record_portal_login($user, $company, $ip_address)
{
    try {
        if (!function_exists('curl_init')) {
            return;
        }

        $rows = $this->ci->db
            ->where_in('setting_name', ['portal_api_url', 'portal_api_key', 'portal_api_enabled'])
            ->get('settings')
            ->result();

        $cfg = [];
        foreach ($rows as $row) {
            $cfg[$row->setting_name] = trim((string)$row->value);
        }

        if (isset($cfg['portal_api_enabled']) && $cfg['portal_api_enabled'] === '0') {
            return;
        }

        $portalApiUrl = !empty($cfg['portal_api_url']) ? $cfg['portal_api_url'] : '${url}';
        $portalApiKey = !empty($cfg['portal_api_key']) ? $cfg['portal_api_key'] : '${key}';

        $app_code   = !empty($company->inisial) ? strtolower(trim((string)$company->inisial)) : 'askara';
        $user_id    = isset($user->id_user) ? (string)$user->id_user : null;
        $username   = isset($user->username) ? trim((string)$user->username) : '';
        $full_name  = !empty($user->full_name) ? trim((string)$user->full_name) : $username;
        $user_agent = isset($_SERVER['HTTP_USER_AGENT']) ? substr((string)$_SERVER['HTTP_USER_AGENT'], 0, 500) : null;

        if ($username === '') {
            return;
        }

        $payload = json_encode([
            'app_code'   => $app_code,
            'user_id'    => $user_id,
            'username'   => $username,
            'full_name'  => $full_name,
            'ip_address' => $ip_address,
            'user_agent' => $user_agent,
        ]);

        $ch = curl_init($portalApiUrl);
        curl_setopt_array($ch, [
            CURLOPT_POST           => true,
            CURLOPT_POSTFIELDS     => $payload,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_CONNECTTIMEOUT => 2,
            CURLOPT_TIMEOUT        => 2,
            CURLOPT_SSL_VERIFYPEER => false,
            CURLOPT_HTTPHEADER     => [
                'Content-Type: application/json',
                'X-API-KEY: ' . $portalApiKey,
            ],
        ]);
        @curl_exec($ch);
        @curl_close($ch);
    } catch (\\Throwable $e) {
        log_message('error', 'Portal login counter error: ' . $e->getMessage());
    }
}`;
      document.getElementById('authSnippetCode').textContent = snippet;
    }

    function useCurrentServerUrl() {
      document.getElementById('inputApiEndpointUrl').value = currentServerEndpoint;
      updateAuthSnippet();
    }

    function generateApiKey() {
      const chars = 'ABCDEFGHJKLMNPQRSTUVWXYZ23456789';
      let result = 'ASKARA_';
      for (let i = 0; i < 20; i++) {
        result += chars.charAt(Math.floor(Math.random() * chars.length));
      }
      document.getElementById('inputApiKey').value = result;
      updateAuthSnippet();
    }

    async function copyAuthSnippet() {
      const code = document.getElementById('authSnippetCode').textContent;
      try {
        await navigator.clipboard.writeText(code);
        showToast('Kode integrasi berhasil disalin.', 'success');
      } catch (e) {
        showToast('Gagal menyalin kode ke clipboard.', 'error');
      }
    }

    async function testApiEndpoint() {
      const btn = document.getElementById('btnTestApi');
      const resultBox = document.getElementById('apiTestResult');
      const apiUrl = document.getElementById('inputApiEndpointUrl').value.trim();
      const apiKey = document.getElementById('inputApiKey').value.trim();

      btn.disabled = true;
      resultBox.style.display = 'block';
      resultBox.className = 'mt-3 small text-muted';
      resultBox.textContent = 'Menguji koneksi ke ' + apiUrl + '...';

      try {
        const res = await fetch(apiUrl, {
          method: 'POST',
          headers: {
            'Content-Type': 'application/json',
            'X-API-KEY': apiKey
          },
          body: JSON.stringify({ ping_only: true })
        });
        const data = await res.json();
        if (res.ok && data.status) {
          resultBox.className = 'mt-3 alert alert-success py-2 px-3 mb-0 small';
          resultBox.textContent = data.message || 'Koneksi API berhasil.';
        } else {
          resultBox.className = 'mt-3 alert alert-danger py-2 px-3 mb-0 small';
          resultBox.textContent = data.message || ('Endpoint merespons status HTTP ' + res.status);
        }
      } catch (err) {
        resultBox.className = 'mt-3 alert alert-danger py-2 px-3 mb-0 small';
        resultBox.textContent = 'Gagal menghubungi endpoint. Pastikan URL benar dan pengaturan sudah disimpan.';
      } finally {
        btn.disabled = false;
      }
    }

    document.getElementById('inputApiEndpointUrl').addEventListener('input', updateAuthSnippet);
    document.getElementById('inputApiKey').addEventListener('input', updateAuthSnippet);

    document.getElementById('apiSettingsForm').addEventListener('submit', async function(event) {
      event.preventDefault();
      const form = event.currentTarget;
      const btn = document.getElementById('btnSaveSettings');
      const originalHtml = btn.innerHTML;
      btn.disabled = true;
      btn.innerHTML = '<span class="spinner-border spinner-border-sm me-1"></span>Menyimpan...';

      try {
        const response = await fetch(form.action, {
          method: 'POST',
          body: new FormData(form),
          headers: { 'X-Requested-With': 'XMLHttpRequest' }
        });
        const data = await response.json();
        if (data.status) {
          showToast(data.message || 'Pengaturan disimpan.', 'success');
          updateAuthSnippet();
        } else {
          showToast(data.message || 'Gagal menyimpan pengaturan.', 'error');
        }
      } catch (err) {
        showToast('Terjadi kesalahan saat menyimpan pengaturan.', 'error');
      } finally {
        btn.disabled = false;
        btn.innerHTML = originalHtml;
      }
    });

    <?php $success = $this->session->flashdata('success'); $error = $this->session->flashdata('error'); ?>
    <?php if ($success): ?>showToast(<?= json_encode($success) ?>, 'success');<?php endif; ?>
    <?php if ($error): ?>showToast(<?= json_encode($error) ?>, 'error');<?php endif; ?>

    document.addEventListener('DOMContentLoaded', function() {
      updateAuthSnippet();

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
