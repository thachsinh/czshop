<?php
class ModelSaleCustomer extends Model {
	public $table = 'customer';
	public $fields = array('customer_id', 'customer_group_id', 'store_id', 'firstname', 'lastname', 'email', 'telephone', 'fax', 'password', 'salt', 'cart', 'wishlist', 'newsletter', 'address_id', 'custom_field', 'ip', 'status', 'approved', 'safe', 'token', 'date_added');
	public $primaryKey = 'customer_id';
	public $addr_fields = array('address_id', 'customer_id', 'firstname', 'lastname', 'company', 'address_1', 'address_2', 'city', 'postcode', 'country_id', 'zone_id', 'custom_field');

	public function addCustomer($data) {
		$tmp = $this->initData($data, TRUE);
		$tmp['salt'] = $salt = substr(md5(uniqid(rand(), true)), 0, 9);
		$tmp['password'] = sha1($salt . sha1($salt . sha1($data['password'])));
		$tmp['custom_field'] = isset($data['custom_field']) ? serialize($data['custom_field']) : '';
		$this->db->set('date_added', 'NOW()', FALSE);
		$this->db->insert($this->table, $tmp);

		//$this->db->query("INSERT INTO " . DB_PREFIX . "customer SET customer_group_id = '" . (int)$data['customer_group_id'] . "', firstname = '" . $this->db->escape($data['firstname']) . "', lastname = '" . $this->db->escape($data['lastname']) . "', email = '" . $this->db->escape($data['email']) . "', telephone = '" . $this->db->escape($data['telephone']) . "', fax = '" . $this->db->escape($data['fax']) . "', custom_field = '" . $this->db->escape(isset($data['custom_field']) ? serialize($data['custom_field']) : '') . "', newsletter = '" . (int)$data['newsletter'] . "', salt = '" . $this->db->escape($salt = substr(md5(uniqid(rand(), true)), 0, 9)) . "', password = '" . $this->db->escape(sha1($salt . sha1($salt . sha1($data['password'])))) . "', status = '" . (int)$data['status'] . "', approved = '" . (int)$data['approved'] . "', safe = '" . (int)$data['safe'] . "', date_added = NOW()");

		//$customer_id = $this->db->getLastId();
		$customer_id = $this->db->insert_id();

		if (isset($data['address'])) {
			foreach ($data['address'] as $address) {
				$tmp = $this->initData($address, TRUE, $this->addr_fields);
				$tmp['custom_field'] = isset($address['custom_field']) ? serialize($address['custom_field']) : '';
				$this->db->set('customer_id', $customer_id);
				$this->db->insert('address', $tmp);

				//$this->db->query("INSERT INTO " . DB_PREFIX . "address SET customer_id = '" . (int)$customer_id . "', firstname = '" . $this->db->escape($address['firstname']) . "', lastname = '" . $this->db->escape($address['lastname']) . "', company = '" . $this->db->escape($address['company']) . "', address_1 = '" . $this->db->escape($address['address_1']) . "', address_2 = '" . $this->db->escape($address['address_2']) . "', city = '" . $this->db->escape($address['city']) . "', postcode = '" . $this->db->escape($address['postcode']) . "', country_id = '" . (int)$address['country_id'] . "', zone_id = '" . (int)$address['zone_id'] . "', custom_field = '" . $this->db->escape(isset($address['custom_field']) ? serialize($address['custom_field']) : '') . "'");

				if (isset($address['default'])) {
					//$address_id = $this->db->getLastId();
					$address_id = $this->db->insert_id();
					$this->db->where($this->primaryKey, $customer_id);
					$this->db->set('address_id', $address_id);
					$this->db->update($this->table);

					//$this->db->query("UPDATE " . DB_PREFIX . "customer SET address_id = '" . (int)$address_id . "' WHERE customer_id = '" . (int)$customer_id . "'");
				}
			}
		}

		$this->tracking->log(LOG_FUNCTION::$sale_customer, LOG_ACTION_ADD, $customer_id);
	}

	public function editCustomer($customer_id, $data) {
		if (!isset($data['custom_field'])) {
			$data['custom_field'] = array();
		}

		$tmp = $this->initData($data, TRUE);
		$tmp['custom_field'] = isset($data['custom_field']) ? serialize($data['custom_field']) : '';
		if(isset($data['password']) && strlen($data['password'])) {
			unset($tmp['password']);
		}
		$this->db->where($this->primaryKey, (int)$customer_id);
		$this->db->update($this->table, $tmp);
		//$this->db->query("UPDATE " . DB_PREFIX . "customer SET customer_group_id = '" . (int)$data['customer_group_id'] . "', firstname = '" . $this->db->escape($data['firstname']) . "', lastname = '" . $this->db->escape($data['lastname']) . "', email = '" . $this->db->escape($data['email']) . "', telephone = '" . $this->db->escape($data['telephone']) . "', fax = '" . $this->db->escape($data['fax']) . "', custom_field = '" . $this->db->escape(isset($data['custom_field']) ? serialize($data['custom_field']) : '') . "', newsletter = '" . (int)$data['newsletter'] . "', status = '" . (int)$data['status'] . "', approved = '" . (int)$data['approved'] . "', safe = '" . (int)$data['safe'] . "' WHERE customer_id = '" . (int)$customer_id . "'");

		if (isset($data['password'])) {

			$salt = substr(md5(uniqid(rand(), true)), 0, 9);
			$this->db->set('salt', $salt);
			$this->db->set('password', sha1($salt . sha1($salt . sha1($data['password']))));
			$this->db->where($this->primaryKey, (int)$customer_id);
			$this->db->update($this->table);
			//$this->db->query("UPDATE " . DB_PREFIX . "customer SET salt = '" . $this->db->escape($salt = substr(md5(uniqid(rand(), true)), 0, 9)) . "', password = '" . $this->db->escape(sha1($salt . sha1($salt . sha1($data['password'])))) . "' WHERE customer_id = '" . (int)$customer_id . "'");
		}

		$this->db->where($this->primaryKey, (int)$customer_id);
		$this->db->delete('address');

		//$this->db->query("DELETE FROM " . DB_PREFIX . "address WHERE customer_id = '" . (int)$customer_id . "'");

		if (isset($data['address'])) {
			foreach ($data['address'] as $address) {
				if (!isset($address['custom_field'])) {
					$address['custom_field'] = array();
				}
				$tmp = $this->initData($address, TRUE, $this->addr_fields);
				$tmp['custom_field'] = isset($address['custom_field']) ? serialize($address['custom_field']) : '';
				$this->db->set('customer_id', $customer_id);
				$this->db->insert('address', $tmp);

				//$this->db->query("INSERT INTO " . DB_PREFIX . "address SET address_id = '" . (int)$address['address_id'] . "', customer_id = '" . (int)$customer_id . "', firstname = '" . $this->db->escape($address['firstname']) . "', lastname = '" . $this->db->escape($address['lastname']) . "', company = '" . $this->db->escape($address['company']) . "', address_1 = '" . $this->db->escape($address['address_1']) . "', address_2 = '" . $this->db->escape($address['address_2']) . "', city = '" . $this->db->escape($address['city']) . "', postcode = '" . $this->db->escape($address['postcode']) . "', country_id = '" . (int)$address['country_id'] . "', zone_id = '" . (int)$address['zone_id'] . "', custom_field = '" . $this->db->escape(isset($address['custom_field']) ? serialize($address['custom_field']) : '') . "'");

				if (isset($address['default'])) {
					//$address_id = $this->db->getLastId();

					//$this->db->query("UPDATE " . DB_PREFIX . "customer SET address_id = '" . (int)$address_id . "' WHERE customer_id = '" . (int)$customer_id . "'");

					$address_id = $this->db->insert_id();
					$this->db->where($this->primaryKey, $customer_id);
					$this->db->set('address_id', $address_id);
					$this->db->update($this->table);
				}
			}
		}

		$this->tracking->log(LOG_FUNCTION::$sale_customer, LOG_ACTION_MODIFY, $customer_id);
	}

	public function editStatus($customer_id, $status) {
		$this->db->where($this->primaryKey, (int)$customer_id);
		$this->db->set('status', (int)$status);
		$this->db->update($this->table);

		$this->tracking->log(LOG_FUNCTION::$sale_customer, LOG_ACTION_MODIFY, $customer_id);
	}

	public function editToken($customer_id, $token) {
		$this->db->where($this->primaryKey, (int)$customer_id);
		$this->db->set('token', $token);
		$this->db->update($this->table);

		//$this->db->query("UPDATE " . DB_PREFIX . "customer SET token = '" . $this->db->escape($token) . "' WHERE customer_id = '" . (int)$customer_id . "'");

		$this->tracking->log(LOG_FUNCTION::$sale_customer, LOG_ACTION_MODIFY, $customer_id);
	}

	public function deleteCustomer($customer_id) {
		$this->db->where($this->primaryKey, (int)$customer_id);
		$this->db->delete(array($this->table, 'customer_reward', 'customer_transaction', 'customer_ip', 'address'));

		/*$this->db->query("DELETE FROM " . DB_PREFIX . "customer WHERE customer_id = '" . (int)$customer_id . "'");
		$this->db->query("DELETE FROM " . DB_PREFIX . "customer_reward WHERE customer_id = '" . (int)$customer_id . "'");
		$this->db->query("DELETE FROM " . DB_PREFIX . "customer_transaction WHERE customer_id = '" . (int)$customer_id . "'");
		$this->db->query("DELETE FROM " . DB_PREFIX . "customer_ip WHERE customer_id = '" . (int)$customer_id . "'");
		$this->db->query("DELETE FROM " . DB_PREFIX . "address WHERE customer_id = '" . (int)$customer_id . "'");*/

		$this->tracking->log(LOG_FUNCTION::$sale_customer, LOG_ACTION_DELETE, $customer_id);
	}

	public function getCustomer($customer_id) {
		$this->db->distinct();
		$this->db->from($this->table);
		$this->db->where($this->primaryKey, (int)$customer_id);
		return $this->db->get()->row_array();

		//$query = $this->db->query("SELECT DISTINCT * FROM " . DB_PREFIX . "customer WHERE customer_id = '" . (int)$customer_id . "'");

		//return $query->row;
	}

	public function getCustomerByEmail($email) {
		$this->db->distinct();
		$this->db->from($this->table);
		$this->db->where('email', utf8_strtolower($email));
		return $this->db->get()->row_array();

		//$query = $this->db->query("SELECT DISTINCT * FROM " . DB_PREFIX . "customer WHERE LCASE(email) = '" . $this->db->escape(utf8_strtolower($email)) . "'");

		//return $query->row;
	}

	public function getCustomers($data = array()) {
		$this->db->select('*, CONCAT(c.firstname,\'	\',c.lastname) AS name, cgd.name AS customer_group', FALSE);
		$this->db->from('customer c');
		$this->db->join('customer_group_description cgd', 'c.customer_group_id = cgd.customer_group_id', 'left');
		$this->db->where('cgd.language_id', (int)$this->config->get('config_language_id'));

		//$sql = "SELECT *, CONCAT(c.firstname, ' ', c.lastname) AS name, cgd.name AS customer_group FROM " . DB_PREFIX . "customer c LEFT JOIN " . DB_PREFIX . "customer_group_description cgd ON (c.customer_group_id = cgd.customer_group_id) WHERE cgd.language_id = '" . (int)$this->config->get('config_language_id') . "'";

		//$implode = array();

		if (!empty($data['filter_name'])) {
			//$implode[] = "CONCAT(c.firstname, ' ', c.lastname) LIKE '%" . $this->db->escape($data['filter_name']) . "%'";
			$this->db->like('c.firstname', $data['filter_name']);
		}

		if (!empty($data['filter_email'])) {
			//$implode[] = "c.email LIKE '" . $this->db->escape($data['filter_email']) . "%'";
			$this->db->like('c.email', $data['filter_email']);
		}

		if (isset($data['filter_newsletter']) && !is_null($data['filter_newsletter'])) {
			$this->db->where('c.newsletter', (int)$data['filter_newsletter']);
			//$implode[] = "c.newsletter = '" . (int)$data['filter_newsletter'] . "'";
		}

		if (!empty($data['filter_customer_group_id'])) {
			$this->db->where('c.customer_group_id', (int)$data['filter_customer_group_id']);

			//$implode[] = "c.customer_group_id = '" . (int)$data['filter_customer_group_id'] . "'";
		}

		if (!empty($data['filter_ip'])) {
			$this->db->where('c.ip', $data['filter_ip']);

			//$implode[] = "c.customer_id IN (SELECT customer_id FROM " . DB_PREFIX . "customer_ip WHERE ip = '" . $this->db->escape($data['filter_ip']) . "')";
		}

		if (isset($data['filter_status']) && !is_null($data['filter_status'])) {
			$this->db->where('c.status', (int)$data['filter_status']);

			//$implode[] = "c.status = '" . (int)$data['filter_status'] . "'";
		}

		if (isset($data['filter_approved']) && !is_null($data['filter_approved'])) {
			$this->db->where('c.approved', (int)$data['filter_approved']);

			//$implode[] = "c.approved = '" . (int)$data['filter_approved'] . "'";
		}

		if (!empty($data['filter_date_added'])) {
			$this->db->where('DATE(c.date_added) = DATE(' . $this->db->escape($data['filter_date_added']) . ')', NULL, FALSE);

			//$implode[] = "DATE(c.date_added) = DATE('" . $this->db->escape($data['filter_date_added']) . "')";
		}

		/*if ($implode) {
			$sql .= " AND " . implode(" AND ", $implode);
		}*/

		$sort_data = array(
			'name',
			'c.email',
			'customer_group',
			'c.status',
			'c.approved',
			'c.ip',
			'c.date_added'
		);

		$order = 'ASC';
		if (isset($data['order']) && ($data['order'] == 'DESC')) {
			$order = 'DESC';
		}

		if (isset($data['sort']) && in_array($data['sort'], $sort_data)) {
			//$sql .= " ORDER BY " . $data['sort'];
			$this->db->order_by($data['sort'], $order);
		} else {
			$this->db->order_by('name', $order);
			//$sql .= " ORDER BY name";
		}

		/*
		if (isset($data['order']) && ($data['order'] == 'DESC')) {
			$sql .= " DESC";
		} else {
			$sql .= " ASC";
		}*/

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

		//return $query->rows;
	}

	public function approve($customer_id) {
		$customer_info = $this->getCustomer($customer_id);

		if ($customer_info) {
			$this->db->query("UPDATE " . DB_PREFIX . "customer SET approved = '1' WHERE customer_id = '" . (int)$customer_id . "'");

			$this->load->language('mail/customer');

			$this->load->model('setting/store');

			$store_info = $this->model_setting_store->getStore($customer_info['store_id']);

			if ($store_info) {
				$store_name = $store_info['name'];
				$store_url = $store_info['url'] . 'index.php?route=account/login';
			} else {
				$store_name = $this->config->get('config_name');
				$store_url = HTTP_CATALOG . 'index.php?route=account/login';
			}

			$message  = sprintf($this->language->get('text_approve_welcome'), $store_name) . "\n\n";
			$message .= $this->language->get('text_approve_login') . "\n";
			$message .= $store_url . "\n\n";
			$message .= $this->language->get('text_approve_services') . "\n\n";
			$message .= $this->language->get('text_approve_thanks') . "\n";
			$message .= $store_name;

			$mail = new Mail($this->config->get('config_mail'));
			$mail->setTo($customer_info['email']);
			$mail->setFrom($this->config->get('config_email'));
			$mail->setSender($store_name);
			$mail->setSubject(sprintf($this->language->get('text_approve_subject'), $store_name));
			$mail->setText(html_entity_decode($message, ENT_QUOTES, 'UTF-8'));
			$mail->send();
		}

		$this->tracking->log(LOG_FUNCTION::$sale_customer, LOG_ACTION_MODIFY, $customer_id);
	}

	public function getAddress($address_id) {
		$this->db->select('*');
		$this->db->from('address');
		$this->db->where('address_id', (int)$address_id);
		$address_query = $this->db->get()->row_array();

		//$address_query = $this->db->query("SELECT * FROM " . DB_PREFIX . "address WHERE address_id = '" . (int)$address_id . "'");

		if (!empty($address_query)) {
			$this->db->select('*');
			$this->db->from('country');
			$this->db->where('country_id', (int)$address_query['country_id']);
			$country_query = $this->db->get()->row_array();
			//$country_query = $this->db->query("SELECT * FROM `" . DB_PREFIX . "country` WHERE country_id = '" . (int)$address_query->row['country_id'] . "'");

			if (!empty($country_query)) {

				/*$country = $country_query->row['name'];
				$iso_code_2 = $country_query->row['iso_code_2'];
				$iso_code_3 = $country_query->row['iso_code_3'];
				$address_format = $country_query->row['address_format'];
				*/

				$country = $country_query['name'];
				$iso_code_2 = $country_query['iso_code_2'];
				$iso_code_3 = $country_query['iso_code_3'];
				$address_format = $country_query['address_format'];
			} else {
				$country = '';
				$iso_code_2 = '';
				$iso_code_3 = '';
				$address_format = '';
			}

			$this->db->select('*')->from('zone')->where('zone_id', (int)$address_query['zone_id']);
			$zone_query = $this->db->get()->row_array();
			//$zone_query = $this->db->query("SELECT * FROM `" . DB_PREFIX . "zone` WHERE zone_id = '" . (int)$address_query->row['zone_id'] . "'");

			if (!empty($zone_query)) {
				$zone = $zone_query['name'];
				$zone_code = $zone_query['code'];
				//$zone = $zone_query->row['name'];
				//$zone_code = $zone_query->row['code'];
			} else {
				$zone = '';
				$zone_code = '';
			}

			/*
			return array(
				'address_id'     => $address_query->row['address_id'],
				'customer_id'    => $address_query->row['customer_id'],
				'firstname'      => $address_query->row['firstname'],
				'lastname'       => $address_query->row['lastname'],
				'company'        => $address_query->row['company'],
				'address_1'      => $address_query->row['address_1'],
				'address_2'      => $address_query->row['address_2'],
				'postcode'       => $address_query->row['postcode'],
				'city'           => $address_query->row['city'],
				'zone_id'        => $address_query->row['zone_id'],
				'zone'           => $zone,
				'zone_code'      => $zone_code,
				'country_id'     => $address_query->row['country_id'],
				'country'        => $country,
				'iso_code_2'     => $iso_code_2,
				'iso_code_3'     => $iso_code_3,
				'address_format' => $address_format,
				'custom_field'   => unserialize($address_query->row['custom_field'])
			);
			*/

			return array(
				'address_id'     => $address_query['address_id'],
				'customer_id'    => $address_query['customer_id'],
				'firstname'      => $address_query['firstname'],
				'lastname'       => $address_query['lastname'],
				'company'        => $address_query['company'],
				'address_1'      => $address_query['address_1'],
				'address_2'      => $address_query['address_2'],
				'postcode'       => $address_query['postcode'],
				'city'           => $address_query['city'],
				'zone_id'        => $address_query['zone_id'],
				'zone'           => $zone,
				'zone_code'      => $zone_code,
				'country_id'     => $address_query['country_id'],
				'country'        => $country,
				'iso_code_2'     => $iso_code_2,
				'iso_code_3'     => $iso_code_3,
				'address_format' => $address_format,
				'custom_field'   => unserialize($address_query['custom_field'])
			);
	
		}
	}

	public function getAddresses($customer_id) {
		$address_data = array();

		$this->db->select('address_id');
		$this->db->from('address');
		$this->db->where($this->primaryKey, (int)$customer_id);

		//$query = $this->db->query("SELECT address_id FROM " . DB_PREFIX . "address WHERE customer_id = '" . (int)$customer_id . "'");

		foreach ($this->db->get()->result_array() as $result) {
			$address_info = $this->getAddress($result['address_id']);

			if ($address_info) {
				$address_data[$result['address_id']] = $address_info;
			}
		}

		return $address_data;
	}

	public function getTotalCustomers($data = array()) {
		$this->db->select('COUNT(*) AS `total`');
		$this->db->from($this->table);
		//$this->db->where('cgd.language_id', (int)$this->config->get('config_language_id'));

		//$sql = "SELECT *, CONCAT(c.firstname, ' ', c.lastname) AS name, cgd.name AS customer_group FROM " . DB_PREFIX . "customer c LEFT JOIN " . DB_PREFIX . "customer_group_description cgd ON (c.customer_group_id = cgd.customer_group_id) WHERE cgd.language_id = '" . (int)$this->config->get('config_language_id') . "'";

		//$implode = array();

		if (!empty($data['filter_name'])) {
			//$implode[] = "CONCAT(c.firstname, ' ', c.lastname) LIKE '%" . $this->db->escape($data['filter_name']) . "%'";
			$this->db->like('firstname', $data['filter_name']);
		}

		if (!empty($data['filter_email'])) {
			//$implode[] = "c.email LIKE '" . $this->db->escape($data['filter_email']) . "%'";
			$this->db->like('email', $data['filter_email']);
		}

		if (isset($data['filter_newsletter']) && !is_null($data['filter_newsletter'])) {
			$this->db->where('newsletter', (int)$data['filter_newsletter']);
			//$implode[] = "c.newsletter = '" . (int)$data['filter_newsletter'] . "'";
		}

		if (!empty($data['filter_customer_group_id'])) {
			$this->db->where('customer_group_id', (int)$data['filter_customer_group_id']);

			//$implode[] = "c.customer_group_id = '" . (int)$data['filter_customer_group_id'] . "'";
		}

		if (!empty($data['filter_ip'])) {
			$this->db->where('ip', $data['filter_ip']);

			//$implode[] = "c.customer_id IN (SELECT customer_id FROM " . DB_PREFIX . "customer_ip WHERE ip = '" . $this->db->escape($data['filter_ip']) . "')";
		}

		if (isset($data['filter_status']) && !is_null($data['filter_status'])) {
			$this->db->where('status', (int)$data['filter_status']);

			//$implode[] = "c.status = '" . (int)$data['filter_status'] . "'";
		}

		if (isset($data['filter_approved']) && !is_null($data['filter_approved'])) {
			$this->db->where('approved', (int)$data['filter_approved']);

			//$implode[] = "c.approved = '" . (int)$data['filter_approved'] . "'";
		}

		if (!empty($data['filter_date_added'])) {
			$this->db->where('DATE(date_added) = DATE(' . $this->db->escape($data['filter_date_added']) . ')', NULL, FALSE);

			//$implode[] = "DATE(c.date_added) = DATE('" . $this->db->escape($data['filter_date_added']) . "')";
		}

		//$sql = "SELECT COUNT(*) AS total FROM " . DB_PREFIX . "customer";

		/*
		$implode = array();

		if (!empty($data['filter_name'])) {
			$implode[] = "CONCAT(firstname, ' ', lastname) LIKE '%" . $this->db->escape($data['filter_name']) . "%'";
		}

		if (!empty($data['filter_email'])) {
			$implode[] = "email LIKE '" . $this->db->escape($data['filter_email']) . "%'";
		}

		if (isset($data['filter_newsletter']) && !is_null($data['filter_newsletter'])) {
			$implode[] = "newsletter = '" . (int)$data['filter_newsletter'] . "'";
		}

		if (!empty($data['filter_customer_group_id'])) {
			$implode[] = "customer_group_id = '" . (int)$data['filter_customer_group_id'] . "'";
		}

		if (!empty($data['filter_ip'])) {
			$implode[] = "customer_id IN (SELECT customer_id FROM " . DB_PREFIX . "customer_ip WHERE ip = '" . $this->db->escape($data['filter_ip']) . "')";
		}

		if (isset($data['filter_status']) && !is_null($data['filter_status'])) {
			$implode[] = "status = '" . (int)$data['filter_status'] . "'";
		}

		if (isset($data['filter_approved']) && !is_null($data['filter_approved'])) {
			$implode[] = "approved = '" . (int)$data['filter_approved'] . "'";
		}

		if (!empty($data['filter_date_added'])) {
			$implode[] = "DATE(date_added) = DATE('" . $this->db->escape($data['filter_date_added']) . "')";
		}

		if ($implode) {
			$sql .= " WHERE " . implode(" AND ", $implode);
		}
		*/

		//$query = $this->db->query($sql);

		//return $query->row['total'];
		$query = $this->db->get()->row_array();
		return $query['total'];
	}

	public function getTotalCustomersAwaitingApproval() {
		$this->db->select('COUNT(*) AS `total`');
		$this->db->from($this->table);
		$this->db->where('status', 0);
		$this->db->or_where('approved', 0);

		//$query = $this->db->query("SELECT COUNT(*) AS total FROM " . DB_PREFIX . "customer WHERE status = '0' OR approved = '0'");

		//return $query->row['total'];
		$query = $this->db->get()->result_array();
		return $query['total'];
	}

	public function getTotalAddressesByCustomerId($customer_id) {
		$this->db->select('COUNT(*) AS `total`');
		$this->db->from('address');
		$this->db->where($this->primaryKey, (int)$customer_id);
		$query = $this->db->get()->row_array();
		return $query['total'];

		//$query = $this->db->query("SELECT COUNT(*) AS total FROM " . DB_PREFIX . "address WHERE customer_id = '" . (int)$customer_id . "'");

		//return $query->row['total'];
	}

	public function getTotalAddressesByCountryId($country_id) {
		$this->db->select('COUNT(*) AS `total`');
		$this->db->from('address');
		$this->db->where('country_id', (int)$country_id);
		$query = $this->db->get()->row_array();
		return $query['total'];

		//$query = $this->db->query("SELECT COUNT(*) AS total FROM " . DB_PREFIX . "address WHERE country_id = '" . (int)$country_id . "'");

		//return $query->row['total'];
	}

	public function getTotalAddressesByZoneId($zone_id) {
		$this->db->select('COUNT(*) AS `total`');
		$this->db->from('address');
		$this->db->where('zone_id', (int)$zone_id);
		$query = $this->db->get()->row_array();
		return $query['total'];

		//$query = $this->db->query("SELECT COUNT(*) AS total FROM " . DB_PREFIX . "address WHERE zone_id = '" . (int)$zone_id . "'");

		//return $query->row['total'];
	}

	public function getTotalCustomersByCustomerGroupId($customer_group_id) {
		$this->db->select('COUNT(*) AS `total`');
		$this->db->from($this->table);
		$this->db->where('customer_group_id', (int)$customer_group_id);
		$query = $this->db->get()->row_array();
		return $query['total'];

		//$query = $this->db->query("SELECT COUNT(*) AS total FROM " . DB_PREFIX . "customer WHERE customer_group_id = '" . (int)$customer_group_id . "'");

		//return $query->row['total'];
	}

	public function addHistory($customer_id, $comment) {
		$this->db->set($this->primaryKey, (int)$customer_id);
		$this->db->set('comment', strip_tags($comment));
		$this->db->set('date_added', 'NOW()', FALSE);
		$this->db->insert('customer_history');

		$this->tracking->log(LOG_FUNCTION::$sale_customer, LOG_ACTION_MODIFY, $customer_id);

		//$this->db->query("INSERT INTO " . DB_PREFIX . "customer_history SET customer_id = '" . (int)$customer_id . "', comment = '" . $this->db->escape(strip_tags($comment)) . "', date_added = NOW()");
	}

	public function getHistories($customer_id, $start = 0, $limit = 10) {
		if ($start < 0) {
			$start = 0;
		}

		if ($limit < 1) {
			$limit = 10;
		}
		$this->db->select('comment, date_added');
		$this->db->from('customer_history');
		$this->db->where($this->primaryKey, (int)$customer_id);
		$this->db->order_by('date_added', 'DESC');
		$this->db->limit($limit, $start);


		//$query = $this->db->query("SELECT comment, date_added FROM " . DB_PREFIX . "customer_history WHERE customer_id = '" . (int)$customer_id . "' ORDER BY date_added DESC LIMIT " . (int)$start . "," . (int)$limit);

		//return $query->rows;
		return $this->db->get()->result_array();
	}

	public function getTotalHistories($customer_id) {
		$this->db->select('COUNT(*) AS `total`');
		$this->db->from('customer_history');
		$this->db->where($this->primaryKey, (int)$customer_id);
		$query = $this->db->get()->row_array();
		return $query['total'];

		//$query = $this->db->query("SELECT COUNT(*) AS total FROM " . DB_PREFIX . "customer_history WHERE customer_id = '" . (int)$customer_id . "'");

		//return $query->row['total'];
	}

	public function addTransaction($customer_id, $description = '', $amount = '', $order_id = 0) {
		$customer_info = $this->getCustomer($customer_id);

		if ($customer_info) {
			$this->db->set($this->primaryKey, (int)$customer_id);
			$this->db->set('order_id', (int)$order_id);
			$this->db->set('description', $description);
			$this->db->set('amount', (float)$amount);
			$this->db->set('date_added', 'NOW()', FALSE);
			$this->db->insert('customer_transaction');

			//$this->db->query("INSERT INTO " . DB_PREFIX . "customer_transaction SET customer_id = '" . (int)$customer_id . "', order_id = '" . (int)$order_id . "', description = '" . $this->db->escape($description) . "', amount = '" . (float)$amount . "', date_added = NOW()");

			$this->load->language('mail/customer');

			$this->load->model('setting/store');

			$store_info = $this->model_setting_store->getStore($customer_info['store_id']);

			if ($store_info) {
				$store_name = $store_info['name'];
			} else {
				$store_name = $this->config->get('config_name');
			}

			$message  = sprintf($this->language->get('text_transaction_received'), $this->currency->format($amount, $this->config->get('config_currency'))) . "\n\n";
			$message .= sprintf($this->language->get('text_transaction_total'), $this->currency->format($this->getTransactionTotal($customer_id)));

			$mail = new Mail($this->config->get('config_mail'));
			$mail->setTo($customer_info['email']);
			$mail->setFrom($this->config->get('config_email'));
			$mail->setSender($store_name);
			$mail->setSubject(sprintf($this->language->get('text_transaction_subject'), $this->config->get('config_name')));
			$mail->setText(html_entity_decode($message, ENT_QUOTES, 'UTF-8'));
			$mail->send();
		}

		$this->tracking->log(LOG_FUNCTION::$sale_customer, LOG_ACTION_MODIFY, $customer_id);
	}

	public function deleteTransaction($order_id) {
		$this->db->where('order_id', (int)$order_id);
		$this->db->delete('customer_transaction');

		//$this->db->query("DELETE FROM " . DB_PREFIX . "customer_transaction WHERE order_id = '" . (int)$order_id . "'");
	}

	public function getTransactions($customer_id, $start = 0, $limit = 10) {
		if ($start < 0) {
			$start = 0;
		}

		if ($limit < 1) {
			$limit = 10;
		}

		$this->db->select('*');
		$this->db->from('customer_transaction');
		$this->db->where($this->primaryKey, (int)$customer_id);
		$this->db->order_by('date_added', 'DESC');
		$this->db->limit($limit, $start);

		//$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "customer_transaction WHERE customer_id = '" . (int)$customer_id . "' ORDER BY date_added DESC LIMIT " . (int)$start . "," . (int)$limit);

		//return $query->rows;
		return $this->db->get()->result_array();
	}

	public function getTotalTransactions($customer_id) {
		$this->db->select('COUNT(*) AS `total`');
		$this->db->from('customer_transaction');
		$this->db->where($this->primaryKey, (int)$customer_id);
		$query = $this->db->get()->row_array();
		return $query['total'];


		//$query = $this->db->query("SELECT COUNT(*) AS total  FROM " . DB_PREFIX . "customer_transaction WHERE customer_id = '" . (int)$customer_id . "'");

		//return $query->row['total'];
	}

	public function getTransactionTotal($customer_id) {
		$this->db->select('SUM(amount) AS `total`');
		$this->db->from('customer_transaction');
		$this->db->where($this->primaryKey, (int)$customer_id);
		$query = $this->db->get()->row_array();
		return $query['total'];

		//$query = $this->db->query("SELECT SUM(amount) AS total FROM " . DB_PREFIX . "customer_transaction WHERE customer_id = '" . (int)$customer_id . "'");

		//return $query->row['total'];
	}

	public function getTotalTransactionsByOrderId($order_id) {
		$this->db->select('COUNT(*) AS `total`');
		$this->db->from('customer_transaction');
		$this->db->where('order_id', (int)$order_id);
		$query = $this->db->get()->row_array();
		return $query['total'];

		//$query = $this->db->query("SELECT COUNT(*) AS total FROM " . DB_PREFIX . "customer_transaction WHERE order_id = '" . (int)$order_id . "'");

		//return $query->row['total'];
	}

	public function addReward($customer_id, $description = '', $points = '', $order_id = 0) {
		$customer_info = $this->getCustomer($customer_id);

		if ($customer_info) {
			$this->db->set($this->primaryKey, (int)$customer_id);
			$this->db->set('order_id', (int)$order_id);
			$this->db->set('points', (int)$points);
			$this->db->set('description', $description);
			$this->db->set('date_added', 'NOW()', FALSE);
			$this->db->insert('customer_reward');
			//$this->db->query("INSERT INTO " . DB_PREFIX . "customer_reward SET customer_id = '" . (int)$customer_id . "', order_id = '" . (int)$order_id . "', points = '" . (int)$points . "', description = '" . $this->db->escape($description) . "', date_added = NOW()");

			$this->load->language('mail/customer');

			$this->load->model('setting/store');

			$store_info = $this->model_setting_store->getStore($customer_info['store_id']);

			if ($store_info) {
				$store_name = $store_info['name'];
			} else {
				$store_name = $this->config->get('config_name');
			}

			$message  = sprintf($this->language->get('text_reward_received'), $points) . "\n\n";
			$message .= sprintf($this->language->get('text_reward_total'), $this->getRewardTotal($customer_id));

			$mail = new Mail($this->config->get('config_mail'));
			$mail->setTo($customer_info['email']);
			$mail->setFrom($this->config->get('config_email'));
			$mail->setSender($store_name);
			$mail->setSubject(sprintf($this->language->get('text_reward_subject'), $store_name));
			$mail->setText(html_entity_decode($message, ENT_QUOTES, 'UTF-8'));
			$mail->send();
		}

		$this->tracking->log(LOG_FUNCTION::$sale_customer, LOG_ACTION_MODIFY, $customer_id);
	}

	public function deleteReward($order_id) {
		$this->db->where('order_id', (int)$order_id);
		$this->db->where('points >', 0);
		$this->db->delete('customer_reward');

		//$this->db->query("DELETE FROM " . DB_PREFIX . "customer_reward WHERE order_id = '" . (int)$order_id . "' AND points > 0");
	}

	public function getRewards($customer_id, $start = 0, $limit = 10) {
		$this->db->select('*');
		$this->db->from('customer_reward');
		$this->db->where($this->primaryKey, (int)$customer_id);
		$this->db->order_by('date_added', 'DESC');
		$this->db->limit((int)$limit, (int)$start);
		return $this->db->get()->result_array();

		//$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "customer_reward WHERE customer_id = '" . (int)$customer_id . "' ORDER BY date_added DESC LIMIT " . (int)$start . "," . (int)$limit);

		//return $query->rows;
	}

	public function getTotalRewards($customer_id) {
		$this->db->select('COUNT(*) AS `total`');
		$this->db->from('customer_reward');
		$this->db->where($this->primaryKey, (int)$customer_id);
		$query = $this->db->get()->row_array();
		return $query['total'];

		//$query = $this->db->query("SELECT COUNT(*) AS total FROM " . DB_PREFIX . "customer_reward WHERE customer_id = '" . (int)$customer_id . "'");

		return $query->row['total'];
	}

	public function getRewardTotal($customer_id) {
		$this->db->select('SUM(points) AS `total`');
		$this->db->from('customer_reward');
		$this->db->where($this->primaryKey, (int)$customer_id);
		$query = $this->db->get()->row_array();
		return $query['total'];

		//$query = $this->db->query("SELECT SUM(points) AS total FROM " . DB_PREFIX . "customer_reward WHERE customer_id = '" . (int)$customer_id . "'");

		//return $query->row['total'];
	}

	public function getTotalCustomerRewardsByOrderId($order_id) {
		$this->db->select('COUNT(*) AS `total`');
		$this->db->from('customer_reward');
		$this->db->where('order_id', (int)$order_id);
		$query = $this->db->get()->row_array();
		return $query['total'];

		//$query = $this->db->query("SELECT COUNT(*) AS total FROM " . DB_PREFIX . "customer_reward WHERE order_id = '" . (int)$order_id . "'");

		//return $query->row['total'];
	}

	public function getIps($customer_id) {
		return $this->db->select('*')->from('customer_ip')->where($this->primaryKey, (int)$customer_id)->get()->row_array();

		//$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "customer_ip WHERE customer_id = '" . (int)$customer_id . "'");

		//return $query->rows;
	}

	public function getTotalIps($customer_id) {
		$this->db->select('COUNT(*) AS `total`');
		$this->db->from('customer_ip');
		$this->db->where($this->primaryKey, (int)$customer_id);
		$query = $this->db->get()->row_array();
		return $query['total'];

		//$query = $this->db->query("SELECT COUNT(*) AS total FROM " . DB_PREFIX . "customer_ip WHERE customer_id = '" . (int)$customer_id . "'");

		//return $query->row['total'];
	}

	public function getTotalCustomersByIp($ip) {
		$this->db->select('COUNT(*) AS `total`');
		$this->db->from('customer_ip');
		$this->db->where('ip', $ip);
		$query = $this->db->get()->row_array();
		return $query['total'];

		//$query = $this->db->query("SELECT COUNT(*) AS total FROM " . DB_PREFIX . "customer_ip WHERE ip = '" . $this->db->escape($ip) . "'");

		//return $query->row['total'];
	}

	public function addBanIp($ip) {
		$this->db->set('ip', $ip);
		$this->db->insert('customer_ban_ip');

		//$this->db->query("INSERT INTO `" . DB_PREFIX . "customer_ban_ip` SET `ip` = '" . $this->db->escape($ip) . "'");
	}

	public function removeBanIp($ip) {
		$this->db->where('ip', $ip);
		$this->db->delete('customer_ban_ip');

		//$this->db->query("DELETE FROM `" . DB_PREFIX . "customer_ban_ip` WHERE `ip` = '" . $this->db->escape($ip) . "'");
	}

	public function getTotalBanIpsByIp($ip) {
		$this->db->select('COUNT(*) AS `total`');
		$this->db->from('customer_ban_ip');
		$this->db->where('ip', $ip);
		$query = $this->db->get()->row_array();
		return $query['total'];

		//$query = $this->db->query("SELECT COUNT(*) AS total FROM `" . DB_PREFIX . "customer_ban_ip` WHERE `ip` = '" . $this->db->escape($ip) . "'");

		//return $query->row['total'];
	}
	
	public function getTotalLoginAttempts($email) {
		$this->db->select('COUNT(*) AS `total`');
		$this->db->from('customer_login');
		$this->db->where('email', $email);
		$query = $this->db->get()->row_array();
		return $query['total'];

		//$query = $this->db->query("SELECT * FROM `" . DB_PREFIX . "customer_login` WHERE `email` = '" . $this->db->escape($email) . "'");

		//return $query->row;
	}	

	public function deleteLoginAttempts($email) {
		$this->db->where('email', $email);
		$this->db->delete('customer_login');

		//$this->db->query("DELETE FROM `" . DB_PREFIX . "customer_login` WHERE `email` = '" . $this->db->escape($email) . "'");
	}		
}