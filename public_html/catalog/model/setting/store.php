<?php
class ModelSettingStore extends Model {
	public $table = 'store';

	public function getStores($data = array()) {
		$store_data = $this->cache->get('store');

		if (!$store_data) {
			$this->db->select('*')
				->from($this->table)
				->order_by('url', 'ASC');


			//$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "store ORDER BY url");

			$store_data = $this->db->get()->result_array();

			$this->cache->set('store', $store_data);
		}

		return $store_data;
	}
}