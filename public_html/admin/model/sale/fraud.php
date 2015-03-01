<?php
class ModelSaleFraud extends Model {
	public $table = 'order_fraud';
	public function getFraud($order_id) {
		$this->db->select('*');
		$this->db->from($this->table);
		$this->db->where('order_id', (int)$order_id);
		return $this->db->get()->row_array();

		//$query = $this->db->query("SELECT * FROM `" . DB_PREFIX . "order_fraud` WHERE order_id = '" . (int)$order_id . "'");

		//return $query->row;
	}
}