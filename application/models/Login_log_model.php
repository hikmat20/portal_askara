<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Login_log_model extends CI_Model {

    protected $table = 'app_login_logs';

    public function __construct() {
        parent::__construct();
    }

    /**
     * Resolve an application row by app_id or app_code (falling back to name or URL segment).
     */
    public function resolve_app($app_id = null, $app_code = null) {
        $app_id = (int)$app_id;
        if ($app_id > 0) {
            $app = $this->db->get_where('apps', ['id' => $app_id])->row();
            if ($app) {
                return $app;
            }
        }

        $code = strtolower(trim((string)$app_code));
        if ($code !== '') {
            $this->db->where('LOWER(app_code)', $code);
            $this->db->order_by('id', 'ASC');
            $app = $this->db->get('apps', 1)->row();
            if ($app) {
                return $app;
            }

            $this->db->where('LOWER(name)', $code);
            $this->db->order_by('id', 'ASC');
            $app = $this->db->get('apps', 1)->row();
            if ($app) {
                return $app;
            }

            $this->db->like('LOWER(url)', '/' . $code, 'both');
            $this->db->order_by('id', 'ASC');
            $app = $this->db->get('apps', 1)->row();
            if ($app) {
                return $app;
            }
        }

        return null;
    }

    /**
     * Record a successful user login into app_login_logs.
     */
    public function record_login($data) {
        $insert = [
            'app_id'     => (int)$data['app_id'],
            'user_id'    => isset($data['user_id']) && $data['user_id'] !== '' ? (string)$data['user_id'] : null,
            'username'   => trim((string)$data['username']),
            'full_name'  => isset($data['full_name']) && $data['full_name'] !== '' ? trim((string)$data['full_name']) : trim((string)$data['username']),
            'ip_address' => isset($data['ip_address']) ? substr((string)$data['ip_address'], 0, 45) : null,
            'user_agent' => isset($data['user_agent']) ? substr((string)$data['user_agent'], 0, 500) : null,
            'login_at'   => !empty($data['login_at']) ? $data['login_at'] : date('Y-m-d H:i:s'),
        ];

        $this->db->insert($this->table, $insert);
        return $this->db->insert_id();
    }

    /**
     * Get aggregated login counters keyed by app_id.
     */
    public function get_counters_by_app() {
        $this->db->select("
            app_id,
            SUM(CASE WHEN YEARWEEK(login_at, 1) = YEARWEEK(CURDATE(), 1) THEN 1 ELSE 0 END) AS week_count,
            SUM(CASE WHEN YEAR(login_at) = YEAR(CURDATE()) AND MONTH(login_at) = MONTH(CURDATE()) THEN 1 ELSE 0 END) AS month_count,
            SUM(CASE WHEN YEAR(login_at) = YEAR(CURDATE()) THEN 1 ELSE 0 END) AS year_count,
            COUNT(id) AS total_count,
            COUNT(DISTINCT username) AS unique_users
        ", FALSE);
        $this->db->group_by('app_id');
        $rows = $this->db->get($this->table)->result();

        $counters = [];
        foreach ($rows as $row) {
            $counters[(int)$row->app_id] = (object)[
                'week_count'   => (int)$row->week_count,
                'month_count'  => (int)$row->month_count,
                'year_count'   => (int)$row->year_count,
                'total_count'  => (int)$row->total_count,
                'unique_users' => (int)$row->unique_users,
            ];
        }
        return $counters;
    }

    /**
     * Get summary counters for a single app or all apps combined.
     */
    public function get_summary($app_id = null) {
        $this->db->select("
            COALESCE(SUM(CASE WHEN YEARWEEK(login_at, 1) = YEARWEEK(CURDATE(), 1) THEN 1 ELSE 0 END), 0) AS week_count,
            COALESCE(SUM(CASE WHEN YEAR(login_at) = YEAR(CURDATE()) AND MONTH(login_at) = MONTH(CURDATE()) THEN 1 ELSE 0 END), 0) AS month_count,
            COALESCE(SUM(CASE WHEN YEAR(login_at) = YEAR(CURDATE()) THEN 1 ELSE 0 END), 0) AS year_count,
            COUNT(id) AS total_count,
            COUNT(DISTINCT CASE WHEN YEARWEEK(login_at, 1) = YEARWEEK(CURDATE(), 1) THEN username ELSE NULL END) AS unique_users_week,
            COUNT(DISTINCT CASE WHEN YEAR(login_at) = YEAR(CURDATE()) AND MONTH(login_at) = MONTH(CURDATE()) THEN username ELSE NULL END) AS unique_users_month,
            COUNT(DISTINCT CASE WHEN YEAR(login_at) = YEAR(CURDATE()) THEN username ELSE NULL END) AS unique_users_year,
            COUNT(DISTINCT username) AS unique_users_total
        ", FALSE);

        $app_id = (int)$app_id;
        if ($app_id > 0) {
            $this->db->where('app_id', $app_id);
        }

        $row = $this->db->get($this->table)->row();
        if (!$row) {
            return (object)[
                'week_count'         => 0,
                'month_count'        => 0,
                'year_count'         => 0,
                'total_count'        => 0,
                'unique_users_week'  => 0,
                'unique_users_month' => 0,
                'unique_users_year'  => 0,
                'unique_users_total' => 0,
            ];
        }

        return (object)[
            'week_count'         => (int)$row->week_count,
            'month_count'        => (int)$row->month_count,
            'year_count'         => (int)$row->year_count,
            'total_count'        => (int)$row->total_count,
            'unique_users_week'  => (int)$row->unique_users_week,
            'unique_users_month' => (int)$row->unique_users_month,
            'unique_users_year'  => (int)$row->unique_users_year,
            'unique_users_total' => (int)$row->unique_users_total,
        ];
    }

    /**
     * Get per-user login counters and chart dataset.
     */
    public function get_user_stats($app_id = null, $period = 'month', $limit = 50) {
        $period = $this->_normalize_period($period);
        $period_expr = $this->_period_count_expression($period);

        $this->db->select("
            username,
            MAX(full_name) AS full_name,
            MAX(user_id) AS user_id,
            SUM(CASE WHEN YEARWEEK(login_at, 1) = YEARWEEK(CURDATE(), 1) THEN 1 ELSE 0 END) AS week_count,
            SUM(CASE WHEN YEAR(login_at) = YEAR(CURDATE()) AND MONTH(login_at) = MONTH(CURDATE()) THEN 1 ELSE 0 END) AS month_count,
            SUM(CASE WHEN YEAR(login_at) = YEAR(CURDATE()) THEN 1 ELSE 0 END) AS year_count,
            COUNT(id) AS total_count,
            {$period_expr} AS period_count,
            MAX(login_at) AS last_login_at
        ", FALSE);

        $app_id = (int)$app_id;
        if ($app_id > 0) {
            $this->db->where('app_id', $app_id);
        }

        $this->db->group_by('username');
        $this->db->order_by('period_count', 'DESC');
        $this->db->order_by('total_count', 'DESC');
        $this->db->order_by('last_login_at', 'DESC');
        $this->db->limit((int)$limit);

        $rows = $this->db->get($this->table)->result();
        foreach ($rows as $row) {
            $row->week_count   = (int)$row->week_count;
            $row->month_count  = (int)$row->month_count;
            $row->year_count   = (int)$row->year_count;
            $row->total_count  = (int)$row->total_count;
            $row->period_count = (int)$row->period_count;
        }
        return $rows;
    }

    /**
     * Get per-application comparison stats across all apps.
     */
    public function get_app_comparison_stats() {
        $this->db->select("
            a.id,
            a.name,
            a.app_code,
            a.category,
            a.color,
            COALESCE(SUM(CASE WHEN YEARWEEK(l.login_at, 1) = YEARWEEK(CURDATE(), 1) THEN 1 ELSE 0 END), 0) AS week_count,
            COALESCE(SUM(CASE WHEN YEAR(l.login_at) = YEAR(CURDATE()) AND MONTH(l.login_at) = MONTH(CURDATE()) THEN 1 ELSE 0 END), 0) AS month_count,
            COALESCE(SUM(CASE WHEN YEAR(l.login_at) = YEAR(CURDATE()) THEN 1 ELSE 0 END), 0) AS year_count,
            COUNT(l.id) AS total_count,
            COUNT(DISTINCT l.username) AS unique_users,
            MAX(l.login_at) AS last_login_at
        ", FALSE);
        $this->db->from('apps a');
        $this->db->join($this->table . ' l', 'a.id = l.app_id', 'left');
        $this->db->group_by(['a.id', 'a.name', 'a.app_code', 'a.category', 'a.color', 'a.sort_order']);
        $this->db->order_by('total_count', 'DESC');
        $this->db->order_by('a.sort_order', 'ASC');
        $this->db->order_by('a.name', 'ASC');

        $rows = $this->db->get()->result();
        foreach ($rows as $row) {
            $row->id           = (int)$row->id;
            $row->week_count   = (int)$row->week_count;
            $row->month_count  = (int)$row->month_count;
            $row->year_count   = (int)$row->year_count;
            $row->total_count  = (int)$row->total_count;
            $row->unique_users = (int)$row->unique_users;
        }
        return $rows;
    }

    /**
     * Get detailed chronological login history.
     */
    public function get_login_history($app_id = null, $period = 'month', $limit = 100) {
        $period = $this->_normalize_period($period);

        $this->db->select('l.id, l.app_id, l.user_id, l.username, l.full_name, l.ip_address, l.user_agent, l.login_at, a.name AS app_name, a.app_code, a.color AS app_color');
        $this->db->from($this->table . ' l');
        $this->db->join('apps a', 'a.id = l.app_id', 'left');

        $app_id = (int)$app_id;
        if ($app_id > 0) {
            $this->db->where('l.app_id', $app_id);
        }

        $this->_apply_period_filter($period, 'l.login_at');

        $this->db->order_by('l.login_at', 'DESC');
        $this->db->order_by('l.id', 'DESC');
        $this->db->limit((int)$limit);

        return $this->db->get()->result();
    }

    /**
     * Get API endpoint configuration from portal_settings.
     */
    public function get_api_settings() {
        $defaults = [
            'api_endpoint_url' => function_exists('site_url') ? site_url('index.php/api/log_login') : 'http://localhost/portal_askara/index.php/api/log_login',
            'api_key'          => 'ASKARA_PORTAL_KEY_2026',
            'api_enabled'      => '1',
        ];

        if ($this->db->table_exists('portal_settings')) {
            $rows = $this->db->get('portal_settings')->result();
            foreach ($rows as $row) {
                if (array_key_exists($row->setting_key, $defaults) && $row->setting_value !== null && $row->setting_value !== '') {
                    $defaults[$row->setting_key] = $row->setting_value;
                }
            }
        }

        return (object)$defaults;
    }

    /**
     * Save API endpoint configuration into portal_settings.
     */
    public function save_api_settings($data) {
        $allowed_keys = ['api_endpoint_url', 'api_key', 'api_enabled'];
        $this->db->trans_begin();

        foreach ($allowed_keys as $key) {
            if (!array_key_exists($key, $data)) {
                continue;
            }
            $value = (string)$data[$key];
            $exists = $this->db->get_where('portal_settings', ['setting_key' => $key])->num_rows() > 0;
            if ($exists) {
                $this->db->where('setting_key', $key);
                $this->db->update('portal_settings', ['setting_value' => $value]);
            } else {
                $this->db->insert('portal_settings', [
                    'setting_key'   => $key,
                    'setting_value' => $value,
                ]);
            }
        }

        if ($this->db->trans_status() === FALSE) {
            $this->db->trans_rollback();
            return false;
        }
        $this->db->trans_commit();
        return true;
    }

    private function _normalize_period($period) {
        $allowed = ['week', 'month', 'year', 'all'];
        return in_array($period, $allowed, true) ? $period : 'month';
    }

    private function _period_count_expression($period) {
        switch ($period) {
            case 'week':
                return 'SUM(CASE WHEN YEARWEEK(login_at, 1) = YEARWEEK(CURDATE(), 1) THEN 1 ELSE 0 END)';
            case 'year':
                return 'SUM(CASE WHEN YEAR(login_at) = YEAR(CURDATE()) THEN 1 ELSE 0 END)';
            case 'all':
                return 'COUNT(id)';
            case 'month':
            default:
                return 'SUM(CASE WHEN YEAR(login_at) = YEAR(CURDATE()) AND MONTH(login_at) = MONTH(CURDATE()) THEN 1 ELSE 0 END)';
        }
    }

    private function _apply_period_filter($period, $column = 'login_at') {
        switch ($period) {
            case 'week':
                $this->db->where("YEARWEEK({$column}, 1) = YEARWEEK(CURDATE(), 1)", NULL, FALSE);
                break;
            case 'month':
                $this->db->where("YEAR({$column}) = YEAR(CURDATE()) AND MONTH({$column}) = MONTH(CURDATE())", NULL, FALSE);
                break;
            case 'year':
                $this->db->where("YEAR({$column}) = YEAR(CURDATE())", NULL, FALSE);
                break;
            case 'all':
            default:
                break;
        }
    }
}
