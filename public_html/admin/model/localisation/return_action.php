<?php
class ModelLocalisationReturnAction extends Model {
	public $table = 'return_action';
	public $primaryKey = 'return_action_id';
	public $fields = array();

	public function addReturnAction($data) {
		foreach ($data['return_action'] as $language_id => $value) {
			if (isset($return_action_id)) {
				$this->db->set($this->primaryKey, (int)$return_action_id);
				$this->db->set('language_id', (int)$language_id);
				$this->db->set('name', $this->db->escape($value['name']));
				$this->db->insert($this->table);
				//$this->db->query("INSERT INTO " . DB_PREFIX . "return_action SET return_action_id = '" . (int)$return_action_id . "', language_id = '" . (int)$language_id . "', name = '" . $this->db->escape($value['name']) . "'");
			} else {
				$this->db->set('language_id', (int)$language_id);
				$this->db->set('name', $this->db->escape($value['name']));
				$this->db->insert($this->table);
				//$this->db->query("INSERT INTO " . DB_PREFIX . "return_action SET language_id = '" . (int)$language_id . "', name = '" . $this->db->escape($value['name']) . "'");

				//$return_action_id = $this->db->getLastId();
				$return_action_id = $this->db->insert_id();
			}
		}

		$this->cache->delete('return_action');
	}

	public function editReturnAction($return_action_id, $data) {
		$this->db->where($this->primaryKey, (int)$return_action_id);
		$this->db->delete($this->table);

		//$this->db->query("DELETE FROM " . DB_PREFIX . "return_action WHERE return_action_id = '" . (int)$return_action_id . "'");

		foreach ($data['return_action'] as $language_id => $value) {
			$this->db->set($this->primaryKey, (int)$return_action_id);
			$this->db->set('language_id', (int)$language_id);
			$this->db->set('name', $this->db->escape($value['name']));
			$this->db->insert($this->table);
			//$this->db->query("INSERT INTO " . DB_PREFIX . "return_action SET return_action_id = '" . (int)$return_action_id . "', language_id = '" . (int)$language_id . "', name = '" . $this->db->escape($value['name']) . "'");
		}

		$this->cache->delete('return_action');
	}

	public function deleteReturnAction($return_action_id) {
		$this->db->where($this->primaryKey, (int)$return_action_id);
		$this->db->delete($this->table);
		//$this->db->query("DELETE FROM " . DB_PREFIX . "return_action WHERE return_action_id = '" . (int)$return_action_id . "'");

		$this->cache->delete('return_action');
	}

	public function getReturnAction($return_action_id) {
		$this->db->select('*');
		$this->db->where($this->primaryKey, (int)$return_action_id);
		$this->db->where('language_id', (int)$this->config->get('config_language_id'));

		//$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "return_action WHERE return_action_id = '" . (int)$return_action_id . "' AND language_id = '" . (int)$this->config->get('config_language_id') . "'");

		//return $query->row;
		$this->db->get()->row_array();
	}

	public function getReturnActions($data = array()) {
		if ($data) {
			$this->db->select('*');
			$this->db->from($this->table);
			$this->db->where('language_id', (int)$this->config->get('config_language_id'));

			//$sql = "SELECT * FROM " . DB_PREFIX . "return_action WHERE language_id = '" . (int)$this->config->get('config_language_id') . "'";

			//$sql .= " ORDER BY name";

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

			//return $query->rows;
			return $this->db->get()->result_array();
		} else {
			$return_action_data = $this->cache->get('return_action.' . (int)$this->config->get('config_language_id'));

			if (!$return_action_data) {
				$this->db->select('*');
				$this->db->from($this->table);
				$this->db->where('language_id', (int)$this->config->get('config_language_id'));

				//$query = $this->db->query("SELECT return_action_id, name FROM " . DB_PREFIX . "return_action WHERE language_id = '" . (int)$this->config->get('config_language_id') . "' ORDER BY name");

				//$return_action_data = $query->rows;
				$return_action_data = $this->db->get()->row_array();

				$this->cache->set('return_action.' . (int)$this->config->get('config_language_id'), $return_action_data);
			}

			return $return_action_data;
		}
	}

	public function getReturnActionDescriptions($return_action_id) {
		$return_action_data = array();

		$this->db->select('*');
		$this->db->from($this->table);
		$this->db->where($this->primaryKey, (int)$return_action_id);
		//$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "return_action WHERE return_action_id = '" . (int)$return_action_id . "'");

		foreach ($this->db->get()->result_array() as $result) {
			$return_action_data[$result['language_id']] = array('name' => $result['name']);
		}

		return $return_action_data;
	}

	public function getTotalReturnActions() {
		$this->db->select('COUNT(*) AS `total`');
		$this->db->where('language_id', (int)$this->config->get('config_language_id'));
		$query = $this->db->get($this->table)->row_array();
		return $query['total'];
		//$query = $this->db->query("SELECT COUNT(*) AS total FROM " . DB_PREFIX . "return_action WHERE language_id = '" . (int)$this->config->get('config_language_id') . "'");

		//return $query->row['total'];
	}
}