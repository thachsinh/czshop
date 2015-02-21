<?php
class ModelLocalisationLocation extends Model {
	public $table = 'location';

	public function getLocation($location_id) {
		$this->db->select('location_id, name, address, geocode, telephone, fax, image, open, comment')
			->from($this->table)
			->where('location_id', (int)$location_id);

		return $this->db->get()->row_array();

		//$query = $this->db->query("SELECT location_id, name, address, geocode, telephone, fax, image, open, comment FROM " . DB_PREFIX . "location WHERE location_id = '" . (int)$location_id . "'");

		//return $query->row;
	}
}