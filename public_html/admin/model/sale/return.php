<?php
class ModelSaleReturn extends Model {
	public $table = 'return';
	public $history_table = 'return_history';
	public $primaryKey = 'return_id';
	public $fields = array('return_id', 'order_id', 'product_id', 'customer_id', 'firstname', 'lastname', 'email', 'telephone', 'product', 'model', 'quantity', 'opened', 'return_reason_id', 'return_action_id', 'return_status_id', 'comment', 'date_ordered', 'date_added', 'date_modified');
	public $history_fields = array('return_history_id', 'return_id', 'return_status_id', 'notify', 'comment', 'date_added');

	public function addReturn($data) {
		$tmp = $this->initData($data, TRUE);
		$this->db->set('date_added', 'NOW()', FALSE);
		$this->db->set('date_modified', 'NOW()', FALSE);
		$this->db->insert($this->table, $tmp);

		$return_id = $this->db->insert_id();

		//$this->db->query("INSERT INTO `" . DB_PREFIX . "return` SET order_id = '" . (int)$data['order_id'] . "', product_id = '" . (int)$data['product_id'] . "', customer_id = '" . (int)$data['customer_id'] . "', firstname = '" . $this->db->escape($data['firstname']) . "', lastname = '" . $this->db->escape($data['lastname']) . "', email = '" . $this->db->escape($data['email']) . "', telephone = '" . $this->db->escape($data['telephone']) . "', product = '" . $this->db->escape($data['product']) . "', model = '" . $this->db->escape($data['model']) . "', quantity = '" . (int)$data['quantity'] . "', opened = '" . (int)$data['opened'] . "', return_reason_id = '" . (int)$data['return_reason_id'] . "', return_action_id = '" . (int)$data['return_action_id'] . "', return_status_id = '" . (int)$data['return_status_id'] . "', comment = '" . $this->db->escape($data['comment']) . "', date_ordered = '" . $this->db->escape($data['date_ordered']) . "', date_added = NOW(), date_modified = NOW()");

		$this->tracking->log(LOG_FUNCTION::$sale_return, LOG_ACTION_ADD, $return_id);
	}

	public function editReturn($return_id, $data) {
		$tmp = $this->initData($data, TRUE);
		$this->db->set('date_modified', 'NOW()', FALSE);
		$this->db->where($this->primaryKey, (int)$return_id);
		$this->db->update($this->table, $tmp);

		//$this->db->query("UPDATE `" . DB_PREFIX . "return` SET order_id = '" . (int)$data['order_id'] . "', product_id = '" . (int)$data['product_id'] . "', customer_id = '" . (int)$data['customer_id'] . "', firstname = '" . $this->db->escape($data['firstname']) . "', lastname = '" . $this->db->escape($data['lastname']) . "', email = '" . $this->db->escape($data['email']) . "', telephone = '" . $this->db->escape($data['telephone']) . "', product = '" . $this->db->escape($data['product']) . "', model = '" . $this->db->escape($data['model']) . "', quantity = '" . (int)$data['quantity'] . "', opened = '" . (int)$data['opened'] . "', return_reason_id = '" . (int)$data['return_reason_id'] . "', return_action_id = '" . (int)$data['return_action_id'] . "', comment = '" . $this->db->escape($data['comment']) . "', date_ordered = '" . $this->db->escape($data['date_ordered']) . "', date_modified = NOW() WHERE return_id = '" . (int)$return_id . "'");

		$this->tracking->log(LOG_FUNCTION::$sale_return, LOG_ACTION_MODIFY, $return_id);
	}

	public function deleteReturn($return_id) {
		$this->db->where($this->primaryKey, (int)$return_id);
		$this->db->delete(array($this->table, $this->history_table));

		//$this->db->query("DELETE FROM `" . DB_PREFIX . "return` WHERE return_id = '" . (int)$return_id . "'");
		//$this->db->query("DELETE FROM " . DB_PREFIX . "return_history WHERE return_id = '" . (int)$return_id . "'");

		$this->tracking->log(LOG_FUNCTION::$sale_return, LOG_ACTION_DELETE, $return_id);
	}

	public function getReturn($return_id) {
		//$this->db->select('DISTINCT *, (CONCAT(c.firstname, \' \', c.lastname)', false);
		$query = $this->db->query("SELECT DISTINCT *, (SELECT CONCAT(c.firstname, ' ', c.lastname) FROM " . DB_PREFIX . "customer c WHERE c.customer_id = r.customer_id) AS customer FROM `" . DB_PREFIX . "return` r WHERE r.return_id = '" . (int)$return_id . "'");

		return $query->row_array();
		//$this->db->from('customer');

	}

	public function getReturns($data = array()) {
		$this->db->select('*, CONCAT(r.firstname, \' \', r.lastname) AS customer', FALSE);
		$this->db->from($this->table . ' r');

		//$sql = "SELECT *, CONCAT(r.firstname, ' ', r.lastname) AS customer, (SELECT rs.name FROM " . DB_PREFIX . "return_status rs WHERE rs.return_status_id = r.return_status_id AND rs.language_id = '" . (int)$this->config->get('config_language_id') . "') AS status FROM `" . DB_PREFIX . "return` r";

		if (!empty($data['filter_return_id'])) {
			$this->db->where('r.return_id', (int)$data['filter_return_id']);

			//$implode[] = "r.return_id = '" . (int)$data['filter_return_id'] . "'";
		}

		if (!empty($data['filter_customer'])) {
			$this->db->like('r.firstname', (int)$data['filter_customer']);
			//$implode[] = "CONCAT(r.firstname, ' ', r.lastname) LIKE '" . $this->db->escape($data['filter_customer']) . "%'";
		}

		if (!empty($data['filter_order_id'])) {
			$this->db->where('r.order_id', (int)$data['filter_order_id']);

			//$implode[] = "r.order_id = '" . $this->db->escape($data['filter_order_id']) . "'";
		}

		if (!empty($data['filter_product'])) {
			$this->db->where('r.product', $data['filter_product']);

			//$implode[] = "r.product = '" . $this->db->escape($data['filter_product']) . "'";
		}

		if (!empty($data['filter_model'])) {
			$this->db->where('r.model', $data['filter_model']);

			//$implode[] = "r.model = '" . $this->db->escape($data['filter_model']) . "'";
		}

		if (!empty($data['filter_return_status_id'])) {
			$this->db->where('r.return_status_id', (int)$data['filter_return_status_id']);

			//$implode[] = "r.return_status_id = '" . (int)$data['filter_return_status_id'] . "'";
		}
		/*
		$implode = array();

		if (!empty($data['filter_return_id'])) {
			$implode[] = "r.return_id = '" . (int)$data['filter_return_id'] . "'";
		}

		if (!empty($data['filter_order_id'])) {
			$implode[] = "r.order_id = '" . (int)$data['filter_order_id'] . "'";
		}

		if (!empty($data['filter_customer'])) {
			$implode[] = "CONCAT(r.firstname, ' ', r.lastname) LIKE '" . $this->db->escape($data['filter_customer']) . "%'";
		}

		if (!empty($data['filter_product'])) {
			$implode[] = "r.product = '" . $this->db->escape($data['filter_product']) . "'";
		}

		if (!empty($data['filter_model'])) {
			$implode[] = "r.model = '" . $this->db->escape($data['filter_model']) . "'";
		}

		if (!empty($data['filter_return_status_id'])) {
			$implode[] = "r.return_status_id = '" . (int)$data['filter_return_status_id'] . "'";
		}

		if (!empty($data['filter_date_added'])) {
			$implode[] = "DATE(r.date_added) = DATE('" . $this->db->escape($data['filter_date_added']) . "')";
		}

		if (!empty($data['filter_date_modified'])) {
			$implode[] = "DATE(r.date_modified) = DATE('" . $this->db->escape($data['filter_date_modified']) . "')";
		}

		if ($implode) {
			$sql .= " WHERE " . implode(" AND ", $implode);
		}*/

		$sort_data = array(
			'r.return_id',
			'r.order_id',
			'customer',
			'r.product',
			'r.model',
			'status',
			'r.date_added',
			'r.date_modified'
		);

		$order = 'ASC';
		if (isset($data['order']) && ($data['order'] == 'DESC')) {
			$order = 'DESC';
		}

		if (isset($data['sort']) && in_array($data['sort'], $sort_data)) {
			//$sql .= " ORDER BY " . $data['sort'];
			$this->db->order_by($data['sort'], $order);
		} else {
			$this->db->order_by('r.return_id', $order);

			//$sql .= " ORDER BY r.return_id";
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

		//$query = $this->db->query($sql);

		//return $query->result_array();
		return $this->db->get()->result_array();
	}

	public function getTotalReturns($data = array()) {
		$this->db->select('COUNT(*) AS total');
		$this->db->from($this->table . ' r');

		//$sql = "SELECT COUNT(*) AS total FROM `" . DB_PREFIX . "return`r";

		//$implode = array();

		if (!empty($data['filter_return_id'])) {
			$this->db->where('r.return_id', (int)$data['filter_return_id']);

			//$implode[] = "r.return_id = '" . (int)$data['filter_return_id'] . "'";
		}

		if (!empty($data['filter_customer'])) {
			$this->db->like('r.firstname', (int)$data['filter_customer']);
			//$implode[] = "CONCAT(r.firstname, ' ', r.lastname) LIKE '" . $this->db->escape($data['filter_customer']) . "%'";
		}

		if (!empty($data['filter_order_id'])) {
			$this->db->where('r.order_id', (int)$data['filter_order_id']);

			//$implode[] = "r.order_id = '" . $this->db->escape($data['filter_order_id']) . "'";
		}

		if (!empty($data['filter_product'])) {
			$this->db->where('r.product', $data['filter_product']);

			//$implode[] = "r.product = '" . $this->db->escape($data['filter_product']) . "'";
		}

		if (!empty($data['filter_model'])) {
			$this->db->where('r.model', $data['filter_model']);

			//$implode[] = "r.model = '" . $this->db->escape($data['filter_model']) . "'";
		}

		if (!empty($data['filter_return_status_id'])) {
			$this->db->where('r.return_status_id', (int)$data['filter_return_status_id']);

			//$implode[] = "r.return_status_id = '" . (int)$data['filter_return_status_id'] . "'";
		}

		if (!empty($data['filter_date_added'])) {
			//$implode[] = "DATE(r.date_added) = DATE('" . $this->db->escape($data['filter_date_added']) . "')";
		}

		if (!empty($data['filter_date_modified'])) {
			//$implode[] = "DATE(r.date_modified) = DATE('" . $this->db->escape($data['filter_date_modified']) . "')";
		}

		//if ($implode) {
		//	$sql .= " WHERE " . implode(" AND ", $implode);
		//}

		//$query = $this->db->query($sql);

		//return $query->row['total'];
		$data = $this->db->get()->row_array();
		return $data['total'];
	}

	public function getTotalReturnsByReturnStatusId($return_status_id) {
		$this->db->select('COUNT(*) AS total');
		$this->db->from($this->table);
		$this->db->where('return_status_id', (int)$return_status_id);
		$data = $this->db->get()->row_array();
		return $data['total'];

		//$query = $this->db->query("SELECT COUNT(*) AS total FROM `" . DB_PREFIX . "return` WHERE return_status_id = '" . (int)$return_status_id . "'");

		//return $query->row['total'];
	}

	public function getTotalReturnsByReturnReasonId($return_reason_id) {
		$this->db->select('COUNT(*) AS total');
		$this->db->from($this->table);
		$this->db->where('return_reason_id', (int)$return_reason_id);
		$data = $this->db->get()->row_array();
		return $data['total'];
		//$query = $this->db->query("SELECT COUNT(*) AS total FROM `" . DB_PREFIX . "return` WHERE return_reason_id = '" . (int)$return_reason_id . "'");

		//return $query->row['total'];
	}

	public function getTotalReturnsByReturnActionId($return_action_id) {
		$this->db->select('COUNT(*) AS total');
		$this->db->from($this->table);
		$this->db->where('return_action_id', (int)$return_action_id);
		$data = $this->db->get()->row_array();
		return $data['total'];

		//$query = $this->db->query("SELECT COUNT(*) AS total FROM `" . DB_PREFIX . "return` WHERE return_action_id = '" . (int)$return_action_id . "'");

		//return $query->row['total'];
	}

	public function addReturnHistory($return_id, $data) {
		$tmp = $this->initData($data, TRUE);
		$this->db->set('date_modified', 'NOW()', FALSE);
		$this->db->where($this->primaryKey, (int)$return_id);
		$this->db->update($this->table, $tmp);

		//$this->db->query("UPDATE `" . DB_PREFIX . "return` SET return_status_id = '" . (int)$data['return_status_id'] . "', date_modified = NOW() WHERE return_id = '" . (int)$return_id . "'");

		//$this->db->query("INSERT INTO " . DB_PREFIX . "return_history SET return_id = '" . (int)$return_id . "', return_status_id = '" . (int)$data['return_status_id'] . "', notify = '" . (isset($data['notify']) ? (int)$data['notify'] : 0) . "', comment = '" . $this->db->escape(strip_tags($data['comment'])) . "', date_added = NOW()");
		$tmp = $this->initData($data, TRUE, $this->history_fields);
		$this->db->set('date_added', 'NOW()', FASLE);
		$this->db->set('notify', (isset($data['notify']) ? (int)$data['notify'] : 0));
		$this->db->set($this->primaryKey, (int)$return_id);
		$this->db->insert($this->history_table, $tmp);

		if ($data['notify']) {
			$this->db->select('*, rs.name AS status');
			$this->db->from($this->table . ' r');
			$this->db->join('return_status rs', 'r.return_status_id = rs.return_status_i', 'left');
			$this->db->where('r.return_id', (int)$return_id);
			$this->db->where('rs.language_id', (int)$this->config->get('config_language_id'));

			//$return_query = $this->db->query("SELECT *, rs.name AS status FROM `" . DB_PREFIX . "return` r LEFT JOIN " . DB_PREFIX . "return_status rs ON (r.return_status_id = rs.return_status_id) WHERE r.return_id = '" . (int)$return_id . "' AND rs.language_id = '" . (int)$this->config->get('config_language_id') . "'");
			$row = $this->db->get()->row_array();
			if (!empty($row)) {
				$this->load->language('mail/return');

				$subject = sprintf($this->language->get('text_subject'), $this->config->get('config_name'), $return_id);

				$message  = $this->language->get('text_return_id') . ' ' . $return_id . "\n";
				$message .= $this->language->get('text_date_added') . ' ' . date($this->language->get('date_format_short'), strtotime($row['date_added'])) . "\n\n";
				$message .= $this->language->get('text_return_status') . "\n";
				$message .= $row['status'] . "\n\n";

				if ($data['comment']) {
					$message .= $this->language->get('text_comment') . "\n\n";
					$message .= strip_tags(html_entity_decode($data['comment'], ENT_QUOTES, 'UTF-8')) . "\n\n";
				}

				$message .= $this->language->get('text_footer');

				$mail = new Mail($this->config->get('config_mail'));
				$mail->setTo($row['email']);
				$mail->setFrom($this->config->get('config_email'));
				$mail->setSender($this->config->get('config_name'));
				$mail->setSubject($subject);
				$mail->setText(html_entity_decode($message, ENT_QUOTES, 'UTF-8'));
				$mail->send();
			}
		}

		$this->tracking->log(LOG_FUNCTION::$sale_return, LOG_ACTION_MODIFY, $return_id);
	}

	public function getReturnHistories($return_id, $start = 0, $limit = 10) {
		if ($start < 0) {
			$start = 0;
		}

		if ($limit < 1) {
			$limit = 10;
		}

		$this->db->select('rh.date_added, rs.name AS status, rh.comment, rh.notify');
		$this->db->from($this->history_table . ' rh');
		$this->db->join('return_status rs', 'rh.return_status_id = rs.return_status_id', 'left');
		$this->db->where('rh.return_id', (int)$return_id);
		$this->db->where('rs.language_id', (int)$this->config->get('config_language_id'));
		$this->db->order_by('rh.date_added', 'ASC');
		$this->db->limit($limit, $start);

		//$query = $this->db->query("SELECT rh.date_added, rs.name AS status, rh.comment, rh.notify FROM " . DB_PREFIX . "return_history rh LEFT JOIN " . DB_PREFIX . "return_status rs ON rh.return_status_id = rs.return_status_id WHERE rh.return_id = '" . (int)$return_id . "' AND rs.language_id = '" . (int)$this->config->get('config_language_id') . "' ORDER BY rh.date_added ASC LIMIT " . (int)$start . "," . (int)$limit);

		//return $query->result_array();
		return $this->db->get()->result_array();
	}

	public function getTotalReturnHistories($return_id) {
		$this->db->select('COUNT(*) AS `total`');
		$this->db->from($this->history_table);
		$this->db->where($this->primaryKey, (int)$return_id);
		$data = $this->db->get()->row_array();
		return $data['total'];

		//$query = $this->db->query("SELECT COUNT(*) AS total FROM " . DB_PREFIX . "return_history WHERE return_id = '" . (int)$return_id . "'");

		//return $query->row['total'];
	}

	public function getTotalReturnHistoriesByReturnStatusId($return_status_id) {
		$this->db->select('COUNT(*) AS `total`');
		$this->db->from($this->history_table);
		$this->db->where('return_status_id', (int)$return_status_id);
		$this->db->group_by('return_id');
		$data = $this->db->get()->row_array();
		return $data['total'];

		//$query = $this->db->query("SELECT COUNT(*) AS total FROM " . DB_PREFIX . "return_history WHERE return_status_id = '" . (int)$return_status_id . "' GROUP BY return_id");

		//return $query->row['total'];
	}
}