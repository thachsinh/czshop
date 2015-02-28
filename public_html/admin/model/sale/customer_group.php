<?php
class ModelSaleCustomerGroup extends Model {
	public $table = 'customer_group';
	public $primaryKey = 'customer_group_id';
	public $fields = array('customer_group_id', 'approval', 'sort_order');
	public $desc_fields = array('customer_group_id', 'language_id', 'name', 'description');

	public function addCustomerGroup($data) {
		$tmp = $this->initData($data, TRUE);
		$this->db->insert($this->table, $tmp);

		//$this->db->query("INSERT INTO " . DB_PREFIX . "customer_group SET approval = '" . (int)$data['approval'] . "', sort_order = '" . (int)$data['sort_order'] . "'");

		//$customer_group_id = $this->db->getLastId();
		$customer_group_id = $this->db->insert_id();

		foreach ($data['customer_group_description'] as $language_id => $value) {
			$tmp = $this->initData($data['customer_group_description'][$language_id], TRUE, $this->desc_fields);
			$this->db->set($this->primaryKey, $customer_group_id);
			$this->db->set('language_id', (int)$language_id);
			$this->db->insert('customer_group_description', $tmp);
			//$this->db->query("INSERT INTO " . DB_PREFIX . "customer_group_description SET customer_group_id = '" . (int)$customer_group_id . "', language_id = '" . (int)$language_id . "', name = '" . $this->db->escape($value['name']) . "', description = '" . $this->db->escape($value['description']) . "'");
		}

		$this->tracking->log(LOG_FUNCTION::$sale_customer_group, LOG_ACTION_ADD, $customer_group_id);
	}

	public function editCustomerGroup($customer_group_id, $data) {
		$tmp = $this->initData($data, TRUE);
		$this->db->where($this->primaryKey, (int)$customer_group_id);
		$this->db->update($this->table, $tmp);

		//$this->db->query("UPDATE " . DB_PREFIX . "customer_group SET approval = '" . (int)$data['approval'] . "', sort_order = '" . (int)$data['sort_order'] . "' WHERE customer_group_id = '" . (int)$customer_group_id . "'");

		//$this->db->query("DELETE FROM " . DB_PREFIX . "customer_group_description WHERE customer_group_id = '" . (int)$customer_group_id . "'");
		$this->db->where($this->primaryKey, (int)$customer_group_id);
		$this->db->delete('customer_group_description');

		foreach ($data['customer_group_description'] as $language_id => $value) {
			$tmp = $this->initData($data['customer_group_description'][$language_id], TRUE, $this->desc_fields);
			$this->db->set($this->primaryKey, $customer_group_id);
			$this->db->set('language_id', (int)$language_id);
			$this->db->insert('customer_group_description', $tmp);

			//$this->db->query("INSERT INTO " . DB_PREFIX . "customer_group_description SET customer_group_id = '" . (int)$customer_group_id . "', language_id = '" . (int)$language_id . "', name = '" . $this->db->escape($value['name']) . "', description = '" . $this->db->escape($value['description']) . "'");
		}

		$this->tracking->log(LOG_FUNCTION::$sale_customer_group, LOG_ACTION_MODIFY, $customer_group_id);
	}

	public function deleteCustomerGroup($customer_group_id) {
		$this->db->where($this->primaryKey, (int)$customer_group_id);
		$this->db->delete(array($this->table, 'customer_group_description', 'product_discount', 'product_special', 'product_reward'));
		/*
		$this->db->query("DELETE FROM " . DB_PREFIX . "customer_group WHERE customer_group_id = '" . (int)$customer_group_id . "'");
		$this->db->query("DELETE FROM " . DB_PREFIX . "customer_group_description WHERE customer_group_id = '" . (int)$customer_group_id . "'");
		$this->db->query("DELETE FROM " . DB_PREFIX . "product_discount WHERE customer_group_id = '" . (int)$customer_group_id . "'");
		$this->db->query("DELETE FROM " . DB_PREFIX . "product_special WHERE customer_group_id = '" . (int)$customer_group_id . "'");
		$this->db->query("DELETE FROM " . DB_PREFIX . "product_reward WHERE customer_group_id = '" . (int)$customer_group_id . "'");
		*/

		$this->tracking->log(LOG_FUNCTION::$sale_customer_group, LOG_ACTION_DELETE, $customer_group_id);
	}

	public function getCustomerGroup($customer_group_id) {
		$this->db->select('*');
		$this->db->from($this->table . ' cg');
		$this->db->join('customer_group_description cgd', 'cg.customer_group_id = cgd.customer_group_id', 'left');
		$this->db->where('cg.customer_group_id', (int)$customer_group_id);
		$this->db->where('cgd.language_id', (int)$this->config->get('config_language_id'));

		//$query = $this->db->query("SELECT DISTINCT * FROM " . DB_PREFIX . "customer_group cg LEFT JOIN " . DB_PREFIX . "customer_group_description cgd ON (cg.customer_group_id = cgd.customer_group_id) WHERE cg.customer_group_id = '" . (int)$customer_group_id . "' AND cgd.language_id = '" . (int)$this->config->get('config_language_id') . "'");

		//return $query->row;
		return $this->db->get()->row_array();
	}

	public function getCustomerGroups($data = array()) {
		$this->db->select('*');
		$this->db->from($this->table . ' cg');
		$this->db->join('customer_group_description cgd', 'cg.customer_group_id = cgd.customer_group_id', 'left');
		$this->db->where('cgd.language_id', (int)$this->config->get('config_language_id'));


		//$sql = "SELECT * FROM " . DB_PREFIX . "customer_group cg LEFT JOIN " . DB_PREFIX . "customer_group_description cgd ON (cg.customer_group_id = cgd.customer_group_id) WHERE cgd.language_id = '" . (int)$this->config->get('config_language_id') . "'";

		$sort_data = array(
			'cgd.name',
			'cg.sort_order'
		);

		$order = 'ASC';
		if (isset($data['order']) && ($data['order'] == 'DESC')) {
			$order = 'DESC';
		}

		if (isset($data['sort']) && in_array($data['sort'], $sort_data)) {
			//$sql .= " ORDER BY " . $data['sort'];
			$this->db->order_by($data['sort'], $order);
		} else {
			$this->db->order_by('cgd.name', $order);
			//$sql .= " ORDER BY cgd.name";
		}

		/*
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
	}

	public function getCustomerGroupDescriptions($customer_group_id) {
		$customer_group_data = array();

		$this->db->select('*');
		$this->db->from('customer_group_description');
		$this->db->where($this->primaryKey, (int)$customer_group_id);

		//$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "customer_group_description WHERE customer_group_id = '" . (int)$customer_group_id . "'");

		foreach ($this->db->get()->result_array() as $result) {
			$customer_group_data[$result['language_id']] = array(
				'name'        => $result['name'],
				'description' => $result['description']
			);
		}

		return $customer_group_data;
	}

	public function getTotalCustomerGroups() {
		$this->db->select('COUNT(*) AS `total`');
		$query = $this->db->get($this->table)->row_array();
		return $query['total'];

		//$query = $this->db->query("SELECT COUNT(*) AS total FROM " . DB_PREFIX . "customer_group");

		//return $query->row['total'];
	}
}