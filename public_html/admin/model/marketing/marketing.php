<?php
class ModelMarketingMarketing extends Model {
	public $table = 'marketing';
	public $primaryKey = 'marketing_id';
	public $fields = array('marketing_id', 'name', 'description', 'code', 'clicks', 'date_added');

	public function addMarketing($data) {
		$this->event->trigger('pre.admin.marketing.add', $data);

		$this->db->set('name', $data['name']);
		$this->db->set('description', $data['description']);
		$this->db->set('code', $data['code']);
		$this->db->set('date_added', 'NOW()', FALSE);
		$this->db->insert($this->table);
		//$this->db->query("INSERT INTO " . DB_PREFIX . "marketing SET name = '" . $this->db->escape($data['name']) . "', description = '" . $this->db->escape($data['description']) . "', code = '" . $this->db->escape($data['code']) . "', date_added = NOW()");

		$marketing_id = $this->db->insert_id();

		$this->event->trigger('post.admin.marketing.add', $marketing_id);

		return $marketing_id;
	}

	public function editMarketing($marketing_id, $data) {
		$this->event->trigger('pre.admin.marketing.edit', $data);

		$this->db->set('name', $data['name']);
		$this->db->set('description', $data['description']);
		$this->db->set('code', $data['code']);
		$this->db->set('date_added', 'NOW()', FALSE);
		$this->db->where($this->primaryKey, (int)$marketing_id);
		$this->db->update($this->table);

		//$this->db->query("UPDATE " . DB_PREFIX . "marketing SET name = '" . $this->db->escape($data['name']) . "', description = '" . $this->db->escape($data['description']) . "', code = '" . $this->db->escape($data['code']) . "' WHERE marketing_id = '" . (int)$marketing_id . "'");

		$this->event->trigger('post.admin.marketing.edit', $marketing_id);
	}

	public function deleteMarketing($marketing_id) {
		$this->event->trigger('pre.admin.marketing.delete', $marketing_id);

		$this->db->where($this->primaryKey, (int)$marketing_id);
		$this->db->delete($this->table);

		//$this->db->query("DELETE FROM " . DB_PREFIX . "marketing WHERE marketing_id = '" . (int)$marketing_id . "'");

		$this->event->trigger('post.admin.marketing.delete', $marketing_id);
	}

	public function getMarketing($marketing_id) {
		$this->db->distinct('*');
		$this->db->from($this->table);
		$this->db->where($this->primaryKey, (int)$marketing_id);
		return $this->db->get()->row_array();

		//$query = $this->db->query("SELECT DISTINCT * FROM " . DB_PREFIX . "marketing WHERE marketing_id = '" . (int)$marketing_id . "'");

		//return $query->row;
	}

	public function getMarketings($data = array()) {
		$implode = array();

		/*$order_statuses = $this->config->get('config_complete_status');

		foreach ($order_statuses as $order_status_id) {
			$implode[] = "o.order_status_id = '" . (int)$order_status_id . "'";
		}

		$sql = "SELECT *, (SELECT COUNT(*) FROM `" . DB_PREFIX . "order` o WHERE (" . implode(" OR ", $implode) . ") AND o.marketing_id = m.marketing_id) AS orders FROM " . DB_PREFIX . "marketing m";
		*/
		$this->db->select('*');
		$this->db->from($this->table. ' m');

		if (!empty($data['filter_name'])) {
			$this->db->like('m.name', $data['filter_name']);

			//$implode[] = "name LIKE '" . $this->db->escape($data['filter_name']) . "'";
		}

		if (!empty($data['filter_code'])) {
			$this->db->where('m.code', $data['filter_code']);

			//$implode[] = "code = '" . $this->db->escape($data['filter_code']) . "'";
		}

		if (!empty($data['filter_date_added'])) {
			$this->db->where('DATE(m.date_added) = DATE(\'' . $this->db->escape($data['filter_date_added']) . '\')', NULL, FALSE);

			//$implode[] = "DATE(date_added) = DATE('" . $this->db->escape($data['filter_date_added']) . "')";
		}

		$sort_data = array(
			'm.name',
			'm.code',
			'm.date_added'
		);

		$order = 'ASC';
		if (isset($data['order']) && ($data['order'] == 'DESC')) {
			$order = 'DESC';
		}

		if (isset($data['sort']) && in_array($data['sort'], $sort_data)) {
			//$sql .= " ORDER BY " . $data['sort'];
			$this->db->order_by($data['sort'], $order);
		} else {
			$this->db->order_by('m.name', $order);

			//$sql .= " ORDER BY name";
		}

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

		//return $query->result_array();
		return $this->db->get()->result_array();
	}

	public function getTotalMarketings($data = array()) {
		$this->db->select('COUNT(*) AS total');
		$this->db->from($this->table);

		//$sql = "SELECT COUNT(*) AS total FROM " . DB_PREFIX . "marketing";

		$implode = array();

		if (!empty($data['filter_name'])) {
			$this->db->like('name', $data['filter_name']);

			//$implode[] = "name LIKE '" . $this->db->escape($data['filter_name']) . "'";
		}

		if (!empty($data['filter_code'])) {
			$this->db->where('code', $data['filter_code']);

			//$implode[] = "code = '" . $this->db->escape($data['filter_code']) . "'";
		}

		if (!empty($data['filter_date_added'])) {
			$this->db->where('DATE(date_added) = DATE(\'' . $this->db->escape($data['filter_date_added']) . '\')', NULL, FALSE);

			//$implode[] = "DATE(date_added) = DATE('" . $this->db->escape($data['filter_date_added']) . "')";
		}

		$data = $this->db->get()->row_array();
		return $data['total'];

	}
}
