<?php
class ModelLogicticsVehicle extends Model {
	public $table = 'vehicle';
	public $primaryKey = 'vehicle_id';
	public $fields = array('vehicle_id', 'vin', 'make', 'model', 'production_date', 'model_number', 'drive_type', 'chassis', 'engine', 'transmission', 'body', 'odometer', 'note', 'status');
	public function addVehicle($data) {
		$data = $this->initData($data, TRUE);
		$this->db->insert($this->table, $data);

		$vehicle_id = $this->db->insert_id();

		$this->cache->delete('vehicle');

		$this->tracking->log(LOG_FUNCTION::$logictics_vehicle, LOG_ACTION_ADD, $vehicle_id);
	}

	public function editVehicle($vehicle_id, $data) {
		$data = $this->initData($data);
		$this->db->where($this->primaryKey, (int)$vehicle_id);
		$this->db->update($this->table, $data);
		$this->cache->delete('vehicle');

		$this->tracking->log(LOG_FUNCTION::$logictics_vehicle, LOG_ACTION_MODIFY, $vehicle_id);
	}

	public function editStatus($vehicle_id, $status) {

		$this->db->set('status', (int) $status);
		$this->db->where($this->primaryKey, (int) $vehicle_id);
		$this->db->update($this->table);

		$this->tracking->log(LOG_FUNCTION::$logictics_vehicle, LOG_ACTION_MODIFY, $vehicle_id);
	}

	public function deleteVehicle($vehicle_id) {
		//$this->db->query("DELETE FROM " . DB_PREFIX . "vehicle WHERE vehicle_id = '" . (int)$vehicle_id . "'");
		$this->db->where($this->primaryKey, (int)$vehicle_id);
		$this->db->delete($this->table);

		$this->cache->delete('vehicle');

		$this->tracking->log(LOG_FUNCTION::$logictics_vehicle, LOG_ACTION_DELETE, $vehicle_id);
	}

	public function getVehicle($vehicle_id) {
		//$query = $this->db->query("SELECT DISTINCT * FROM " . DB_PREFIX . "vehicle WHERE vehicle_id = '" . (int)$vehicle_id . "'");
		$this->db->distinct();
		$this->db->where($this->primaryKey, (int)$vehicle_id);
		return $this->db->get($this->table)->row_array();
		//return $query->row;
	}

	public function getVehicles($data = array()) {

		if ($data) {
			$this->db->select('*');
			$this->db->from($this->table);

			$sort_data = array(
				'vin',
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
				//$sql .= " ORDER BY vin";
				$this->db->order_by('vin', $order);
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
			$vehicle_data = $this->cache->get('vehicle');

			if (!$vehicle_data) {
				$this->db->select('*');
				$this->db->order_by('vin');
				
				//$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "vehicle ORDER BY vin ASC");

				//$vehicle_data = $query->rows;
				$vehicle_data = $this->db->get($this->table)->result_array();

				$this->cache->set('vehicle', $vehicle_data);
			}

			return $vehicle_data;
		}
	}

	public function getTotalVehicles() {
		$this->db->select('COUNT(*) AS `total`');
		$query = $this->db->get($this->table)->row_array();
		return $query['total'];
		//$query = $this->db->query("SELECT COUNT(*) AS total FROM " . DB_PREFIX . "vehicle");

		//return $query->row['total'];
	}
}