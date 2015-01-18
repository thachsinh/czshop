<?php
class ModelCatalogInformation extends Model {
	public $table = 'information';
	public $primaryKey = 'information_id';
	public $fields = array('information_id', 'bottom', 'sort_order', 'status');
	public $desc_fields = array('information_id', 'language_id', 'title', 'description', 'meta_title', 'meta_description', 'meta_keyword');
	
	public function addInformation($data) {
		$this->event->trigger('pre.admin.information.add', $data);
		$tmp = $this->initData($data, TRUE);
		$this->db->set('bottom', (isset($data['bottom']) ? (int)$data['bottom'] : 0));
		$this->db->insert($this->table, $tmp);

		//$this->db->query("INSERT INTO " . DB_PREFIX . "information SET sort_order = '" . (int)$data['sort_order'] . "', bottom = '" . (isset($data['bottom']) ? (int)$data['bottom'] : 0) . "', status = '" . (int)$data['status'] . "'");

		//$information_id = $this->db->getLastId();
		$information_id = $this->db->insert_id();

		foreach ($data['information_description'] as $language_id => $value) {
			$tmp = $this->initData($data['information_description'][$language_id], TRUE, $this->desc_fields);
			//var_dump(($tmp)); exit;
			$this->db->set('language_id', (int)$language_id);
			$this->db->set('information_id', $information_id);
			$this->db->insert('information_description', $tmp);

			//$this->db->query("INSERT INTO " . DB_PREFIX . "information_description SET information_id = '" . (int)$information_id . "', language_id = '" . (int)$language_id . "', title = '" . $this->db->escape($value['title']) . "', description = '" . $this->db->escape($value['description']) . "', meta_title = '" . $this->db->escape($value['meta_title']) . "', meta_description = '" . $this->db->escape($value['meta_description']) . "', meta_keyword = '" . $this->db->escape($value['meta_keyword']) . "'");
		}

		if (isset($data['information_store'])) {
			foreach ($data['information_store'] as $store_id) {
				$this->db->set($this->primaryKey, $information_id);
				$this->db->set('store_id', (int)$store_id);
				$this->db->insert('information_to_store');
				//$this->db->query("INSERT INTO " . DB_PREFIX . "information_to_store SET information_id = '" . (int)$information_id . "', store_id = '" . (int)$store_id . "'");
			}
		}

		if (isset($data['information_layout'])) {
			foreach ($data['information_layout'] as $store_id => $layout_id) {
				$this->db->set($this->primaryKey, $information_id);
				$this->db->set('store_id', (int)$store_id);
				$this->db->set('layout_id', (int)$layout_id);
				$this->db->insert('information_to_layout');

				//$this->db->query("INSERT INTO " . DB_PREFIX . "information_to_layout SET information_id = '" . (int)$information_id . "', store_id = '" . (int)$store_id . "', layout_id = '" . (int)$layout_id . "'");
			}
		}

		if (isset($data['keyword'])) {
			$this->db->set('query', 'information_id=' . $information_id);
			$this->db->set('keyword', $data['keyword']);
			$this->db->insert('url_alias');

			//$this->db->query("INSERT INTO " . DB_PREFIX . "url_alias SET query = 'information_id=" . (int)$information_id . "', keyword = '" . $this->db->escape($data['keyword']) . "'");
		}

		$this->cache->delete('information');

		$this->event->trigger('post.admin.information.add', $information_id);

		return $information_id;
	}

	public function editInformation($information_id, $data) {
		$this->event->trigger('pre.admin.information.edit', $data);

		$tmp = $this->initData($data, TRUE);
		$this->db->where($this->primaryKey, (int)$information_id);
		$this->db->update($this->table, $tmp);
		$this->db->set('bottom', (isset($data['bottom']) ? (int)$data['bottom'] : 0));

		//$this->db->query("UPDATE " . DB_PREFIX . "information SET sort_order = '" . (int)$data['sort_order'] . "', bottom = '" . (isset($data['bottom']) ? (int)$data['bottom'] : 0) . "', status = '" . (int)$data['status'] . "' WHERE information_id = '" . (int)$information_id . "'");

		//$this->db->query("DELETE FROM " . DB_PREFIX . "information_description WHERE information_id = '" . (int)$information_id . "'");
		$this->db->where($this->primaryKey, (int)$information_id);
		$this->db->delete('information_description');

		foreach ($data['information_description'] as $language_id => $value) {
			$tmp = $this->initData($data['information_description'][$language_id], TRUE, $this->desc_fields);
			//var_dump(($tmp)); exit;
			$this->db->set('language_id', (int)$language_id);
			$this->db->set('information_id', $information_id);
			$this->db->insert('information_description', $tmp);

			//$this->db->query("INSERT INTO " . DB_PREFIX . "information_description SET information_id = '" . (int)$information_id . "', language_id = '" . (int)$language_id . "', title = '" . $this->db->escape($value['title']) . "', description = '" . $this->db->escape($value['description']) . "', meta_title = '" . $this->db->escape($value['meta_title']) . "', meta_description = '" . $this->db->escape($value['meta_description']) . "', meta_keyword = '" . $this->db->escape($value['meta_keyword']) . "'");
		}

		$this->db->where($this->primaryKey, (int)$information_id);
		$this->db->delete('information_to_store');
		//$this->db->query("DELETE FROM " . DB_PREFIX . "information_to_store WHERE information_id = '" . (int)$information_id . "'");

		if (isset($data['information_store'])) {
			foreach ($data['information_store'] as $store_id) {
				$this->db->set($this->primaryKey, $information_id);
				$this->db->set('store_id', (int)$store_id);
				$this->db->insert('information_to_store');
				//$this->db->query("INSERT INTO " . DB_PREFIX . "information_to_store SET information_id = '" . (int)$information_id . "', store_id = '" . (int)$store_id . "'");
			}
		}

		$this->db->where($this->primaryKey, (int)$information_id);
		$this->db->delete('information_to_layout');

		//$this->db->query("DELETE FROM " . DB_PREFIX . "information_to_layout WHERE information_id = '" . (int)$information_id . "'");

		if (isset($data['information_layout'])) {
			foreach ($data['information_layout'] as $store_id => $layout_id) {
				$this->db->set($this->primaryKey, $information_id);
				$this->db->set('store_id', (int)$store_id);
				$this->db->set('layout_id', (int)$layout_id);
				$this->db->insert('information_to_layout');

				//$this->db->query("INSERT INTO " . DB_PREFIX . "information_to_layout SET information_id = '" . (int)$information_id . "', store_id = '" . (int)$store_id . "', layout_id = '" . (int)$layout_id . "'");
			}
		}

		$this->db->where('query', 'information_id=' . (int)$information_id);
		$this->db->delete('url_alias');

		//$this->db->query("DELETE FROM " . DB_PREFIX . "url_alias WHERE query = 'information_id=" . (int)$information_id . "'");

		if ($data['keyword']) {
			$this->db->set('query', 'information_id=' . $information_id);
			$this->db->set('keyword', $data['keyword']);
			$this->db->insert('url_alias');

			//$this->db->query("INSERT INTO " . DB_PREFIX . "url_alias SET query = 'information_id=" . (int)$information_id . "', keyword = '" . $this->db->escape($data['keyword']) . "'");
		}

		$this->cache->delete('information');

		$this->event->trigger('post.admin.information.edit', $information_id);
	}

	public function editStatus($information_id, $status) {
		$this->db->set('status', (int)$status);
		$this->db->where($this->primaryKey, (int)$information_id);
		$this->db->update($this->table);
	}

	public function deleteInformation($information_id) {
		$this->event->trigger('pre.admin.information.delete', $information_id);

		/*$this->db->query("DELETE FROM " . DB_PREFIX . "information WHERE information_id = '" . (int)$information_id . "'");
		$this->db->query("DELETE FROM " . DB_PREFIX . "information_description WHERE information_id = '" . (int)$information_id . "'");
		$this->db->query("DELETE FROM " . DB_PREFIX . "information_to_store WHERE information_id = '" . (int)$information_id . "'");
		$this->db->query("DELETE FROM " . DB_PREFIX . "information_to_layout WHERE information_id = '" . (int)$information_id . "'");
		$this->db->query("DELETE FROM " . DB_PREFIX . "url_alias WHERE query = 'information_id=" . (int)$information_id . "'");*/
		$this->db->where($this->primaryKey, (int)$information_id);
		$this->db->delete(array($this->table, 'information_description', 'information_to_store', 'information_to_layout'));
		$this->db->where('query', 'information_id=' . (int)$information_id);
		$this->db->delete('url_alias');

		$this->cache->delete('information');

		$this->event->trigger('post.admin.information.delete', $information_id);
	}

	public function getInformation($information_id) {
		$query = $this->db->query("SELECT DISTINCT *, (SELECT keyword FROM " . DB_PREFIX . "url_alias WHERE query = 'information_id=" . (int)$information_id . "') AS keyword FROM " . DB_PREFIX . "information WHERE information_id = '" . (int)$information_id . "'");

		return $query->row_array();
	}

	public function getInformations($data = array()) {
		if ($data) {
			
			$this->db->select('*');
			$this->db->from('information i');
			$this->db->join('information_description id', 'i.information_id = id.information_id', 'left');
			$this->db->where('id.language_id', (int)$this->config->get('config_language_id'));
			//$sql = "SELECT * FROM " . DB_PREFIX . "information i LEFT JOIN " . DB_PREFIX . "information_description id ON (i.information_id = id.information_id) WHERE id.language_id = '" . (int)$this->config->get('config_language_id') . "'";

			$sort_data = array(
				'id.title',
				'i.sort_order',
				'i.status'
			);

			$order = 'ASC';
			if (isset($data['order']) && ($data['order'] == 'DESC')) {
				$order = 'DESC';
			}
			
			if (isset($data['sort']) && in_array($data['sort'], $sort_data)) {
				//$sql .= " ORDER BY " . $data['sort'];
				$this->db->order_by($data['sort'], $order);
			} else {
				//$sql .= " ORDER BY id.title";
				$this->db->order_by('id.title', $order);
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
			$information_data = $this->cache->get('information.' . (int)$this->config->get('config_language_id'));

			if (!$information_data) {
				$this->db->select('*');
				$this->db->from($this->table . ' i');
				$this->db->join('information_description id', 'i.information_id = id.information_id', 'left');
				$this->db->where('id.language_id', (int)$this->config->get('config_language_id'));
				$this->db->order_by('id.title');
				//$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "information i LEFT JOIN " . DB_PREFIX . "information_description id ON (i.information_id = id.information_id) WHERE id.language_id = '" . (int)$this->config->get('config_language_id') . "' ORDER BY id.title");

				//$information_data = $query->rows;
				$information_data = $this->db->get()->result_array();

				$this->cache->set('information.' . (int)$this->config->get('config_language_id'), $information_data);
			}

			return $information_data;
		}
	}

	public function getInformationDescriptions($information_id) {
		$information_description_data = array();

		//$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "information_description WHERE information_id = '" . (int)$information_id . "'");

		$this->db->select('*');
		$this->db->where($this->primaryKey, (int)$information_id);
		$query = $this->db->get('information_description');
		
		foreach ($query->result_array() as $result) {
			$information_description_data[$result['language_id']] = array(
				'title'            => $result['title'],
				'description'      => $result['description'],
				'meta_title'       => $result['meta_title'],
				'meta_description' => $result['meta_description'],
				'meta_keyword'     => $result['meta_keyword']
			);
		}

		return $information_description_data;
	}

	public function getInformationStores($information_id) {
		$information_store_data = array();
		$this->db->select('*');
		$this->db->where($this->primaryKey, (int)$information_id);
		$query = $this->db->get('information_to_store');

		//$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "information_to_store WHERE information_id = '" . (int)$information_id . "'");

		foreach ($query->result_array() as $result) {
			$information_store_data[] = $result['store_id'];
		}
		//var_dump($information_store_data);

		return $information_store_data;
	}

	public function getInformationLayouts($information_id) {
		$information_layout_data = array();

		$this->db->select('*');
		$this->db->where($this->primaryKey, (int)$information_id);
		$query = $this->db->get('information_to_layout');
		//return $query['total'];
		
		//$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "information_to_layout WHERE information_id = '" . (int)$information_id . "'");

		foreach ($query->result_array() as $result) {
			$information_layout_data[$result['store_id']] = $result['layout_id'];
		}

		return $information_layout_data;
	}

	public function getTotalInformations() {
		//$query = $this->db->query("SELECT COUNT(*) AS total FROM " . DB_PREFIX . "information");

		//return $query->row['total'];
		
		$this->db->select('COUNT(*) AS `total`');
		$query = $this->db->get($this->table)->row_array();
		return $query['total'];
	}

	public function getTotalInformationsByLayoutId($layout_id) {
		$this->db->select('COUNT(*) AS `total`');
		$this->db->where('layout_id', (int)$layout_id);
		$query = $this->db->get('information_to_layout')->row_array();
		return $query['total'];
		//$query = $this->db->query("SELECT COUNT(*) AS total FROM " . DB_PREFIX . "information_to_layout WHERE layout_id = '" . (int)$layout_id . "'");

		//return $query->row['total'];
	}
}