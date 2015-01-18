<?php
class ModelLocalisationTaxClass extends Model {
	public $table = 'tax_class';
	public $primaryKey = 'tax_class_id';
	public $fields = array();

	public function addTaxClass($data) {
		$tmp = $this->initData($data);
		$this->db->set('date_added' , 'NOW', FALSE);
		$this->db->insert($this->table, $tmp);

		//$this->db->query("INSERT INTO " . DB_PREFIX . "tax_class SET title = '" . $this->db->escape($data['title']) . "', description = '" . $this->db->escape($data['description']) . "', date_added = NOW()");

		//$tax_class_id = $this->db->getLastId();
		$tax_class_id = $this->db->insert_id();

		if (isset($data['tax_rule'])) {
			foreach ($data['tax_rule'] as $tax_rule) {
				$this->db->set($this->primaryKey, (int)$tax_class_id);
				$this->db->set('tax_rate_id', (int)$tax_rule['tax_rate_id']);
				$this->db->set('based', $this->db->escape($tax_rule['based']));
				$this->db->set('priority', (int)$tax_rule['priority']);
				$this->db->insert('tax_rule');
				//$this->db->query("INSERT INTO " . DB_PREFIX . "tax_rule SET tax_class_id = '" . (int)$tax_class_id . "', tax_rate_id = '" . (int)$tax_rule['tax_rate_id'] . "', based = '" . $this->db->escape($tax_rule['based']) . "', priority = '" . (int)$tax_rule['priority'] . "'");
			}
		}

		$this->cache->delete('tax_class');
	}

	public function editTaxClass($tax_class_id, $data) {
		$tmp = $this->initData($data);
		$this->db->where($this->primaryKey, (int)$tax_class_id);
		$this->db->update($this->table, $tmp);

		//$this->db->query("UPDATE " . DB_PREFIX . "tax_class SET title = '" . $this->db->escape($data['title']) . "', description = '" . $this->db->escape($data['description']) . "', date_modified = NOW() WHERE tax_class_id = '" . (int)$tax_class_id . "'");

		$this->db->where($this->primaryKey, (int)$tax_class_id);
		$this->db->delete('tax_rule');
		//$this->db->query("DELETE FROM " . DB_PREFIX . "tax_rule WHERE tax_class_id = '" . (int)$tax_class_id . "'");

		if (isset($data['tax_rule'])) {
			foreach ($data['tax_rule'] as $tax_rule) {
				$this->db->set($this->primaryKey, (int)$tax_class_id);
				$this->db->set('tax_rate_id', (int)$tax_rule['tax_rate_id']);
				$this->db->set('based', $this->db->escape($tax_rule['based']));
				$this->db->set('priority', (int)$tax_rule['priority']);
				$this->db->insert('tax_rule');
				//$this->db->query("INSERT INTO " . DB_PREFIX . "tax_rule SET tax_class_id = '" . (int)$tax_class_id . "', tax_rate_id = '" . (int)$tax_rule['tax_rate_id'] . "', based = '" . $this->db->escape($tax_rule['based']) . "', priority = '" . (int)$tax_rule['priority'] . "'");
			}
		}

		$this->cache->delete('tax_class');
	}

	public function deleteTaxClass($tax_class_id) {
		$this->db->where($this->primaryKey, (int)$tax_class_id);
		$this->db->from($this->table);
		$this->db->delete(array($this->table, 'tax_rule'));

		//$this->db->query("DELETE FROM " . DB_PREFIX . "tax_class WHERE tax_class_id = '" . (int)$tax_class_id . "'");
		//$this->db->query("DELETE FROM " . DB_PREFIX . "tax_rule WHERE tax_class_id = '" . (int)$tax_class_id . "'");

		$this->cache->delete('tax_class');
	}

	public function getTaxClass($tax_class_id) {
		$this->db->select('*');
		$this->db->from($this->table);
		$this->db->where($this->primaryKey, (int)$tax_class_id);
		return $this->db->get()->row_array();

		//$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "tax_class WHERE tax_class_id = '" . (int)$tax_class_id . "'");

		//return $query->row;
	}

	public function getTaxClasses($data = array()) {
		if ($data) {
			$this->db->select('*');
			$this->db->from($this->table);
			//$sql = "SELECT * FROM " . DB_PREFIX . "tax_class";

			$order = 'ASC';
			if (isset($data['order']) && ($data['order'] == 'DESC')) {
				$order = 'DESC';
			}
			$this->db->order_by('title', $order);

			/*$sql .= " ORDER BY title";

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

				//$sql .= " LIMIT " . (int)$data['start'] . "," . (int)$data['limit'];
				$this->db->limit($data['limit'], $data['start']);
			}

			//$query = $this->db->query($sql);

			//return $query->rows;
			return $this->db->get()->result_array();
		} else {
			$tax_class_data = $this->cache->get('tax_class');

			if (!$tax_class_data) {
				$this->db->select('*');
				$this->db->from($this->table);

				//$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "tax_class");

				//$tax_class_data = $query->rows;
				$tax_class_data = $this->db->get()->result_array();

				$this->cache->set('tax_class', $tax_class_data);
			}

			return $tax_class_data;
		}
	}

	public function getTotalTaxClasses() {
		$this->db->select('COUNT(*) AS `total`');
		$query = $this->db->get($this->table)->row_array();
		return $query['total'];
		//$query = $this->db->query("SELECT COUNT(*) AS total FROM " . DB_PREFIX . "tax_class");

		//return $query->row['total'];
	}

	public function getTaxRules($tax_class_id) {
		$this->db->select('*');
		$this->db->from('tax_rule');
		$this->db->where($this->primaryKey, (int)$tax_class_id);
		//$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "tax_rule WHERE tax_class_id = '" . (int)$tax_class_id . "'");

		//return $query->rows;
		return $this->db->get()->result_array();
	}

	public function getTotalTaxRulesByTaxRateId($tax_rate_id) {
		$this->db->select('COUNT(DISTINCT tax_class_id) AS `total`');
		$this->db->where('tax_rate_id', (int)$tax_rate_id);
		$query = $this->db->get($this->table)->row_array();
		return $query['total'];
		//$query = $this->db->query("SELECT COUNT(DISTINCT tax_class_id) AS total FROM " . DB_PREFIX . "tax_rule WHERE tax_rate_id = '" . (int)$tax_rate_id . "'");

		//return $query->row['total'];
	}
}