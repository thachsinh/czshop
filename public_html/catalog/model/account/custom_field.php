<?php
class ModelAccountCustomField extends Model {
	public function getCustomField($custom_field_id) {
		$query = $this->db->query("SELECT * FROM `" . DB_PREFIX . "custom_field` cf LEFT JOIN `" . DB_PREFIX . "custom_field_description` cfd ON (cf.custom_field_id = cfd.custom_field_id) WHERE cf.status = '1' AND cf.custom_field_id = '" . (int)$custom_field_id . "' AND cfd.language_id = '" . (int)$this->config->get('config_language_id') . "'");

		return $query->row;
	}

	public function getCustomFields($customer_group_id = 0)
	{
		$custom_field_data = array();

		if (!$customer_group_id) {
			$custom_fields = $this->db->select('*')
				->from('custom_field cf')
				->join('custom_field_description cfd', 'cf.custom_field_id = cfd.custom_field_id', 'left')
				->where('cf.status = 1')
				->where('cfd.language_id = ' . (int) $this->config->get('config_language_id'))
				->order_by('cf.sort_order')
				->get()
				->result_array();
		} else {
			$custom_fields = $this->db->select('*')
				->from('custom_field_customer_group cfcg')
				->join('custom_field cf', 'cfcg.custom_field_id = cf.custom_field_id', 'left')
				->join('custom_field_description cfd', 'cf.custom_field_id = cfd.custom_field_id', 'left')
				->where('cf.status = 1')
				->where('cfd.language_id = ' . (int) $this->config->get('config_language_id'))
				->where('cfcg.customer_group_id = ' . (int) $customer_group_id)
				->order_by('cf.sort_order')
				->get()
				->result_array();
		}

		foreach ($custom_fields as $custom_field) {
			$custom_field_value_data = array();

			if ($custom_field['type'] == 'select' || $custom_field['type'] == 'radio' || $custom_field['type'] == 'checkbox') {
				$custom_field_values = $this->db->select('*')
					->from('custom_field_value cfv')
					->left('custom_field_value_description cfvd', 'cfv.custom_field_value_id = cfvd.custom_field_value_id', 'left')
					->where('cfv.custom_field_id = ' . (int) $custom_field['custom_field_id'])
					->where('cfvd.language_id = ' . (int) $this->config->get('config_language_id'))
					->order_by('cfv.sort_order')
					->get()
					->result_array();

				foreach ($custom_field_values as $custom_field_value) {
					$custom_field_value_data[] = array(
						'custom_field_value_id' => $custom_field_value['custom_field_value_id'],
						'name'                  => $custom_field_value['name']
					);
				}
			}

			$custom_field_data[] = array(
				'custom_field_id'    => $custom_field['custom_field_id'],
				'custom_field_value' => $custom_field_value_data,
				'name'               => $custom_field['name'],
				'type'               => $custom_field['type'],
				'value'              => $custom_field['value'],
				'location'           => $custom_field['location'],
				'required'           => empty($custom_field['required']) || $custom_field['required'] == 0 ? false : true,
				'sort_order'         => $custom_field['sort_order']
			);
		}

		return $custom_field_data;
	}
}