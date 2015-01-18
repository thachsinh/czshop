<?php
class ModelSaleCustomerBanIp extends Model {
	public $table = 'customer_ban_ip';
	public $fields = array('customer_ban_ip_id', 'ip');
	public $primaryKey = 'customer_ban_ip_id';

	public function addCustomerBanIp($data) {
		$data = $this->initData($data, TRUE);
		$this->db->insert($this->table, $data);
		return $this->db->insert_id();
		//$this->db->query("INSERT INTO `" . DB_PREFIX . "customer_ban_ip` SET `ip` = '" . $this->db->escape($data['ip']) . "'");
	}

	public function editCustomerBanIp($customer_ban_ip_id, $data) {
		$data = $this->initData($data, TRUE);
		$this->db->where($this->primaryKey, (int)$customer_ban_ip_id);
		$this->db->update($this->table, $data);

		//$this->db->query("UPDATE `" . DB_PREFIX . "customer_ban_ip` SET `ip` = '" . $this->db->escape($data['ip']) . "' WHERE customer_ban_ip_id = '" . (int)$customer_ban_ip_id . "'");
	}

	public function deleteCustomerBanIp($customer_ban_ip_id) {
		$this->db->where($this->primaryKey, (int)$customer_ban_ip_id);
		$this->db->delete($this->table);

		//$this->db->query("DELETE FROM `" . DB_PREFIX . "customer_ban_ip` WHERE customer_ban_ip_id = '" . (int)$customer_ban_ip_id . "'");
	}

	public function getCustomerBanIp($customer_ban_ip_id) {
		$this->db->select('*');
		$this->db->from($this->table);
		$this->db->where($this->primaryKey, (int)$customer_ban_ip_id);
		return $this->db->get()->row_array();

		//$query = $this->db->query("SELECT * FROM `" . DB_PREFIX . "customer_ban_ip` WHERE customer_ban_ip_id = '" . (int)$customer_ban_ip_id . "'");

		//return $query->row;
	}

	public function getCustomerBanIps($data = array()) {
		$sql = "SELECT *, (SELECT COUNT(DISTINCT customer_id) FROM `" . DB_PREFIX . "customer_ip` ci WHERE ci.ip = cbi.ip) AS total FROM `" . DB_PREFIX . "customer_ban_ip` cbi";

		$sql .= " ORDER BY `ip`";

		if (isset($data['order']) && ($data['order'] == 'DESC')) {
			$sql .= " DESC";
		} else {
			$sql .= " ASC";
		}

		if (isset($data['start']) || isset($data['limit'])) {
			if ($data['start'] < 0) {
				$data['start'] = 0;
			}

			if ($data['limit'] < 1) {
				$data['limit'] = 20;
			}

			$sql .= " LIMIT " . (int)$data['start'] . "," . (int)$data['limit'];
		}

		$query = $this->db->query($sql);

		//return $query->rows;
		return $query->result_array();
	}

	public function getTotalCustomerBanIps($data = array()) {
		$this->db->select('COUNT(*) AS `total`');
		$query = $this->db->get($this->table)->row_array();
		return $query['total'];

		//$query = $this->db->query("SELECT COUNT(*) AS total FROM `" . DB_PREFIX . "customer_ban_ip`");

		//return $query->row['total'];
	}
}