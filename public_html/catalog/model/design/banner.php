<?php
class ModelDesignBanner extends Model {
	public $table = 'banner_image';
	public $desc_table = 'banner_image_description';


	public function getBanner($banner_id) {
		$this->db->select('*')
			->from($this->table . ' bi')
			->join($this->desc_table . ' bid', 'bi.banner_image_id  = bid.banner_image_id', 'left')
			->where('bi.banner_id', (int)$banner_id)
			->where('bid.language_id ', (int)$this->config->get('config_language_id'))
			->order_by('bi.sort_order', 'ASC');
		return $this->db->get()->result_array();

		/*$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "banner_image bi LEFT JOIN " . DB_PREFIX . "banner_image_description bid ON (bi.banner_image_id  = bid.banner_image_id) WHERE bi.banner_id = '" . (int)$banner_id . "' AND bid.language_id = '" . (int)$this->config->get('config_language_id') . "' ORDER BY bi.sort_order ASC");

		return $query->rows;*/
	}
}