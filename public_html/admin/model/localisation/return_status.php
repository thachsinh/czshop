<?php
class ModelLocalisationReturnStatus extends Model {
	public $table = 'return_status';
	public $primaryKey = 'return_status_id';
	public $fields = array('return_status_id', 'language_id', 'name');

	public function addReturnStatus($data) {
		foreach ($data['return_status'] as $language_id => $value) {
			if (isset($return_status_id)) {
				$this->db->set($this->primaryKey, (int)$return_status_id);
				$this->db->set('language_id', (int)$language_id);
				$this->db->set('name', $this->db->escape($value['name']));
				$this->db->insert($this->table);

				//$this->db->query("INSERT INTO " . DB_PREFIX . "return_status SET return_status_id = '" . (int)$return_status_id . "', language_id = '" . (int)$language_id . "', name = '" . $this->db->escape($value['name']) . "'");
			} else {
				//$this->db->query("INSERT INTO " . DB_PREFIX . "return_status SET language_id = '" . (int)$language_id . "', name = '" . $this->db->escape($value['name']) . "'");

				//$return_status_id = $this->db->getLastId();
				$this->db->set('language_id', (int)$language_id);
				$this->db->set('name', $this->db->escape($value['name']));
				$this->db->insert($this->table);
				$return_status_id = $this->db->insert_id();
			}
		}

		$this->cache->delete('return_status');

		$this->tracking->log(LOG_FUNCTION::$localisation_return_status, LOG_ACTION_ADD, $return_status_id);
	}

	public function editReturnStatus($return_status_id, $data) {
		$this->db->where($this->primaryKey, (int)$return_status_id);
		$this->db->delete($this->table);
		//$this->db->query("DELETE FROM " . DB_PREFIX . "return_status WHERE return_status_id = '" . (int)$return_status_id . "'");

		foreach ($data['return_status'] as $language_id => $value) {
			$this->db->set($this->primaryKey, (int)$return_status_id);
			$this->db->set('language_id', (int)$language_id);
			$this->db->set('name', $this->db->escape($value['name']));
			$this->db->insert($this->table);

			//$this->db->query("INSERT INTO " . DB_PREFIX . "return_status SET return_status_id = '" . (int)$return_status_id . "', language_id = '" . (int)$language_id . "', name = '" . $this->db->escape($value['name']) . "'");
		}

		$this->cache->delete('return_status');

		$this->tracking->log(LOG_FUNCTION::$localisation_return_status, LOG_ACTION_MODIFY, $return_status_id);
	}

	public function deleteReturnStatus($return_status_id) {
		$this->db->where($this->primaryKey, (int)$return_status_id);
		$this->db->delete($this->table);
		//$this->db->query("DELETE FROM " . DB_PREFIX . "return_status WHERE return_status_id = '" . (int)$return_status_id . "'");

		$this->cache->delete('return_status');

		$this->tracking->log(LOG_FUNCTION::$localisation_return_status, LOG_ACTION_DELETE, $return_status_id);
	}

	public function getReturnStatus($return_status_id) {
		//$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "return_status WHERE return_status_id = '" . (int)$return_status_id . "' AND language_id = '" . (int)$this->config->get('config_language_id') . "'");

		//return $query->row;

		$this->db->select('*');
		$this->db->where($this->primaryKey, (int)$return_status_id);
		$this->db->where('language_id', (int)$this->config->get('config_language_id'));
		return $this->db->get()->row_array();
	}

	public function getReturnStatuses($data = array()) {

		if ($data) {
			$this->db->select('*');
			$this->db->from($this->table);
			$this->db->where('language_id', (int)$this->config->get('config_language_id'));

			//$sql = "SELECT * FROM " . DB_PREFIX . "return_status WHERE language_id = '" . (int)$this->config->get('config_language_id') . "'";

			/*$sql .= " ORDER BY name";

			if (isset($data['order']) && ($data['order'] == 'DESC')) {
				$sql .= " DESC";
			} else {
				$sql .= " ASC";
			}*/



			$order = 'ASC';
			if (isset($data['order']) && ($data['order'] == 'DESC')) {
				$order = 'DESC';
			}

			$this->db->order_by('name', $order);



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

			//$query = $this->db->query($sql);

			return $this->db->get()->result_array();
			//return $query->rows;
		} else {
			$return_status_data = $this->cache->get('return_status.' . (int)$this->config->get('config_language_id'));

			if (!$return_status_data) {
				//$query = $this->db->query("SELECT return_status_id, name FROM " . DB_PREFIX . "return_status WHERE language_id = '" . (int)$this->config->get('config_language_id') . "' ORDER BY name");

				$this->db->select('return_status_id, name');
				$this->db->from($this->table);
				$this->db->where('language_id', (int)$this->config->get('config_language_id'));
				$this->db->order_by('name', 'ASC');
				$return_status_data = $this->db->get()->result_array();

				//$return_status_data = $query->rows;

				$this->cache->set('return_status.' . (int)$this->config->get('config_language_id'), $return_status_data);
			}

			return $return_status_data;
		}
	}

	public function getReturnStatusDescriptions($return_status_id) {
		$return_status_data = array();
		$this->db->select('*');
		$this->db->from($this->table);
		$this->db->where($this->primaryKey, (int)$return_status_id);

		//$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "return_status WHERE return_status_id = '" . (int)$return_status_id . "'");

		foreach ($this->db->get()->result_array() as $result) {
			$return_status_data[$result['language_id']] = array('name' => $result['name']);
		}

		return $return_status_data;
	}

	public function getTotalReturnStatuses() {
		$this->db->select('COUNT(*) AS `total`');
		$this->db->where('language_id', (int)$this->config->get('config_language_id'));
		$query = $this->db->get($this->table)->row_array();
		return $query['total'];
		//$query = $this->db->query("SELECT COUNT(*) AS total FROM " . DB_PREFIX . "return_status WHERE language_id = '" . (int)$this->config->get('config_language_id') . "'");

		//return $query->row['total'];
	}
}