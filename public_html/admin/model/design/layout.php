<?php
class ModelDesignLayout extends Model {
	public $table = 'layout';
	public $primaryKey = 'layout_id';
	public $fields = array();

	public function addLayout($data) {
		$this->event->trigger('pre.admin.layout.add', $data);
		$tmp = $this->initData($data, TRUE);
		$this->db->insert($this->table, $tmp);

		//$this->db->query("INSERT INTO " . DB_PREFIX . "layout SET name = '" . $this->db->escape($data['name']) . "'");

		//$layout_id = $this->db->getLastId();
		$layout_id = $this->db->insert_id();

		if (isset($data['layout_route'])) {
			foreach ($data['layout_route'] as $layout_route) {
				$this->db->set('layout_id', (int)$layout_id);
				$this->db->set('store_id', (int)$layout_route['store_id']);
				$this->db->set('route', $this->db->escape($layout_route['route']));
				$this->db->insert('layout_route');
				//$this->db->query("INSERT INTO " . DB_PREFIX . "layout_route SET layout_id = '" . (int)$layout_id . "', store_id = '" . (int)$layout_route['store_id'] . "', route = '" . $this->db->escape($layout_route['route']) . "'");
			}
		}
		
		if (isset($data['layout_module'])) {
			foreach ($data['layout_module'] as $layout_module) {
				$this->db->set('layout_id', (int)$layout_id);
				$this->db->set('position', $this->db->escape($layout_module['position']));
				$this->db->set('code', $this->db->escape($layout_module['code']));
				$this->db->set('sort_order', (int)$layout_module['sort_order']);
				$this->db->insert('layout_module');
				//$this->db->query("INSERT INTO " . DB_PREFIX . "layout_module SET layout_id = '" . (int)$layout_id . "', code = '" . $this->db->escape($layout_module['code']) . "', position = '" . $this->db->escape($layout_module['position']) . "', sort_order = '" . (int)$layout_module['sort_order'] . "'");
			}
		}
		
		$this->event->trigger('post.admin.layout.add', $layout_id);

		return $layout_id;
	}

	public function editLayout($layout_id, $data) {
		$this->event->trigger('pre.admin.layout.edit', $data);
		$tmp = $this->initData($data);
		$this->db->where($this->primaryKey, (int)$layout_id);
		$this->db->update($this->table, $tmp);

		//$this->db->query("UPDATE " . DB_PREFIX . "layout SET name = '" . $this->db->escape($data['name']) . "' WHERE layout_id = '" . (int)$layout_id . "'");

		//$this->db->query("DELETE FROM " . DB_PREFIX . "layout_route WHERE layout_id = '" . (int)$layout_id . "'");
		$this->db->where($this->primaryKey, (int)$layout_id);
		$this->db->delete('layout_route');

		if (isset($data['layout_route'])) {
			foreach ($data['layout_route'] as $layout_route) {
				$this->db->set($this->primaryKey, (int)$layout_id);
				$this->db->set('store_id', (int)$layout_route['store_id']);
				$this->db->set('route', $this->db->escape($layout_route['route']));
				$this->db->insert('layout_route');
				//$this->db->query("INSERT INTO " . DB_PREFIX . "layout_route SET layout_id = '" . (int)$layout_id . "', store_id = '" . (int)$layout_route['store_id'] . "', route = '" . $this->db->escape($layout_route['route']) . "'");
			}
		}
		
		//$this->db->query("DELETE FROM " . DB_PREFIX . "layout_module WHERE layout_id = '" . (int)$layout_id . "'");
		$this->db->where($this->primaryKey, (int)$layout_id);
		$this->db->delete('layout_module');

		if (isset($data['layout_module'])) {
			foreach ($data['layout_module'] as $layout_module) {
				$this->db->set('store_id', (int)$layout_module['store_id']);
				$this->db->set('code', $this->db->escape($layout_module['code']));
				$this->db->set('position', $this->db->escape($layout_module['position']));
				$this->db->set('position', (int)$layout_module['sort_order']);
				$this->db->insert('layout_module');
				//$this->db->query("INSERT INTO " . DB_PREFIX . "layout_module SET layout_id = '" . (int)$layout_id . "', code = '" . $this->db->escape($layout_module['code']) . "', position = '" . $this->db->escape($layout_module['position']) . "', sort_order = '" . (int)$layout_module['sort_order'] . "'");
			}
		}
		
		$this->event->trigger('post.admin.layout.edit', $layout_id);
	}

	public function deleteLayout($layout_id) {
		$this->event->trigger('pre.admin.layout.delete', $layout_id);
		$this->db->where($this->primaryKey, (int)$layout_id);
		$this->db->delete(array($this->table, 'layout_route', 'layout_module', 'category_to_layout', 'product_to_layout', 'information_to_layout'));

		/*$this->db->query("DELETE FROM " . DB_PREFIX . "layout WHERE layout_id = '" . (int)$layout_id . "'");
		$this->db->query("DELETE FROM " . DB_PREFIX . "layout_route WHERE layout_id = '" . (int)$layout_id . "'");
		$this->db->query("DELETE FROM " . DB_PREFIX . "layout_module WHERE layout_id = '" . (int)$layout_id . "'");
		$this->db->query("DELETE FROM " . DB_PREFIX . "category_to_layout WHERE layout_id = '" . (int)$layout_id . "'");
		$this->db->query("DELETE FROM " . DB_PREFIX . "product_to_layout WHERE layout_id = '" . (int)$layout_id . "'");
		$this->db->query("DELETE FROM " . DB_PREFIX . "information_to_layout WHERE layout_id = '" . (int)$layout_id . "'");*/

		$this->event->trigger('post.admin.layout.delete', $layout_id);
	}

	public function getLayout($layout_id) {
		$this->db->distinct();
		$this->db->from($this->table);
		$this->db->where($this->primaryKey, (int)$layout_id);
		return $this->db->get()->row_array();
		//$query = $this->db->query("SELECT DISTINCT * FROM " . DB_PREFIX . "layout WHERE layout_id = '" . (int)$layout_id . "'");

		//return $query->row;
	}

	public function getLayouts($data = array()) {
		$this->db->select('*');
		$this->db->from($this->table);
		//$sql = "SELECT * FROM " . DB_PREFIX . "layout";

		$sort_data = array('name');

		$order = 'ASC';
		if (isset($data['order']) && ($data['order'] == 'DESC')) {
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

		return $this->db->get()->result_array();
		//$query = $this->db->query($sql);

		//return $query->rows;
	}

	public function getLayoutRoutes($layout_id) {
		$this->db->select('*');
		$this->db->from('layout_route');
		$this->db->where($this->primaryKey, (int)$layout_id);
		return $this->db->get()->result_array();

		//$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "layout_route WHERE layout_id = '" . (int)$layout_id . "'");

		//return $query->rows;
	}
	
	public function getLayoutModules($layout_id) {
		$this->db->select('*');
		$this->db->from('layout_module');
		$this->db->where($this->primaryKey, (int)$layout_id);
		return $this->db->get()->result_array();

		/*$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "layout_module WHERE layout_id = '" . (int)$layout_id . "'");

		return $query->rows;*/
	}
	
	public function getTotalLayouts() {
		$this->db->select('COUNT(*) AS `total`');
		$query = $this->db->get($this->table)->row_array();
		return $query['total'];
		//$query = $this->db->query("SELECT COUNT(*) AS total FROM " . DB_PREFIX . "layout");

		//return $query->row['total'];
	}
}