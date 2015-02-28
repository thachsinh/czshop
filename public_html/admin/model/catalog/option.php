<?php
class ModelCatalogOption extends Model {

	public $table = 'option';
	public $fields = array();
	public $desc_fields = array();
	public $vdesc_fields = array();
	public $value_fields = array();
	public $primaryKey = 'option_id';

	public function addOption($data) {
		$this->event->trigger('pre.admin.option.add', $data);

		$tmp = $this->initData($data, TRUE);
		$this->db->insert($this->table, $tmp);

		//$this->db->query("INSERT INTO `" . DB_PREFIX . "option` SET type = '" . $this->db->escape($data['type']) . "', sort_order = '" . (int)$data['sort_order'] . "'");

		$option_id = $this->db->insert_id(); //$this->db->getLastId();

		foreach ($data['option_description'] as $language_id => $value) {
			$tmp = $this->initData($data['option_description'][$language_id], TRUE, $this->desc_fields);
			$this->db->set('language_id', (int)$language_id);
			$this->db->set($this->primaryKey, (int)$option_id);
			$this->db->insert('option_description', $tmp);

			//$this->db->query("INSERT INTO " . DB_PREFIX . "option_description SET option_id = '" . (int)$option_id . "', language_id = '" . (int)$language_id . "', name = '" . $this->db->escape($value['name']) . "'");
		}

		if (isset($data['option_value'])) {
			foreach ($data['option_value'] as $option_value) {
				$tmp = $this->initData($data['option_value'][$language_id], TRUE, $this->value_fields);
				$tmp['image'] = html_entity_decode($option_value['image'], ENT_QUOTES, 'UTF-8');
				$this->db->set($this->primaryKey, (int)$option_id);
				//$this->db->insert('option_description', $tmp);

				$this->db->insert('option_value', $tmp);
				$option_value_id = $this->db->insert_id();

				//$this->db->query("INSERT INTO " . DB_PREFIX . "option_value SET option_id = '" . (int)$option_id . "', image = '" . $this->db->escape(html_entity_decode($option_value['image'], ENT_QUOTES, 'UTF-8')) . "', sort_order = '" . (int)$option_value['sort_order'] . "'");

				//$option_value_id = $this->db->getLastId();

				foreach ($option_value['option_value_description'] as $language_id => $option_value_description) {
					$tmp = $this->initData($option_value['option_value_description'][$language_id], TRUE, $this->vdesc_fields);
					$this->db->set('language_id', (int)$language_id);
					$this->db->set($this->primaryKey, (int)$option_id);
					$this->db->set('option_value_id', (int)$option_value_id);
					$this->db->insert('option_value_description', $tmp);
					//$this->db->query("INSERT INTO " . DB_PREFIX . "option_value_description SET option_value_id = '" . (int)$option_value_id . "', language_id = '" . (int)$language_id . "', option_id = '" . (int)$option_id . "', name = '" . $this->db->escape($option_value_description['name']) . "'");
				}
			}
		}

		$this->event->trigger('post.admin.option.add', $option_id);

		return $option_id;
	}

	public function editOption($option_id, $data) {
		$this->event->trigger('pre.admin.option.edit', $data);

		$tmp = $this->initData($data, TRUE);
		$this->db->where($this->primaryKey, (int)$option_id);
		$this->db->update($this->table, $tmp);

		//$this->db->query("UPDATE `" . DB_PREFIX . "option` SET type = '" . $this->db->escape($data['type']) . "', sort_order = '" . (int)$data['sort_order'] . "' WHERE option_id = '" . (int)$option_id . "'");

		//$this->db->query("DELETE FROM " . DB_PREFIX . "option_description WHERE option_id = '" . (int)$option_id . "'");
		$this->db->where($this->primaryKey, (int)$option_id);
		$this->db->delete($this->table);

		foreach ($data['option_description'] as $language_id => $value) {

			//$this->db->query("INSERT INTO " . DB_PREFIX . "option_description SET option_id = '" . (int)$option_id . "', language_id = '" . (int)$language_id . "', name = '" . $this->db->escape($value['name']) . "'");
			$tmp = $this->initData($data['option_description'][$language_id], TRUE, $this->desc_fields);
			$this->db->set('language_id', (int)$language_id);
			$this->db->set($this->primaryKey, (int)$option_id);
			$this->db->insert('option_description', $tmp);
		}

		$this->db->where($this->primaryKey, (int)$option_id);
		$this->db->delete(array('option_value', 'option_value_description'));
		//$this->db->query("DELETE FROM " . DB_PREFIX . "option_value WHERE option_id = '" . (int)$option_id . "'");
		//$this->db->query("DELETE FROM " . DB_PREFIX . "option_value_description WHERE option_id = '" . (int)$option_id . "'");

		if (isset($data['option_value'])) {
			foreach ($data['option_value'] as $option_value) {
				if ($option_value['option_value_id']) {
					$tmp = $this->initData($data['option_value'][$language_id], TRUE, $this->value_fields);
					$tmp['image'] = html_entity_decode($option_value['image'], ENT_QUOTES, 'UTF-8');
					$this->db->set($this->primaryKey, (int)$option_id);
					$this->db->insert('option_value', $tmp);
					
					//$this->db->query("INSERT INTO " . DB_PREFIX . "option_value SET option_value_id = '" . (int)$option_value['option_value_id'] . "', option_id = '" . (int)$option_id . "', image = '" . $this->db->escape(html_entity_decode($option_value['image'], ENT_QUOTES, 'UTF-8')) . "', sort_order = '" . (int)$option_value['sort_order'] . "'");
				} else {
					//$this->db->query("INSERT INTO " . DB_PREFIX . "option_value SET option_id = '" . (int)$option_id . "', image = '" . $this->db->escape(html_entity_decode($option_value['image'], ENT_QUOTES, 'UTF-8')) . "', sort_order = '" . (int)$option_value['sort_order'] . "'");

					$tmp = $this->initData($data['option_value'][$language_id], TRUE, $this->value_fields);
					$tmp['image'] = html_entity_decode($option_value['image'], ENT_QUOTES, 'UTF-8');
					$this->db->set($this->primaryKey, (int)$option_id);
					$this->db->insert('option_value', $tmp);
				}

				//$option_value_id = $this->db->getLastId();
				$option_value_id = $this->db->insert_id();

				foreach ($option_value['option_value_description'] as $language_id => $option_value_description) {
					//$this->db->query("INSERT INTO " . DB_PREFIX . "option_value_description SET option_value_id = '" . (int)$option_value_id . "', language_id = '" . (int)$language_id . "', option_id = '" . (int)$option_id . "', name = '" . $this->db->escape($option_value_description['name']) . "'");

					$tmp = $this->initData($option_value['option_value_description'][$language_id], TRUE, $this->vdesc_fields);
					$this->db->set('language_id', (int)$language_id);
					$this->db->set($this->primaryKey, (int)$option_id);
					$this->db->set('option_value_id', (int)$option_value_id);
					$this->db->insert('option_value_description', $tmp);
				}
			}

		}

		$this->event->trigger('post.admin.option.edit', $option_id);
	}

	public function deleteOption($option_id) {
		$this->event->trigger('pre.admin.option.delete', $option_id);

		$this->db->where($this->primaryKey, (int)$option_id);
		$this->db->delete(array($this->table , 'option_description', 'option_value', 'option_value_description'));
		/*

		$this->db->query("DELETE FROM `" . DB_PREFIX . "option` WHERE option_id = '" . (int)$option_id . "'");
		$this->db->query("DELETE FROM " . DB_PREFIX . "option_description WHERE option_id = '" . (int)$option_id . "'");
		$this->db->query("DELETE FROM " . DB_PREFIX . "option_value WHERE option_id = '" . (int)$option_id . "'");
		$this->db->query("DELETE FROM " . DB_PREFIX . "option_value_description WHERE option_id = '" . (int)$option_id . "'");*/

		$this->event->trigger('post.admin.option.delete', $option_id);
	}

	public function getOption($option_id) {
		$this->db->select('*');
		$this->db->from($this->table . ' o');
		$this->db->join('option_description od', 'o.option_id = od.option_id', 'left');
		$this->db->where('o.option_id', (int)$option_id);
		$this->db->where('od.language_id', (int)$this->config->get('config_language_id'));

		return $this->db->get()->row_array();
		//$query = $this->db->query("SELECT * FROM `" . DB_PREFIX . "option` o LEFT JOIN " . DB_PREFIX . "option_description od ON (o.option_id = od.option_id) WHERE o.option_id = '" . (int)$option_id . "' AND od.language_id = '" . (int)$this->config->get('config_language_id') . "'");

		//return $query->row;
	}

	public function getOptions($data = array()) {
		//$sql = "SELECT * FROM `" . DB_PREFIX . "option` o LEFT JOIN " . DB_PREFIX . "option_description od ON (o.option_id = od.option_id) WHERE od.language_id = '" . (int)$this->config->get('config_language_id') . "'";

		$this->db->select('*');
		$this->db->from($this->table . ' o');
		$this->db->join('option_description od', 'o.option_id = od.option_id', 'left');
		$this->db->where('od.language_id', (int)$this->config->get('config_language_id'));

		if (!empty($data['filter_name'])) {
			//$sql .= " AND od.name LIKE '" . $this->db->escape($data['filter_name']) . "%'";
			$this->db->like('od.name', $data['filter_name']);
		}

		$sort_data = array(
			'od.name',
			'o.type',
			'o.sort_order'
		);

		$order = 'ASC';
		if (isset($data['order']) && ($data['order'] == 'DESC')) {
			$order = 'DESC';
		}

		if (isset($data['sort']) && in_array($data['sort'], $sort_data)) {
			$this->db->order_by($data['sort'], $order);
		} else {
			$this->db->order_by('od.name', $order);
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

	public function getOptionDescriptions($option_id) {
		$option_data = array();

		$this->db->select('*')->from('option_description');
		$this->db->where($this->primaryKey, (int)$option_id);


		//$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "option_description WHERE option_id = '" . (int)$option_id . "'");

		foreach ($this->db->get()->result_array() as $result) {
			$option_data[$result['language_id']] = array('name' => $result['name']);
		}

		return $option_data;
	}

	public function getOptionValue($option_value_id) {

		$this->db->select('*');
		$this->db->from('option_value ov');
		$this->db->join('option_value_description ovd', 'ov.option_value_id = ovd.option_value_id', 'left');
		$this->db->where('ov.option_value_id', (int)$option_value_id);
		$this->db->where('ovd.language_id', (int)$this->config->get('config_language_id'));

		//$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "option_value ov LEFT JOIN " . DB_PREFIX . "option_value_description ovd ON (ov.option_value_id = ovd.option_value_id) WHERE ov.option_value_id = '" . (int)$option_value_id . "' AND ovd.language_id = '" . (int)$this->config->get('config_language_id') . "'");

		return $this->db->get()->row_array();
	}

	public function getOptionValues($option_id) {
		$option_value_data = array();
		$this->db->select('*');
		$this->db->from('option_value ov');
		$this->db->join('option_value_description ovd', 'ov.option_value_id = ovd.option_value_id', 'left');
		$this->db->where('ov.option_id', (int)$option_id);
		$this->db->where('ovd.language_id', (int)$this->config->get('config_language_id'));
		$this->db->order_by('ov.sort_order', 'ASC');
		$this->db->order_by('ovd.name', 'ASC');

		//$option_value_query = $this->db->query("SELECT * FROM " . DB_PREFIX . "option_value ov LEFT JOIN " . DB_PREFIX . "option_value_description ovd ON (ov.option_value_id = ovd.option_value_id) WHERE ov.option_id = '" . (int)$option_id . "' AND ovd.language_id = '" . (int)$this->config->get('config_language_id') . "' ORDER BY ov.sort_order, ovd.name");

		foreach ($this->db->get()->result_array() as $option_value) {
			$option_value_data[] = array(
				'option_value_id' => $option_value['option_value_id'],
				'name'            => $option_value['name'],
				'image'           => $option_value['image'],
				'sort_order'      => $option_value['sort_order']
			);
		}

		return $option_value_data;
	}

	public function getOptionValueDescriptions($option_id) {
		$option_value_data = array();

		$this->db->select('*')->from('option_value')->where($this->primaryKey, (int)$option_id);
		$this->db->order_by('sort_order', 'ASC');

		//$option_value_query = $this->db->query("SELECT * FROM " . DB_PREFIX . "option_value WHERE option_id = '" . (int)$option_id . "' ORDER BY sort_order");

		foreach ($this->db->get()->result_array() as $option_value) {
			$option_value_description_data = array();

			$this->db->select('*')->from('option_value_description')->where('option_value_id', (int)$option_value['option_value_id']);

			//$option_value_description_query = $this->db->query("SELECT * FROM " . DB_PREFIX . "option_value_description WHERE option_value_id = '" . (int)$option_value['option_value_id'] . "'");

			foreach ($this->db->get()->result_array() as $option_value_description) {
				$option_value_description_data[$option_value_description['language_id']] = array('name' => $option_value_description['name']);
			}

			$option_value_data[] = array(
				'option_value_id'          => $option_value['option_value_id'],
				'option_value_description' => $option_value_description_data,
				'image'                    => $option_value['image'],
				'sort_order'               => $option_value['sort_order']
			);
		}

		return $option_value_data;
	}

	public function getTotalOptions() {
		//$query = $this->db->query("SELECT COUNT(*) AS total FROM `" . DB_PREFIX . "option`");

		//return $query->row['total'];

		$this->db->select('COUNT(*) AS `total`');
		$query = $this->db->get($this->table)->row_array();
		return $query['total'];
	}
}