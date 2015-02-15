<?php
class ModelDesignLayout extends Model {

	/**
	 * @edited SUN
	 * @param $route
	 * @return int
	 */
	public function getLayout($route)
	{
		$row = $this->db->select('*')
			->from('layout_route')
			->where('route LIKE ', $this->db->escape($route))
			->where('store_id = ', (int)$this->config->get('config_store_id'))
			->order_by('route DESC')
			->get()
			->row_array();

		if ($row) {
			return $row['layout_id'];
		} else {
			return 0;
		}
	}

	/**
	 * @edited SUN
	 * @param $layout_id
	 * @param $position
	 * @return mixed
	 */
	public function getLayoutModules($layout_id, $position)
	{
		$rows = $this->db->select('*')
			->from('layout_module')
			->where('layout_id = ', (int) $layout_id)
			->where('position = ', $this->db->escape($position))
			->order_by('sort_order')
			->get()
			->result_array();
		
		return $rows;
	}
}