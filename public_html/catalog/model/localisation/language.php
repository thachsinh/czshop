<?php

/**
 * @modified SUN
 * Class ModelLocalisationLanguage
 */
class ModelLocalisationLanguage extends Model {
	public $table = 'language';

	/**
	 * @param $language_id
	 * @return mixed
	 */
	public function getLanguage($language_id)
	{
		$this->db->select('*')
			->from($this->table)
			->where('language_id', (int)$language_id);
		return $this->db->get()->row_array();

		//$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "language WHERE language_id = '" . (int)$language_id . "'");
		//return $query->row_array();
	}

	/**
	 * @return array
	 */
	public function getLanguages()
	{
		$language_data = $this->cache->get('language');

		if (!$language_data) {
			$language_data = array();
			$this->db->select('*')
				->from($this->table)
				->order_by('sort_order', 'ASC')
				->order_by('name', 'ASC');


			//$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "language ORDER BY sort_order, name");
			foreach ($this->db->get()->result_array() as $result) {
				$language_data[$result['code']] = array(
					'language_id' => $result['language_id'],
					'name'        => $result['name'],
					'code'        => $result['code'],
					'locale'      => $result['locale'],
					'image'       => $result['image'],
					'directory'   => $result['directory'],
					'sort_order'  => $result['sort_order'],
					'status'      => $result['status']
				);
			}
			$this->cache->set('language', $language_data);
		}

		return $language_data;
	}
}