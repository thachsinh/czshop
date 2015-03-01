<?php
class ModelCatalogCustomFieldGroup extends Model {
	public $table = 'custom_field_product_group';
	public $primaryKey = 'custom_field_group_id';
	public $fields = array('custom_field_group_id', 'sort_order');
	public $desc_table = 'custom_field_product_group_description';
	public $desc_fields = array('custom_field_group_id', 'language_id', 'name', 'description');

	public function addCustomFieldGroup($data) {
		$tmp = $this->initData($data, TRUE);
		$this->db->insert($this->table, $tmp);
		$custom_field_group_id = $this->db->insert_id();

		foreach ($data['custom_field_group_description'] as $language_id => $value) {
			$tmp = $this->initData($data['custom_field_group_description'][$language_id], TRUE, $this->desc_fields);
			$this->db->set($this->primaryKey, $custom_field_group_id);
			$this->db->set('language_id', (int)$language_id);
			$this->db->insert($this->desc_table, $tmp);
		}
	}

	public function editCustomFieldGroup($custom_field_group_id, $data) {
		$tmp = $this->initData($data, TRUE);
		$this->db->where($this->primaryKey, (int)$custom_field_group_id);
		$this->db->update($this->table, $tmp);

		$this->db->where($this->primaryKey, (int)$custom_field_group_id);
		$this->db->delete($this->desc_table);

		foreach ($data['custom_field_group_description'] as $language_id => $value) {
			$tmp = $this->initData($data['custom_field_group_description'][$language_id], TRUE, $this->desc_fields);
			$this->db->set($this->primaryKey, $custom_field_group_id);
			$this->db->set('language_id', (int)$language_id);
			$this->db->insert($this->desc_table, $tmp);
		}
	}

	public function deleteCustomFieldGroup($custom_field_group_id) {
		$this->db->where($this->primaryKey, (int)$custom_field_group_id);
		$this->db->delete(array($this->table, $this->desc_table));
	}

	public function getCustomFieldGroup($custom_field_group_id) {
		//echo $custom_field_group_id;
		$this->db->select('*');
		$this->db->from($this->table . ' cg');
		$this->db->join($this->desc_table . ' cgd', 'cg.custom_field_group_id = cgd.custom_field_group_id', 'left');
		$this->db->where('cg.custom_field_group_id', (int)$custom_field_group_id);
		$this->db->where('cgd.language_id', (int)$this->config->get('config_language_id'));

		return $this->db->get()->row_array();
		//var_dump($data);
		//return $data;
		//echo $this->db->last_query();
	}

	public function getCustomFieldGroups($data = array()) {
		$this->db->select('*');
		$this->db->from($this->table . ' cg');
		$this->db->join($this->desc_table . ' cgd', 'cg.custom_field_group_id = cgd.custom_field_group_id', 'left');
		$this->db->where('cgd.language_id', (int)$this->config->get('config_language_id'));

		if (!empty($data['custom_field_group_name'])) {
			$this->db->like('cgd.name', $data['custom_field_group_name']);
		}

		$sort_data = array(
			'cgd.name',
			'cg.sort_order'
		);

		$order = 'ASC';
		if (isset($data['order']) && ($data['order'] == 'DESC')) {
			$order = 'DESC';
		}

		if (isset($data['sort']) && in_array($data['sort'], $sort_data)) {
			$this->db->order_by($data['sort'], $order);
		} else {
			$this->db->order_by('cgd.name', $order);
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

	public function getCustomFieldGroupDescriptions($custom_field_group_id) {
		$custom_field_group_data = array();

		$this->db->select('*');
		$this->db->from($this->desc_table);
		$this->db->where($this->primaryKey, (int)$custom_field_group_id);

		foreach ($this->db->get()->result_array() as $result) {
			$custom_field_group_data[$result['language_id']] = array(
				'name'        => $result['name'],
				'description' => $result['description']
			);
		}

		return $custom_field_group_data;
	}

	public function getTotalCustomFieldGroups() {
		$this->db->select('COUNT(*) AS `total`');
		$query = $this->db->get($this->table)->row_array();
		return $query['total'];
	}
}