<?php
class ModelCatalogRecurring extends Model {
	public $table = 'recurring';
	public $primaryKey = 'recurring_id';
	public $fields = array();

	public function addRecurring($data) {
		$this->event->trigger('pre.admin.recurring.add', $data);
		$tmp = $this->initData($data, TRUE);
		$tmp['price'] = (float)$data['price'];
		$this->db->insert($this->table, $tmp);

		//$this->db->query("INSERT INTO `" . DB_PREFIX . "recurring` SET `sort_order` = " . (int)$data['sort_order'] . ", `status` = " . (int)$data['status'] . ", `price` = " . (float)$data['price'] . ", `frequency` = '" . $this->db->escape($data['frequency']) . "', `duration` = " . (int)$data['duration'] . ", `cycle` = " . (int)$data['cycle'] . ", `trial_status` = " . (int)$data['trial_status'] . ", `trial_price` = " . (float)$data['trial_price'] . ", `trial_frequency` = '" . $this->db->escape($data['trial_frequency']) . "', `trial_duration` = " . (int)$data['trial_duration'] . ", `trial_cycle` = '" . (int)$data['trial_cycle'] . "'");

		//$recurring_id = $this->db->getLastId();
		$recurring_id = $this->db->insert_id();

		foreach ($data['recurring_description'] as $language_id => $recurring_description) {
			$this->db->set($this->primaryKey, $recurring_id);
			$this->db->set('language_id', (int)$language_id);
			$this->db->set('name', $recurring_description['name']);
			$this->db->insert('recurring_description');
			//$this->db->query("INSERT INTO `" . DB_PREFIX . "recurring_description` (`recurring_id`, `language_id`, `name`) VALUES (" . (int)$recurring_id . ", " . (int)$language_id . ", '" . $this->db->escape($recurring_description['name']) . "')");
		}

		$this->event->trigger('post.admin.recurring.add', $recurring_id);

		$this->tracking->log(LOG_FUNCTION::$catalog_recurring, LOG_ACTION_ADD, $recurring_id);

		return $recurring_id;
	}

	public function editStatus($recurring_id, $status) {
		$this->db->set('status', (int)$status);
		$this->db->where($this->primaryKey, (int)$recurring_id);
		$this->db->update($this->table);

		$this->tracking->log(LOG_FUNCTION::$catalog_recurring, LOG_ACTION_MODIFY, $recurring_id);
	}

	public function editRecurring($recurring_id, $data) {
		$this->event->trigger('pre.admin.recurring.edit', $data);

		$this->db->where($this->primaryKey, (int)$recurring_id);
		$this->db->delete('recurring_description');
		//$this->db->query("DELETE FROM `" . DB_PREFIX . "recurring_description` WHERE recurring_id = '" . (int)$recurring_id . "'");

		$tmp = $this->initData($data, TRUE);
		$tmp['price'] = (float)$data['price'];
		$this->db->where($this->primaryKey, (int)$recurring_id);
		$thid->db->update($this->table, $tmp);

		//$this->db->query("UPDATE `" . DB_PREFIX . "recurring` SET `price` = '" . (float)$data['price'] . "', `frequency` = '" . $this->db->escape($data['frequency']) . "', `duration` = '" . (int)$data['duration'] . "', `cycle` = '" . (int)$data['cycle'] . "', `sort_order` = '" . (int)$data['sort_order'] . "', `status` = '" . (int)$data['status'] . "', `trial_price` = '" . (float)$data['trial_price'] . "', `trial_frequency` = '" . $this->db->escape($data['trial_frequency']) . "', `trial_duration` = '" . (int)$data['trial_duration'] . "', `trial_cycle` = '" . (int)$data['trial_cycle'] . "', `trial_status` = '" . (int)$data['trial_status'] . "' WHERE recurring_id = '" . (int)$recurring_id . "'");

		foreach ($data['recurring_description'] as $language_id => $recurring_description) {
			$this->db->set($this->primaryKey, $recurring_id);
			$this->db->set('language_id', (int)$language_id);
			$this->db->set('name', $recurring_description['name']);
			$this->db->insert('recurring_description');
			//$this->db->query("INSERT INTO `" . DB_PREFIX . "recurring_description` (`recurring_id`, `language_id`, `name`) VALUES (" . (int)$recurring_id . ", " . (int)$language_id . ", '" . $this->db->escape($recurring_description['name']) . "')");
		}

		$this->event->trigger('post.admin.recurring.edit', $recurring_id);
		$this->tracking->log(LOG_FUNCTION::$catalog_recurring, LOG_ACTION_MODIFY, $recurring_id);
	}

	public function copyProfile($recurring_id) {
		$data = $this->getRecurring($recurring_id);

		$data['recurring_description'] = $this->getRecurringDescription($recurring_id);

		foreach ($data['recurring_description'] as &$recurring_description) {
			$recurring_description['name'] .= ' - 2';
		}

		$this->addRecurring($data);
	}

	public function deleteRecurring($recurring_id) {
		$this->event->trigger('pre.admin.recurring.delete', $recurring_id);
		$this->db->where($this->primaryKey, (int)$recurring_id);
		$this->db->delete(array($this->table, 'recurring_description', 'product_recurring'));

		//$this->db->query("DELETE FROM `" . DB_PREFIX . "recurring` WHERE recurring_id = " . (int)$recurring_id . "");
		//$this->db->query("DELETE FROM `" . DB_PREFIX . "recurring_description` WHERE recurring_id = " . (int)$recurring_id . "");
		//$this->db->query("DELETE FROM `" . DB_PREFIX . "product_recurring` WHERE recurring_id = " . (int)$recurring_id . "");
		$this->db->where($this->primaryKey, (int)$recurring_id);
		$this->db->set($this->primaryKey, 0);
		$this->db->update('order_recurring');

		//$this->db->query("UPDATE `" . DB_PREFIX . "order_recurring` SET `recurring_id` = 0 WHERE `recurring_id` = " . (int)$recurring_id . "");

		$this->event->trigger('post.admin.recurring.delete', $recurring_id);
		$this->tracking->log(LOG_FUNCTION::$catalog_recurring, LOG_ACTION_DELETE, $recurring_id);
	}

	public function getRecurring($recurring_id) {
		$this->db->select('*');
		$this->db->from($this->table);
		$this->db->where($this->primaryKey, (int)$recurring_id);

		//$query = $this->db->query("SELECT * FROM `" . DB_PREFIX . "recurring` WHERE recurring_id = '" . (int)$recurring_id . "'");

		//return $query->row;
		return $this->db->get()->row_array();
	}

	public function getRecurringDescription($recurring_id) {
		$recurring_description_data = array();
		$this->db->select('*');
		$this->db->from('recurring_description');
		$this->db->where($this->primaryKey, (int)$recurring_id);

		//$query = $this->db->query("SELECT * FROM `" . DB_PREFIX . "recurring_description` WHERE `recurring_id` = '" . (int)$recurring_id . "'");

		foreach ($this->db->get()->result_array() as $result) {
			$recurring_description_data[$result['language_id']] = array('name' => $result['name']);
		}

		return $recurring_description_data;
	}

	public function getRecurrings($data = array()) {
		$this->db->select('*');
		$this->db->from($this->table . ' r');
		$this->db->join('recurring_description rd', 'r.recurring_id = rd.recurring_id', 'left');
		$this->db->where('language_id', (int)$this->config->get('config_language_id'));

		//$sql = "SELECT * FROM `" . DB_PREFIX . "recurring` r LEFT JOIN " . DB_PREFIX . "recurring_description rd ON (r.recurring_id = rd.recurring_id) WHERE rd.language_id = '" . (int)$this->config->get('config_language_id') . "'";

		if (!empty($data['filter_name'])) {
			$this->db->like('rd.name', $this->db->escape($data['filter_name']));
			//$sql .= " AND rd.name LIKE '" . $this->db->escape($data['filter_name']) . "%'";
		}

		$order = 'ASC';
		if (isset($data['order']) && ($data['order'] == 'DESC')) {
			$order = 'DESC';
		}
		$sort_data = array(
			'rd.name',
			'r.sort_order',
			'r.status'
		);

		if (isset($data['sort']) && in_array($data['sort'], $sort_data)) {
			//$sql .= " ORDER BY " . $data['sort'];
			$this->db->order_by($data['sort'], $order);
		} else {
			$this->db->order_by('rd.name', $order);
			//$sql .= " ORDER BY rd.name";
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

		return $this->db->get()->result_array();
		//$query = $this->db->query($sql);

		//return $query->rows;
	}

	public function getTotalRecurrings() {
		$this->db->select('COUNT(*) AS `total`');
		$query = $this->db->get($this->table)->row_array();
		return $query['total'];

		//$query = $this->db->query("SELECT COUNT(*) AS total FROM `" . DB_PREFIX . "recurring`");

		//return $query->row['total'];
	}
}