<?php
class ModelLocalisationWeightClass extends Model {
	public $table = 'weight_class';
	public $primaryKey = 'weight_class_id';
	public $fields = array('weight_class_id', 'value');

	public function addWeightClass($data) {
		$this->db->set('value', (float)$data['value']);
		$this->db->insert($this->table);

		//$this->db->query("INSERT INTO " . DB_PREFIX . "weight_class SET value = '" . (float)$data['value'] . "'");

		//$weight_class_id = $this->db->getLastId();
		$weight_class_id = $this->db->insert_id();

		foreach ($data['weight_class_description'] as $language_id => $value) {
			$this->db->set($this->primaryKey, $weight_class_id);
			$this->db->set('language_id', (int)$language_id);
			$this->db->set('title', $this->db->escape($value['title']));
			$this->db->set('unit', $this->db->escape($value['unit']));
			$this->db->insert('weight_class_description');
			//$this->db->query("INSERT INTO " . DB_PREFIX . "weight_class_description SET weight_class_id = '" . (int)$weight_class_id . "', language_id = '" . (int)$language_id . "', title = '" . $this->db->escape($value['title']) . "', unit = '" . $this->db->escape($value['unit']) . "'");
		}

		$this->cache->delete('weight_class');

		$this->tracking->log(LOG_FUNCTION::$localisation_weight_class, LOG_ACTION_ADD, $weight_class_id);
	}

	public function editWeightClass($weight_class_id, $data) {
		$tmp = $this->initData($data, TRUE);
		$this->db->where($this->primaryKey, (int)$weight_class_id);
		$this->db->update($this->table, $tmp);

		//$this->db->query("UPDATE " . DB_PREFIX . "weight_class SET value = '" . (float)$data['value'] . "' WHERE weight_class_id = '" . (int)$weight_class_id . "'");

		$this->db->where($this->primaryKey, (int)$weight_class_id);
		$this->db->delete('weight_class_description');

		//$this->db->query("DELETE FROM " . DB_PREFIX . "weight_class_description WHERE weight_class_id = '" . (int)$weight_class_id . "'");

		foreach ($data['weight_class_description'] as $language_id => $value) {
			$this->db->set($this->primaryKey, (int)$weight_class_id);
			$this->db->set('language_id', (int)$language_id);
			$this->db->set('title', $this->db->escape($value['title']));
			$this->db->set('unit', $this->db->escape($value['unit']));

			//$this->db->query("INSERT INTO " . DB_PREFIX . "weight_class_description SET weight_class_id = '" . (int)$weight_class_id . "', language_id = '" . (int)$language_id . "', title = '" . $this->db->escape($value['title']) . "', unit = '" . $this->db->escape($value['unit']) . "'");
		}

		$this->cache->delete('weight_class');

		$this->tracking->log(LOG_FUNCTION::$localisation_weight_class, LOG_ACTION_MODIFY, $weight_class_id);
	}

	public function deleteWeightClass($weight_class_id) {
		$this->db->where($this->primaryKey, (int)$weight_class_id);
		$this->db->delete(array($this->table, 'weight_class_description'));

		//$this->db->query("DELETE FROM " . DB_PREFIX . "weight_class WHERE weight_class_id = '" . (int)$weight_class_id . "'");
		//$this->db->query("DELETE FROM " . DB_PREFIX . "weight_class_description WHERE weight_class_id = '" . (int)$weight_class_id . "'");

		$this->cache->delete('weight_class');

		$this->tracking->log(LOG_FUNCTION::$localisation_weight_class, LOG_ACTION_DELETE, $weight_class_id);
	}

	public function getWeightClasses($data = array()) {
		if ($data) {

			$this->db->select('*');
				$this->db->from($this->table . ' wc');
				$this->db->join('weight_class_description wcd', 'wc.weight_class_id = wcd.weight_class_id', 'left');
			$this->db->where('wcd.language_id', (int)$this->config->get('config_language_id'));
				
			//$sql = "SELECT * FROM " . DB_PREFIX . "weight_class wc LEFT JOIN " . DB_PREFIX . "weight_class_description wcd ON (wc.weight_class_id = wcd.weight_class_id) WHERE wcd.language_id = '" . (int)$this->config->get('config_language_id') . "'";

			$sort_data = array(
				'title',
				'unit',
				'value'
			);

			$order = 'ASC';
			if (isset($data['order']) && ($data['order'] == 'DESC')) {
				$order = 'DESC';
			}

			if (isset($data['sort']) && in_array($data['sort'], $sort_data)) {
				//$sql .= " ORDER BY " . $data['sort'];
				$this->db->order_by($data['sort'], $order);
			} else {
				//$sql .= " ORDER BY title";
				$this->db->order_by('title', $order);
			}

			/*if (isset($data['order']) && ($data['order'] == 'DESC')) {
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

				//$sql .= " LIMIT " . (int)$data['start'] . "," . (int)$data['limit'];
				$this->db->limit($data['limit'], $data['start']);
			}

			//$query = $this->db->query($sql);

			//return $query->rows;
			return $this->db->get()->result_array();
		} else {
			$weight_class_data = $this->cache->get('weight_class.' . (int)$this->config->get('config_language_id'));

			if (!$weight_class_data) {
				$this->db->select('*');
				$this->db->from($this->table . ' wc');
				$this->db->join('weight_class_description wcd', 'wc.weight_class_id = wcd.weight_class_id', 'left');
				$this->db->where('wcd.language_id', (int)$this->config->get('config_language_id'));
				//$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "weight_class wc LEFT JOIN " . DB_PREFIX . "weight_class_description wcd ON (wc.weight_class_id = wcd.weight_class_id) WHERE wcd.language_id = '" . (int)$this->config->get('config_language_id') . "'");

				//$weight_class_data = $query->rows;
				$weight_class_data = $this->db->get()->result_array();

				$this->cache->set('weight_class.' . (int)$this->config->get('config_language_id'), $weight_class_data);
			}

			return $weight_class_data;
		}
	}

	public function getWeightClass($weight_class_id) {
		$this->db->select('*');
		$this->db->from($this->table . ' wc');
		$this->db->join('weight_class_description wcd', 'wc.weight_class_id = wcd.weight_class_id', 'left');
		$this->db->where('wc.weight_class_id', (int)$weight_class_id);
		$this->db->where('wcd.language_id', (int)$this->config->get('config_language_id'));
		return $this->db->get()->row_array();

		//$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "weight_class wc LEFT JOIN " . DB_PREFIX . "weight_class_description wcd ON (wc.weight_class_id = wcd.weight_class_id) WHERE wc.weight_class_id = '" . (int)$weight_class_id . "' AND wcd.language_id = '" . (int)$this->config->get('config_language_id') . "'");

		//return $query->row;
	}

	public function getWeightClassDescriptionByUnit($unit) {
		$this->db->select('*');
		$this->db->from('weight_class_description');
		$this->db->where('language_id', (int)$this->config->get('config_language_id'));

		//$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "weight_class_description WHERE unit = '" . $this->db->escape($unit) . "' AND language_id = '" . (int)$this->config->get('config_language_id') . "'");

		//return $query->row;
		return $this->db->get()->row_array();
	}

	public function getWeightClassDescriptions($weight_class_id) {
		$weight_class_data = array();

		$this->db->select('*');
		$this->db->from('weight_class_description');
		$this->db->where($this->primaryKey, (int)$weight_class_id);

		//$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "weight_class_description WHERE weight_class_id = '" . (int)$weight_class_id . "'");

		foreach ($this->db->get()->result_array() as $result) {
			$weight_class_data[$result['language_id']] = array(
				'title' => $result['title'],
				'unit'  => $result['unit']
			);
		}

		return $weight_class_data;
	}

	public function getTotalWeightClasses() {
		$this->db->select('COUNT(*) AS `total`');
		$query = $this->db->get($this->table)->row_array();
		return $query['total'];

		//$query = $this->db->query("SELECT COUNT(*) AS total FROM " . DB_PREFIX . "weight_class");

		//return $query->row['total'];
	}
}