<?php
class ModelLogicticsDriver extends Model {
	public $table = 'driver';
	public $primaryKey = 'driver_id';
	public $fields = array('driver_id', 'name', 'birthday', 'gender', 'address', 'driving_status', 'driving_licence_number', 'licence_valid_from', 'licence_valid_to', 'note', 'status');
	public function addDriver($data) {
		$data = $this->initData($data, TRUE);
		$this->db->insert($this->table, $data);

		$this->cache->delete('driver');
	}

	public function editDriver($driver_id, $data) {
		$data = $this->initData($data);
		$this->db->where($this->primaryKey, (int)$driver_id);
		$this->db->update($this->table, $data);
		$this->cache->delete('driver');
	}

	public function editStatus($driver_id, $status) {

		$this->db->set('status', (int) $status);
		$this->db->where($this->primaryKey, (int) $driver_id);
		$this->db->update($this->table);
	}

	public function deleteDriver($driver_id) {
		$this->db->where($this->primaryKey, (int)$driver_id);
		$this->db->delete($this->table);

		$this->cache->delete('driver');
	}

	public function getDriver($driver_id) {
		$this->db->distinct();
		$this->db->where($this->primaryKey, (int)$driver_id);
		return $this->db->get($this->table)->row_array();
	}

	public function getDrivers($data = array()) {

		if ($data) {
			$this->db->select('*');
			$this->db->from($this->table);

			$sort_data = array(
				'name',
			);
			
			$order = 'ASC';
			if (isset($data['order']) && ($data['order'] == 'DESC')) {
				//$sql .= " DESC";
				$order = 'DESC';
			}

			if (isset($data['sort']) && in_array($data['sort'], $sort_data)) {
				$this->db->order_by($data['sort'], $order);
			} else {
				$this->db->order_by('name', $order);
			}

			if (isset($data['start']) || isset($data['limit'])) {
				if ($data['start'] < 0) {
					$data['start'] = 0;
				}

				if ($data['limit'] < 1) {
					$data['limit'] = 20;
				}

				$this->db->limit($data['limit'], $data['start']);
			}
		
			return $this->db->get()->result_array();
		} else {
			$driver_data = $this->cache->get('driver');

			if (!$driver_data) {
				$this->db->select('*');
				$this->db->order_by('name');
				
				$driver_data = $this->db->get($this->table)->result_array();

				$this->cache->set('driver', $driver_data);
			}

			return $driver_data;
		}
	}

	public function getTotalDrivers() {
		$this->db->select('COUNT(*) AS `total`');
		$query = $this->db->get($this->table)->row_array();
		return $query['total'];
	}
}