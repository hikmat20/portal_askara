<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Portal extends CI_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->model('App_model');
        $this->load->model('User_model');
        $this->load->model('Login_log_model');
        $this->load->helper(['url', 'form']);
        $this->load->library(['session', 'upload']);
    }

    // -------------------------------------------------------
    // AUTH HELPER
    // -------------------------------------------------------
    private function is_logged_in() {
        return $this->session->userdata('logged_in') === true;
    }

    private function require_login() {
        if (!$this->is_logged_in()) {
            if ($this->input->is_ajax_request()) {
                return $this->output
                    ->set_content_type('application/json')
                    ->set_output(json_encode([
                        'status' => false,
                        'message' => 'Silakan login terlebih dahulu.',
                        'require_login' => true,
                    ]));
            }
            $this->session->set_flashdata('error', 'Silakan login terlebih dahulu.');
            redirect(site_url('/'));
        }
        return true;
    }

    // -------------------------------------------------------
    // INDEX
    // -------------------------------------------------------
    public function index() {
        $search    = $this->input->get('q', TRUE);
        $category  = $this->input->get('cat', TRUE);
        $logged_in = $this->is_logged_in();

        $data['apps']           = $this->App_model->get_all($search, $category);
        $data['categories']     = $this->App_model->get_categories();
        $data['search']         = $search;
        $data['category']       = $category;
        $data['logged_in']      = $logged_in;
        $data['current_user']   = $this->session->userdata('username');
        $data['app_counters']   = $logged_in ? $this->Login_log_model->get_counters_by_app() : [];
        $data['portal_summary'] = $logged_in ? $this->Login_log_model->get_summary() : null;

        $this->load->view('portal/index', $data);
    }

    // -------------------------------------------------------
    // LOGIN
    // -------------------------------------------------------
    public function login() {
        if ($this->input->server('REQUEST_METHOD') !== 'POST') {
            redirect('/');
        }

        $username = $this->input->post('username', TRUE);
        $password = $this->input->post('password');

        $user = $this->User_model->login($username, $password);

        if ($user) {
            $this->session->set_userdata([
                'logged_in'  => true,
                'user_id'    => $user->id,
                'username'   => $user->username,
                'full_name'  => $user->full_name,
            ]);

            if ($this->input->is_ajax_request()) {
                return $this->output
                    ->set_content_type('application/json')
                    ->set_output(json_encode([
                        'status' => true,
                        'message' => 'Login berhasil! Selamat datang, ' . $user->full_name,
                    ]));
            }
            $this->session->set_flashdata('success', 'Login berhasil!');
        } else {
            if ($this->input->is_ajax_request()) {
                return $this->output
                    ->set_content_type('application/json')
                    ->set_output(json_encode([
                        'status' => false,
                        'message' => 'Username atau password salah.',
                    ]));
            }
            $this->session->set_flashdata('error', 'Username atau password salah.');
        }

        redirect(site_url('/'));
    }

    // -------------------------------------------------------
    // LOGOUT
    // -------------------------------------------------------
    public function logout() {
        $this->session->unset_userdata(['logged_in', 'user_id', 'username', 'full_name']);
        $this->session->set_flashdata('success', 'Berhasil logout.');
        redirect(site_url('/'));
    }

    // -------------------------------------------------------
    // USERS MANAGEMENT
    // -------------------------------------------------------
    public function users() {
        if ($this->require_login() !== true) return;

        $data['users']        = $this->User_model->get_all();
        $data['logged_in']    = true;
        $data['current_user'] = $this->session->userdata('username');
        $this->load->view('portal/users', $data);
    }

    public function user_store() {
        if ($this->require_login() !== true) return;

        $username  = $this->input->post('username', TRUE);
        $full_name = $this->input->post('full_name', TRUE);
        $password  = $this->input->post('password');

        if (empty($username) || empty($password)) {
            if ($this->input->is_ajax_request()) {
                return $this->output->set_content_type('application/json')
                    ->set_output(json_encode(['status' => false, 'message' => 'Username dan password wajib diisi.']));
            }
            $this->session->set_flashdata('error', 'Username dan password wajib diisi.');
            redirect(site_url('index.php/users'));
        }

        if ($this->User_model->username_exists($username)) {
            if ($this->input->is_ajax_request()) {
                return $this->output->set_content_type('application/json')
                    ->set_output(json_encode(['status' => false, 'message' => 'Username sudah digunakan.']));
            }
            $this->session->set_flashdata('error', 'Username sudah digunakan.');
            redirect(site_url('index.php/users'));
        }

        $this->User_model->insert([
            'username'  => $username,
            'full_name' => $full_name ?: $username,
            'password'  => $password,
        ]);

        if ($this->input->is_ajax_request()) {
            return $this->output->set_content_type('application/json')
                ->set_output(json_encode(['status' => true, 'message' => 'User berhasil ditambahkan!', 'redirect' => site_url('index.php/users')]));
        }
        $this->session->set_flashdata('success', 'User berhasil ditambahkan!');
        redirect(site_url('index.php/users'));
    }

    public function user_update() {
        if ($this->require_login() !== true) return;

        $id        = (int)$this->input->post('id');
        $username  = $this->input->post('username', TRUE);
        $full_name = $this->input->post('full_name', TRUE);
        $password  = $this->input->post('password');

        if (empty($username)) {
            if ($this->input->is_ajax_request()) {
                return $this->output->set_content_type('application/json')
                    ->set_output(json_encode(['status' => false, 'message' => 'Username wajib diisi.']));
            }
            $this->session->set_flashdata('error', 'Username wajib diisi.');
            redirect(site_url('index.php/users'));
        }

        if ($this->User_model->username_exists($username, $id)) {
            if ($this->input->is_ajax_request()) {
                return $this->output->set_content_type('application/json')
                    ->set_output(json_encode(['status' => false, 'message' => 'Username sudah digunakan.']));
            }
            $this->session->set_flashdata('error', 'Username sudah digunakan.');
            redirect(site_url('index.php/users'));
        }

        $data = [
            'username'  => $username,
            'full_name' => $full_name ?: $username,
            'password'  => $password,
        ];

        $this->User_model->update($id, $data);

        if ($this->input->is_ajax_request()) {
            return $this->output->set_content_type('application/json')
                ->set_output(json_encode(['status' => true, 'message' => 'User berhasil diperbarui!', 'redirect' => site_url('index.php/users')]));
        }
        $this->session->set_flashdata('success', 'User berhasil diperbarui!');
        redirect(site_url('index.php/users'));
    }

    public function user_delete() {
        if ($this->require_login() !== true) return;

        $id = (int)$this->input->post('id');

        // Jangan hapus diri sendiri
        if ($id == $this->session->userdata('user_id')) {
            if ($this->input->is_ajax_request()) {
                return $this->output->set_content_type('application/json')
                    ->set_output(json_encode(['status' => false, 'message' => 'Tidak bisa menghapus akun sendiri.']));
            }
            $this->session->set_flashdata('error', 'Tidak bisa menghapus akun sendiri.');
            redirect(site_url('index.php/users'));
        }

        $this->User_model->delete($id);

        if ($this->input->is_ajax_request()) {
            return $this->output->set_content_type('application/json')
                ->set_output(json_encode(['status' => true, 'message' => 'User berhasil dihapus!', 'redirect' => site_url('index.php/users')]));
        }
        $this->session->set_flashdata('success', 'User berhasil dihapus!');
        redirect(site_url('index.php/users'));
    }

    // -------------------------------------------------------
    // SEARCH (AJAX)
    // -------------------------------------------------------
    public function search_ajax() {
        if (!$this->input->is_ajax_request()) {
            return;
        }

        $q = $this->input->get('q', TRUE);
        $apps = $this->App_model->get_all($q);
        $logged_in = $this->is_logged_in();
        $app_counters = $logged_in ? $this->Login_log_model->get_counters_by_app() : [];

        $html = '';
        if (!empty($apps)) {
            foreach ($apps as $app) {
                $initials = strtoupper(mb_substr($app->name, 0, 1));
                if (strpos($app->name, ' ') !== false) {
                    $parts = explode(' ', $app->name);
                    $initials = strtoupper(mb_substr($parts[0], 0, 1) . mb_substr($parts[1], 0, 1));
                }
                $accent_color = $app->color ?: '#58a6ff';
                $json_app = htmlspecialchars(json_encode($app), ENT_QUOTES, 'UTF-8');
                $app_name_js = htmlspecialchars(json_encode($app->name), ENT_QUOTES, 'UTF-8');
                
                $html .= '<div style="position:relative;">';
                $html .= '<a href="' . htmlspecialchars($app->url) . '" target="_blank" rel="noopener noreferrer" class="app-card ' . ($app->is_active ? '' : 'inactive') . '" style="--card-accent: ' . htmlspecialchars($accent_color) . ';">';
                
                if ($logged_in) {
                    $html .= '<div class="card-actions" onclick="event.preventDefault();event.stopPropagation();">';
                    $html .= '<button class="card-action-btn btn-stats" title="Statistik Login" onclick="openStatsModal(' . (int)$app->id . ', ' . $app_name_js . ')"><i class="bi bi-bar-chart-line"></i></button>';
                    $html .= '<button class="card-action-btn btn-edit" title="Edit" onclick="openEditModal(' . $json_app . ')"><i class="bi bi-pencil"></i></button>';
                    $html .= '<button class="card-action-btn btn-delete" title="Hapus" onclick="openDeleteModal(' . (int)$app->id . ', ' . $app_name_js . ')"><i class="bi bi-trash"></i></button>';
                    $html .= '</div>';
                }
                
                if ($app->logo && file_exists(FCPATH . $app->logo)) {
                    $html .= '<div class="app-logo-wrap"><img src="' . base_url($app->logo) . '" alt="' . htmlspecialchars($app->name) . '" /></div>';
                } else {
                    $html .= '<div class="app-logo-wrap" style="background: ' . htmlspecialchars($accent_color) . '1a;"><div class="app-logo-initials" style="background: ' . htmlspecialchars($accent_color) . ';">' . $initials . '</div></div>';
                }
                
                $html .= '<h6 class="app-name">' . htmlspecialchars($app->name) . '</h6>';
                $html .= '<p class="app-desc">' . htmlspecialchars($app->description ?: '—') . '</p>';
                $html .= '<div class="app-meta"><span class="app-category-badge">' . htmlspecialchars($app->category) . '</span></div>';

                if ($logged_in) {
                    $cnt = isset($app_counters[(int)$app->id]) ? $app_counters[(int)$app->id] : null;
                    $w = $cnt ? (int)$cnt->week_count : 0;
                    $m = $cnt ? (int)$cnt->month_count : 0;
                    $y = $cnt ? (int)$cnt->year_count : 0;
                    $html .= '<div class="app-login-counter" title="Lihat statistik & grafik login per user" onclick="event.preventDefault();event.stopPropagation();openStatsModal(' . (int)$app->id . ', ' . $app_name_js . ');">';
                    $html .= '<i class="bi bi-bar-chart-line-fill"></i>';
                    $html .= '<span>M: <strong>' . number_format($w, 0, ',', '.') . '</strong></span>';
                    $html .= '<span class="counter-sep">&middot;</span>';
                    $html .= '<span>B: <strong>' . number_format($m, 0, ',', '.') . '</strong></span>';
                    $html .= '<span class="counter-sep">&middot;</span>';
                    $html .= '<span>T: <strong>' . number_format($y, 0, ',', '.') . '</strong></span>';
                    $html .= '</div>';
                }

                $html .= '</a></div>';
            }
            
            // Add the "Tambah Aplikasi" button at the end (only if logged in)
            if ($logged_in) {
                $html .= '<div class="add-card" role="button" data-bs-toggle="modal" data-bs-target="#modalApp" onclick="resetModalForCreate()">';
                $html .= '<div class="add-card-icon"><i class="bi bi-plus-lg"></i></div>';
                $html .= '<div>Tambah Aplikasi</div>';
                $html .= '</div>';
            }
        } else {
            $html = '<div class="empty-state" style="grid-column: 1/-1;"><i class="bi bi-search d-block" style="font-size:3rem;margin-bottom:16px;opacity:.4;"></i><h5>Tidak ada hasil</h5><p>Coba kata kunci lain.</p></div>';
        }

        return $this->output
            ->set_content_type('application/json')
            ->set_output(json_encode([
                'status' => true,
                'html' => $html,
                'count' => count($apps)
            ]));
    }

    // -------------------------------------------------------
    // STORE (Create)
    // -------------------------------------------------------
    public function store() {
        if ($this->require_login() !== true) return;

        if ($this->input->server('REQUEST_METHOD') !== 'POST') {
            redirect('/');
        }

        $logo_path = null;

        // Handle logo upload
        if (!empty($_FILES['logo']['name'])) {
            $config = [
                'upload_path'   => FCPATH . 'assets/uploads/logos/',
                'allowed_types' => 'gif|jpg|jpeg|png|webp|svg',
                'max_size'      => 2048,
                'encrypt_name'  => TRUE,
            ];
            $this->upload->initialize($config);

            if ($this->upload->do_upload('logo')) {
                $logo_path = 'assets/uploads/logos/' . $this->upload->data('file_name');
            } else {
                if ($this->input->is_ajax_request()) {
                    return $this->output
                        ->set_content_type('application/json')
                        ->set_output(json_encode([
                            'status' => false,
                            'message' => 'Upload logo gagal: ' . $this->upload->display_errors('', ''),
                        ]));
                }

                $this->session->set_flashdata('error', 'Upload logo gagal: ' . $this->upload->display_errors('', ''));
                redirect(site_url('/'));
            }
        }

        $name = $this->input->post('name', TRUE);
        $url = $this->input->post('url', TRUE);
        $custom_code = $this->input->post('app_code', TRUE);

        $data = [
            'name'        => $name,
            'app_code'    => $this->App_model->generate_app_code($name, $url, $custom_code),
            'url'         => $url,
            'description' => $this->input->post('description', TRUE),
            'color'       => $this->input->post('color', TRUE) ?: '#6366f1',
            'category'    => $this->input->post('category', TRUE) ?: 'General',
            'logo'        => $logo_path,
            'sort_order'  => (int)$this->input->post('sort_order'),
            'is_active'   => 1,
        ];

        $this->App_model->insert($data);

        if ($this->input->is_ajax_request()) {
            return $this->output
                ->set_content_type('application/json')
                ->set_output(json_encode([
                    'status' => true,
                    'message' => 'Aplikasi berhasil ditambahkan!',
                    'redirect' => site_url('/'),
                ]));
        }

        $this->session->set_flashdata('success', 'Aplikasi berhasil ditambahkan!');
        redirect(site_url('/'));
    }

    // -------------------------------------------------------
    // UPDATE (Edit)
    // -------------------------------------------------------
    public function update() {
        if ($this->require_login() !== true) return;

        $id  = (int)$this->input->post('id');
        $app = $this->App_model->get_by_id($id);

        if (!$app) {
            $this->session->set_flashdata('error', 'Aplikasi tidak ditemukan.');
            redirect(site_url('/'));
        }

        $logo_path = $app->logo; // keep existing

        if (!empty($_FILES['logo']['name'])) {
            $config = [
                'upload_path'   => FCPATH . 'assets/uploads/logos/',
                'allowed_types' => 'gif|jpg|jpeg|png|webp|svg',
                'max_size'      => 2048,
                'encrypt_name'  => TRUE,
            ];
            $this->upload->initialize($config);

            if ($this->upload->do_upload('logo')) {
                // Delete old logo
                if ($app->logo && file_exists(FCPATH . $app->logo)) {
                    @unlink(FCPATH . $app->logo);
                }
                $logo_path = 'assets/uploads/logos/' . $this->upload->data('file_name');
            } else {
                if ($this->input->is_ajax_request()) {
                    return $this->output
                        ->set_content_type('application/json')
                        ->set_output(json_encode([
                            'status' => false,
                            'message' => 'Upload logo gagal: ' . $this->upload->display_errors('', ''),
                        ]));
                }
                $this->session->set_flashdata('error', 'Upload logo gagal: ' . $this->upload->display_errors('', ''));
                redirect(site_url('/'));
            }
        }

        // Remove logo if user checked "hapus logo"
        if ($this->input->post('remove_logo') == '1') {
            if ($app->logo && file_exists(FCPATH . $app->logo)) {
                @unlink(FCPATH . $app->logo);
            }
            $logo_path = null;
        }

        $name = $this->input->post('name', TRUE);
        $url = $this->input->post('url', TRUE);
        $custom_code = $this->input->post('app_code', TRUE);

        $data = [
            'name'        => $name,
            'app_code'    => $this->App_model->generate_app_code($name, $url, $custom_code),
            'url'         => $url,
            'description' => $this->input->post('description', TRUE),
            'color'       => $this->input->post('color', TRUE) ?: '#6366f1',
            'category'    => $this->input->post('category', TRUE) ?: 'General',
            'logo'        => $logo_path,
            'sort_order'  => (int)$this->input->post('sort_order'),
        ];

        $this->App_model->update($id, $data);

        if ($this->input->is_ajax_request()) {
            return $this->output
                ->set_content_type('application/json')
                ->set_output(json_encode([
                    'status' => true,
                    'message' => 'Aplikasi berhasil diperbarui!',
                    'redirect' => site_url('/'),
                ]));
        }

        $this->session->set_flashdata('success', 'Aplikasi berhasil diperbarui!');
        redirect(site_url('/'));
    }

    // -------------------------------------------------------
    // DELETE
    // -------------------------------------------------------
    public function delete() {
        if ($this->require_login() !== true) return;

        $id  = (int)$this->input->post('id');
        $app = $this->App_model->get_by_id($id);

        if ($app) {
            if ($app->logo && file_exists(FCPATH . $app->logo)) {
                @unlink(FCPATH . $app->logo);
            }
            $this->App_model->delete($id);
            $this->session->set_flashdata('success', 'Aplikasi berhasil dihapus!');
        } else {
            $this->session->set_flashdata('error', 'Aplikasi tidak ditemukan.');
        }

        redirect(site_url('/'));
    }

    // -------------------------------------------------------
    // TOGGLE ACTIVE
    // -------------------------------------------------------
    public function toggle() {
        if ($this->require_login() !== true) return;

        $id = (int)$this->input->post('id');
        $this->App_model->toggle_active($id);
        redirect(site_url('/'));
    }

    // -------------------------------------------------------
    // LOGIN COUNTER & ANALYTICS (API + ADMIN VIEWS)
    // -------------------------------------------------------
    public function api_log_login() {
        if ($this->input->server('REQUEST_METHOD') !== 'POST') {
            return $this->output
                ->set_status_header(405)
                ->set_content_type('application/json')
                ->set_output(json_encode([
                    'status'  => false,
                    'message' => 'Method not allowed. Gunakan POST.',
                ]));
        }

        $api_settings = $this->Login_log_model->get_api_settings();
        if ($api_settings->api_enabled !== '1') {
            return $this->output
                ->set_status_header(403)
                ->set_content_type('application/json')
                ->set_output(json_encode([
                    'status'  => false,
                    'message' => 'API pencatatan login sedang dinonaktifkan.',
                ]));
        }

        $raw = $this->input->raw_input_stream;
        $json = !empty($raw) ? json_decode($raw, true) : null;
        $payload = is_array($json) ? $json : $this->input->post(NULL, TRUE);

        $expected_key = $api_settings->api_key;
        $provided_key = $this->input->get_request_header('X-API-KEY', TRUE);
        if (empty($provided_key) && isset($payload['api_key'])) {
            $provided_key = $payload['api_key'];
        }

        $remote_ip = $this->input->ip_address();
        $is_local = in_array($remote_ip, ['127.0.0.1', '::1'], true);

        if (!empty($provided_key) && !hash_equals($expected_key, (string)$provided_key)) {
            return $this->output
                ->set_status_header(401)
                ->set_content_type('application/json')
                ->set_output(json_encode([
                    'status'  => false,
                    'message' => 'API key tidak valid.',
                ]));
        }

        if (empty($provided_key) && !$is_local) {
            return $this->output
                ->set_status_header(401)
                ->set_content_type('application/json')
                ->set_output(json_encode([
                    'status'  => false,
                    'message' => 'Diperlukan header X-API-KEY.',
                ]));
        }

        if (!empty($payload['ping_only'])) {
            return $this->output
                ->set_content_type('application/json')
                ->set_output(json_encode([
                    'status'  => true,
                    'message' => 'Koneksi API Portal valid dan siap menerima log login.',
                ]));
        }

        $app_id     = isset($payload['app_id']) ? (int)$payload['app_id'] : 0;
        $app_code   = isset($payload['app_code']) ? trim((string)$payload['app_code']) : '';
        $user_id    = isset($payload['user_id']) ? trim((string)$payload['user_id']) : null;
        $username   = isset($payload['username']) ? trim((string)$payload['username']) : '';
        $full_name  = isset($payload['full_name']) ? trim((string)$payload['full_name']) : $username;
        $ip_address = !empty($payload['ip_address']) ? trim((string)$payload['ip_address']) : $remote_ip;
        $user_agent = !empty($payload['user_agent']) ? trim((string)$payload['user_agent']) : $this->input->user_agent();
        $login_at   = !empty($payload['login_at']) ? trim((string)$payload['login_at']) : date('Y-m-d H:i:s');

        if ($username === '') {
            return $this->output
                ->set_status_header(400)
                ->set_content_type('application/json')
                ->set_output(json_encode([
                    'status'  => false,
                    'message' => 'Parameter username wajib diisi.',
                ]));
        }

        $app = $this->Login_log_model->resolve_app($app_id, $app_code);
        if (!$app) {
            return $this->output
                ->set_status_header(404)
                ->set_content_type('application/json')
                ->set_output(json_encode([
                    'status'  => false,
                    'message' => 'Aplikasi tidak ditemukan di portal.',
                ]));
        }

        $log_id = $this->Login_log_model->record_login([
            'app_id'     => $app->id,
            'user_id'    => $user_id,
            'username'   => $username,
            'full_name'  => $full_name,
            'ip_address' => $ip_address,
            'user_agent' => $user_agent,
            'login_at'   => $login_at,
        ]);

        return $this->output
            ->set_content_type('application/json')
            ->set_output(json_encode([
                'status'   => true,
                'message'  => 'Login berhasil dicatat.',
                'log_id'   => $log_id,
                'app_id'   => (int)$app->id,
                'app_name' => $app->name,
            ]));
    }

    public function stats_ajax() {
        if ($this->require_login() !== true) return;

        $app_id = (int)$this->input->get('app_id', TRUE);
        $period = $this->input->get('period', TRUE) ?: 'month';
        if (!in_array($period, ['week', 'month', 'year', 'all'], true)) {
            $period = 'month';
        }

        $app = $app_id > 0 ? $this->App_model->get_by_id($app_id) : null;
        if ($app_id > 0 && !$app) {
            return $this->output
                ->set_status_header(404)
                ->set_content_type('application/json')
                ->set_output(json_encode([
                    'status'  => false,
                    'message' => 'Aplikasi tidak ditemukan.',
                ]));
        }

        return $this->output
            ->set_content_type('application/json')
            ->set_output(json_encode([
                'status'     => true,
                'period'     => $period,
                'app'        => $app,
                'summary'    => $this->Login_log_model->get_summary($app_id),
                'user_stats' => $this->Login_log_model->get_user_stats($app_id, $period, 50),
                'history'    => $this->Login_log_model->get_login_history($app_id, $period, 100),
            ]));
    }

    public function analytics() {
        if ($this->require_login() !== true) return;

        $app_id = (int)$this->input->get('app_id', TRUE);
        $period = $this->input->get('period', TRUE) ?: 'month';
        if (!in_array($period, ['week', 'month', 'year', 'all'], true)) {
            $period = 'month';
        }

        $data['apps']            = $this->App_model->get_all();
        $data['selected_app']    = $app_id > 0 ? $this->App_model->get_by_id($app_id) : null;
        $data['selected_app_id'] = $app_id;
        $data['selected_period'] = $period;
        $data['summary']         = $this->Login_log_model->get_summary($app_id);
        $data['app_comparison']  = $this->Login_log_model->get_app_comparison_stats();
        $data['user_stats']      = $this->Login_log_model->get_user_stats($app_id, $period, 50);
        $data['history']         = $this->Login_log_model->get_login_history($app_id, $period, 100);
        $data['api_settings']    = $this->Login_log_model->get_api_settings();
        $data['logged_in']       = true;
        $data['current_user']    = $this->session->userdata('username');

        $this->load->view('portal/analytics', $data);
    }

    // -------------------------------------------------------
    // API SETTINGS (ADMIN ONLY)
    // -------------------------------------------------------
    public function api_settings() {
        if ($this->require_login() !== true) return;

        $data['api_settings'] = $this->Login_log_model->get_api_settings();
        $data['apps']         = $this->App_model->get_all();
        $data['logged_in']    = true;
        $data['current_user'] = $this->session->userdata('username');

        $this->load->view('portal/api_settings', $data);
    }

    public function api_settings_save() {
        if ($this->require_login() !== true) return;

        if ($this->input->server('REQUEST_METHOD') !== 'POST') {
            redirect(site_url('index.php/api_settings'));
        }

        $api_endpoint_url = trim((string)$this->input->post('api_endpoint_url', TRUE));
        $api_key          = trim((string)$this->input->post('api_key', TRUE));
        $api_enabled      = $this->input->post('api_enabled') ? '1' : '0';

        if ($api_endpoint_url === '' || !filter_var($api_endpoint_url, FILTER_VALIDATE_URL)) {
            $msg = 'URL Endpoint API tidak valid. Gunakan format http://... atau https://...';
            if ($this->input->is_ajax_request()) {
                return $this->output
                    ->set_content_type('application/json')
                    ->set_output(json_encode(['status' => false, 'message' => $msg]));
            }
            $this->session->set_flashdata('error', $msg);
            redirect(site_url('index.php/api_settings'));
        }

        if (strlen($api_key) < 6) {
            $msg = 'API Key minimal 6 karakter.';
            if ($this->input->is_ajax_request()) {
                return $this->output
                    ->set_content_type('application/json')
                    ->set_output(json_encode(['status' => false, 'message' => $msg]));
            }
            $this->session->set_flashdata('error', $msg);
            redirect(site_url('index.php/api_settings'));
        }

        $saved = $this->Login_log_model->save_api_settings([
            'api_endpoint_url' => $api_endpoint_url,
            'api_key'          => $api_key,
            'api_enabled'      => $api_enabled,
        ]);

        if ($saved) {
            if ($this->input->is_ajax_request()) {
                return $this->output
                    ->set_content_type('application/json')
                    ->set_output(json_encode([
                        'status'  => true,
                        'message' => 'Pengaturan Link API berhasil disimpan.',
                        'data'    => $this->Login_log_model->get_api_settings(),
                    ]));
            }
            $this->session->set_flashdata('success', 'Pengaturan Link API berhasil disimpan.');
        } else {
            if ($this->input->is_ajax_request()) {
                return $this->output
                    ->set_content_type('application/json')
                    ->set_output(json_encode([
                        'status'  => false,
                        'message' => 'Gagal menyimpan pengaturan Link API.',
                    ]));
            }
            $this->session->set_flashdata('error', 'Gagal menyimpan pengaturan Link API.');
        }

        redirect(site_url('index.php/api_settings'));
    }

    // -------------------------------------------------------
    // TEST ROUTE
    // -------------------------------------------------------
    public function test() {
        echo 'Route test successful!';
        echo '<br>Controller: ' . __CLASS__;
        echo '<br>Method: ' . __METHOD__;
        echo '<br>URI: ' . $this->uri->uri_string();
    }
}
