<?php
class ModelAccountCustomerGroup extends Model
{
	public function getCustomerGroup($customer_group_id)
	{
		$row = $this->db->select('*')
			->from('customer_group cg')
			->join('customer_group_description cgd', 'cg.customer_group_id = cgd.customer_group_id', 'left')
			->where('cg.customer_group_id = ' . (int) $customer_group_id)
			->where('cgd.language_id = ' . (int) $this->config->get('config_language_id'))
			->get()
			->row_array();

		return $row;
	}

	public function getCustomerGroups() {
		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "customer_group cg LEFT JOIN " . DB_PREFIX . "customer_group_description cgd ON (cg.customer_group_id = cgd.customer_group_id) WHERE cgd.language_id = '" . (int)$this->config->get('config_language_id') . "' ORDER BY cg.sort_order ASC, cgd.name ASC");

		return $query->rows;
	}
}