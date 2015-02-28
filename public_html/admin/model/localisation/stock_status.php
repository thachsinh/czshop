<?php
class ModelLocalisationStockStatus extends Model {
	public $table = 'stock_status';
	public $primaryKey = 'stock_status_id';
	public $fields = array('stock_status_id', 'language_id', 'name');

	public function addStockStatus($data) {
		foreach ($data['stock_status'] as $language_id => $value) {
			if (isset($stock_status_id)) {
				//$this->db->query("INSERT INTO " . DB_PREFIX . "stock_status SET stock_status_id = '" . (int)$stock_status_id . "', language_id = '" . (int)$language_id . "', name = '" . $this->db->escape($value['name']) . "'");
				$this->db->set($this->primaryKey, (int)$stock_status_id);
				$this->db->set('language_id', (int)$language_id);
				$this->db->set('name', $this->db->escape($value['name']));
				$this->db->insert($this->table);
			} else {
				//$this->db->query("INSERT INTO " . DB_PREFIX . "stock_status SET language_id = '" . (int)$language_id . "', name = '" . $this->db->escape($value['name']) . "'");
				$this->db->set('language_id', (int)$language_id);
				$this->db->set('name', $this->db->escape($value['name']));
				$this->db->insert($this->table);
				$stock_status_id = $this->db->insert_id();
				//$stock_status_id = $this->db->getLastId();
			}
		}

		$this->cache->delete('stock_status');

		$this->tracking->log(LOG_FUNCTION::$localisation_stock_status, LOG_ACTION_ADD, $stock_status_id);
	}

	public function editStockStatus($stock_status_id, $data) {
		$this->db->where($this->primaryKey, (int)$stock_status_id);
		$this->db->delete($this->table);
		//$this->db->query("DELETE FROM " . DB_PREFIX . "stock_status WHERE stock_status_id = '" . (int)$stock_status_id . "'");

		foreach ($data['stock_status'] as $language_id => $value) {
			$this->db->set($this->primaryKey, (int)$stock_status_id);
			$this->db->set('language_id', (int)$language_id);
			$this->db->set('name', $this->db->escape($value['name']));
			$this->db->insert($this->table);
			//$this->db->query("INSERT INTO " . DB_PREFIX . "stock_status SET stock_status_id = '" . (int)$stock_status_id . "', language_id = '" . (int)$language_id . "', name = '" . $this->db->escape($value['name']) . "'");
		}

		$this->cache->delete('stock_status');

		$this->tracking->log(LOG_FUNCTION::$localisation_stock_status, LOG_ACTION_MODIFY, $stock_status_id);
	}

	public function deleteStockStatus($stock_status_id) {
		$this->db->where($this->primaryKey, (int)$stock_status_id);
		$this->db->delete($this->table);

		//$this->db->query("DELETE FROM " . DB_PREFIX . "stock_status WHERE stock_status_id = '" . (int)$stock_status_id . "'");

		$this->cache->delete('stock_status');

		$this->tracking->log(LOG_FUNCTION::$localisation_stock_status, LOG_ACTION_DELETE, $stock_status_id);
	}

	public function getStockStatus($stock_status_id) {
		$this->db->select('*');
		$this->db->where($this->primaryKey, (int)$stock_status_id);
		$this->db->where('language_id', (int)$this->config->get('config_language_id'));
		//$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "stock_status WHERE stock_status_id = '" . (int)$stock_status_id . "' AND language_id = '" . (int)$this->config->get('config_language_id') . "'");

		//return $query->row;
		return $this->db->get()->row_array();
	}

	public function getStockStatuses($data = array()) {
		if ($data) {

			$this->db->select('*');
			$this->db->from($this->table);
			$this->db->where('language_id', (int)$this->config->get('config_language_id'));

			$order = 'ASC';
			if (isset($data['order']) && ($data['order'] == 'DESC')) {
				$order = 'DESC';
			}

			/*$sql = "SELECT * FROM " . DB_PREFIX . "stock_status WHERE language_id = '" . (int)$this->config->get('config_language_id') . "'";

			$sql .= " ORDER BY name";

			if (isset($data['order']) && ($data['order'] == 'DESC')) {
				$sql .= " DESC";
			} else {
				$sql .= " ASC";
			}*/

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

			//$query = $this->db->query($sql);

			//return $query->rows;
		} else {
			$stock_status_data = $this->cache->get('stock_status.' . (int)$this->config->get('config_language_id'));

			if (!$stock_status_data) {
				//$query = $this->db->query("SELECT stock_status_id, name FROM " . DB_PREFIX . "stock_status WHERE language_id = '" . (int)$this->config->get('config_language_id') . "' ORDER BY name");

				$this->db->select('*');
				$this->db->from($this->table);
				$this->db->where('language_id', (int)$this->config->get('config_language_id'));
				$this->db->order_by('name', 'ASC');
				//$return_reason_data = $this->db->get()->result_array();

				//$stock_status_data = $query->rows;
				$stock_status_data = $this->db->get()->result_array();

				$this->cache->set('stock_status.' . (int)$this->config->get('config_language_id'), $stock_status_data);
			}

			return $stock_status_data;
		}
	}

	public function getStockStatusDescriptions($stock_status_id) {
		$stock_status_data = array();
		$this->db->select('*');
		$this->db->from($this->table);
		$this->db->where($this->primaryKey, (int)$stock_status_id);

		//$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "stock_status WHERE stock_status_id = '" . (int)$stock_status_id . "'");

		foreach ($this->db->get()->result_array() as $result) {
			$stock_status_data[$result['language_id']] = array('name' => $result['name']);
		}

		return $stock_status_data;
	}

	public function getTotalStockStatuses() {
		$this->db->select('COUNT(*) AS `total`');
		$this->db->where('language_id', (int)$this->config->get('config_language_id'));
		$query = $this->db->get($this->table)->row_array();
		return $query['total'];

		//$query = $this->db->query("SELECT COUNT(*) AS total FROM " . DB_PREFIX . "stock_status WHERE language_id = '" . (int)$this->config->get('config_language_id') . "'");

		//return $query->row['total'];
	}
}