<?php
class ModelMarketingAffiliate extends Model {
	public $table = 'affiliate';
	public $primaryKey = 'affiliate_id';
	public $fields =  array('affiliate', 'firstname', 'lastname', 'email', 'telephone', 'fax', 'password', 'salt', 'company', 'website', 'address_1', 'address_2', 'city', 'postcode', 'country_id', 'zone_id', 'code', 'commission', 'tax', 'payment', 'cheque', 'paypal', 'bank_name', 'bank_branch_number', 'bank_swift_code', 'bank_account_name', 'bank_account_number', 'ip','status', 'approved', 'date_added');
	public $activity_table = 'affiliate_activity';
	public $transaction_table = 'affiliate_transaction';

	public function addAffiliate($data) {
		$this->event->trigger('pre.admin.affiliate.add', $data);
		$tmp = $this->initData($data, TRUE);
		$this->db->set('date_added', 'NOW()', FALSE);
		$this->db->set('commission', (float)$data['commission']);
		$this->db->set('salt', $salt = substr(md5(uniqid(rand(), true)), 0, 9));
		$this->db->set('password', sha1($salt . sha1($salt . sha1($data['password']))));
		$this->db->insert($this->table, $tmp);


		//$this->db->query("INSERT INTO " . DB_PREFIX . "affiliate SET firstname = '" . $this->db->escape($data['firstname']) . "', lastname = '" . $this->db->escape($data['lastname']) . "', email = '" . $this->db->escape($data['email']) . "', telephone = '" . $this->db->escape($data['telephone']) . "', fax = '" . $this->db->escape($data['fax']) . "', salt = '" . $this->db->escape($salt = substr(md5(uniqid(rand(), true)), 0, 9)) . "', password = '" . $this->db->escape(sha1($salt . sha1($salt . sha1($data['password'])))) . "', company = '" . $this->db->escape($data['company']) . "', website = '" . $this->db->escape($data['website']) . "', address_1 = '" . $this->db->escape($data['address_1']) . "', address_2 = '" . $this->db->escape($data['address_2']) . "', city = '" . $this->db->escape($data['city']) . "', postcode = '" . $this->db->escape($data['postcode']) . "', country_id = '" . (int)$data['country_id'] . "', zone_id = '" . (int)$data['zone_id'] . "', code = '" . $this->db->escape($data['code']) . "', commission = '" . (float)$data['commission'] . "', tax = '" . $this->db->escape($data['tax']) . "', payment = '" . $this->db->escape($data['payment']) . "', cheque = '" . $this->db->escape($data['cheque']) . "', paypal = '" . $this->db->escape($data['paypal']) . "', bank_name = '" . $this->db->escape($data['bank_name']) . "', bank_branch_number = '" . $this->db->escape($data['bank_branch_number']) . "', bank_swift_code = '" . $this->db->escape($data['bank_swift_code']) . "', bank_account_name = '" . $this->db->escape($data['bank_account_name']) . "', bank_account_number = '" . $this->db->escape($data['bank_account_number']) . "', status = '" . (int)$data['status'] . "', date_added = NOW()");

		$affiliate_id = $this->db->insert_id();

		$this->event->trigger('post.admin.affiliate.add', $affiliate_id);

		return $affiliate_id;
	}

	public function editAffiliate($affiliate_id, $data) {
		$this->event->trigger('pre.admin.affiliate.edit', $data);

		$tmp = $this->initData($data, TRUE);
		$this->db->set('date_added', 'NOW()', FALSE);
		$this->db->set('commission', (float)$data['commission']);
		//$this->db->set('password', sha1($salt . sha1($salt . sha1($data['password']))));
		//$this->db->set('salt', substr(md5(uniqid(rand(), true)), 0, 9));
		$this->db->where($this->primaryKey, (int)$affiliate_id);
		$this->db->update($this->table, $tmp);

		//$this->db->query("UPDATE " . DB_PREFIX . "affiliate SET firstname = '" . $this->db->escape($data['firstname']) . "', lastname = '" . $this->db->escape($data['lastname']) . "', email = '" . $this->db->escape($data['email']) . "', telephone = '" . $this->db->escape($data['telephone']) . "', fax = '" . $this->db->escape($data['fax']) . "', company = '" . $this->db->escape($data['company']) . "', website = '" . $this->db->escape($data['website']) . "', address_1 = '" . $this->db->escape($data['address_1']) . "', address_2 = '" . $this->db->escape($data['address_2']) . "', city = '" . $this->db->escape($data['city']) . "', postcode = '" . $this->db->escape($data['postcode']) . "', country_id = '" . (int)$data['country_id'] . "', zone_id = '" . (int)$data['zone_id'] . "', code = '" . $this->db->escape($data['code']) . "', commission = '" . (float)$data['commission'] . "', tax = '" . $this->db->escape($data['tax']) . "', payment = '" . $this->db->escape($data['payment']) . "', cheque = '" . $this->db->escape($data['cheque']) . "', paypal = '" . $this->db->escape($data['paypal']) . "', bank_name = '" . $this->db->escape($data['bank_name']) . "', bank_branch_number = '" . $this->db->escape($data['bank_branch_number']) . "', bank_swift_code = '" . $this->db->escape($data['bank_swift_code']) . "', bank_account_name = '" . $this->db->escape($data['bank_account_name']) . "', bank_account_number = '" . $this->db->escape($data['bank_account_number']) . "', status = '" . (int)$data['status'] . "' WHERE affiliate_id = '" . (int)$affiliate_id . "'");

		if ($data['password']) {
			$this->db->set('salt', $salt = substr(md5(uniqid(rand(), true)), 0, 9));
			$this->db->set('password', sha1($salt . sha1($salt . sha1($data['password']))));
			$this->db->where($this->primaryKey, (int)$affiliate_id);
			$this->db->update($this->table);

			//$this->db->query("UPDATE " . DB_PREFIX . "affiliate SET salt = '" . $this->db->escape($salt = substr(md5(uniqid(rand(), true)), 0, 9)) . "', password = '" . $this->db->escape(sha1($salt . sha1($salt . sha1($data['password'])))) . "' WHERE affiliate_id = '" . (int)$affiliate_id . "'");
		}

		$this->event->trigger('post.admin.affiliate.edit', $affiliate_id);
	}

	public function deleteAffiliate($affiliate_id) {
		$this->event->trigger('pre.admin.affiliate.delete', $affiliate_id);
		$this->db->where($this->primaryKey, (int)$affiliate_id);
		$this->db->delete(array($this->table, $this->activity_table, $this->transaction_table));


		/*$this->db->query("DELETE FROM " . DB_PREFIX . "affiliate WHERE affiliate_id = '" . (int)$affiliate_id . "'");
		$this->db->query("DELETE FROM " . DB_PREFIX . "affiliate_activity WHERE affiliate_id = '" . (int)$affiliate_id . "'");
		$this->db->query("DELETE FROM " . DB_PREFIX . "affiliate_transaction WHERE affiliate_id = '" . (int)$affiliate_id . "'");*/

		$this->event->trigger('post.admin.affiliate.delete', $affiliate_id);
	}

	public function getAffiliate($affiliate_id) {
		$this->db->where($this->primaryKey, (int)$affiliate_id);
		$this->db->distinct('*');
		$this->db->from($this->table);
		return $this->db->get()->row_array();

		//$query = $this->db->query("SELECT DISTINCT * FROM " . DB_PREFIX . "affiliate WHERE affiliate_id = '" . (int)$affiliate_id . "'");

		//return $query->row_array();
	}

	public function getAffiliateByEmail($email) {
		$this->db->where('email', utf8_strtolower($email));
		$this->db->distinct('*');
		$this->db->from($this->table);
		return $this->db->get()->row_array();

		//$query = $this->db->query("SELECT DISTINCT * FROM " . DB_PREFIX . "affiliate WHERE LCASE(email) = '" . $this->db->escape(utf8_strtolower($email)) . "'");

		//return $query->row_array();
	}

	public function getAffiliates($data = array()) {
		$this->db->select('*, CONCAT(a.firstname, \' \', a.lastname) AS name, (SUM(at.amount)', FALSE);
		$this->db->from($this->table . ' a');
		//$sql = "SELECT *, CONCAT(a.firstname, ' ', a.lastname) AS name, (SELECT SUM(at.amount) FROM " . DB_PREFIX . "affiliate_transaction at WHERE at.affiliate_id = a.affiliate_id GROUP BY at.affiliate_id) AS balance FROM " . DB_PREFIX . "affiliate a";

		$implode = array();

		if (!empty($data['filter_name'])) {
			$this->db->like('CONCAT(a.firstname, \' \', a.lastname)', $data['filter_name']);
			//$implode[] = "CONCAT(a.firstname, ' ', a.lastname) LIKE '" . $this->db->escape($data['filter_name']) . "%'";
		}

		if (!empty($data['filter_email'])) {
			$this->db->where('a.mail', utf8_strtolower($data['filter_email']));

			//$implode[] = "LCASE(a.email) = '" . $this->db->escape(utf8_strtolower($data['filter_email'])) . "'";
		}

		if (!empty($data['filter_code'])) {
			$this->db->where('a.code', $data['filter_code']);

			//$implode[] = "a.code = '" . $this->db->escape($data['filter_code']) . "'";
		}

		if (isset($data['filter_status']) && !is_null($data['filter_status'])) {
			$this->db->where('a.status', (int)$data['filter_status']);

			//$implode[] = "a.status = '" . (int)$data['filter_status'] . "'";
		}

		if (isset($data['filter_approved']) && !is_null($data['filter_approved'])) {
			$this->db->where('a.approved', (int)$data['filter_approved']);

			//$implode[] = "a.approved = '" . (int)$data['filter_approved'] . "'";
		}

		if (!empty($data['filter_date_added'])) {
			$this->db->where('DATE(a.date_added) = DATE(\'' . $this->db->escape($data['filter_date_added']) . '\')', NULL, FALSE);

			//$implode[] = "DATE(a.date_added) = DATE('" . $this->db->escape($data['filter_date_added']) . "')";
		}

		$sort_data = array(
			'name',
			'a.email',
			'a.code',
			'a.status',
			'a.approved',
			'a.date_added'
		);

		$order = 'ASC';
		if (isset($data['order']) && ($data['order'] == 'DESC')) {
			$order = 'DESC';
		}

		if (isset($data['sort']) && in_array($data['sort'], $sort_data)) {
			$this->db->order_by($data['sort'], $order);

			//$sql .= " ORDER BY " . $data['sort'];
		} else {
			$this->db->order_by('name', $order);

			//$sql .= " ORDER BY name";
		}

		if (isset($data['order']) && ($data['order'] == 'DESC')) {
			$sql .= " DESC";
		} else {
			$sql .= " ASC";
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

		return $this->db->get()->result_array();

		//$query = $this->db->query($sql);

		//return $query->result_array();
	}

	public function approve($affiliate_id) {

		$affiliate_info = $this->getAffiliate($affiliate_id);

		if ($affiliate_info) {
			$this->event->trigger('pre.admin.affiliate.approve', $affiliate_id);

			$this->db->query("UPDATE " . DB_PREFIX . "affiliate SET approved = '1' WHERE affiliate_id = '" . (int)$affiliate_id . "'");

			$this->load->language('mail/affiliate');

			$message  = sprintf($this->language->get('text_approve_welcome'), $this->config->get('config_name')) . "\n\n";
			$message .= $this->language->get('text_approve_login') . "\n";
			$message .= HTTP_CATALOG . 'index.php?route=affiliate/login' . "\n\n";
			$message .= $this->language->get('text_approve_services') . "\n\n";
			$message .= $this->language->get('text_approve_thanks') . "\n";
			$message .= $this->config->get('config_name');

			$mail = new Mail($this->config->get('config_mail'));
			$mail->setTo($affiliate_info['email']);
			$mail->setFrom($this->config->get('config_email'));
			$mail->setSender($this->config->get('config_name'));
			$mail->setSubject(sprintf($this->language->get('text_approve_subject'), $this->config->get('config_name')));
			$mail->setText(html_entity_decode($message, ENT_QUOTES, 'UTF-8'));
			$mail->send();

			$this->event->trigger('post.admin.affiliate.approve', $affiliate_id);
		}
	}

	public function getAffiliatesByNewsletter() {
		$this->db->select('*')->from($this->table);
		$this->db->where('newsletter', 1);
		$this->db->order_by('firstname', 'ASC');
		$this->db->order_by('lastname', 'ASC');
		$this->db->order_by('email', 'ASC');
		//$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "affiliate WHERE newsletter = '1' ORDER BY firstname, lastname, email");

		//return $query->result_array();
		return $this->db->get()->result_array();
	}

	public function getTotalAffiliates($data = array()) {
		$this->db->select('COUNT(*) AS total');
		$this->db->from($this->table . ' a');

		//$sql = "SELECT COUNT(*) AS total FROM " . DB_PREFIX . "affiliate";

		if (!empty($data['filter_name'])) {
			$this->db->like('CONCAT(a.firstname, \' \', a.lastname)', $data['filter_name']);
			//$implode[] = "CONCAT(a.firstname, ' ', a.lastname) LIKE '" . $this->db->escape($data['filter_name']) . "%'";
		}

		if (!empty($data['filter_email'])) {
			$this->db->where('a.mail', utf8_strtolower($data['filter_email']));

			//$implode[] = "LCASE(a.email) = '" . $this->db->escape(utf8_strtolower($data['filter_email'])) . "'";
		}

		if (!empty($data['filter_code'])) {
			$this->db->where('a.code', $data['filter_code']);

			//$implode[] = "a.code = '" . $this->db->escape($data['filter_code']) . "'";
		}

		if (isset($data['filter_status']) && !is_null($data['filter_status'])) {
			$this->db->where('a.status', (int)$data['filter_status']);

			//$implode[] = "a.status = '" . (int)$data['filter_status'] . "'";
		}

		if (isset($data['filter_approved']) && !is_null($data['filter_approved'])) {
			$this->db->where('a.approved', (int)$data['filter_approved']);

			//$implode[] = "a.approved = '" . (int)$data['filter_approved'] . "'";
		}

		if (!empty($data['filter_date_added'])) {
			$this->db->where('DATE(a.date_added) = DATE(\'' . $this->db->escape($data['filter_date_added']) . '\')', NULL, FALSE);

			//$implode[] = "DATE(a.date_added) = DATE('" . $this->db->escape($data['filter_date_added']) . "')";
		}

		//$query = $this->db->query($sql)->row_array();

		//return $query['total'];
		$data =  $this->db->get()->row_array();
		return $data['total'];
	}

	public function getTotalAffiliatesAwaitingApproval() {
		$this->db->select('COUNT(*) AS total');
		$this->db->from($this->table);
		$this->db->where('status', 0);
		$this->db->where('approved', 0);
		$data = $this->db->get()->row_array();
		return $data['total'];

		//$query = $this->db->query("SELECT COUNT(*) AS total FROM " . DB_PREFIX . "affiliate WHERE status = '0' OR approved = '0'")->row_array();

		//return $query['total'];
	}

	public function getTotalAffiliatesByCountryId($country_id) {
		$this->db->select('COUNT(*) AS total');
		$this->db->from($this->table);
		$this->db->where('country_id', (int)$country_id);
		$data = $this->db->get()->row_array();
		return $data['total'];
		//$query = $this->db->query("SELECT COUNT(*) AS total FROM " . DB_PREFIX . "affiliate WHERE country_id = '" . (int)$country_id . "'")->row_array();

		//return $query['total'];
	}

	public function getTotalAffiliatesByZoneId($zone_id) {
		$this->db->select('COUNT(*) AS total');
		$this->db->from($this->table);
		$this->db->where('zone_id', (int)$zone_id);
		$data = $this->db->get()->row_array();
		return $data['total'];

		//$query = $this->db->query("SELECT COUNT(*) AS total FROM " . DB_PREFIX . "affiliate WHERE zone_id = '" . (int)$zone_id . "'");

		//return $query->row['total'];
	}

	public function addTransaction($affiliate_id, $description = '', $amount = '', $order_id = 0) {
		$affiliate_info = $this->getAffiliate($affiliate_id);

		if ($affiliate_info) {
			$this->event->trigger('pre.admin.affiliate.transaction.add', $affiliate_id);

			$this->db->query("INSERT INTO " . DB_PREFIX . "affiliate_transaction SET affiliate_id = '" . (int)$affiliate_id . "', order_id = '" . (float)$order_id . "', description = '" . $this->db->escape($description) . "', amount = '" . (float)$amount . "', date_added = NOW()");

			$affiliate_transaction_id = $this->db->insert_id();

			$this->load->language('mail/affiliate');

			$message  = sprintf($this->language->get('text_transaction_received'), $this->currency->format($amount, $this->config->get('config_currency'))) . "\n\n";
			$message .= sprintf($this->language->get('text_transaction_total'), $this->currency->format($this->getTransactionTotal($affiliate_id), $this->config->get('config_currency')));

			$mail = new Mail($this->config->get('config_mail'));
			$mail->setTo($affiliate_info['email']);
			$mail->setFrom($this->config->get('config_email'));
			$mail->setSender($this->config->get('config_name'));
			$mail->setSubject(sprintf($this->language->get('text_transaction_subject'), $this->config->get('config_name')));
			$mail->setText(html_entity_decode($message, ENT_QUOTES, 'UTF-8'));
			$mail->send();

			$this->event->trigger('post.admin.affiliate.transaction.add', $affiliate_transaction_id);

			return $affiliate_transaction_id;
		}
	}

	public function deleteTransaction($order_id) {
		$this->event->trigger('pre.admin.affiliate.transaction.delete', $order_id);

		$this->db->where('order_id', (int)$order_id);
		$this->db->delete($this->transaction_table);

		//$this->db->query("DELETE FROM " . DB_PREFIX . "affiliate_transaction WHERE order_id = '" . (int)$order_id . "'");

		$this->event->trigger('post.admin.affiliate.transaction.delete', $order_id);
	}

	public function getTransactions($affiliate_id, $start = 0, $limit = 10) {
		if ($start < 0) {
			$start = 0;
		}

		if ($limit < 1) {
			$limit = 10;
		}

		$this->db->select('*')->from($this->transaction_table)->where('affiliate_id', (int)$affiliate_id);
		$this->db->order_by('date_added', 'DESC');
		$this->db->limit((int)$limit, (int)$start);
		return $this->db->get()->result_array();

		//$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "affiliate_transaction WHERE affiliate_id = '" . (int)$affiliate_id . "' ORDER BY date_added DESC LIMIT " . (int)$start . "," . (int)$limit);

	//	return $query->result_array();
	}

	public function getTotalTransactions($affiliate_id) {
		$this->db->where('affiliate_id', (int)$affiliate_id);
		$this->db->select('COUNT(*) AS total');
		$this->db->from($this->transaction_table);
		$data = $this->db->get()->row_array();
		return $data['total'];

		//$query = $this->db->query("SELECT COUNT(*) AS total  FROM " . DB_PREFIX . "affiliate_transaction WHERE affiliate_id = '" . (int)$affiliate_id . "'")->row_array();

		//return $query['total'];
	}

	public function getTransactionTotal($affiliate_id) {
		$this->db->where('affiliate_id', (int)$affiliate_id);
		$this->db->select('SUM(amount) AS total');
		$this->db->from($this->transaction_table);
		$data = $this->db->get()->row_array();
		return $data['total'];

		//$query = $this->db->query("SELECT SUM(amount) AS total FROM " . DB_PREFIX . "affiliate_transaction WHERE affiliate_id = '" . (int)$affiliate_id . "'")->row_array();

		//return $query['total'];
	}

	public function getTotalTransactionsByOrderId($order_id) {
		$this->db->where('order_id', (int)$order_id);
		$this->db->select('COUNT(*) AS total');
		$this->db->from($this->transaction_table);
		$data = $this->db->get()->row_array();
		return $data['total'];

		//$query = $this->db->query("SELECT COUNT(*) AS total FROM " . DB_PREFIX . "affiliate_transaction WHERE order_id = '" . (int)$order_id . "'")->row_array();

		//return $query['total'];
	}
	
	public function getTotalLoginAttempts($email) {
		$this->db->where('email', $email);
		$this->db->select('*');
		$this->db->from('affiliate_login');
		return $this->db->get()->result_array();

		//$query = $this->db->query("SELECT * FROM `" . DB_PREFIX . "affiliate_login` WHERE `email` = '" . $this->db->escape($email) . "'");

		//return $query->row_array();
	}	

	public function deleteLoginAttempts($email) {
		$this->db->where('email', $email);
		$this->db->delete('affiliate_login');

		//$this->db->query("DELETE FROM `" . DB_PREFIX . "affiliate_login` WHERE `email` = '" . $this->db->escape($email) . "'");
	}	
}