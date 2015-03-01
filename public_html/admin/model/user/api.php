<?php
class ModelUserApi extends Model {
	public $table = 'api';
	public $primaryKey = 'api_id';

	public function addApi($data) {
		$this->db->set('username', $data['username']);
		$this->db->set('password', $data['password']);
		$this->db->set('status', (int)$data['status']);
		$this->db->set('date_added', 'NOW()', FALSE);
		$this->db->set('date_modified', 'NOW()', FALSE);
		$this->db->insert($this->table);
		return $this->db->insert_id();
		//$this->db->query("INSERT INTO `" . DB_PREFIX . "api` SET username = '" . $this->db->escape($data['username']) . "', `password` = '" . $this->db->escape($data['password']) . "', status = '" . (int)$data['status'] . "', date_added = NOW(), date_modified = NOW()");
	}

	public function editApi($api_id, $data) {
		$this->db->set('username', $data['username']);
		$this->db->set('password', $data['password']);
		$this->db->set('status', (int)$data['status']);
		$this->db->set('date_added', 'NOW()', FALSE);
		$this->db->set('date_modified', 'NOW()', FALSE);
		$this->db->where($this->primaryKey, (int)$api_id);
		$this->db->update($this->table);

		//$this->db->query("UPDATE `" . DB_PREFIX . "api` SET username = '" . $this->db->escape($data['username']) . "', `password` = '" . $this->db->escape($data['password']) . "', status = '" . (int)$data['status'] . "', date_modified = NOW() WHERE api_id = '" . (int)$api_id . "'");
	}

	public function deleteApi($api_id) {
		$this->db->where($this->primaryKey, (int)$api_id);
		$this->db->delete($this->table);

		//$this->db->query("DELETE FROM `" . DB_PREFIX . "api` WHERE api_id = '" . (int)$api_id . "'");
	}

	public function getApi($api_id) {
		$this->db->select('*')->from($this->table)->where($this->primaryKey, (int)$api_id);
		return $this->db->get()->row_array();

		//$query = $this->db->query("SELECT * FROM `" . DB_PREFIX . "api` WHERE api_id = '" . (int)$api_id . "'");

		//return $query->row;
	}

	public function getApis($data = array()) {
		$this->db->select('*');
		$this->db->from($this->table);

		//$sql = "SELECT * FROM `" . DB_PREFIX . "api`";

		$sort_data = array(
			'username',
			'status',
			'date_added',
			'date_modified'
		);

		$order = 'ASC';
		if (isset($data['order']) && ($data['order'] == 'DESC')) {
			$order = 'DESC';
		}

		if (isset($data['sort']) && in_array($data['sort'], $sort_data)) {
			//$sql .= " ORDER BY " . $data['sort'];
			$this->db->order_by($data['sort'], $order);
		} else {
			$this->db->order_by('username', $order);
		}

		if (isset($data['start']) || isset($data['limit'])) {
			if ($data['start'] < 0) {
				$data['start'] = 0;
			}

			if ($data['limit'] < 1) {
				$data['limit'] = 20;
			}

			//$sql .= " LIMIT " . (int)$data['start'] . "," . (int)$data['limit'];
			$this->db->limit($data['limit'], $data['start']);
		}

		return $this->db->get()->result_array();
	}

	public function getTotalApis() {
		$this->db->select('COUNT(*) AS total');
		$this->db->from($this->table);
		$data = $this->db->get()->row_array();
		return $data['total'];

		//$query = $this->db->query("SELECT COUNT(*) AS total FROM `" . DB_PREFIX . "api`");

		//return $query->row['total'];
	}
}