<?php

/**
 * Class ModelLocalisationCountry
 * @author SUN
 */
class ModelLocalisationCountry extends Model {

	/**
	 * Get Country by country_id
	 * @param $country_id
	 * @return mixed
	 */
	public function getCountry($country_id)
	{
		$query = $this->db->select('*')
			->from('country')
			->where('country_id = ' . (int) $country_id)
			->where('status = 1');

		return $query->get()->row_array();
	}

	/**
	 * Get all available Countries
	 * @return mixed
	 */
	public function getCountries()
	{
		$country_data = $this->cache->get('country.status');

		if (!$country_data) {
			$query = $this->db->select('*')
				->from('country')
				->where('status = 1')
				->order_by('name');
			$country_data = $query->get()->result_array();
			$this->cache->set('country.status', $country_data);
		}

		return $country_data;
	}
}