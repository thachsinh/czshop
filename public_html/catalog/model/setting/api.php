<?php
class ModelSettingApi extends Model {
	public $table = 'api';
	public function login($username, $password) {
		$this->db->select('*')
			->from($this->table)
			->where('username', $username)
			->where('password', $password);

		return $this->db->get()->row_array();

		//$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "api WHERE username = '" . $this->db->escape($username) . "' AND password = '" . $this->db->escape($password) . "'");

		//return $query->row;
	}
}