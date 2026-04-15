<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Portal extends CI_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->model('App_model');
        $this->load->helper(['url', 'form']);
        $this->load->library(['session', 'upload']);
    }

    // -------------------------------------------------------
    // INDEX
    // -------------------------------------------------------
    public function index() {
        $search   = $this->input->get('q');
        $category = $this->input->get('cat');

        $data['apps']       = $this->App_model->get_all($search, $category);
        $data['categories'] = $this->App_model->get_categories();
        $data['search']     = $search;
        $data['category']   = $category;

        $this->load->view('portal/index', $data);
    }

    // -------------------------------------------------------
    // SEARCH (AJAX)
    // -------------------------------------------------------
    public function search_ajax() {
        if (!$this->input->is_ajax_request()) {
            return;
        }

        $q = $this->input->get('q');
        $apps = $this->App_model->get_all($q);

        $html = '';
        if (!empty($apps)) {
            foreach ($apps as $app) {
                $initials = strtoupper(mb_substr($app->name, 0, 1));
                if (strpos($app->name, ' ') !== false) {
                    $parts = explode(' ', $app->name);
                    $initials = strtoupper(mb_substr($parts[0], 0, 1) . mb_substr($parts[1], 0, 1));
                }
                $accent_color = $app->color ?: '#58a6ff';
                $json_app = json_encode($app);
                
                $html .= '<div style="position:relative;">';
                $html .= '<a href="' . htmlspecialchars($app->url) . '" target="_blank" rel="noopener noreferrer" class="app-card ' . ($app->is_active ? '' : 'inactive') . '" style="--card-accent: ' . htmlspecialchars($accent_color) . ';">';
                $html .= '<div class="card-actions" onclick="event.preventDefault();event.stopPropagation();">';
                $html .= '<button class="card-action-btn btn-edit" onclick="openEditModal(' . $json_app . ')"><i class="bi bi-pencil"></i></button>';
                $html .= '<button class="card-action-btn btn-delete" onclick="openDeleteModal(' . $app->id . ', \'' . addslashes($app->name) . '\')"><i class="bi bi-trash"></i></button>';
                $html .= '</div>';
                
                if ($app->logo && file_exists(FCPATH . $app->logo)) {
                    $html .= '<div class="app-logo-wrap"><img src="' . base_url($app->logo) . '" alt="' . htmlspecialchars($app->name) . '" /></div>';
                } else {
                    $html .= '<div class="app-logo-wrap" style="background: ' . htmlspecialchars($accent_color) . '1a;"><div class="app-logo-initials" style="background: ' . htmlspecialchars($accent_color) . ';">' . $initials . '</div></div>';
                }
                
                $html .= '<h6 class="app-name">' . htmlspecialchars($app->name) . '</h6>';
                $html .= '<p class="app-desc">' . htmlspecialchars($app->description ?: '—') . '</p>';
                $html .= '<div class="app-meta"><span class="app-category-badge">' . htmlspecialchars($app->category) . '</span></div></a></div>';
            }
            
            // Add the "Tambah Aplikasi" button at the end
            $html .= '<div class="add-card" role="button" data-bs-toggle="modal" data-bs-target="#modalApp" onclick="resetModalForCreate()">';
            $html .= '<div class="add-card-icon"><i class="bi bi-plus-lg"></i></div>';
            $html .= '<div>Tambah Aplikasi</div>';
            $html .= '</div>';
        } else {
            $html = '<div class="empty-state" style="grid-column: 1/-1;"><i class="bi bi-search d-block" style="font-size:3rem;margin-bottom:16px;opacity:.4;"></i><h5>Tidak ada hasil</h5><p>Coba kata kunci lain.</p></div>';
        }

        echo json_encode([
            'status' => true,
            'html' => $html,
            'count' => count($apps)
        ]);
    }

    // -------------------------------------------------------
    // STORE (Create)
    // -------------------------------------------------------
    public function store() {
      
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

        $data = [
            'name'        => $this->input->post('name', TRUE),
            'url'         => $this->input->post('url', TRUE),
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
        // if ($this->input->server('REQUEST_METHOD') !== 'POST') {
        //     redirect('/');
        // }

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

        $data = [
            'name'        => $this->input->post('name', TRUE),
            'url'         => $this->input->post('url', TRUE),
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
        $id = (int)$this->input->post('id');
        $this->App_model->toggle_active($id);
        redirect(site_url('/'));
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
