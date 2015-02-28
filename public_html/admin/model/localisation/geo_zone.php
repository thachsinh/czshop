<?php
class ModelLocalisationGeoZone extends Model {
	public $table = 'geo_zone';
	public $primaryKey = 'geo_zone_id';
	public $fields = array();
	public function addGeoZone($data) {
		//$this->db->query("INSERT INTO " . DB_PREFIX . "geo_zone SET name = '" . $this->db->escape($data['name']) . "', description = '" . $this->db->escape($data['description']) . "', date_added = NOW()");

		$tmp = $this->initData($data, TRUE);
		$this->db->set('date_added', 'NOW()', FALSE);
		$this->db->insert($this->table, $tmp);
		//$geo_zone_id = $this->db->getLastId();
		$geo_zone_id = $this->db->insert_id();
		
		if (isset($data['zone_to_geo_zone'])) {
			foreach ($data['zone_to_geo_zone'] as $value) {
				//$this->db->query("INSERT INTO " . DB_PREFIX . "zone_to_geo_zone SET country_id = '" . (int)$value['country_id'] . "', zone_id = '" . (int)$value['zone_id'] . "', geo_zone_id = '" . (int)$geo_zone_id . "', date_added = NOW()");
				$this->db->set('country_id', (int)$value['country_id']);
				$this->db->set('zone_id', (int)$value['zone_id']);
				$this->db->set('geo_zone_id', (int)$geo_zone_id);
				$this->db->set('date_added', 'NOW()', FALSE);
				$this->db->update($this->table);
			}
		}

		$this->cache->delete('geo_zone');

		$this->tracking->log(LOG_FUNCTION::$localisation_geo_zone, LOG_ACTION_ADD, $geo_zone_id);
	}

	public function editGeoZone($geo_zone_id, $data) {
		
		$this->db->set('name', $this->db->escape($data['name']));
		$this->db->set('description', $this->db->escape($data['description']));
		$this->db->set('date_modified', 'NOW()', FALSE);
		$this->db->where($this->primaryKey, (int)$geo_zone_id);
		$this->db->update($this->table);
		
		//$this->db->query("UPDATE " . DB_PREFIX . "geo_zone SET name = '" . $this->db->escape($data['name']) . "', description = '" . $this->db->escape($data['description']) . "', date_modified = NOW() WHERE geo_zone_id = '" . (int)$geo_zone_id . "'");

		//$this->db->query("DELETE FROM " . DB_PREFIX . "zone_to_geo_zone WHERE geo_zone_id = '" . (int)$geo_zone_id . "'");
		$this->db->where($this->primaryKey, (int)$geo_zone_id);
		$this->db->delete('zone_to_geo_zone');

		if (isset($data['zone_to_geo_zone'])) {
			foreach ($data['zone_to_geo_zone'] as $value) {
				//$this->db->query("INSERT INTO " . DB_PREFIX . "zone_to_geo_zone SET country_id = '" . (int)$value['country_id'] . "', zone_id = '" . (int)$value['zone_id'] . "', geo_zone_id = '" . (int)$geo_zone_id . "', date_added = NOW()");
				$this->db->set('country_id', (int)$value['country_id']);
				$this->db->set('zone_id', (int)$value['zone_id']);
				$this->db->set('geo_zone_id', (int)$geo_zone_id );
				$this->db->set('date_added', 'NOW()', FALSE);
				$this->db->insert('zone_to_geo_zone');
			}
		}

		$this->cache->delete('geo_zone');

		$this->tracking->log(LOG_FUNCTION::$localisation_geo_zone, LOG_ACTION_MODIFY, $geo_zone_id);
	}

	public function deleteGeoZone($geo_zone_id) {
		$this->db->where($this->primaryKey, (int)$geo_zone_id);
		$this->db->delete(array($this->table, 'zone_to_geo_zone'));
		//$this->db->query("DELETE FROM " . DB_PREFIX . "geo_zone WHERE geo_zone_id = '" . (int)$geo_zone_id . "'");
		//$this->db->query("DELETE FROM " . DB_PREFIX . "zone_to_geo_zone WHERE geo_zone_id = '" . (int)$geo_zone_id . "'");

		$this->cache->delete('geo_zone');

		$this->tracking->log(LOG_FUNCTION::$localisation_geo_zone, LOG_ACTION_DELETE, $geo_zone_id);
	}

	public function getGeoZone($geo_zone_id) {
		$this->db->distinct();
		$this->db->from($this->table);
		$this->db->where($this->primaryKey, (int)$geo_zone_id);
		//$query = $this->db->query("SELECT DISTINCT * FROM " . DB_PREFIX . "geo_zone WHERE geo_zone_id = '" . (int)$geo_zone_id . "'");

		//return $query->row;
		return $this->db->get()->row_array();
	}

	public function getGeoZones($data = array()) {
		if ($data) {
			$this->db->select('*');
			$this->db->from($this->table);
			//$sql = "SELECT * FROM " . DB_PREFIX . "geo_zone";

			$sort_data = array(
				'name',
				'description'
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
			$geo_zone_data = $this->cache->get('geo_zone');

			if (!$geo_zone_data) {
				$this->db->select('*');
				$this->db->from($this->table);
				$this->db->order_by('name', 'ASC');
				
				//$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "geo_zone ORDER BY name ASC");

				//$geo_zone_data = $query->rows;
				$geo_zone_data = $this->db->get()->result_array();

				$this->cache->set('geo_zone', $geo_zone_data);
			}

			return $geo_zone_data;
		}
	}

	public function getTotalGeoZones() {
		$this->db->select('COUNT(*) AS `total`');
		$query = $this->db->get($this->table)->row_array();
		return $query['total'];

		//$query = $this->db->query("SELECT COUNT(*) AS total FROM " . DB_PREFIX . "geo_zone");

		//return $query->row['total'];
	}

	public function getZoneToGeoZones($geo_zone_id) {
		$this->db->select('*');
		$this->db->from('zone_to_geo_zone');
		$this->db->where($this->primaryKey, (int)$geo_zone_id);
		return $this->db->get()->result_array();
		//$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "zone_to_geo_zone WHERE geo_zone_id = '" . (int)$geo_zone_id . "'");

		//return $query->rows;
	}

	public function getTotalZoneToGeoZoneByGeoZoneId($geo_zone_id) {
		$this->db->select('COUNT(*) AS `total`');
		$this->db->where($this->primaryKey, (int)$geo_zone_id);
		$query = $this->db->get('zone_to_geo_zone')->row_array();
		return $query['total'];
		//$query = $this->db->query("SELECT COUNT(*) AS total FROM " . DB_PREFIX . "zone_to_geo_zone WHERE geo_zone_id = '" . (int)$geo_zone_id . "'");

		//return $query->row['total'];
	}

	public function getTotalZoneToGeoZoneByCountryId($country_id) {
		$this->db->select('COUNT(*) AS `total`');
		$this->db->where('country_id', (int)$country_id);
		$query = $this->db->get('zone_to_geo_zone')->row_array();
		return $query['total'];
		//$query = $this->db->query("SELECT COUNT(*) AS total FROM " . DB_PREFIX . "zone_to_geo_zone WHERE country_id = '" . (int)$country_id . "'");

		//return $query->row['total'];
	}

	public function getTotalZoneToGeoZoneByZoneId($zone_id) {
		$this->db->select('COUNT(*) AS `total`');
		$this->db->where('zone_id', (int)$zone_id);
		$query = $this->db->get('zone_to_geo_zone')->row_array();
		return $query['total'];
		//$query = $this->db->query("SELECT COUNT(*) AS total FROM " . DB_PREFIX . "zone_to_geo_zone WHERE zone_id = '" . (int)$zone_id . "'");

		//return $query->row['total'];
	}
}