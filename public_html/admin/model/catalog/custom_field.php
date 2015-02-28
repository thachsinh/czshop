<?php
class ModelCatalogCustomField extends Model {
	public $table = 'custom_field_product';
	public $primaryKey = 'custom_field_id';
	public $fields = array('custom_field_id', 'type', 'value', 'status', 'sort_order');
	public $desc_table = 'custom_field_product_description';
	public $desc_fields = array('custom_field_id', 'language_id', 'name');
	public $value_table = 'custom_field_product_value';
	public $desc_value_table = 'custom_field_product_value_description';
	public $group_table = 'custom_field_product_custom_field_product_group';

	public function addCustomField($data) {
		$tmp = $this->initData($data, TRUE);
		$this->db->insert($this->table, $tmp);
		$custom_field_id = $this->db->insert_id();
		foreach ($data['custom_field_description'] as $language_id => $value) {
			$tmp = $this->initData($data['custom_field_description'][$language_id], TRUE, $this->desc_fields);
			$this->db->set($this->primaryKey, $custom_field_id);
			$this->db->set('language_id', (int)$language_id);
			$this->db->insert($this->desc_table, $tmp);
		}

		if (isset($data['custom_field_group'])) {
			foreach ($data['custom_field_group'] as $custom_field_group) {
				if (isset($custom_field_group['custom_field_group_id'])) {
					$this->db->set('custom_field_id', $custom_field_id);
					$this->db->set('custom_field_group_id', (int)$custom_field_group['custom_field_group_id']);
					$this->db->set('required', (int)(isset($custom_field_group['required']) ? 1 : 0));
					$this->db->insert($this->group_table);
				}
			}
		}

		if (isset($data['custom_field_value'])) {
			foreach ($data['custom_field_value'] as $custom_field_value) {
				$this->db->set('custom_field_id', (int)$custom_field_id);
				$this->db->set('sort_order', (int)$custom_field_value['sort_order']);
				$this->db->insert($this->value_table);

				$custom_field_value_id = $this->db->insert_id();

				foreach ($custom_field_value['custom_field_value_description'] as $language_id => $custom_field_value_description) {
					$this->db->set('custom_field_value_id', (int)$custom_field_value_id);
					$this->db->set('custom_field_id', $custom_field_id);
					$this->db->set('language_id', (int)$language_id);
					$this->db->set('name', $custom_field_value_description['name']);
					$this->db->insert($this->desc_value_table);
				}
			}
		}

		$this->tracking->log(LOG_FUNCTION::$catalog_custom_field, LOG_ACTION_ADD, $custom_field_id);
	}

	public function editCustomField($custom_field_id, $data) {
		$tmp = $this->initData($data, TRUE);
		$this->db->where($this->primaryKey, (int)$custom_field_id);
		$this->db->update($this->table, $tmp);
		$this->db->where($this->primaryKey, (int)$custom_field_id);
		$this->db->delete('custom_field_description');

		foreach ($data['custom_field_description'] as $language_id => $value) {
			$tmp = $this->initData($data['custom_field_description'][$language_id], TRUE, $this->desc_fields);
			$this->db->set($this->primaryKey, $custom_field_id);
			$this->db->set('language_id', (int)$language_id);
			$this->db->insert('custom_field_description', $tmp);
		}

		$this->db->where($this->primaryKey, (int)$custom_field_id);
		$this->db->delete($this->group_table);

		if (isset($data['custom_field_group'])) {
			foreach ($data['custom_field_group'] as $custom_field_group) {
				if (isset($custom_field_group['custom_field_group_id'])) {
					//print_r($custom_field_id . '--'. $custom_field_group['custom_field_group_id']); exit;
					$this->db->set('custom_field_id', $custom_field_id);
					$this->db->set('custom_field_group_id', (int)$custom_field_group['custom_field_group_id']);
					$this->db->set('required', (int)(isset($custom_field_group['required']) ? 1 : 0));
					$this->db->insert($this->group_table);
				}
			}
		}

		$this->db->where($this->primaryKey, (int)$custom_field_id);
		$this->db->delete(array($this->value_table, $this->desc_value_table));

		if (isset($data['custom_field_value'])) {
			foreach ($data['custom_field_value'] as $custom_field_value) {
				if ($custom_field_value['custom_field_value_id']) {
					$this->db->set('custom_field_value_id', (int)$custom_field_value['custom_field_value_id']);
					$this->db->set('custom_field_id', (int)$custom_field_id);
					$this->db->set('sort_order', (int)$custom_field_value['sort_order']);
					$this->db->insert($this->value_table);
				} else {
					$this->db->set('custom_field_id', (int)$custom_field_id);
					$this->db->set('sort_order', (int)$custom_field_value['sort_order']);
					$this->db->insert($this->value_table);
				}

				$custom_field_value_id = $this->db->insert_id();

				foreach ($custom_field_value['custom_field_value_description'] as $language_id => $custom_field_value_description) {
					$this->db->set('custom_field_value_id', (int)$custom_field_value_id);
					$this->db->set('custom_field_id', $custom_field_id);
					$this->db->set('language_id', (int)$language_id);
					$this->db->set('name', $custom_field_value_description['name']);
					$this->db->insert($this->desc_value_table);
				}
			}
		}

		$this->tracking->log(LOG_FUNCTION::$catalog_custom_field, LOG_ACTION_MODIFY, $custom_field_id);
	}

	public function editStatus($custom_field_id, $status) {
		$this->db->set('status', (int)$status);
		$this->db->where($this->primaryKey, (int)$custom_field_id);
		$this->db->update($this->table);
		$this->tracking->log(LOG_FUNCTION::$catalog_custom_field, LOG_ACTION_MODIFY, $custom_field_id);
	}

	public function deleteCustomField($custom_field_id) {
		$this->db->where($this->primaryKey, (int)$custom_field_id);
		$this->db->delete(array($this->table, $this->desc_table, $this->value_table, $this->desc_value_table, $this->group_table));
		$this->tracking->log(LOG_FUNCTION::$catalog_custom_field, LOG_ACTION_DELETE, $custom_field_id);
	}

	public function getCustomField($custom_field_id) {
		$this->db->select('*');
		$this->db->from($this->table . ' cf');
		$this->db->join($this->desc_table . ' cfd', 'cf.custom_field_id = cfd.custom_field_id', 'left');
		$this->db->where('cf.custom_field_id', (int)$custom_field_id);
		$this->db->where('cfd.language_id', (int)$this->config->get('config_language_id'));
		return $this->db->get()->row_array();
	}

	public function getCustomFields($data = array()) {
		if (empty($data['filter_custom_field_group_id'])) {
			$this->db->select('*');
			$this->db->from($this->table . ' cf');
			$this->db->join($this->desc_table . ' cfd', 'cf.custom_field_id = cfd.custom_field_id', 'left');
			$this->db->where('cfd.language_id', (int)$this->config->get('config_language_id'));
		} else {
			$this->db->select('*');
			$this->db->from($this->group_table . ' cfcg');
			$this->db->join($this->table . ' cf', 'cfcg.custom_field_id = cf.custom_field_id', 'left');
			$this->db->join($this->desc_table . ' cfd', 'cf.custom_field_id = cfd.custom_field_id', 'left');
			$this->db->where('cfd.language_id', (int)$this->config->get('config_language_id'));
		}

		if (!empty($data['filter_name'])) {
			$this->db->like('cfd.name', $data['filter_name']);
		}

		if (!empty($data['filter_custom_field_group_id'])) {
			$this->db->where('cfcg.custom_field_group_id', (int)$data['filter_custom_field_group_id']);
		}

		$sort_data = array(
			'cfd.name',
			'cf.type',
			'cf.location',
			'cf.status',
			'cf.sort_order'
		);

		$order = 'ASC';
		if (isset($data['order']) && ($data['order'] == 'DESC')) {
			$order = 'DESC';
		}

		if (isset($data['sort']) && in_array($data['sort'], $sort_data)) {
			$this->db->order_by($data['sort'], $order);
		} else {
			$this->db->order_by('cfd.name', $order);
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

	public function getCustomFieldDescriptions($custom_field_id) {
		$custom_field_data = array();

		$this->db->select('*');
		$this->db->from($this->desc_table);
		$this->db->where($this->primaryKey, (int)$custom_field_id);

		foreach ($this->db->get()->result_array() as $result) {
			$custom_field_data[$result['language_id']] = array('name' => $result['name']);
		}

		return $custom_field_data;
	}
	
	public function getCustomFieldValue($custom_field_value_id) {
		$this->db->select('*');
		$this->db->from('custom_field_value cfv');
		$this->db->join('custom_field_value_description cfvd', 'cfv.custom_field_value_id = cfvd.custom_field_value_id', 'left');
		$this->db->where('cfv.custom_field_value_id', (int)$custom_field_value_id);
		$this->db->where('cfvd.language_id', (int)$this->config->get('config_language_id'));
		return $this->db->get()->row_array();
	}
	
	public function getCustomFieldValues($custom_field_id) {
		$custom_field_value_data = array();

		$this->db->select('*');
		$this->db->from($this->value_table . ' cfv');
		$this->db->join($this->desc_value_table . ' cfvd', 'cfv.custom_field_value_id = cfvd.custom_field_value_id', 'left');
		$this->db->where('cfv.custom_field_id', (int)$custom_field_id);
		$this->db->where('cfvd.language_id', (int)$this->config->get('config_language_id'));
		$this->db->order_by('cfv.sort_order', 'ASC');
		$custom_field_value_query = $this->db->get()->result_array();

		foreach ($custom_field_value_query as $custom_field_value) {
			$custom_field_value_data[$custom_field_value['custom_field_value_id']] = array(
				'custom_field_value_id' => $custom_field_value['custom_field_value_id'],
				'name'                  => $custom_field_value['name']
			);
		}

		return $custom_field_value_data;
	}
	
	public function getCustomFieldGroup($custom_field_id) {
		$this->load->model('catalog/custom_field');
		$this->db->select('*');
		$this->db->from($this->group_table);
		$this->db->where($this->primaryKey, (int)$custom_field_id);
		return $this->db->get()->result_array();
	}

	public function getCustomFieldValueDescriptions($custom_field_id) {
		$custom_field_value_data = array();
		$custom_field_value_query = $this->db->select('*')->from($this->value_table)->where($this->primaryKey, (int)$custom_field_id)->get()->result_array();

		foreach ($custom_field_value_query as $custom_field_value) {
			$custom_field_value_description_data = array();

			$custom_field_value_description_query = $this->db->select('*')->from($this->desc_value_table)->where('custom_field_value_id', (int)$custom_field_value['custom_field_value_id'])->get()->result_array();

			foreach ($custom_field_value_description_query as $custom_field_value_description) {
				$custom_field_value_description_data[$custom_field_value_description['language_id']] = array('name' => $custom_field_value_description['name']);
			}

			$custom_field_value_data[] = array(
				'custom_field_value_id'          => $custom_field_value['custom_field_value_id'],
				'custom_field_value_description' => $custom_field_value_description_data,
				'sort_order'                     => $custom_field_value['sort_order']
			);
		}

		return $custom_field_value_data;
	}

	public function getTotalCustomFields() {
		$this->db->select('COUNT(*) AS `total`');
		$this->db->from($this->table);
		$query = $this->db->get()->row_array();
		return $query['total'];
	}
}