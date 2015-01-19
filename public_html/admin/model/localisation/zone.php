<?php
class ModelLocalisationZone extends Model {
	public $table = 'zone';
	public $primaryKey = 'zone_id';
	public $fields = array();

	public function addZone($data) {
		$data = $this->initData($data, TRUE);
		$this->db->insert($this->table, $data);

		//$this->db->query("INSERT INTO " . DB_PREFIX . "zone SET status = '" . (int)$data['status'] . "', name = '" . $this->db->escape($data['name']) . "', code = '" . $this->db->escape($data['code']) . "', country_id = '" . (int)$data['country_id'] . "'");

		$this->cache->delete('zone');
	}

	public function editZone($zone_id, $data) {
		$data = $this->initData($data, TRUE);
		$this->db->where($this->primaryKey, (int)$zone_id);
		$this->db->update($this->table, $data);

		//$this->db->query("UPDATE " . DB_PREFIX . "zone SET status = '" . (int)$data['status'] . "', name = '" . $this->db->escape($data['name']) . "', code = '" . $this->db->escape($data['code']) . "', country_id = '" . (int)$data['country_id'] . "' WHERE zone_id = '" . (int)$zone_id . "'");

		$this->cache->delete('zone');
	}

	public function editStatus($zone_id, $status) {

		$this->db->set('status', (int) $status);
		$this->db->where($this->primaryKey, (int) $zone_id);
		$this->db->update($this->table);

		$this->cache->delete('zone');
	}

	public function deleteZone($zone_id) {
		$this->db->where($this->primaryKey, (int)$zone_id);
		$this->db->delete($this->table);
		//$this->db->query("DELETE FROM " . DB_PREFIX . "zone WHERE zone_id = '" . (int)$zone_id . "'");

		$this->cache->delete('zone');
	}

	public function getZone($zone_id) {
		$this->db->distinct();
		$this->db->from($this->table);
		$this->db->where($this->primaryKey, (int)$zone_id);
		return $this->db->get()->row_array();

		//$query = $this->db->query("SELECT DISTINCT * FROM " . DB_PREFIX . "zone WHERE zone_id = '" . (int)$zone_id . "'");

		//return $query->row;
	}

	public function getZones($data = array()) {

		$this->db->select('z.*, c.name AS country');
		$this->db->from($this->table . ' z');
		$this->db->join('country c', 'z.country_id = c.country_id', 'left');

		//$sql = "SELECT *, z.name, c.name AS country FROM " . DB_PREFIX . "zone z LEFT JOIN " . DB_PREFIX . "country c ON (z.country_id = c.country_id)";

		$sort_data = array(
			'c.name',
			'z.name',
			'z.code',
			'z.status'
		);

		$order = 'ASC';
		if (isset($data['order']) && ($data['order'] == 'DESC')) {
			$order = 'DESC';
		}

		if (isset($data['sort']) && in_array($data['sort'], $sort_data)) {
			//$sql .= " ORDER BY " . $data['sort'];
			$this->db->order_by($data['sort'], $order);
		} else {
			$this->db->order_by('c.name', $order);
			//$sql .= " ORDER BY c.name";
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
		//echo '<pre>'; print_r($this->db->get()->result_array()); die;

		return $this->db->get()->result_array();
	}

	public function getZonesByCountryId($country_id) {
		$zone_data = $this->cache->get('zone.' . (int)$country_id);

		if (!$zone_data) {
			$this->db->select('*');
			$this->db->from($this->table);
			$this->db->where('country_id', (int)$country_id);
			$this->db->where('status', 1);
			$this->db->order_by('name', 'ASC');
			$zone_data = $this->db->get()->result_array();

			//$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "zone WHERE country_id = '" . (int)$country_id . "' AND status = '1' ORDER BY name");

			//$zone_data = $query->rows;

			$this->cache->set('zone.' . (int)$country_id, $zone_data);
		}

		return $zone_data;
	}

	public function getTotalZones() {
		$this->db->select('COUNT(*) AS `total`');
		$query = $this->db->get($this->table)->row_array();
		return $query['total'];

		//$query = $this->db->query("SELECT COUNT(*) AS total FROM " . DB_PREFIX . "zone");

		//return $query->row['total'];
	}

	public function getTotalZonesByCountryId($country_id) {
		$this->db->select('COUNT(*) AS `total`');
		$this->db->where('country_id', (int)$country_id);
		$query = $this->db->get($this->table)->row_array();
		return $query['total'];

		//$query = $this->db->query("SELECT COUNT(*) AS total FROM " . DB_PREFIX . "zone WHERE country_id = '" . (int)$country_id . "'");

		//return $query->row['total'];
	}
}