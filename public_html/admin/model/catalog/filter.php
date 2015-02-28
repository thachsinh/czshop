<?php
class ModelCatalogFilter extends Model {
	public $table = 'filter';
	public $fields = array();
	public $desc_fields = array();
	public $primaryKey = 'filter_id';

	public function addFilter($data) {
		$this->event->trigger('pre.admin.filter.add', $data);

		$this->db->set('sort_order', (int)$data['sort_order']);
		$this->db->insert('filter_group');

		//$this->db->query("INSERT INTO `" . DB_PREFIX . "filter_group` SET sort_order = '" . (int)$data['sort_order'] . "'");

		//$filter_group_id = $this->db->getLastId();
		$filter_group_id = $this->db->insert_id();

		foreach ($data['filter_group_description'] as $language_id => $value) {
			//$tmp = $this->initData($data['filter_group_description'], TRUE, $this->desc_fields);
			$this->db->set('name', $value['name']);
			$this->db->set('filter_group_id', (int)$filter_group_id);
			$this->db->set('language_id', (int)$language_id);
			$this->db->insert('filter_group_description');

			//$this->db->query("INSERT INTO " . DB_PREFIX . "filter_group_description SET filter_group_id = '" . (int)$filter_group_id . "', language_id = '" . (int)$language_id . "', name = '" . $this->db->escape($value['name']) . "'");
		}

		if (isset($data['filter'])) {
			foreach ($data['filter'] as $filter) {
				$this->db->set('filter_group_id', (int)$filter_group_id);
				$this->db->set('sort_order', (int)$filter['sort_order']);
				$this->db->insert($this->table);

				//$this->db->query("INSERT INTO " . DB_PREFIX . "filter SET filter_group_id = '" . (int)$filter_group_id . "', sort_order = '" . (int)$filter['sort_order'] . "'");

				//$filter_id = $this->db->getLastId();
				$filter_id = $this->db->insert_id();

				foreach ($filter['filter_description'] as $language_id => $filter_description) {
					$this->db->set('filter_id', $filter_id);
					$this->db->set('language_id', (int)$language_id);
					$this->db->set('filter_group_id', (int)$filter_group_id);
					$this->db->set('name', $filter_description['name']);
					$this->db->insert('filter_description');
					//$this->db->query("INSERT INTO " . DB_PREFIX . "filter_description SET filter_id = '" . (int)$filter_id . "', language_id = '" . (int)$language_id . "', filter_group_id = '" . (int)$filter_group_id . "', name = '" . $this->db->escape($filter_description['name']) . "'");
				}
			}
		}

		$this->event->trigger('post.admin.filter.add', $filter_group_id);

		return $filter_group_id;
	}

	public function editFilter($filter_group_id, $data) {
		$this->event->trigger('pre.admin.filter.edit', $data);

		$this->db->set('sort_order', (int)$data['sort_order']);
		$this->db->where('filter_group_id', (int)$filter_group_id);
		$this->db->update('filter_group');

		//$this->db->query("UPDATE `" . DB_PREFIX . "filter_group` SET sort_order = '" . (int)$data['sort_order'] . "' WHERE filter_group_id = '" . (int)$filter_group_id . "'");

		$this->db->where('filter_group_id', (int)$filter_group_id);
		$this->db->delete('filter_group_description');

		//$this->db->query("DELETE FROM " . DB_PREFIX . "filter_group_description WHERE filter_group_id = '" . (int)$filter_group_id . "'");

		foreach ($data['filter_group_description'] as $language_id => $value) {
			//$this->db->query("INSERT INTO " . DB_PREFIX . "filter_group_description SET filter_group_id = '" . (int)$filter_group_id . "', language_id = '" . (int)$language_id . "', name = '" . $this->db->escape($value['name']) . "'");
			$this->db->set('name', $value['name']);
			$this->db->set('filter_group_id', (int)$filter_group_id);
			$this->db->set('language_id', (int)$language_id);
			$this->db->insert('filter_group_description');
		}

		$this->db->where('filter_group_id', (int)$filter_group_id);
		$this->db->delete(array('filter_group_description', 'filter_description'));

		//$this->db->query("DELETE FROM " . DB_PREFIX . "filter WHERE filter_group_id = '" . (int)$filter_group_id . "'");
		//$this->db->query("DELETE FROM " . DB_PREFIX . "filter_description WHERE filter_group_id = '" . (int)$filter_group_id . "'");

		if (isset($data['filter'])) {
			foreach ($data['filter'] as $filter) {
				if ($filter['filter_id']) {
					$this->db->set('filter_group_id', (int)$filter_group_id);
					$this->db->set('filter_id', (int)$filter['filter_id']);
					$this->db->set('sort_order', (int)$filter['sort_order']);
					$this->db->insert($this->table);

					//$this->db->query("INSERT INTO " . DB_PREFIX . "filter SET filter_id = '" . (int)$filter['filter_id'] . "', filter_group_id = '" . (int)$filter_group_id . "', sort_order = '" . (int)$filter['sort_order'] . "'");
				} else {
					$this->db->set('filter_group_id', (int)$filter_group_id);
					$this->db->set('sort_order', (int)$filter['sort_order']);
					$this->db->insert($this->table);

					//$this->db->query("INSERT INTO " . DB_PREFIX . "filter SET filter_group_id = '" . (int)$filter_group_id . "', sort_order = '" . (int)$filter['sort_order'] . "'");
				}

				$filter_id = $this->db->insert_id();

				foreach ($filter['filter_description'] as $language_id => $filter_description) {
					//$this->db->query("INSERT INTO " . DB_PREFIX . "filter_description SET filter_id = '" . (int)$filter_id . "', language_id = '" . (int)$language_id . "', filter_group_id = '" . (int)$filter_group_id . "', name = '" . $this->db->escape($filter_description['name']) . "'");
					$this->db->set('filter_id', $filter_id);
					$this->db->set('language_id', (int)$language_id);
					$this->db->set('filter_group_id', (int)$filter_group_id);
					$this->db->set('name', $filter_description['name']);
					$this->db->insert('filter_description');
				}
			}
		}

		$this->event->trigger('post.admin.filter.edit', $filter_group_id);
	}

	public function deleteFilter($filter_group_id) {
		$this->event->trigger('pre.admin.filter.delete', $filter_group_id);

		$this->db->where($this->primaryKey, (int)$filter_group_id);
		$this->db->delete(array($this->table, 'filter_group', 'filter_group_description', 'filter_description'));
		/*
		$this->db->query("DELETE FROM `" . DB_PREFIX . "filter_group` WHERE filter_group_id = '" . (int)$filter_group_id . "'");
		$this->db->query("DELETE FROM `" . DB_PREFIX . "filter_group_description` WHERE filter_group_id = '" . (int)$filter_group_id . "'");
		$this->db->query("DELETE FROM `" . DB_PREFIX . "filter` WHERE filter_group_id = '" . (int)$filter_group_id . "'");
		$this->db->query("DELETE FROM `" . DB_PREFIX . "filter_description` WHERE filter_group_id = '" . (int)$filter_group_id . "'");*/

		$this->event->trigger('post.admin.filter.delete', $filter_group_id);
	}

	public function getFilterGroup($filter_group_id) {
		$this->db->select('*');
		$this->db->from($this->table . ' fg');
		$this->db->join('filter_group_description fgd', 'fg.filter_group_id = fgd.filter_group_id', 'left');
		$this->db->where('fgd.language_id', (int)$this->config->get('config_language_id'));
		$this->db->where('fg.filter_group_id', (int)$filter_group_id);

		return $this->db->get()->row_array();
		//$query = $this->db->query("SELECT * FROM `" . DB_PREFIX . "filter_group` fg LEFT JOIN " . DB_PREFIX . "filter_group_description fgd ON (fg.filter_group_id = fgd.filter_group_id) WHERE fg.filter_group_id = '" . (int)$filter_group_id . "' AND fgd.language_id = '" . (int)$this->config->get('config_language_id') . "'");

		//return $query->row;
	}

	public function getFilterGroups($data = array()) {
		$this->db->select('*');
		$this->db->from($this->table . ' fg');
		$this->db->join('filter_group_description fgd', 'fg.filter_group_id = fgd.filter_group_id', 'left');
		$this->db->where('fgd.language_id', (int)$this->config->get('config_language_id'));

		//$sql = "SELECT * FROM `" . DB_PREFIX . "filter_group` fg LEFT JOIN " . DB_PREFIX . "filter_group_description fgd ON (fg.filter_group_id = fgd.filter_group_id) WHERE fgd.language_id = '" . (int)$this->config->get('config_language_id') . "'";

		$sort_data = array(
			'fgd.name',
			'fg.sort_order'
		);

		$order = 'ASC';
		if (isset($data['order']) && ($data['order'] == 'DESC')) {
			$order = 'DESC';
		} 

		if (isset($data['sort']) && in_array($data['sort'], $sort_data)) {
			$this->db->order_by($data['sort'], $order);
		} else {
			$this->db->order_by('fgd.name', $order);
		}

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

		return $this->db->get()->result_array();
	}

	public function getFilterGroupDescriptions($filter_group_id) {
		$filter_group_data = array();

		$this->db->select('*')->from('filter_group_description');
		$this->db->where('filter_group_id', (int)$filter_group_id);

		//$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "filter_group_description WHERE filter_group_id = '" . (int)$filter_group_id . "'");

		foreach ($this->db->get()->result_array() as $result) {
			$filter_group_data[$result['language_id']] = array('name' => $result['name']);
		}

		return $filter_group_data;
	}

	public function getFilter($filter_id) {
		$query = $this->db->query("SELECT *, (SELECT name FROM " . DB_PREFIX . "filter_group_description fgd WHERE f.filter_group_id = fgd.filter_group_id AND fgd.language_id = '" . (int)$this->config->get('config_language_id') . "') AS `group` FROM " . DB_PREFIX . "filter f LEFT JOIN " . DB_PREFIX . "filter_description fd ON (f.filter_id = fd.filter_id) WHERE f.filter_id = '" . (int)$filter_id . "' AND fd.language_id = '" . (int)$this->config->get('config_language_id') . "'");

		//return $query->row;
		return $this->db->query($query)->row_array();
	}

	public function getFilters($data) {
		$sql = "SELECT *, (SELECT name FROM " . DB_PREFIX . "filter_group_description fgd WHERE f.filter_group_id = fgd.filter_group_id AND fgd.language_id = '" . (int)$this->config->get('config_language_id') . "') AS `group` FROM " . DB_PREFIX . "filter f LEFT JOIN " . DB_PREFIX . "filter_description fd ON (f.filter_id = fd.filter_id) WHERE fd.language_id = '" . (int)$this->config->get('config_language_id') . "'";

		if (!empty($data['filter_name'])) {
			$sql .= " AND fd.name LIKE '" . $this->db->escape($data['filter_name']) . "%'";
		}

		$sql .= " ORDER BY f.sort_order ASC";

		if (isset($data['start']) || isset($data['limit'])) {
			if ($data['start'] < 0) {
				$data['start'] = 0;
			}

			if ($data['limit'] < 1) {
				$data['limit'] = 20;
			}

			$sql .= " LIMIT " . (int)$data['start'] . "," . (int)$data['limit'];
		}

		//$query = $this->db->query($sql);

		//return $query->rows;
		return $this->db->query($sql)->result_array();
	}

	public function getFilterDescriptions($filter_group_id) {
		$filter_data = array();

		$this->db->select('*')->from($this->table);
		$this->db->where('filter_group_id', (int)$filter_group_id);

		//$filter_query = $this->db->query("SELECT * FROM " . DB_PREFIX . "filter WHERE filter_group_id = '" . (int)$filter_group_id . "'");

		foreach ($this->db->get()->result_array() as $filter) {
			$filter_description_data = array();

			//$filter_description_query = $this->db->query("SELECT * FROM " . DB_PREFIX . "filter_description WHERE filter_id = '" . (int)$filter['filter_id'] . "'");
			$this->db->select('*')->from('filter_description');
			$this->db->where($this->primaryKey, (int)$filter['filter_id']);

			foreach ($this->db->get()->result_array() as $filter_description) {
				$filter_description_data[$filter_description['language_id']] = array('name' => $filter_description['name']);
			}

			$filter_data[] = array(
				'filter_id'          => $filter['filter_id'],
				'filter_description' => $filter_description_data,
				'sort_order'         => $filter['sort_order']
			);
		}

		return $filter_data;
	}

	public function getTotalFilterGroups() {
		$this->db->select('COUNT(*) AS `total`');
		$query = $this->db->get('filter_group')->row_array();
		return $query['total'];

		//$query = $this->db->query("SELECT COUNT(*) AS total FROM `" . DB_PREFIX . "filter_group`");

		//return $query->row['total'];
	}
}
