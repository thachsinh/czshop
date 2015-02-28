<?php
class ModelLocalisationLengthClass extends Model {
	public $table = 'length_class';
	public $primaryKey = 'length_class_id';
	public $fields = array('length_class_id', 'value');

	public function addLengthClass($data) {
		$tmp = $this->initData($data, TRUE);
		$this->db->insert($this->table, $tmp);
		//$this->db->query("INSERT INTO " . DB_PREFIX . "length_class SET value = '" . (float)$data['value'] . "'");

		//$length_class_id = $this->db->getLastId();
		$length_class_id = $this->db->insert_id();

		foreach ($data['length_class_description'] as $language_id => $value) {
			$this->db->set($this->primaryKey, $length_class_id);
			$this->db->set('language_id', (int)$language_id);
			$this->db->set('title', $this->db->escape($value['title']));
			$this->db->set('unit', $this->db->escape($value['unit']));
			$this->db->insert('length_class_description');
			//$this->db->query("INSERT INTO " . DB_PREFIX . "length_class_description SET length_class_id = '" . (int)$length_class_id . "', language_id = '" . (int)$language_id . "', title = '" . $this->db->escape($value['title']) . "', unit = '" . $this->db->escape($value['unit']) . "'");
		}

		$this->cache->delete('length_class');

		$this->tracking->log(LOG_FUNCTION::$localisation_length_class, LOG_ACTION_ADD, $length_class_id);
	}

	public function editLengthClass($length_class_id, $data) {
		$tmp = $this->initData($data, TRUE);
		$this->db->where($this->primaryKey, (int)$length_class_id);
		$this->db->update($this->table, $tmp);
		//$this->db->query("UPDATE " . DB_PREFIX . "length_class SET value = '" . (float)$data['value'] . "' WHERE length_class_id = '" . (int)$length_class_id . "'");

		//$this->db->query("DELETE FROM " . DB_PREFIX . "length_class_description WHERE length_class_id = '" . (int)$length_class_id . "'");
		$this->db->where($this->primaryKey, (int)$length_class_id);
		$this->db->delete('length_class_description');

		foreach ($data['length_class_description'] as $language_id => $value) {
			$this->db->set($this->primaryKey, (int)$length_class_id);
			$this->db->set('language_id', (int)$language_id);
			$this->db->set('title', $this->db->escape($value['title']));
			$this->db->set('unit', $this->db->escape($value['unit']));
			$this->db->insert('length_class_description');
			//$this->db->query("INSERT INTO " . DB_PREFIX . "length_class_description SET length_class_id = '" . (int)$length_class_id . "', language_id = '" . (int)$language_id . "', title = '" . $this->db->escape($value['title']) . "', unit = '" . $this->db->escape($value['unit']) . "'");
		}

		$this->cache->delete('length_class');

		$this->tracking->log(LOG_FUNCTION::$localisation_length_class, LOG_ACTION_MODIFY, $length_class_id);
	}

	public function deleteLengthClass($length_class_id) {
		$this->db->where($this->primaryKey, (int)$length_class_id);
		$this->db->delete(array($this->table, 'length_class_description'));
		//$this->db->query("DELETE FROM " . DB_PREFIX . "length_class WHERE length_class_id = '" . (int)$length_class_id . "'");
		//$this->db->query("DELETE FROM " . DB_PREFIX . "length_class_description WHERE length_class_id = '" . (int)$length_class_id . "'");

		$this->cache->delete('length_class');

		$this->tracking->log(LOG_FUNCTION::$localisation_length_class, LOG_ACTION_DELETE, $length_class_id);
	}

	public function getLengthClasses($data = array()) {
		if ($data) {
			$this->db->select('*');
			$this->db->from('length_class lc');
			$this->db->join('length_class_description lcd', 'lc.length_class_id = lcd.length_class_id', 'left');
			$this->db->where('lcd.language_id', (int)$this->config->get('config_language_id'));

			//$sql = "SELECT * FROM " . DB_PREFIX . "length_class lc LEFT JOIN " . DB_PREFIX . "length_class_description lcd ON (lc.length_class_id = lcd.length_class_id) WHERE lcd.language_id = '" . (int)$this->config->get('config_language_id') . "'";

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
				$this->db->order_by('title', $order);
				//$sql .= " ORDER BY title";
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

				$this->db->limit($data['limit'], $data['start']);
				//$sql .= " LIMIT " . (int)$data['start'] . "," . (int)$data['limit'];
			}

			//$query = $this->db->query($sql);

			//return $query->rows;

			return $this->db->get()->result_array();
		} else {
			$length_class_data = $this->cache->get('length_class.' . (int)$this->config->get('config_language_id'));

			if (!$length_class_data) {
				$this->db->select('*');
				$this->db->from('length_class lc');
				$this->db->join('length_class_description lcd', 'lc.length_class_id = lcd.length_class_id', 'left');
				$this->db->where('lcd.language_id', (int)$this->config->get('config_language_id'));

				//$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "length_class lc LEFT JOIN " . DB_PREFIX . "length_class_description lcd ON (lc.length_class_id = lcd.length_class_id) WHERE lcd.language_id = '" . (int)$this->config->get('config_language_id') . "'");

				//$length_class_data = $query->rows;
				$length_class_data = $this->db->get()->result_array();

				$this->cache->set('length_class.' . (int)$this->config->get('config_language_id'), $length_class_data);
			}

			return $length_class_data;
		}
	}

	public function getLengthClass($length_class_id) {
		$this->db->select('*');
		$this->db->from('length_class lc');
		$this->db->join('length_class_description lcd', 'lc.length_class_id = lcd.length_class_id', 'left');
		$this->db->where('lc.length_class_id', (int)$length_class_id);
		$this->db->where('lcd.language_id', (int)$this->config->get('config_language_id'));

		return $this->db->get()->row_array();
		//$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "length_class lc LEFT JOIN " . DB_PREFIX . "length_class_description lcd ON (lc.length_class_id = lcd.length_class_id) WHERE lc.length_class_id = '" . (int)$length_class_id . "' AND lcd.language_id = '" . (int)$this->config->get('config_language_id') . "'");

		//return $query->row;
	}

	public function getLengthClassDescriptionByUnit($unit) {
		$this->db->select('*');
		$this->db->from('length_class_description');
		$this->db->where('unit', $this->db->escape($unit));
		$this->db->where('language_id', (int)$this->config->get('config_language_id'));

		return $this->db->get()->row_array();
		//$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "length_class_description WHERE unit = '" . $this->db->escape($unit) . "' AND language_id = '" . (int)$this->config->get('config_language_id') . "'");

		//return $query->row;
	}

	public function getLengthClassDescriptions($length_class_id) {
		$length_class_data = array();
		$this->db->select('*');
		$this->db->from('length_class_description');
		$this->db->where($this->primaryKey, (int)$length_class_id);
		$query = $this->db->get();

		//$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "length_class_description WHERE length_class_id = '" . (int)$length_class_id . "'");

		foreach ($query->result_array() as $result) {
			$length_class_data[$result['language_id']] = array(
				'title' => $result['title'],
				'unit'  => $result['unit']
			);
		}

		return $length_class_data;
	}

	public function getTotalLengthClasses() {
		$this->db->select('COUNT(*) AS `total`');
		$query = $this->db->get($this->table)->row_array();
		return $query['total'];

		//$query = $this->db->query("SELECT COUNT(*) AS total FROM " . DB_PREFIX . "length_class");

		//return $query->row['total'];
	}
}