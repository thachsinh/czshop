<?php
class ModelCatalogInformation extends Model
{
	/**
	 * @edited SUN
	 * @param $information_id
	 * @return mixed
	 */
	public function getInformation($information_id)
	{
		$row = $this->db->select('*', 'DISTINCT')
			->from('information i')
			->join('information_description id', 'i.information_id = id.information_id', 'left')
			->join('information_to_store i2s', 'i.information_id = i2s.information_id', 'left')
			->where('i.information_id = ' . (int) $information_id)
			->where('id.language_id = ' . (int) $this->config->get('config_language_id'))
			->where('i2s.store_id = ' . (int) $this->config->get('config_store_id'))
			->where('i.status = 1')
			->get()
			->row_array();

		return $row;
	}


	/**
	 * @edited SUN
	 * @return mixed
	 */
	public function getInformations()
	{
		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "information i
			LEFT JOIN " . DB_PREFIX . "information_description id ON (i.information_id = id.information_id)
			LEFT JOIN " . DB_PREFIX . "information_to_store i2s ON (i.information_id = i2s.information_id)
			WHERE id.language_id = '" . (int)$this->config->get('config_language_id') . "'
			AND i2s.store_id = '" . (int)$this->config->get('config_store_id') . "' AND i.status = '1'
			ORDER BY i.sort_order, LCASE(id.title) ASC");

		return $query->result_array();
	}

	/**
	 * @edited SUN
	 * @param $information_id
	 * @return int
	 */
	public function getInformationLayoutId($information_id)
	{
		$row = $this->db->query("SELECT * FROM " . DB_PREFIX . "information_to_layout
				WHERE information_id = '" . (int)$information_id . "'
				AND store_id = '" . (int)$this->config->get('config_store_id') . "'"
			)
			->row_array();

		if ($row) {
			return $row['layout_id'];
		} else {
			return 0;
		}
	}
}