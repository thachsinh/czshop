<?php
class ModelCatalogAttribute extends Model {
	public $table = 'attribute';
	public $fields = array();
	public $desc_fields = array();
	public $primaryKey = 'attribute_id';

	public function addAttribute($data) {
		$this->event->trigger('pre.admin.attribute.add', $data);

		$tmp = $this->initData($data, TRUE);
		$this->db->insert($this->table, $tmp);
		//$this->db->query("INSERT INTO " . DB_PREFIX . "attribute SET attribute_group_id = '" . (int)$data['attribute_group_id'] . "', sort_order = '" . (int)$data['sort_order'] . "'");

		//$attribute_id = $this->db->getLastId();
		$attribute_id = $this->db->insert_id();

		foreach ($data['attribute_description'] as $language_id => $value) {
			$tmp = $this->initData($data['attribute_description'][$language_id], TRUE, $this->desc_fields);
			$this->db->set($this->primaryKey, (int)$attribute_id);
			$this->db->set('language_id', (int)$language_id);
			$this->db->insert('attribute_description', $tmp);
			//$this->db->query("INSERT INTO " . DB_PREFIX . "attribute_description SET attribute_id = '" . (int)$attribute_id . "', language_id = '" . (int)$language_id . "', name = '" . $this->db->escape($value['name']) . "'");
		}

		$this->event->trigger('post.admin.attribute.add', $attribute_id);

		return $attribute_id;
	}

	public function editAttribute($attribute_id, $data) {
		$this->event->trigger('pre.admin.attribute.edit', $data);

		$tmp = $this->initData($data, TRUE);
		$this->db->where($this->primaryKey, (int)$attribute_id);
		$this->db->update($this->table, $tmp);

		//$this->db->query("UPDATE " . DB_PREFIX . "attribute SET attribute_group_id = '" . (int)$data['attribute_group_id'] . "', sort_order = '" . (int)$data['sort_order'] . "' WHERE attribute_id = '" . (int)$attribute_id . "'");

		$this->db->where($this->primaryKey, (int)$attribute_id);
		$this->db->delete('attribute_description');
		//$this->db->query("DELETE FROM " . DB_PREFIX . "attribute_description WHERE attribute_id = '" . (int)$attribute_id . "'");

		foreach ($data['attribute_description'] as $language_id => $value) {
			$tmp = $this->initData($data['attribute_description'][$language_id], TRUE, $this->desc_fields);
			$this->db->set($this->primaryKey, (int)$attribute_id);
			$this->db->set('language_id', (int)$language_id);
			$this->db->insert('attribute_description', $tmp);
			//$this->db->query("INSERT INTO " . DB_PREFIX . "attribute_description SET attribute_id = '" . (int)$attribute_id . "', language_id = '" . (int)$language_id . "', name = '" . $this->db->escape($value['name']) . "'");
		}

		$this->event->trigger('post.admin.attribute.edit', $attribute_id);
	}

	public function deleteAttribute($attribute_id) {
		$this->event->trigger('pre.admin.attribute.delete', $attribute_id);

		$this->db->where($this->primaryKey, (int)$attribute_id);
		$this->db->delete(array($this->table, 'attribute_description'));
		//$this->db->query("DELETE FROM " . DB_PREFIX . "attribute WHERE attribute_id = '" . (int)$attribute_id . "'");
		//$this->db->query("DELETE FROM " . DB_PREFIX . "attribute_description WHERE attribute_id = '" . (int)$attribute_id . "'");

		$this->event->trigger('post.admin.attribute.delete', $attribute_id);
	}

	public function getAttribute($attribute_id) {
		$this->db->select('*');
		$this->db->from($this->table . ' a');
		$this->db->join('attribute_description ad', 'a.attribute_id = ad.attribute_id', 'left');
		$this->db->where('a.attribute_id', (int)$attribute_id);
		$this->db->where('ad.language_id', (int)$this->config->get('config_language_id'));

		//$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "attribute a LEFT JOIN " . DB_PREFIX . "attribute_description ad ON (a.attribute_id = ad.attribute_id) WHERE a.attribute_id = '" . (int)$attribute_id . "' AND ad.language_id = '" . (int)$this->config->get('config_language_id') . "'");

		//return $query->row;
		return $this->db->get()->row_array();
	}

	public function getAttributes($data = array()) {
		$sql = "SELECT *, (SELECT agd.name FROM " . DB_PREFIX . "attribute_group_description agd WHERE agd.attribute_group_id = a.attribute_group_id AND agd.language_id = '" . (int)$this->config->get('config_language_id') . "') AS attribute_group FROM " . DB_PREFIX . "attribute a LEFT JOIN " . DB_PREFIX . "attribute_description ad ON (a.attribute_id = ad.attribute_id) WHERE ad.language_id = '" . (int)$this->config->get('config_language_id') . "'";

		if (!empty($data['filter_name'])) {
			$sql .= " AND ad.name LIKE '" . $this->db->escape($data['filter_name']) . "%'";
		}

		if (!empty($data['filter_attribute_group_id'])) {
			$sql .= " AND a.attribute_group_id = '" . $this->db->escape($data['filter_attribute_group_id']) . "'";
		}

		$sort_data = array(
			'ad.name',
			'attribute_group',
			'a.sort_order'
		);

		if (isset($data['sort']) && in_array($data['sort'], $sort_data)) {
			$sql .= " ORDER BY " . $data['sort'];
		} else {
			$sql .= " ORDER BY attribute_group, ad.name";
		}

		if (isset($data['order']) && ($data['order'] == 'DESC')) {
			$sql .= " DESC";
		} else {
			$sql .= " ASC";
		}

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

	public function getAttributeDescriptions($attribute_id) {
		$attribute_data = array();
		$this->db->select('*')->from('attribute_description');
		$this->db->where($this->primaryKey, (int)$attribute_id);

		//$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "attribute_description WHERE attribute_id = '" . (int)$attribute_id . "'");

		foreach ($this->db->get()->result_array() as $result) {
			$attribute_data[$result['language_id']] = array('name' => $result['name']);
		}

		return $attribute_data;
	}

	public function getAttributesByAttributeGroupId($data = array()) {
		$sql = "SELECT *, (SELECT agd.name FROM " . DB_PREFIX . "attribute_group_description agd WHERE agd.attribute_group_id = a.attribute_group_id AND agd.language_id = '" . (int)$this->config->get('config_language_id') . "') AS attribute_group FROM " . DB_PREFIX . "attribute a LEFT JOIN " . DB_PREFIX . "attribute_description ad ON (a.attribute_id = ad.attribute_id) WHERE ad.language_id = '" . (int)$this->config->get('config_language_id') . "'";

		if (!empty($data['filter_name'])) {
			$sql .= " AND ad.name LIKE '" . $this->db->escape($data['filter_name']) . "%'";
		}

		if (!empty($data['filter_attribute_group_id'])) {
			$sql .= " AND a.attribute_group_id = '" . $this->db->escape($data['filter_attribute_group_id']) . "'";
		}

		$sort_data = array(
			'ad.name',
			'attribute_group',
			'a.sort_order'
		);

		if (isset($data['sort']) && in_array($data['sort'], $sort_data)) {
			$sql .= " ORDER BY " . $data['sort'];
		} else {
			$sql .= " ORDER BY ad.name";
		}

		if (isset($data['order']) && ($data['order'] == 'DESC')) {
			$sql .= " DESC";
		} else {
			$sql .= " ASC";
		}

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

	public function getTotalAttributes() {
		$this->db->select('COUNT(*) AS `total`');
		$query = $this->db->get($this->table)->row_array();
		return $query['total'];

		//$query = $this->db->query("SELECT COUNT(*) AS total FROM " . DB_PREFIX . "attribute");

		//return $query->row['total'];
	}

	public function getTotalAttributesByAttributeGroupId($attribute_group_id) {
		$this->db->select('COUNT(*) AS `total`');
		$this->db->where('attribute_group_id', (int)$attribute_group_id);
		$query = $this->db->get($this->table)->row_array();
		return $query['total'];

		//$query = $this->db->query("SELECT COUNT(*) AS total FROM " . DB_PREFIX . "attribute WHERE attribute_group_id = '" . (int)$attribute_group_id . "'");

		//return $query->row['total'];
	}
}