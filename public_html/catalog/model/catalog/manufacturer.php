<?php
class ModelCatalogManufacturer extends Model {
	public $table = 'manufacturer';

	public function getManufacturer($manufacturer_id) {
		$this->db->select('*')
			->from($this->table)
			->where('manufacturer_id', (int)$manufacturer_id);

		return $this->db->get()->row_array();
		/*
		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "manufacturer m LEFT JOIN " . DB_PREFIX . "manufacturer_to_store m2s ON (m.manufacturer_id = m2s.manufacturer_id) WHERE m.manufacturer_id = '" . (int)$manufacturer_id . "' AND m2s.store_id = '" . (int)$this->config->get('config_store_id') . "'");

		return $query->row;*/
	}

	public function getManufacturers($data = array()) {
		if ($data) {
			$this->db->select('*')
				->from($this->table);

			//$sql = "SELECT * FROM " . DB_PREFIX . "manufacturer m LEFT JOIN " . DB_PREFIX . "manufacturer_to_store m2s ON (m.manufacturer_id = m2s.manufacturer_id) WHERE m2s.store_id = '" . (int)$this->config->get('config_store_id') . "'";

			$order = 'ASC';
			if (isset($data['order']) && ($data['order'] == 'DESC')) {
				$order = 'DESC';
			}

			$sort_data = array(
				'name',
				'sort_order'
			);

			if (isset($data['sort']) && in_array($data['sort'], $sort_data)) {
				//$sql .= " ORDER BY " . $data['sort'];
				$this->db->order_by($data['sort'], $order);
			} else {
				$this->db->order_by('name', $order);
			}

			if (isset($data['start']) || isset($data['limit'])) {
				if ($data['start'] < 0) {
					$data['start'] = 0;
				}

				if ($data['limit'] < 1) {
					$data['limit'] = 20;
				}

				$this->db->limit($data['limit'], $data['start']);

				//$sql .= " LIMIT " . (int)$data['start'] . "," . (int)$data['limit'];
			}

			return $this->db->get()->result_array();

		} else {
			//$manufacturer_data = $this->cache->get('manufacturer.' . (int)$this->config->get('config_store_id'));
			$manufacturer_data = $this->cache->get('manufacturer');

			if (!$manufacturer_data) {
				$this->db->select('*')
					->from($this->table)
					->order_by('name');

				//$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "manufacturer m LEFT JOIN " . DB_PREFIX . "manufacturer_to_store m2s ON (m.manufacturer_id = m2s.manufacturer_id) WHERE m2s.store_id = '" . (int)$this->config->get('config_store_id') . "' ORDER BY name");

				$manufacturer_data = $this->db->get()->result_array();

				$this->cache->set('manufacturer', $manufacturer_data);
			}

			return $manufacturer_data;
		}
	}
}