<?php
class ModelSettingSetting extends Model {
	public $table = 'setting';
	public function getSetting($code, $store_id = 0) {
		$data = array();

		$this->db->select('*')
			->from($this->table)
			->where('store_id', (int)$store_id)
			->where('code', $code);


		//$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "setting WHERE store_id = '" . (int)$store_id . "' AND `code` = '" . $this->db->escape($code) . "'");

		foreach ($this->db->get()->result_array() as $result) {
			if (!$result['serialized']) {
				$data[$result['key']] = $result['value'];
			} else {
				$data[$result['key']] = unserialize($result['value']);
			}
		}

		return $data;
	}
}