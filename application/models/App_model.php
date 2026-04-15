<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class App_model extends CI_Model {

    protected $table = 'apps';

    public function __construct() {
        parent::__construct();
    }

    // --- READ ---
    public function get_all($search = '', $category = '') {
        $this->db->order_by('sort_order', 'ASC');
        $this->db->order_by('name', 'ASC');
        if ($search) {
            $this->db->group_start();
            $this->db->like('name', $search);
            $this->db->or_like('description', $search);
            $this->db->group_end();
        }
        if ($category && $category !== 'all') {
            $this->db->where('category', $category);
        }
        return $this->db->get($this->table)->result();
    }

    public function get_by_id($id) {
        return $this->db->get_where($this->table, ['id' => $id])->row();
    }

    public function get_categories() {
        $this->db->select('category');
        $this->db->distinct();
        $this->db->where('is_active', 1);
        $this->db->order_by('category', 'ASC');
        $result = $this->db->get($this->table)->result();
        return array_column($result, 'category');
    }

    // --- CREATE ---
    public function insert($data) {
        $this->db->insert($this->table, $data);
        return $this->db->insert_id();
    }

    // --- UPDATE ---
    public function update($id, $data) {
        $this->db->where('id', $id);
        return $this->db->update($this->table, $data);
    }

    public function toggle_active($id) {
        $app = $this->get_by_id($id);
        if ($app) {
            $this->db->where('id', $id);
            $this->db->update($this->table, ['is_active' => $app->is_active ? 0 : 1]);
            return true;
        }
        return false;
    }

    // --- DELETE ---
    public function delete($id) {
        $this->db->where('id', $id);
        return $this->db->delete($this->table);
    }
}
