<?php
class ModelLocalisationCountry extends Model {
	public $table = 'country';
	public $primaryKey = 'country_id';
	public $fields = array('country_id', 'name', 'iso_code_2', 'iso_code_3', 'address_format', 'postcode_required', 'c');
	public function addCountry($data) {
		
		/*$this->db->query("INSERT INTO " . DB_PREFIX . "country SET name = '" . $this->db->escape($data['name']) . "', iso_code_2 = '" . $this->db->escape($data['iso_code_2']) . "', iso_code_3 = '" . $this->db->escape($data['iso_code_3']) . "', address_format = '" . $this->db->escape($data['address_format']) . "', postcode_required = '" . (int)$data['postcode_required'] . "', c = '" . (int)$data['status'] . "'");*/
		$data = $this->initData($data, TRUE);
		$this->db->insert($this->table, $data);

		$country_id = $this->db->insert_id();

		$this->cache->delete('country');

		$this->tracking->log(LOG_FUNCTION::$localisation_country, LOG_ACTION_ADD, $country_id);
	}

	public function editCountry($country_id, $data) {
		//$this->db->query("UPDATE " . DB_PREFIX . "country SET name = '" . $this->db->escape($data['name']) . "', iso_code_2 = '" . $this->db->escape($data['iso_code_2']) . "', iso_code_3 = '" . $this->db->escape($data['iso_code_3']) . "', address_format = '" . $this->db->escape($data['address_format']) . "', postcode_required = '" . (int)$data['postcode_required'] . "', status = '" . (int)$data['status'] . "' WHERE country_id = '" . (int)$country_id . "'");

		$data = $this->initData($data);
		$this->db->where($this->primaryKey, (int)$country_id);
		$this->db->update($this->table, $data);
		$this->cache->delete('country');

		$this->tracking->log(LOG_FUNCTION::$localisation_country, LOG_ACTION_MODIFY, $country_id);
	}

	public function editStatus($country_id, $status) {

		$this->db->set('status', (int) $status);
		$this->db->where($this->primaryKey, (int) $country_id);
		$this->db->update($this->table);

		$this->tracking->log(LOG_FUNCTION::$localisation_country, LOG_ACTION_MODIFY, $country_id);
	}

	public function deleteCountry($country_id) {
		//$this->db->query("DELETE FROM " . DB_PREFIX . "country WHERE country_id = '" . (int)$country_id . "'");
		$this->db->where($this->primaryKey, (int)$country_id);
		$this->db->delete($this->table);

		$this->cache->delete('country');

		$this->tracking->log(LOG_FUNCTION::$localisation_country, LOG_ACTION_DELETE, $country_id);
	}

	public function getCountry($country_id) {
		//$query = $this->db->query("SELECT DISTINCT * FROM " . DB_PREFIX . "country WHERE country_id = '" . (int)$country_id . "'");
		$this->db->distinct();
		$this->db->where($this->primaryKey, (int)$country_id);
		return $this->db->get($this->table)->row_array();
		//return $query->row;
	}

	public function getCountries($data = array()) {

		if ($data) {
			$this->db->select('*');
			$this->db->from($this->table);

			$sort_data = array(
				'name',
				'iso_code_2',
				'iso_code_3',
				'status'
			);
			
			$order = 'ASC';
			if (isset($data['order']) && ($data['order'] == 'DESC')) {
				//$sql .= " DESC";
				$order = 'DESC';
			}

			if (isset($data['sort']) && in_array($data['sort'], $sort_data)) {
				//$sql .= " ORDER BY " . $data['sort'];
				$this->db->order_by($data['sort'], $order);
			} else {
				//$sql .= " ORDER BY name";
				$this->db->order_by('name', $order);
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
			$country_data = $this->cache->get('country');

			if (!$country_data) {
				$this->db->select('*');
				$this->db->order_by('name');
				
				//$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "country ORDER BY name ASC");

				//$country_data = $query->rows;
				$country_data = $this->db->get($this->table)->result_array();

				$this->cache->set('country', $country_data);
			}

			return $country_data;
		}
	}

	public function getTotalCountries() {
		$this->db->select('COUNT(*) AS `total`');
		$query = $this->db->get($this->table)->row_array();
		return $query['total'];
		//$query = $this->db->query("SELECT COUNT(*) AS total FROM " . DB_PREFIX . "country");

		//return $query->row['total'];
	}
}