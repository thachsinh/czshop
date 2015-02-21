<?php
class ModelAccountActivity extends Model {
	public $table = 'customer_activity';
	public function addActivity($key, $data) {
		if (isset($data['customer_id'])) {
			$customer_id = $data['customer_id'];
		} else {
			$customer_id = 0;
		}

		$this->db->set('customer_id', (int)$customer_id);
		$this->db->set('key', $key);
		$this->db->set('data', serialize($data));
		$this->db->set('ip', $this->request->server['REMOTE_ADDR']);
		$this->db->set('date_added', 'NOW()', FALSE);
		$this->db->insert($this->table);


		//$this->db->query("INSERT INTO `" . DB_PREFIX . "customer_activity` SET `customer_id` = '" . (int)$customer_id . "', `key` = '" . $this->db->escape($key) . "', `data` = '" . $this->db->escape(serialize($data)) . "', `ip` = '" . $this->db->escape($this->request->server['REMOTE_ADDR']) . "', `date_added` = NOW()");
	}
}