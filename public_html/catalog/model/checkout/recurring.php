<?php
class ModelCheckoutRecurring extends Model {
	public $table = 'order_recurring';
	public $fields = array('order_id', 'date_added', 'status', 'product_id', 'product_name', 'product_quantity', 'recurring_id', 'recurring_name', 'recurring_description', 'recurring_frequency', 'recurring_cycle', 'recurring_duration', 'recurring_price', 'trial', 'trial_frequency', 'trial_cycle', 'trial_duration', 'trial_price', 'reference');


	public function create($item, $order_id, $description) {
		$tmp = $this->initData($item, true);
		$tmp['order_id'] = (int)$order_id;
		$tmp['trial_price'] = (float)$item['recurring_trial_price'];
		$tmp['recurring_price'] = (float)$item['recurring_price'];
		$tmp['reference'] = '';
		$tmp['status'] = 6;

		$this->db->set('date_added', 'NOW()', FALSE);
		$this->db->insert($this->table, $tmp);


		//$this->db->query("INSERT INTO `" . DB_PREFIX . "order_recurring` SET `order_id` = '" . (int)$order_id . "', `date_added` = NOW(), `status` = 6, `product_id` = '" . (int)$item['product_id'] . "', `product_name` = '" . $this->db->escape($item['name']) . "', `product_quantity` = '" . $this->db->escape($item['quantity']) . "', `recurring_id` = '" . (int)$item['recurring_id'] . "', `recurring_name` = '" . $this->db->escape($item['recurring_name']) . "', `recurring_description` = '" . $this->db->escape($description) . "', `recurring_frequency` = '" . $this->db->escape($item['recurring_frequency']) . "', `recurring_cycle` = '" . (int)$item['recurring_cycle'] . "', `recurring_duration` = '" . (int)$item['recurring_duration'] . "', `recurring_price` = '" . (float)$item['recurring_price'] . "', `trial` = '" . (int)$item['recurring_trial'] . "', `trial_frequency` = '" . $this->db->escape($item['recurring_trial_frequency']) . "', `trial_cycle` = '" . (int)$item['recurring_trial_cycle'] . "', `trial_duration` = '" . (int)$item['recurring_trial_duration'] . "', `trial_price` = '" . (float)$item['recurring_trial_price'] . "', `reference` = ''");

		return $this->db->insert_id();
	}

	public function addReference($recurring_id, $ref) {
		$this->db->set('reference', $ref);
		$this->db->where('order_recurring_id', (int)$recurring_id);
		return $this->db->update('order_recurring');

		/*$this->db->query("UPDATE " . DB_PREFIX . "order_recurring SET reference = '" . $this->db->escape($ref) . "' WHERE order_recurring_id = '" . (int)$recurring_id . "'");


		if ($this->db->countAffected() > 0) {
			return true;
		} else {
			return false;

		}*/
	}
}
