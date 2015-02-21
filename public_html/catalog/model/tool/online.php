<?php
class ModelToolOnline extends Model {
	public $table = 'customer_online';

	public function whosonline($ip, $customer_id, $url, $referer) {
		$this->db->where('date_added <', date('Y-m-d H:i:s', strtotime('-1 hour')));
		$this->db->delete($this->table);

		//$this->db->query("DELETE FROM `" . DB_PREFIX . "customer_online` WHERE date_added < '" . date('Y-m-d H:i:s', strtotime('-1 hour')) . "'");

		$this->db->select('COUNT(*) AS total')
			->from($this->table)
			->where('ip', $ip);
		$data = $this->db->get()->row_array();

		//$query = $this->db->query("SELECT COUNT(*) AS total FROM `" . DB_PREFIX . "customer_online` WHERE `ip` = '" . $this->db->escape($ip) . "'");

		if (!$data['total']) {
			$this->db->query("REPLACE INTO `" . DB_PREFIX . "customer_online` SET `ip` = '" . $this->db->escape($ip) . "', `customer_id` = '" . (int)$customer_id . "', `url` = '" . $this->db->escape($url) . "', `referer` = '" . $this->db->escape($referer) . "', `date_added` = '" . $this->db->escape(date('Y-m-d H:i:s')) . "'");
		}
	}
}