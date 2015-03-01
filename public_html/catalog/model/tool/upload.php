<?php
class ModelToolUpload extends Model {
	public $table = 'upload';
	public function addUpload($name, $filename) {
		$code = sha1(uniqid(mt_rand(), true));

		$this->db->set('name', $name);
		$this->db->set('filename', $filename);
		$this->db->set('code', $code);
		$this->db->set('date_added', 'NOW()', FALSE);
		$this->db->insert($this->table);

		//$this->db->query("INSERT INTO `" . DB_PREFIX . "upload` SET `name` = '" . $this->db->escape($name) . "', `filename` = '" . $this->db->escape($filename) . "', `code` = '" . $this->db->escape($code) . "', `date_added` = NOW()");

		return $code;
	}

	public function getUploadByCode($code) {
		$this->db->select('*')
			->from($this->table)
			->where('code', $code);
		return $this->db->get()->row_array();

		//$query = $this->db->query("SELECT * FROM `" . DB_PREFIX . "upload` WHERE code = '" . $this->db->escape($code) . "'");

		//return $query->row;
	}
}