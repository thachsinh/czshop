<?php
class ModelExtensionExtension extends Model {

	/**
	 * @modfied SUN
	 * @param $type
	 * @return mixed
	 */
	public function getExtensions($type)
	{
		$extensions = $this->db->select('*')
			->from('extension')
			->where('type = ', $this->db->escape($type))
			->get()
			->result_array();

		return $extensions;
	}
}