<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class User_model extends CI_Model {

    protected $table = 'users_portal';

    public function __construct() {
        parent::__construct();
    }

    /**
     * Authenticate user by username and password
     */
    public function login($username, $password) {
        $user = $this->db->get_where($this->table, ['username' => $username])->row();
        if ($user && password_verify($password, $user->password)) {
            return $user;
        }
        return false;
    }

    /**
     * Get all users
     */
    public function get_all() {
        $this->db->order_by('id', 'ASC');
        return $this->db->get($this->table)->result();
    }

    /**
     * Get user by ID
     */
    public function get_by_id($id) {
        return $this->db->get_where($this->table, ['id' => $id])->row();
    }

    /**
     * Check if username already exists (excluding given id)
     */
    public function username_exists($username, $exclude_id = 0) {
        $this->db->where('username', $username);
        if ($exclude_id) {
            $this->db->where('id !=', $exclude_id);
        }
        return $this->db->count_all_results($this->table) > 0;
    }

    /**
     * Insert new user
     */
    public function insert($data) {
        $data['password'] = password_hash($data['password'], PASSWORD_DEFAULT);
        $data['created_at'] = date('Y-m-d H:i:s');
        $this->db->insert($this->table, $data);
        return $this->db->insert_id();
    }

    /**
     * Update user
     */
    public function update($id, $data) {
        if (!empty($data['password'])) {
            $data['password'] = password_hash($data['password'], PASSWORD_DEFAULT);
        } else {
            unset($data['password']);
        }
        $this->db->where('id', $id);
        return $this->db->update($this->table, $data);
    }

    /**
     * Delete user
     */
    public function delete($id) {
        $this->db->where('id', $id);
        return $this->db->delete($this->table);
    }

    /**
     * Count all users
     */
    public function count_all() {
        return $this->db->count_all($this->table);
    }
}
