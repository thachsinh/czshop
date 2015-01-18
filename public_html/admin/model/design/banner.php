<?php
class ModelDesignBanner extends Model {
	public $table = 'banner';
	public $primaryKey = 'banner_id';
	public $fields = array();

	public function addBanner($data) {
		$this->event->trigger('pre.admin.banner.add', $data);

		//$this->db->query("INSERT INTO " . DB_PREFIX . "banner SET name = '" . $this->db->escape($data['name']) . "', status = '" . (int)$data['status'] . "'");

		//$banner_id = $this->db->getLastId();
		$tmp = $this->initData($data);
		$this->db->insert($this->table, $tmp);
		$banner_id = $this->db->insert_id();

		if (isset($data['banner_image'])) {
			foreach ($data['banner_image'] as $banner_image) {
				//$this->db->query("INSERT INTO " . DB_PREFIX . "banner_image SET banner_id = '" . (int)$banner_id . "', link = '" .  $this->db->escape($banner_image['link']) . "', image = '" .  $this->db->escape($banner_image['image']) . "', sort_order = '" . (int)$banner_image['sort_order'] . "'");
				$this->db->set($this->primaryKey, (int)$banner_id);
				$this->db->set('link', $this->db->escape($banner_image['link']));
				$this->db->set('image', $this->db->escape($banner_image['image']));
				$this->db->set('sort_order', (int)$banner_image['sort_order']);
				$this->db->insert('banner_image');
				$banner_image_id = $this->db->insert_id();
				//$banner_image_id = $this->db->getLastId();

				foreach ($banner_image['banner_image_description'] as $language_id => $banner_image_description) {
					$this->db->set('banner_image_id', (int)$banner_image_id);
					$this->db->set('language_id', (int)$language_id);
					$this->db->set('banner_id', (int)$banner_id);
					$this->db->set('title', $this->db->escape($banner_image_description['title']));
					$this->db->insert('banner_image_description');
					//$this->db->query("INSERT INTO " . DB_PREFIX . "banner_image_description SET banner_image_id = '" . (int)$banner_image_id . "', language_id = '" . (int)$language_id . "', banner_id = '" . (int)$banner_id . "', title = '" .  $this->db->escape($banner_image_description['title']) . "'");
				}
			}
		}

		$this->event->trigger('post.admin.banner.add', $banner_id);

		return $banner_id;
	}

	public function editBanner($banner_id, $data) {
		$this->event->trigger('pre.admin.banner.edit', $data);
		$tmp = $this->initData($data, TRUE);
		$this->db->where($this->primaryKey, (int)$banner_id);
		$this->db->update($this->table, $tmp);

		//$this->db->query("UPDATE " . DB_PREFIX . "banner SET name = '" . $this->db->escape($data['name']) . "', status = '" . (int)$data['status'] . "' WHERE banner_id = '" . (int)$banner_id . "'");

		//$this->db->query("DELETE FROM " . DB_PREFIX . "banner_image WHERE banner_id = '" . (int)$banner_id . "'");
		//$this->db->query("DELETE FROM " . DB_PREFIX . "banner_image_description WHERE banner_id = '" . (int)$banner_id . "'");
		$this->db->where($this->primaryKey, (int)$banner_id);
		$this->db->delete(array('banner_image', 'banner_image_description'));

		if (isset($data['banner_image'])) {
			foreach ($data['banner_image'] as $banner_image) {
				//$this->db->query("INSERT INTO " . DB_PREFIX . "banner_image SET banner_id = '" . (int)$banner_id . "', link = '" .  $this->db->escape($banner_image['link']) . "', image = '" .  $this->db->escape($banner_image['image']) . "', sort_order = '" . (int)$banner_image['sort_order'] . "'");
				$this->db->set('banner_id', (int)$banner_id);
				$this->db->set('link', $this->db->escape($banner_image['link']));
				$this->db->set('image', $this->db->escape($banner_image['image']));
				$this->db->set('sort_order', (int)$banner_image['sort_order']);
				$this->db->insert('banner_image');	
				//$banner_image_id = $this->db->getLastId();
				$banner_image_id = $this->db->insert_id();
				foreach ($banner_image['banner_image_description'] as $language_id => $banner_image_description) {
					$this->db->set('banner_image_id', (int)$banner_image_id);
					$this->db->set('language_id', (int)$language_id);
					$this->db->set($this->primaryKey, (int)$banner_id);
					$this->db->set('title', $this->db->escape($banner_image_description['title']));
					$this->db->insert('banner_image_description');
					//$this->db->query("INSERT INTO " . DB_PREFIX . "banner_image_description SET banner_image_id = '" . (int)$banner_image_id . "', language_id = '" . (int)$language_id . "', banner_id = '" . (int)$banner_id . "', title = '" .  $this->db->escape($banner_image_description['title']) . "'");
				}
			}
		}

		$this->event->trigger('post.admin.banner.edit', $banner_id);
	}

	public function deleteBanner($banner_id) {
		$this->event->trigger('pre.admin.banner.delete', $banner_id);
		$this->db->where($this->primaryKey, (int)$banner_id);
		$this->db->delete(array($this->table, 'banner_image', 'banner_image_description'));
		/*
		$this->db->query("DELETE FROM " . DB_PREFIX . "banner WHERE banner_id = '" . (int)$banner_id . "'");
		$this->db->query("DELETE FROM " . DB_PREFIX . "banner_image WHERE banner_id = '" . (int)$banner_id . "'");
		$this->db->query("DELETE FROM " . DB_PREFIX . "banner_image_description WHERE banner_id = '" . (int)$banner_id . "'");
		*/
		$this->event->trigger('post.admin.banner.delete', $banner_id);
	}

	public function getBanner($banner_id) {
		$this->db->distinct();
		$this->db->from($this->table);
		$this->db->where($this->primaryKey, (int)$banner_id);
		//$query = $this->db->query("SELECT DISTINCT * FROM " . DB_PREFIX . "banner WHERE banner_id = '" . (int)$banner_id . "'");

		//return $query->row;
		return $this->db->get()->row_array();
	}

	public function getBanners($data = array()) {
		$this->db->select('*');
		$this->db->from($this->table);

		//$sql = "SELECT * FROM " . DB_PREFIX . "banner";

		$sort_data = array(
			'name',
			'status'
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

		//$query = $this->db->query($sql);

		//return $query->rows;
		return $this->db->get()->result_array();
	}

	public function getBannerImages($banner_id) {
		$banner_image_data = array();
		$this->db->select('*');
		$this->db->from('banner_image');
		$this->db->where($this->primaryKey, (int)$banner_id);
		$banner_image_query = $this->db->get();

		//$banner_image_query = $this->db->query("SELECT * FROM " . DB_PREFIX . "banner_image WHERE banner_id = '" . (int)$banner_id . "' ORDER BY sort_order ASC");

		foreach ($banner_image_query->result_array() as $banner_image) {
			$banner_image_description_data = array();

			$this->db->select('*');
			$this->db->from('banner_image_description');
			$this->db->where($this->primaryKey, (int)$banner_id);
			$banner_image_description_query = $this->db->get();
			//$banner_image_description_query = $this->db->query("SELECT * FROM " . DB_PREFIX . "banner_image_description WHERE banner_image_id = '" . (int)$banner_image['banner_image_id'] . "' AND banner_id = '" . (int)$banner_id . "'");

			foreach ($banner_image_description_query->result_array() as $banner_image_description) {
				$banner_image_description_data[$banner_image_description['language_id']] = array('title' => $banner_image_description['title']);
			}

			$banner_image_data[] = array(
				'banner_image_description' => $banner_image_description_data,
				'link'                     => $banner_image['link'],
				'image'                    => $banner_image['image'],
				'sort_order'               => $banner_image['sort_order']
			);
		}

		return $banner_image_data;
	}

	public function getTotalBanners() {
		$this->db->select('COUNT(*) AS `total`');
		$query = $this->db->get($this->table)->row_array();
		return $query['total'];
		//$query = $this->db->query("SELECT COUNT(*) AS total FROM " . DB_PREFIX . "banner");

		//return $query->row['total'];
	}
}