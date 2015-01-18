<?php
class ModelCatalogCategory extends Model {
	public $table = 'category';
	public $primaryKey = 'category_id';
	public $fields = array('category_id', 'image', 'parent_id', 'top', 'column', 'sort_order', 'status', 'date_added', 'date_modified');

	public function addCategory($data) {
		$this->event->trigger('pre.admin.category.add', $data);
		$tmp = $this->initData($data, TRUE);
		//var_dump($tmp);
		//var_dump($data);
		//exit;
		$this->db->set('date_modified', 'NOW()', FALSE);
		$this->db->set('date_added', 'NOW()', FALSE);
		$this->db->insert($this->table, $tmp);

		//$this->db->query("INSERT INTO " . DB_PREFIX . "category SET parent_id = '" . (int)$data['parent_id'] . "', `top` = '" . (isset($data['top']) ? (int)$data['top'] : 0) . "', `column` = '" . (int)$data['column'] . "', sort_order = '" . (int)$data['sort_order'] . "', status = '" . (int)$data['status'] . "', date_modified = NOW(), date_added = NOW()");

		//$category_id = $this->db->getLastId();
		$category_id = $this->db->insert_id();

		if (isset($data['image'])) {
			$this->db->where($this->primaryKey, (int)$category_id);
			$this->db->set('image', ($data['image']));
			$this->db->update($this->table);
			//$this->db->query("UPDATE " . DB_PREFIX . "category SET image = '" . ($data['image']) . "' WHERE category_id = '" . (int)$category_id . "'");
		}

		foreach ($data['category_description'] as $language_id => $value) {
			$this->db->set($this->primaryKey, (int)$category_id);
			$this->db->set('language_id', (int)$language_id);
			$this->db->set('name', ($value['name']));
			$this->db->set('description', ($value['description']));
			$this->db->set('meta_title', ($value['meta_title']));
			$this->db->set('meta_description', ($value['meta_description']));
			$this->db->set('meta_keyword', ($value['meta_keyword']));
			//echo $this->db->last_query();
			$this->db->insert('category_description');
			//	exit;
			//$this->db->query("INSERT INTO " . DB_PREFIX . "category_description SET category_id = '" . (int)$category_id . "', language_id = '" . (int)$language_id . "', name = '" . ($value['name']) . "', description = '" . ($value['description']) . "', meta_title = '" . ($value['meta_title']) . "', meta_description = '" . ($value['meta_description']) . "', meta_keyword = '" . ($value['meta_keyword']) . "'");
		}

		// MySQL Hierarchical Data Closure Table Pattern
		$level = 0;

		$this->db->select('*');
		$this->db->from('category_path');
		$this->db->where($this->primaryKey, (int)$data['parent_id']);
		$this->db->order_by('level', 'ASC');

		//$query = $this->db->query("SELECT * FROM `" . DB_PREFIX . "category_path` WHERE category_id = '" . (int)$data['parent_id'] . "' ORDER BY `level` ASC");

		foreach ($this->db->get()->result_array() as $result) {
			$this->db->set($this->primaryKey, (int)$category_id);
			$this->db->set('path_id', (int)$result['path_id']);
			$this->db->set('level', (int)$level);

			//$this->db->query("INSERT INTO `" . DB_PREFIX . "category_path` SET `category_id` = '" . (int)$category_id . "', `path_id` = '" . (int)$result['path_id'] . "', `level` = '" . (int)$level . "'");
			$this->db->insert('category_path');

			$level++;
		}

		$this->db->set($this->primaryKey, (int)$category_id);
		$this->db->set('level', (int)$level);
		$this->db->set('path_id', (int)$category_id);
		$this->db->insert('category_path');
		//$this->db->query("INSERT INTO `" . DB_PREFIX . "category_path` SET `category_id` = '" . (int)$category_id . "', `path_id` = '" . (int)$category_id . "', `level` = '" . (int)$level . "'");

		if (isset($data['category_filter'])) {
			foreach ($data['category_filter'] as $filter_id) {
				$this->db->set('category_id', (int)$category_id);
				$this->db->set('filter_id', (int)$filter_id);
				$this->db->insert('category_filter');
				//$this->db->query("INSERT INTO " . DB_PREFIX . "category_filter SET category_id = '" . (int)$category_id . "', filter_id = '" . (int)$filter_id . "'");
			}
		}

		if (isset($data['category_store'])) {
			foreach ($data['category_store'] as $store_id) {
				$this->db->set('category_id', (int)$category_id);
				$this->db->set('store_id', (int)$store_id);
				$this->db->insert('category_to_store');
				//$this->db->query("INSERT INTO " . DB_PREFIX . "category_to_store SET category_id = '" . (int)$category_id . "', store_id = '" . (int)$store_id . "'");
			}
		}

		// Set which layout to use with this category
		if (isset($data['category_layout'])) {
			foreach ($data['category_layout'] as $store_id => $layout_id) {
				$this->db->set('category_id', (int)$category_id);
				$this->db->set('store_id', (int)$store_id);
				$this->db->set('layout_id', (int)$layout_id);
				$this->db->insert('category_to_layout');
				//$this->db->query("INSERT INTO " . DB_PREFIX . "category_to_layout SET category_id = '" . (int)$category_id . "', store_id = '" . (int)$store_id . "', layout_id = '" . (int)$layout_id . "'");
			}
		}

		if (isset($data['keyword'])) {
			$this->db->set('query', 'category_id=' . (int)$category_id);
			$this->db->set('keyword', ($data['keyword']));
			$this->db->insert('url_alias');
			//$this->db->query("INSERT INTO " . DB_PREFIX . "url_alias SET query = 'category_id=" . (int)$category_id . "', keyword = '" . ($data['keyword']) . "'");
		}

		$this->cache->delete('category');

		$this->event->trigger('post.admin.category.add', $category_id);

		return $category_id;
	}

	public function editCategory($category_id, $data) {
		$this->event->trigger('pre.admin.category.edit', $data);
		$tmp = $this->initData($data, TRUE);
		$tmp['top'] = (isset($data['top']) ? (int)$data['top'] : 0);
		$this->db->set('date_modified', 'NOW()', FALSE);
		$this->db->where($this->primaryKey, (int)$category_id);
		$this->db->update($this->table, $tmp);


		//$this->db->query("UPDATE " . DB_PREFIX . "category SET parent_id = '" . (int)$data['parent_id'] . "', `top` = '" . (isset($data['top']) ? (int)$data['top'] : 0) . "', `column` = '" . (int)$data['column'] . "', sort_order = '" . (int)$data['sort_order'] . "', status = '" . (int)$data['status'] . "', date_modified = NOW() WHERE category_id = '" . (int)$category_id . "'");

		if (isset($data['image'])) {
			$this->db->where($this->primaryKey, (int)$category_id);
			$this->db->set('image', ($data['image']));
			$this->db->update($this->table);

			//$this->db->query("UPDATE " . DB_PREFIX . "category SET image = '" . ($data['image']) . "' WHERE category_id = '" . (int)$category_id . "'");
		}


		$this->db->where($this->primaryKey, (int)$category_id);
		$this->db->delete('category_description');
		//$this->db->query("DELETE FROM " . DB_PREFIX . "category_description WHERE category_id = '" . (int)$category_id . "'");

		foreach ($data['category_description'] as $language_id => $value) {

			$this->db->set($this->primaryKey, (int)$category_id);
			$this->db->set('language_id', (int)$language_id);
			$this->db->set('name', ($value['name']));
			$this->db->set('description', ($value['description']));
			$this->db->set('meta_title', ($value['meta_title']));
			$this->db->set('meta_description', ($value['meta_description']));
			$this->db->set('meta_keyword', ($value['meta_keyword']));
			$this->db->insert('category_description');
			//$this->db->query("INSERT INTO " . DB_PREFIX . "category_description SET category_id = '" . (int)$category_id . "', language_id = '" . (int)$language_id . "', name = '" . ($value['name']) . "', description = '" . ($value['description']) . "', meta_title = '" . ($value['meta_title']) . "', meta_description = '" . ($value['meta_description']) . "', meta_keyword = '" . ($value['meta_keyword']) . "'");
		}

		// MySQL Hierarchical Data Closure Table Pattern
		$this->db->select('*');
		$this->db->from('category_path');
		$this->db->where('path_id', (int)$category_id);
		$this->db->order_by('level', 'ASC');

		//$query = $this->db->query("SELECT * FROM `" . DB_PREFIX . "category_path` WHERE path_id = '" . (int)$category_id . "' ORDER BY level ASC");
		$query = $this->db->get()->result_array();
		if (!empty($query)) {
			foreach ($query as $category_path) {
				// Delete the path below the current one
				$this->db->where($this->primaryKey, (int)$category_path['category_id']);
				$this->db->where('level <', (int)$category_path['level']);
				$this->db->delete('category_path');

				//$this->db->query("DELETE FROM `" . DB_PREFIX . "category_path` WHERE category_id = '" . (int)$category_path['category_id'] . "' AND level < '" . (int)$category_path['level'] . "'");

				$path = array();

				// Get the nodes new parents
				$this->db->select('*')->from('category_path')->where($this->primaryKey, (int)$data['parent_id'])->order_by('level', 'ASC');

				//$query = $this->db->query("SELECT * FROM `" . DB_PREFIX . "category_path` WHERE category_id = '" . (int)$data['parent_id'] . "' ORDER BY level ASC");

				foreach ($this->db->get()->result_array() as $result) {
					$path[] = $result['path_id'];
				}

				// Get whats left of the nodes current path
				$this->db->select('*')->from('category_path')->where($this->primaryKey, (int)$category_path['category_id'])->order_by('level', 'ASC');

				//$query = $this->db->query("SELECT * FROM `" . DB_PREFIX . "category_path` WHERE category_id = '" . (int)$category_path['category_id'] . "' ORDER BY level ASC");

				foreach ($this->db->get()->result_array() as $result) {
					$path[] = $result['path_id'];
				}

				// Combine the paths with a new level
				$level = 0;

				foreach ($path as $path_id) {
					$this->db->query("REPLACE INTO `" . DB_PREFIX . "category_path` SET category_id = '" . (int)$category_path['category_id'] . "', `path_id` = '" . (int)$path_id . "', level = '" . (int)$level . "'");

					$level++;
				}
			}
		} else {
			// Delete the path below the current one
			$this->db->where($this->primaryKey, (int)$category_id);
			$this->db->delete('category_path');

			//$this->db->query("DELETE FROM `" . DB_PREFIX . "category_path` WHERE category_id = '" . (int)$category_id . "'");

			// Fix for records with no paths
			$level = 0;


			$this->db->select('*')->from('category_path')->where($this->primaryKey, (int)$data['parent_id'])->order_by('level', 'ASC');
			//$this->db->where()
			//$query = $this->db->query("SELECT * FROM `" . DB_PREFIX . "category_path` WHERE category_id = '" . (int)$data['parent_id'] . "' ORDER BY level ASC");

			foreach ($this->db->get()->result_array() as $result) {
				$this->db->set($this->primaryKey, (int)$category_id);
				$this->db->set('path_id', (int)$result['path_id']);
				$this->db->set('level', (int)$level);
				$this->db->insert('category_path');

				//$this->db->query("INSERT INTO `" . DB_PREFIX . "category_path` SET category_id = '" . (int)$category_id . "', `path_id` = '" . (int)$result['path_id'] . "', level = '" . (int)$level . "'");

				$level++;
			}

			$this->db->query("REPLACE INTO `" . DB_PREFIX . "category_path` SET category_id = '" . (int)$category_id . "', `path_id` = '" . (int)$category_id . "', level = '" . (int)$level . "'");
		}

		$this->db->where($this->primaryKey, (int)$category_id);
		$this->db->delete('category_filter');

		//$this->db->query("DELETE FROM " . DB_PREFIX . "category_filter WHERE category_id = '" . (int)$category_id . "'");

		if (isset($data['category_filter'])) {
			foreach ($data['category_filter'] as $filter_id) {
				$this->db->set($this->primaryKey, (int)$category_id);
				$this->db->set('filter_id', (int)$filter_id);
				$this->db->insert('category_filter');

				//$this->db->query("INSERT INTO " . DB_PREFIX . "category_filter SET category_id = '" . (int)$category_id . "', filter_id = '" . (int)$filter_id . "'");
			}
		}

		$this->db->where($this->primaryKey, (int)$category_id);
		$this->db->delete('category_to_store');

		//$this->db->query("DELETE FROM " . DB_PREFIX . "category_to_store WHERE category_id = '" . (int)$category_id . "'");

		if (isset($data['category_store'])) {
			foreach ($data['category_store'] as $store_id) {
				$this->db->set($this->primaryKey, (int)$category_id);
				$this->db->set('store_id', (int)$store_id);
				$this->db->insert('category_to_store');
				//$this->db->query("INSERT INTO " . DB_PREFIX . "category_to_store SET category_id = '" . (int)$category_id . "', store_id = '" . (int)$store_id . "'");
			}
		}

		$this->db->where($this->primaryKey, (int)$category_id);
		$this->db->delete('category_to_layout');

		//$this->db->query("DELETE FROM " . DB_PREFIX . "category_to_layout WHERE category_id = '" . (int)$category_id . "'");

		if (isset($data['category_layout'])) {
			foreach ($data['category_layout'] as $store_id => $layout_id) {
				$this->db->set($this->primaryKey, (int)$category_id);
				$this->db->set('store_id', (int)$store_id);
				$this->db->set('layout_id', (int)$layout_id);
				$this->db->insert('category_to_layout');

				//$this->db->query("INSERT INTO " . DB_PREFIX . "category_to_layout SET category_id = '" . (int)$category_id . "', store_id = '" . (int)$store_id . "', layout_id = '" . (int)$layout_id . "'");
			}
		}

		$this->db->where('query', 'category_id=' . (int)$category_id);
		$this->db->delete('url_alias');

		//$this->db->query("DELETE FROM " . DB_PREFIX . "url_alias WHERE query = 'category_id=" . (int)$category_id . "'");

		if ($data['keyword']) {
			$this->db->set('query', 'category_id=' . (int)$category_id);
			$this->db->set('keyword', ($data['keyword']));
			$this->db->insert('url_alias');
			//$this->db->query("INSERT INTO " . DB_PREFIX . "url_alias SET query = 'category_id=" . (int)$category_id . "', keyword = '" . ($data['keyword']) . "'");
		}

		$this->cache->delete('category');

		$this->event->trigger('post.admin.category.edit', $category_id);
	}

	public function editStatus($category_id, $status) {
		$this->db->set('status', (int)$status);
		$this->db->where($this->primaryKey, (int)$category_id);
		$this->db->update($this->table);
	}

	public function deleteCategory($category_id) {

		$this->event->trigger('pre.admin.category.delete', $category_id);
		$this->db->where($this->primaryKey, (int)$category_id);
		$this->db->delete('category_path');


		//$this->db->query("DELETE FROM " . DB_PREFIX . "category_path WHERE category_id = '" . (int)$category_id . "'");

		//$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "category_path WHERE path_id = '" . (int)$category_id . "'");
		$this->db->select('*')->from('category_path')->where('path_id', (int)$category_id);

		foreach ($this->db->get()->result_array() as $result) {
			$this->deleteCategory($result['category_id']);
		}

		$this->db->where($this->primaryKey, (int)$category_id);
		$this->db->delete(array($this->table, 'category_description', 'category_filter', 'category_to_store', 'category_to_layout', 'product_to_category'));

		/*$this->db->query("DELETE FROM " . DB_PREFIX . "category WHERE category_id = '" . (int)$category_id . "'");
		$this->db->query("DELETE FROM " . DB_PREFIX . "category_description WHERE category_id = '" . (int)$category_id . "'");
		$this->db->query("DELETE FROM " . DB_PREFIX . "category_filter WHERE category_id = '" . (int)$category_id . "'");
		$this->db->query("DELETE FROM " . DB_PREFIX . "category_to_store WHERE category_id = '" . (int)$category_id . "'");
		$this->db->query("DELETE FROM " . DB_PREFIX . "category_to_layout WHERE category_id = '" . (int)$category_id . "'");
		$this->db->query("DELETE FROM " . DB_PREFIX . "product_to_category WHERE category_id = '" . (int)$category_id . "'");
		$this->db->query("DELETE FROM " . DB_PREFIX . "url_alias WHERE query = 'category_id=" . (int)$category_id . "'");*/
		$this->db->where('query', 'category_id=' . (int)$category_id);
		$this->db->delete('url_alias');

		$this->cache->delete('category');

		$this->event->trigger('post.admin.category.delete', $category_id);
	}

	public function repairCategories($parent_id = 0) {
		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "category WHERE parent_id = '" . (int)$parent_id . "'");

		foreach ($query->rows as $category) {
			// Delete the path below the current one
			$this->db->query("DELETE FROM `" . DB_PREFIX . "category_path` WHERE category_id = '" . (int)$category['category_id'] . "'");

			// Fix for records with no paths
			$level = 0;

			$query = $this->db->query("SELECT * FROM `" . DB_PREFIX . "category_path` WHERE category_id = '" . (int)$parent_id . "' ORDER BY level ASC");

			foreach ($query->rows as $result) {
				$this->db->query("INSERT INTO `" . DB_PREFIX . "category_path` SET category_id = '" . (int)$category['category_id'] . "', `path_id` = '" . (int)$result['path_id'] . "', level = '" . (int)$level . "'");

				$level++;
			}

			$this->db->query("REPLACE INTO `" . DB_PREFIX . "category_path` SET category_id = '" . (int)$category['category_id'] . "', `path_id` = '" . (int)$category['category_id'] . "', level = '" . (int)$level . "'");

			$this->repairCategories($category['category_id']);
		}
	}

	public function getCategory($category_id) {
		$query = $this->db->query("SELECT DISTINCT *, (SELECT GROUP_CONCAT(cd1.name ORDER BY level SEPARATOR '&nbsp;&nbsp;&gt;&nbsp;&nbsp;') FROM " . DB_PREFIX . "category_path cp LEFT JOIN " . DB_PREFIX . "category_description cd1 ON (cp.path_id = cd1.category_id AND cp.category_id != cp.path_id) WHERE cp.category_id = c.category_id AND cd1.language_id = '" . (int)$this->config->get('config_language_id') . "' GROUP BY cp.category_id) AS path, (SELECT DISTINCT keyword FROM " . DB_PREFIX . "url_alias WHERE query = 'category_id=" . (int)$category_id . "') AS keyword FROM " . DB_PREFIX . "category c LEFT JOIN " . DB_PREFIX . "category_description cd2 ON (c.category_id = cd2.category_id) WHERE c.category_id = '" . (int)$category_id . "' AND cd2.language_id = '" . (int)$this->config->get('config_language_id') . "'");
		$this->db->last_query();
		return $query->row_array();
	}

	public function getCategories($data = array()) {
		$this->db->select('cp.category_id AS category_id, c1.status AS status, GROUP_CONCAT(cd1.name ORDER BY cp.level SEPARATOR \'&nbsp;&nbsp;&gt;&nbsp;&nbsp;\') AS name, c1.parent_id, c1.sort_order');
		$this->db->from('category_path cp');
		$this->db->join('category c1', 'cp.category_id = c1.category_id', 'left');
		$this->db->join('category c2', 'cp.path_id = c2.category_id', 'left');
		$this->db->join('category_description cd1', 'cp.path_id = cd1.category_id', 'left');
		$this->db->join('category_description cd2', 'cp.category_id = cd2.category_id', 'left');
		$this->db->where('cd1.language_id', (int)$this->config->get('config_language_id'));
		$this->db->where('cd2.language_id', (int)$this->config->get('config_language_id'));
		
		if (!empty($data['filter_name'])) {
			$this->db->like('cd2.name', $data['filter_name']);
		}
		
		$this->db->group_by('cp.category_id');
		
		if (isset($data['order']) && ($data['order'] == 'DESC')) {
			$order = 'DESC';
		} else {
			$order = 'ASC';
		}
		
		$sort_data = array(
			'name',
			'sort_order',
			'status'
		);
		
		if (isset($data['sort']) && in_array($data['sort'], $sort_data)) {
			$this->db->order_by($data['sort'], $order);
		} else {
			$this->db->order_by('sort_order', $order);
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
	}

	public function getCategoryDescriptions($category_id) {
		$category_description_data = array();
		
		$this->db->select('*');
		$this->db->where('category_id', (int)$category_id);
		$query = $this->db->get('category_description');
		
		foreach ($query->result_array() as $result) {
			$category_description_data[$result['language_id']] = array(
				'name'             => $result['name'],
				'meta_title'       => $result['meta_title'],
				'meta_description' => $result['meta_description'],
				'meta_keyword'     => $result['meta_keyword'],
				'description'      => $result['description']
			);
		}

		return $category_description_data;
	}

	public function getCategoryFilters($category_id) {
		$category_filter_data = array();
		
		$this->db->select('*');
		$this->db->where('category_id', (int)$category_id);
		$query = $this->db->get('category_filter');

		foreach ($query->result_array() as $result) {
			$category_filter_data[] = $result['filter_id'];
		}

		return $category_filter_data;
	}

	public function getCategoryStores($category_id) {
		$category_store_data = array();
		
		$this->db->select('*');
		$this->db->where('category_id', (int)$category_id);
		$query = $this->db->get('category_to_store');

		foreach ($query->result_array() as $result) {
			$category_store_data[] = $result['store_id'];
		}

		return $category_store_data;
	}

	public function getCategoryLayouts($category_id) {
		$category_layout_data = array();
		
		$this->db->select('*');
		$this->db->where('category_id', (int)$category_id);
		$query = $this->db->get('category_to_layout');
		
		foreach ($query->result_array() as $result) {
			$category_layout_data[$result['store_id']] = $result['layout_id'];
		}

		return $category_layout_data;
	}

	public function getTotalCategories() {
		$this->db->select('COUNT(*) AS `total`');
		$query = $this->db->get('category')->row_array();
		return $query['total'];
	}
	
	public function getTotalCategoriesByLayoutId($layout_id) {
		$this->db->select('COUNT(*) AS `total`');
		$this->db->where('layout_id', (int)$layout_id);
		$query = $this->db->get('category_to_layout')->row_array();
		return $query['total'];
	}	
}
