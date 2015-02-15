<?php

/**
 * @modified SUN
 * Class ModelAccountCustomerGroup
 */
class ModelAccountCustomerGroup extends Model
{
	/**
	 * @param $customer_group_id
	 * @return mixed
	 */
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

	/**
	 * @return mixed
	 */
	public function getCustomerGroups()
	{
		$rows = $this->db->select('*')
			->from('customer_group cg')
			->join('customer_group_description cgd', 'cg.customer_group_id = cgd.customer_group_id', 'left')
			->where('cgd.language_id = ', (int) $this->config->get('config_language_id'))
			->order_by('cg.sort_order ASC, cgd.name ASC')
			->get()
			->result_array();

		return $rows;
	}
}