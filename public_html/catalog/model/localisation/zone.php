<?php

/**
 * Class ModelLocalisationZone
 * @author SUN
 */
class ModelLocalisationZone extends Model
{
	/**
	 * Get Zone by zone_id
	 * @param $zone_id
	 * @return mixed
	 */
	public function getZone($zone_id)
	{
		$zone = $this->db->select('*')
			->from('zone')
			->where('zone_id = ' . (int) $zone_id)
			->where('status = 1')
			->get()
			->row_array();

		return $zone;
	}

	/**
	 * Get Zones by country_id
	 * @param $country_id
	 * @return mixed
	 */
	public function getZonesByCountryId($country_id)
	{
		$zone_data = $this->cache->get('zone.' . (int) $country_id);

		if (!$zone_data) {
			$zone_data = $this->db->select('*')
				->from('zone')
				->where('country_id = ' . (int) $country_id)
				->where('status = 1')
				->order_by('name')
				->get()
				->result_array();
			$this->cache->set('zone.' . (int) $country_id, $zone_data);
		}

		return $zone_data;
	}
}