<?php
class ModelLocalisationOrderStatus extends Model {
	public $table = 'order_status';
	public $primaryKey = 'order_status_id';
	public $fields = array('order_status_id', 'language_id', 'name');

	public function addOrderStatus($data) {
		foreach ($data['order_status'] as $language_id => $value) {
			if (isset($order_status_id)) {
				$this->db->set($this->primaryKey, (int)$order_status_id);
				$this->db->set('language_id', (int)$language_id);
				$this->db->set('name', $this->db->escape($value['name']));
				$this->db->insert($this->table);
				//$this->db->query("INSERT INTO " . DB_PREFIX . "order_status SET order_status_id = '" . (int)$order_status_id . "', language_id = '" . (int)$language_id . "', name = '" . $this->db->escape($value['name']) . "'");
			} else {
				$this->db->set('language_id', (int)$language_id);
				$this->db->set('name', $this->db->escape($value['name']));
				$this->db->insert($this->table);
				//$this->db->query("INSERT INTO " . DB_PREFIX . "order_status SET language_id = '" . (int)$language_id . "', name = '" . $this->db->escape($value['name']) . "'");

				//$order_status_id = $this->db->getLastId();
				$order_status_id = $this->db->insert_id();
			}
		}

		$this->cache->delete('order_status');
	}

	public function editOrderStatus($order_status_id, $data) {

		$this->db->where($this->primaryKey, (int)$order_status_id);
		$this->db->delete($this->table);
		//$this->db->query("DELETE FROM " . DB_PREFIX . "order_status WHERE order_status_id = '" . (int)$order_status_id . "'");

		foreach ($data['order_status'] as $language_id => $value) {
			$this->db->set($this->primaryKey, (int)$order_status_id);
			$this->db->set('language_id', (int)$language_id);
			$this->db->name('name', $this->db->escape($value['name']));
			$this->db->insert($this->table);

			//$this->db->query("INSERT INTO " . DB_PREFIX . "order_status SET order_status_id = '" . (int)$order_status_id . "', language_id = '" . (int)$language_id . "', name = '" . $this->db->escape($value['name']) . "'");
		}

		$this->cache->delete('order_status');
	}

	public function deleteOrderStatus($order_status_id) {

		$this->db->where($this->primaryKey, (int)$order_status_id);
		$this->db->delete($this->table);
		//$this->db->query("DELETE FROM " . DB_PREFIX . "order_status WHERE order_status_id = '" . (int)$order_status_id . "'");

		$this->cache->delete('order_status');
	}

	public function getOrderStatus($order_status_id) {
		$this->db->select('*');
		$this->db->from($this->table);
		$this->db->where($this->primaryKey, (int)$order_status_id);
		$this->db->where('language_id', (int)$this->config->get('config_language_id'));
		return $this->db->get()->row_array();
		//$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "order_status WHERE order_status_id = '" . (int)$order_status_id . "' AND language_id = '" . (int)$this->config->get('config_language_id') . "'");

		//return $query->row;
	}

	public function getOrderStatuses($data = array()) {
		if ($data) {
			$this->db->select('*');
			$this->db->from($this->table);
			$this->db->where('language_id', (int)$this->config->get('config_language_id'));
			//$sql = "SELECT * FROM " . DB_PREFIX . "order_status WHERE language_id = '" . (int)$this->config->get('config_language_id') . "'";

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
			$order_status_data = $this->cache->get('order_status.' . (int)$this->config->get('config_language_id'));

			if (!$order_status_data) {
				$this->db->select('order_status_id, name');
				$this->db->from($this->table);
				$this->db->where('language_id', (int)$this->config->get('config_language_id'));

				//$query = $this->db->query("SELECT order_status_id, name FROM " . DB_PREFIX . "order_status WHERE language_id = '" . (int)$this->config->get('config_language_id') . "' ORDER BY name");

				$order_status_data = $this->db->get()->result_array();

				$this->cache->set('order_status.' . (int)$this->config->get('config_language_id'), $order_status_data);
			}

			return $order_status_data;
		}
	}

	public function getOrderStatusDescriptions($order_status_id) {
		$order_status_data = array();

		$this->db->select('*');
		$this->db->from($this->table);
		$this->db->where($this->primaryKey, (int)$order_status_id);

		//$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "order_status WHERE order_status_id = '" . (int)$order_status_id . "'");

		foreach ($this->db->get()->result_array() as $result) {
			$order_status_data[$result['language_id']] = array('name' => $result['name']);
		}

		return $order_status_data;
	}

	public function getTotalOrderStatuses() {
		$this->db->select('COUNT(*) AS `total`');
		$this->db->where('language_id', (int)$this->config->get('config_language_id'));
		$query = $this->db->get($this->table)->row_array();
		return $query['total'];

		//$query = $this->db->query("SELECT COUNT(*) AS total FROM " . DB_PREFIX . "order_status WHERE language_id = '" . (int)$this->config->get('config_language_id') . "'");

		//return $query->row['total'];
	}
}