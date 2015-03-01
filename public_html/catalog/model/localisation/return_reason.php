<?php
class ModelLocalisationReturnReason extends Model {
	public $table = 'return_reason';
	public $primaryKey = 'return_reason_id';

	public function addReturnReason($data) {
		foreach ($data['return_reason'] as $language_id => $value) {
			if (isset($return_reason_id)) {
				$this->db->set('return_reason_id', (int)$return_reason_id);
				$this->db->set('language_id', $language_id);
				$this->db->set('name', $value['name']);
				$this->db->insert($this->table);
				//$this->db->query("INSERT INTO " . DB_PREFIX . "return_reason SET return_reason_id = '" . (int)$return_reason_id . "', language_id = '" . (int)$language_id . "', name = '" . $this->db->escape($value['name']) . "'");
			} else {
				$this->db->set('language_id', $language_id);
				$this->db->set('name', $value['name']);
				$this->db->insert($this->table);

				//$this->db->query("INSERT INTO " . DB_PREFIX . "return_reason SET language_id = '" . (int)$language_id . "', name = '" . $this->db->escape($value['name']) . "'");

				$return_reason_id = $this->db->insert_id();
			}
		}

		$this->cache->delete('return_reason');
	}

	public function editReturnReason($return_reason_id, $data) {
		$this->db->where($this->primaryKey, (int)$return_reason_id);
		$this->db->delete($this->table);

		//$this->db->query("DELETE FROM " . DB_PREFIX . "return_reason WHERE return_reason_id = '" . (int)$return_reason_id . "'");

		foreach ($data['return_reason'] as $language_id => $value) {
			$this->db->set('return_reason_id', (int)$return_reason_id);
			$this->db->set('language_id', $language_id);
			$this->db->set('name', $value['name']);
			$this->db->insert($this->table);

			//$this->db->query("INSERT INTO " . DB_PREFIX . "return_reason SET return_reason_id = '" . (int)$return_reason_id . "', language_id = '" . (int)$language_id . "', name = '" . $this->db->escape($value['name']) . "'");
		}

		$this->cache->delete('return_reason');
	}

	public function deleteReturnReason($return_reason_id) {

		$this->db->where($this->primaryKey, (int)$return_reason_id);
		$this->db->delete($this->table);

		//$this->db->query("DELETE FROM " . DB_PREFIX . "return_reason WHERE return_reason_id = '" . (int)$return_reason_id . "'");

		$this->cache->delete('return_reason');
	}

	public function getReturnReason($return_reason_id) {
		$this->db->select('*')
			->from($this->table)
			->where($this->primaryKey, (int)$return_reason_id)
			->where('language_id', (int)$this->config->get('config_language_id'));
		return $this->db->get()->row_array();

		//$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "return_reason WHERE return_reason_id = '" . (int)$return_reason_id . "' AND language_id = '" . (int)$this->config->get('config_language_id') . "'");

		//return $query->row;
	}

	public function getReturnReasons($data = array()) {
		if ($data) {
			$this->db->select('*')
				->from($this->table)
				->where('language_id', (int)$this->config->get('config_language_id'));

			//$sql = "SELECT * FROM " . DB_PREFIX . "return_reason WHERE language_id = '" . (int)$this->config->get('config_language_id') . "'";

			//$sql .= " ORDER BY name";

			if (isset($data['return']) && ($data['return'] == 'DESC')) {
				$this->db->order_by('name', 'DESC');
			} else {
				$this->db->order_by('name', 'ASC');
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

			//$query = $this->db->query($sql);

			//return $query->rows;
			return $this->db->get()->result_array();
		} else {
			$return_reason_data = $this->cache->get('return_reason.' . (int)$this->config->get('config_language_id'));

			if (!$return_reason_data) {
				$this->db->select('*')
					->from($this->table)
					->where('language_id', (int)$this->config->get('config_language_id'))
					->order_by('name', 'ASC');

				//$query = $this->db->query("SELECT return_reason_id, name FROM " . DB_PREFIX . "return_reason WHERE language_id = '" . (int)$this->config->get('config_language_id') . "' ORDER BY name");

				$return_reason_data = $this->db->get()->result_array();

				$this->cache->set('return_reason.' . (int)$this->config->get('config_language_id'), $return_reason_data);
			}

			return $return_reason_data;
		}
	}

	public function getReturnReasonDescriptions($return_reason_id) {
		$return_reason_data = array();

		$this->db->select('*')
			->from($this->table)
			->where($this->primaryKey, (int)$return_reason_id);

		//$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "return_reason WHERE return_reason_id = '" . (int)$return_reason_id . "'");

		foreach ($this->db->get()->result_array() as $result) {
			$return_reason_data[$result['language_id']] = array('name' => $result['name']);
		}

		return $return_reason_data;
	}

	public function getTotalReturnReasons() {
		$this->db->select('COUNT(*) AS total')
			->from($this->table)
			->where('language_id', (int)$this->config->get('config_language_id'));

		//$query = $this->db->query("SELECT COUNT(*) AS total FROM " . DB_PREFIX . "return_reason WHERE language_id = '" . (int)$this->config->get('config_language_id') . "'");

		//return $query->row['total'];
		$data = $this->db->get()->row_array();
		return $data['total'];
	}
}