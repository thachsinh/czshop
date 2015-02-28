<?php
class ModelCatalogAttributeGroup extends Model {
	public $table = 'attribute_group';
	public $fields = array();
	public $desc_fields = array();
	public $primaryKey = 'attribute_group_id';

	public function addAttributeGroup($data) {
		$this->event->trigger('pre.admin.attribute_group.add', $data);

		$tmp = $this->initData($data, TRUE);
		$this->db->insert($this->table, $tmp);
		//$this->db->query("INSERT INTO " . DB_PREFIX . "attribute_group SET sort_order = '" . (int)$data['sort_order'] . "'");

		//$attribute_group_id = $this->db->getLastId();
		$attribute_group_id = $this->db->insert_id();

		foreach ($data['attribute_group_description'] as $language_id => $value) {
			//$this->db->query("INSERT INTO " . DB_PREFIX . "attribute_group_description SET attribute_group_id = '" . (int)$attribute_group_id . "', language_id = '" . (int)$language_id . "', name = '" . $this->db->escape($value['name']) . "'");

			$tmp = $this->initData($data['attribute_group_description'][$language_id], TRUE, $this->desc_fields);
			$this->db->set($this->primaryKey, (int)$attribute_group_id);
			$this->db->set('language_id', (int)$language_id);
			$this->db->insert('attribute_group_description', $tmp);
		}

		$this->event->trigger('post.admin.attribute_group.add', $attribute_group_id);

		return $attribute_group_id;
	}

	public function editAttributeGroup($attribute_group_id, $data) {
		$this->event->trigger('pre.admin.attribute_group.edit', $data);

		$tmp = $this->initData($data, TRUE);
		$this->db->where($this->primaryKey, (int)$attribute_group_id);
		$this->db->update($this->table, $tmp);

		//$this->db->query("UPDATE " . DB_PREFIX . "attribute_group SET sort_order = '" . (int)$data['sort_order'] . "' WHERE attribute_group_id = '" . (int)$attribute_group_id . "'");

		$this->db->where($this->primaryKey, (int)$attribute_group_id);
		$this->db->delete('attribute_group_description');

		//$this->db->query("DELETE FROM " . DB_PREFIX . "attribute_group_description WHERE attribute_group_id = '" . (int)$attribute_group_id . "'");

		foreach ($data['attribute_group_description'] as $language_id => $value) {
			$tmp = $this->initData($data['attribute_group_description'][$language_id], TRUE, $this->desc_fields);
			$this->db->set($this->primaryKey, (int)$attribute_group_id);
			$this->db->set('language_id', (int)$language_id);
			$this->db->insert('attribute_group_description', $tmp);

			//$this->db->query("INSERT INTO " . DB_PREFIX . "attribute_group_description SET attribute_group_id = '" . (int)$attribute_group_id . "', language_id = '" . (int)$language_id . "', name = '" . $this->db->escape($value['name']) . "'");
		}

		$this->event->trigger('post.admin.attribute_group.edit', $attribute_group_id);
	}

	public function deleteAttributeGroup($attribute_group_id) {
		$this->event->trigger('pre.admin.attribute_group.delete', $attribute_group_id);

		$this->db->where($this->primaryKey, (int)$attribute_group_id);
		$this->db->delete(array($this->table, 'attribute_group_description'));

		//$this->db->query("DELETE FROM " . DB_PREFIX . "attribute_group WHERE attribute_group_id = '" . (int)$attribute_group_id . "'");
		//$this->db->query("DELETE FROM " . DB_PREFIX . "attribute_group_description WHERE attribute_group_id = '" . (int)$attribute_group_id . "'");

		$this->event->trigger('post.admin.attribute_group.delete', $attribute_group_id);
	}

	public function getAttributeGroup($attribute_group_id) {
		$this->db->select('*')->from($this->table)->where($this->table, (int)$attribute_group_id);
		//$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "attribute_group WHERE attribute_group_id = '" . (int)$attribute_group_id . "'");

		//return $query->row;
		return $this->db->get()->row_array();
	}

	public function getAttributeGroups($data = array()) {

		$this->db->select('*');
		$this->db->from($this->table . ' ag');
		$this->db->join('attribute_group_description agd', 'ag.attribute_group_id = agd.attribute_group_id', 'left');
		$this->db->where('agd.language_id', (int)$this->config->get('config_language_id'));

		//$sql = "SELECT * FROM " . DB_PREFIX . "attribute_group ag LEFT JOIN " . DB_PREFIX . "attribute_group_description agd ON (ag.attribute_group_id = agd.attribute_group_id) WHERE agd.language_id = '" . (int)$this->config->get('config_language_id') . "'";

		$sort_data = array(
			'agd.name',
			'ag.sort_order'
		);
		$order = 'ASC';
		if (isset($data['order']) && ($data['order'] == 'DESC')) {
			$order = 'DESC';
		}

		if (isset($data['sort']) && in_array($data['sort'], $sort_data)) {
			//$sql .= " ORDER BY " . $data['sort'];
			$this->db->order_by($data['sort'], $order);
		} else {
			//$sql .= " ORDER BY agd.name";
			$this->db->order_by('agd.name', $order);
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

	public function getAttributeGroupDescriptions($attribute_group_id) {
		$attribute_group_data = array();
		$this->db->select('*');
		$this->db->from('attribute_group_description');
		$this->db->where($this->primaryKey, (int)$attribute_group_id);


		//$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "attribute_group_description WHERE attribute_group_id = '" . (int)$attribute_group_id . "'");

		foreach ($this->db->get()->result_array() as $result) {
			$attribute_group_data[$result['language_id']] = array('name' => $result['name']);
		}

		return $attribute_group_data;
	}

	public function getTotalAttributeGroups() {
		$this->db->select('COUNT(*) AS `total`');
		$query = $this->db->get($this->table)->row_array();
		return $query['total'];

		//$query = $this->db->query("SELECT COUNT(*) AS total FROM " . DB_PREFIX . "attribute_group");

		//return $query->row['total'];
	}
}