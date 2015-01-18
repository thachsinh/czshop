<?php
class ModelCatalogUrlAlias extends Model {
	public function getUrlAlias($keyword) {
		$this->db->select('*');
		$this->db->from('url_alias');
		$this->db->where('keyword', $this->db->escape($keyword));
		return $this->db->get()->row_array();
		//$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "url_alias WHERE keyword = '" . $this->db->escape($keyword) . "'");

		//return $query->row;
	}
}