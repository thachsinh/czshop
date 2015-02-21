<?php
class ModelAccountReward extends Model {
	public function getRewards($data = array()) {
		$this->db->select('*')
			->from('customer_reward')
			->where('customer_id', (int)$this->customer->getId());

		//$sql = "SELECT * FROM `" . DB_PREFIX . "customer_reward` WHERE customer_id = '" . (int)$this->customer->getId() . "'";

		$sort_data = array(
			'points',
			'description',
			'date_added'
		);

		$order = 'ASC';
		if (isset($data['order']) && ($data['order'] == 'DESC')) {
			$order = 'DESC';
		}

		if (isset($data['sort']) && in_array($data['sort'], $sort_data)) {
			$this->db->order_by($data['sort'], $order);
		} else {
			$this->db->order_by('date_added', $order);
		}

		if (isset($data['start']) || isset($data['limit'])) {
			if ($data['start'] < 0) {
				$data['start'] = 0;
			}

			if ($data['limit'] < 1) {
				$data['limit'] = 20;
			}

			$this->db->limit($data['limit'], $data['start']);
		}

		return $this->db->get()->result_array();
	}

	public function getTotalRewards() {
		$this->db->select('COUNT(*) AS total')
			->from('customer_reward')
			->where('customer_id', (int)$this->customer->getId());

		$data = $this->db->get()->row_array();
		return $data['total'];

		//$query = $this->db->query("SELECT COUNT(*) AS total FROM `" . DB_PREFIX . "customer_reward` WHERE customer_id = '" . (int)$this->customer->getId() . "'");

		//return $query->row['total'];
	}

	public function getTotalPoints() {
		$this->db->select('SUM(points) AS total')
			->from('customer_reward')
			->where('customer_id', (int)$this->customer->getId())
			->group_by('customer_id');

		$data = $this->db->get()->row_array();
		return $data['total'];

		/*$query = $this->db->query("SELECT SUM(points) AS total FROM `" . DB_PREFIX . "customer_reward` WHERE customer_id = '" . (int)$this->customer->getId() . "' GROUP BY customer_id");

		if ($query->num_rows) {
			return $query->row['total'];
		} else {
			return 0;
		}*/
	}
}