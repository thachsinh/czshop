<?php
class ModelSaleRecurring extends Model {
	public $table = 'order_recurring';
	public function getTotalRecurrings($data) {
		$this->db->select('COUNT(*) AS total');
		$this->db->from($this->table . ' orr');
		$this->db->join('order o', 'o.order_id = orr.order_id', 'inner');

		//$sql = "SELECT COUNT(*) AS `total` FROM `" . DB_PREFIX . "order_recurring` `or` JOIN `" . DB_PREFIX . "order` o USING(order_id) WHERE 1 = 1";

		if (!empty($data['filter_order_recurring_id'])) {
			//$sql .= " AND orr.order_recurring_id = " . (int)$data['filter_order_recurring_id'];
			$this->db->where('orr.order_recurring_id', (int)$data['filter_order_recurring_id']);
		}

		if (!empty($data['filter_order_id'])) {
			$this->db->where('orr.order_id', (int)$data['filter_order_id']);
			//$sql .= " AND orr.order_id = " . (int)$data['filter_order_id'];
		}

		if (!empty($data['filter_payment_reference'])) {
			$this->db->like('orr.reference', $data['filter_reference']);

			//$sql .= " AND orr.reference LIKE '" . $this->db->escape($data['filter_reference']) . "%'";
		}

		if (!empty($data['filter_customer'])) {
			$this->db->like('o.firstname', $data['filter_customer']);

			//$sql .= " AND CONCAT(o.firstname, ' ', o.lastname) LIKE '" . $this->db->escape($data['filter_customer']) . "%'";
		}

		if (!empty($data['filter_status'])) {
			$this->db->where('orr.status', (int)$data['filter_status']);

			//$sql .= " AND orr.status = " . (int)$data['filter_status'];
		}

		if (!empty($data['filter_date_added'])) {
			$this->db->where('DATE(orr.date_added) = DATE(' . $this->db->escape($data['filter_date_added']) .')', NULL, FALSE);

			//$sql .= " AND DATE(orr.date_added) = DATE('" . $this->db->escape($data['filter_date_added']) . "')";
		}

		//$query = $this->db->query($sql);

		//return $query->row['total'];
		$data = $this->db->get()->row_array();
		//echo $this->db->last_query();
		return $data['total'];
	}

	public function getRecurrings($data) {
		//$sql = "SELECT orr.order_recurring_id, orr.order_id, orr.reference, orr.`status`, orr.`date_added`, CONCAT(o.`firstname`, ' ', o.`lastname`) AS `customer` FROM `" . DB_PREFIX . "order_recurring` `or` JOIN `" . DB_PREFIX . "order` o USING(`order_id`) WHERE 1 = 1 ";


		$this->db->select('orr.order_recurring_id, orr.order_id, orr.reference, orr.status, orr.date_added, CONCAT(o.firstname, \' \', o.lastname) AS customer', FALSE);
		$this->db->from($this->table . ' orr');
		$this->db->join('order o', 'orr.order_id = o.order_id', 'inner');

		//$sql = "SELECT COUNT(*) AS `total` FROM `" . DB_PREFIX . "order_recurring` `or` JOIN `" . DB_PREFIX . "order` o USING(order_id) WHERE 1 = 1";

		if (!empty($data['filter_order_recurring_id'])) {
			//$sql .= " AND orr.order_recurring_id = " . (int)$data['filter_order_recurring_id'];
			$this->db->where('orr.order_recurring_id', (int)$data['filter_order_recurring_id']);
		}

		if (!empty($data['filter_order_id'])) {
			$this->db->where('orr.order_id', (int)$data['filter_order_id']);
			//$sql .= " AND orr.order_id = " . (int)$data['filter_order_id'];
		}

		if (!empty($data['filter_payment_reference'])) {
			$this->db->like('orr.reference', $data['filter_reference']);

			//$sql .= " AND orr.reference LIKE '" . $this->db->escape($data['filter_reference']) . "%'";
		}

		if (!empty($data['filter_customer'])) {
			$this->db->like('o.firstname', $data['filter_customer']);

			//$sql .= " AND CONCAT(o.firstname, ' ', o.lastname) LIKE '" . $this->db->escape($data['filter_customer']) . "%'";
		}

		if (!empty($data['filter_status'])) {
			$this->db->where('orr.status', (int)$data['filter_status']);

			//$sql .= " AND orr.status = " . (int)$data['filter_status'];
		}

		if (!empty($data['filter_date_added'])) {
			$this->db->where('DATE(orr.date_added) = DATE(' . $this->db->escape($data['filter_date_added']) . ')');
			//$sql .= " AND DATE(orr.date_added) = DATE('" . $this->db->escape($data['filter_date_added']) . "')";
		}

		/*if (!empty($data['filter_order_recurring_id'])) {
			$sql .= " AND orr.order_recurring_id = " . (int)$data['filter_order_recurring_id'];
		}

		if (!empty($data['filter_order_id'])) {
			$sql .= " AND orr.order_id = " . (int)$data['filter_order_id'];
		}

		if (!empty($data['filter_reference'])) {
			$sql .= " AND orr.reference LIKE '" . $this->db->escape($data['filter_reference']) . "%'";
		}

		if (!empty($data['filter_customer'])) {
			$sql .= " AND CONCAT(o.firstname, ' ', o.lastname) LIKE '" . $this->db->escape($data['filter_customer']) . "%'";
		}

		if (!empty($data['filter_status'])) {
			$sql .= " AND orr.status = " . (int)$data['filter_status'];
		}

		if (!empty($data['filter_date_added'])) {
			$sql .= " AND DATE(orr.date_added) = DATE('" . $this->db->escape($data['filter_date_added']) . "')";
		}*/

		$sort_data = array(
			'orr.order_recurring_id',
			'orr.order_id',
			'orr.reference',
			'customer',
			'orr.status',
			'orr.date_added'
		);

		$order = 'ASC';
		if (isset($data['order']) && ($data['order'] == 'DESC')) {
			$order = 'DESC';
		}

		if (isset($data['sort']) && in_array($data['sort'], $sort_data)) {
			//$sql .= " ORDER BY " . $data['sort'];
			$this->db->order_by($data['sort'], $order);
		} else {
			//$sql .= " ORDER BY orr.order_recurring_id";
			$this->db->order_by('orr.order_recurring_id', $order);
		}

		if (isset($data['start']) || isset($data['limit'])) {
			if ($data['start'] < 0) {
				$data['start'] = 0;
			}

			if ($data['limit'] < 1) {
				$data['limit'] = 20;
			}

			//$sql .= " LIMIT " . (int)$data['start'] . "," . (int)$data['limit'];
			$this->db->limit($data['limit'], $data['start']);
		}

		$recurrings = array();

		//$results = $this->db->query($sql)->rows;

		foreach ($this->db->get()->result_array() as $result) {
			$recurrings[] = array(
				'order_recurring_id' => $result['order_recurring_id'],
				'order_id'           => $result['order_id'],
				'reference'          => $result['reference'],
				'customer'           => $result['customer'],
				'status'             => $this->getStatus($result['status']),
				'date_added'         => $result['date_added']
			);
		}

		return $recurrings;
	}

	public function getRecurring($order_recurring_id) {
		$recurring = array();

		$query=  $this->db->select('*')->from($this->table)->where('order_recurring_id', (int)$order_recurring_id)->get()->row_array();
		//$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "order_recurring WHERE order_recurring_id = " . (int)$order_recurring_id);

		if (!empty($query)) {
			$recurring = array(
				'order_recurring_id'    => $query['order_recurring_id'],
				'order_id'              => $query['order_id'],
				'reference'             => $query['reference'],
				'recurring_id'          => $query['recurring_id'],
				'recurring_name'        => $query['recurring_name'],
				'recurring_description' => $query['recurring_description'],
				'product_name'          => $query['product_name'],
				'product_quantity'      => $query['product_quantity'],
				'status'                => $this->getStatus($query['status']),
				'status_id'             => $query['status']
			);
		}

		return $recurring;
	}

	public function getRecurringTransactions($order_recurring_id) {
		$transactions = array();

		$this->db->select('*')->from('order_recurring_transaction')->where('order_recurring_id', (int)$order_recurring_id)->order_by('date_added', 'DESC');

		//$query = $this->db->query("SELECT amount, type, date_added FROM " . DB_PREFIX . "order_recurring_transaction WHERE order_recurring_id = " . (int)$order_recurring_id . " ORDER BY date_added DESC")->rows;

		foreach ($this->db->get()->result_array() as $result) {
			switch ($result['type']) {
				case 0:
					$type = $this->language->get('text_transaction_date_added');
					break;
				case 1:
					$type = $this->language->get('text_transaction_payment');
					break;
				case 2:
					$type = $this->language->get('text_transaction_outstanding_payment');
					break;
				case 3:
					$type = $this->language->get('text_transaction_skipped');
					break;
				case 4:
					$type = $this->language->get('text_transaction_failed');
					break;
				case 5:
					$type = $this->language->get('text_transaction_cancelled');
					break;
				case 6:
					$type = $this->language->get('text_transaction_suspended');
					break;
				case 7:
					$type = $this->language->get('text_transaction_suspended_failed');
					break;
				case 8:
					$type = $this->language->get('text_transaction_outstanding_failed');
					break;
				case 9:
					$type = $this->language->get('text_transaction_expired');
					break;
				default:
					$type = '';
					break;
			}

			$transactions[] = array(
				'date_added' => $result['date_added'],
				'amount'     => $result['amount'],
				'type'       => $type
			);
		}

		return $transactions;
	}

	private function getStatus($status) {
		switch ($status) {
			case 1:
				$result = $this->language->get('text_status_inactive');
				break;
			case 2:
				$result = $this->language->get('text_status_active');
				break;
			case 3:
				$result = $this->language->get('text_status_suspended');
				break;
			case 4:
				$result = $this->language->get('text_status_cancelled');
				break;
			case 5:
				$result = $this->language->get('text_status_expired');
				break;
			case 6:
				$result = $this->language->get('text_status_pending');
				break;
			default:
				$result = '';
				break;
		}

		return $result;
	}
}