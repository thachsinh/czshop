<?php
class ModelLocalisationTaxRate extends Model {
	public $table = 'tax_rate';
	public $primaryKey = 'tax_rate_id';
	public $fields = array('tax_rate_id', 'geo_zone_id', 'name', 'rate', 'type', 'date_added', 'date_modified');

	public function addTaxRate($data) {
		$tmp = $this->initData($data);
		$this->db->set('date_added' , 'NOW()', FALSE);
		$this->db->set('date_modified' , 'NOW()', FALSE);
		$this->db->insert($this->table, $tmp);

		//$this->db->query("INSERT INTO " . DB_PREFIX . "tax_rate SET name = '" . $this->db->escape($data['name']) . "', rate = '" . (float)$data['rate'] . "', `type` = '" . $this->db->escape($data['type']) . "', geo_zone_id = '" . (int)$data['geo_zone_id'] . "', date_added = NOW(), date_modified = NOW()");

		//$tax_rate_id = $this->db->getLastId();
		$tax_rate_id = $this->db->insert_id();

		if (isset($data['tax_rate_customer_group'])) {
			foreach ($data['tax_rate_customer_group'] as $customer_group_id) {
				$this->db->set($this->primaryKey, $tax_rate_id);
				$this->db->set('customer_group_id', (int)$customer_group_id);
				$this->db->insert('tax_rate_to_customer_group');
				//$this->db->query("INSERT INTO " . DB_PREFIX . "tax_rate_to_customer_group SET tax_rate_id = '" . (int)$tax_rate_id . "', customer_group_id = '" . (int)$customer_group_id . "'");
			}
		}
	}

	public function editTaxRate($tax_rate_id, $data) {
		$tmp = $this->initData($data, TRUE);
		$this->db->where($this->primaryKey, (int)$tax_rate_id);
		$this->db->set('date_modified', 'NOW()', FALSE);
		$this->db->update($this->table, $tmp);

		//$this->db->query("UPDATE " . DB_PREFIX . "tax_rate SET name = '" . $this->db->escape($data['name']) . "', rate = '" . (float)$data['rate'] . "', `type` = '" . $this->db->escape($data['type']) . "', geo_zone_id = '" . (int)$data['geo_zone_id'] . "', date_modified = NOW() WHERE tax_rate_id = '" . (int)$tax_rate_id . "'");

		$this->db->where($this->primaryKey, (int)$tax_rate_id);
		$this->db->delete('tax_rate_to_customer_group');

		//$this->db->query("DELETE FROM " . DB_PREFIX . "tax_rate_to_customer_group WHERE tax_rate_id = '" . (int)$tax_rate_id . "'");

		if (isset($data['tax_rate_customer_group'])) {
			foreach ($data['tax_rate_customer_group'] as $customer_group_id) {
				$this->db->set($this->primaryKey, (int)$tax_rate_id);
				$this->db->set('customer_group_id', (int)$customer_group_id);
				$this->db->insert('tax_rate_to_customer_group');

				//$this->db->query("INSERT INTO " . DB_PREFIX . "tax_rate_to_customer_group SET tax_rate_id = '" . (int)$tax_rate_id . "', customer_group_id = '" . (int)$customer_group_id . "'");
			}
		}
	}

	public function deleteTaxRate($tax_rate_id) {
		$this->db->where($this->primaryKey, (int)$tax_rate_id);
		$this->db->delete(array($this->table, 'tax_rate_to_customer_group'));
		
		//$this->db->query("DELETE FROM " . DB_PREFIX . "tax_rate WHERE tax_rate_id = '" . (int)$tax_rate_id . "'");
		//$this->db->query("DELETE FROM " . DB_PREFIX . "tax_rate_to_customer_group WHERE tax_rate_id = '" . (int)$tax_rate_id . "'");
	}

	public function getTaxRate($tax_rate_id) {
		$this->db->select('tr.tax_rate_id, tr.name AS name, tr.rate, tr.type, tr.geo_zone_id, gz.name AS geo_zone, tr.date_added, tr.date_modified');
		$this->db->from($this->table . ' tr');
		$this->db->join('geo_zone gz', 'tr.geo_zone_id = gz.geo_zone_id', 'left');
		$this->db->where('tr.tax_rate_id', (int)$tax_rate_id);
		//$query = $this->db->query("SELECT tr.tax_rate_id, tr.name AS name, tr.rate, tr.type, tr.geo_zone_id, gz.name AS geo_zone, tr.date_added, tr.date_modified FROM " . DB_PREFIX . "tax_rate tr LEFT JOIN " . DB_PREFIX . "geo_zone gz ON (tr.geo_zone_id = gz.geo_zone_id) WHERE tr.tax_rate_id = '" . (int)$tax_rate_id . "'");

		//return $query->row;
		$this->db->get()->row_array();
	}

	public function getTaxRates($data = array()) {
		$this->db->select('tr.tax_rate_id, tr.name AS name, tr.rate, tr.type, gz.name AS geo_zone, tr.date_added, tr.date_modified');
		$this->db->from($this->table . ' tr');
		$this->db->join('geo_zone gz', 'tr.geo_zone_id = gz.geo_zone_id', 'left');

		//$sql = "SELECT tr.tax_rate_id, tr.name AS name, tr.rate, tr.type, gz.name AS geo_zone, tr.date_added, tr.date_modified FROM " . DB_PREFIX . "tax_rate tr LEFT JOIN " . DB_PREFIX . "geo_zone gz ON (tr.geo_zone_id = gz.geo_zone_id)";

		$sort_data = array(
			'tr.name',
			'tr.rate',
			'tr.type',
			'gz.name',
			'tr.date_added',
			'tr.date_modified'
		);

		$order = 'ASC';
		if (isset($data['order']) && ($data['order'] == 'DESC')) {
			$order = 'DESC';
		}


		if (isset($data['sort']) && in_array($data['sort'], $sort_data)) {
			//$sql .= " ORDER BY " . $data['sort'];
			$this->db->order_by($data['sort'], $order);
		} else {
			//$sql .= " ORDER BY tr.name";
			$this->db->order_by('tr.name', $order);
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
	}

	public function getTaxRateCustomerGroups($tax_rate_id) {
		$tax_customer_group_data = array();

		$this->db->select('*');
		$this->db->from('tax_rate_to_customer_group');
		$this->db->where('tax_rate_id', (int)$tax_rate_id);

		//$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "tax_rate_to_customer_group WHERE tax_rate_id = '" . (int)$tax_rate_id . "'");

		foreach ($this->db->get()->result_array() as $result) {
			$tax_customer_group_data[] = $result['customer_group_id'];
		}

		return $tax_customer_group_data;
	}

	public function getTotalTaxRates() {
		$this->db->select('COUNT(*) AS `total`');
		$query = $this->db->get($this->table)->row_array();
		return $query['total'];
		//$query = $this->db->query("SELECT COUNT(*) AS total FROM " . DB_PREFIX . "tax_rate");

		//return $query->row['total'];
	}

	public function getTotalTaxRatesByGeoZoneId($geo_zone_id) {
		$this->db->select('COUNT(*) AS `total`');
		$this->db->where('geo_zone_id', (int)$geo_zone_id);
		$query = $this->db->get($this->table)->row_array();
		return $query['total'];

		//$query = $this->db->query("SELECT COUNT(*) AS total FROM " . DB_PREFIX . "tax_rate WHERE geo_zone_id = '" . (int)$geo_zone_id . "'");

		//return $query->row['total'];
	}
}