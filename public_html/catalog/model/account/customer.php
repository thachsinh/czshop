<?php
class ModelAccountCustomer extends Model
{

	protected $table = 'address';
	protected $primaryKey = 'address_id';
	protected $fields = array('address_id', 'firstname', 'lastname', 'company', 'address_1', 'address_2', 'city', 'postcode',	'country_id', 'zone_id');

	public function addCustomer($data)
	{
		$this->event->trigger('pre.customer.add', $data);

		if (isset($data['customer_group_id']) && is_array($this->config->get('config_customer_group_display')) && in_array($data['customer_group_id'], $this->config->get('config_customer_group_display'))) {
			$customer_group_id = $data['customer_group_id'];
		} else {
			$customer_group_id = $this->config->get('config_customer_group_id');
		}

		// @todo: should be removed
		$customer_group_id = 3;

		$this->load->model('account/customer_group');

		$customer_group_info = $this->model_account_customer_group->getCustomerGroup($customer_group_id);

		$this->db->query("INSERT INTO " . DB_PREFIX . "customer SET customer_group_id = '" . (int)$customer_group_id . "', store_id = '" . (int)$this->config->get('config_store_id') . "', firstname = " . $this->db->escape($data['firstname']) . ", lastname = " . $this->db->escape($data['lastname']) . ", email = " . $this->db->escape($data['email']) . ", telephone = " . $this->db->escape($data['telephone']) . ", fax = " . $this->db->escape($data['fax']) . ", custom_field = " . $this->db->escape(isset($data['custom_field']['account']) ? serialize($data['custom_field']['account']) : '') . ", salt = " . $this->db->escape($salt = substr(md5(uniqid(rand(), true)), 0, 9)) . ", password = " . $this->db->escape(sha1($salt . sha1($salt . sha1($data['password'])))) . ", newsletter = '" . (isset($data['newsletter']) ? (int)$data['newsletter'] : 0) . "', ip = " . $this->db->escape($this->request->server['REMOTE_ADDR']) . ", status = '1', approved = '" . (int)!$customer_group_info['approval'] . "', date_added = NOW()");

		$customer_id = $this->db->insert_id();

		$this->db->query("INSERT INTO " . DB_PREFIX . "address SET customer_id = '" . (int)$customer_id . "', firstname = " . $this->db->escape($data['firstname']) . ", lastname = " . $this->db->escape($data['lastname']) . ", company = " . $this->db->escape($data['company']) . ", address_1 = " . $this->db->escape($data['address_1']) . ", address_2 = " . $this->db->escape($data['address_2']) . ", city = " . $this->db->escape($data['city']) . ", postcode = " . $this->db->escape($data['postcode']) . ", country_id = " . (int)$data['country_id'] . ", zone_id = " . (int)$data['zone_id'] . ", custom_field = " . $this->db->escape(isset($data['custom_field']['address']) ? serialize($data['custom_field']['address']) : ''));

		$address_id = $this->db->insert_id();

		$this->db->query("UPDATE " . DB_PREFIX . "customer SET address_id = '" . (int)$address_id . "' WHERE customer_id = '" . (int)$customer_id . "'");

		$this->load->language('mail/customer');

		$subject = sprintf($this->language->get('text_subject'), $this->config->get('config_name'));

		$message = sprintf($this->language->get('text_welcome'), $this->config->get('config_name')) . "\n\n";

		if (!$customer_group_info['approval']) {
			$message .= $this->language->get('text_login') . "\n";
		} else {
			$message .= $this->language->get('text_approval') . "\n";
		}

		$message .= $this->url->link('account/login', '', 'SSL') . "\n\n";
		$message .= $this->language->get('text_services') . "\n\n";
		$message .= $this->language->get('text_thanks') . "\n";
		$message .= $this->config->get('config_name');

		$mail = new Mail($this->config->get('config_mail'));
		$mail->setTo($data['email']);
		$mail->setFrom($this->config->get('config_email'));
		$mail->setSender($this->config->get('config_name'));
		$mail->setSubject($subject);
		$mail->setText(html_entity_decode($message, ENT_QUOTES, 'UTF-8'));
		$mail->send();

		// Send to main admin email if new account email is enabled
		if ($this->config->get('config_account_mail')) {
			$message  = $this->language->get('text_signup') . "\n\n";
			$message .= $this->language->get('text_website') . ' ' . $this->config->get('config_name') . "\n";
			$message .= $this->language->get('text_firstname') . ' ' . $data['firstname'] . "\n";
			$message .= $this->language->get('text_lastname') . ' ' . $data['lastname'] . "\n";
			$message .= $this->language->get('text_customer_group') . ' ' . $customer_group_info['name'] . "\n";
			$message .= $this->language->get('text_email') . ' '  .  $data['email'] . "\n";
			$message .= $this->language->get('text_telephone') . ' ' . $data['telephone'] . "\n";

			$mail->setTo($this->config->get('config_email'));
			$mail->setSubject(html_entity_decode($this->language->get('text_new_customer'), ENT_QUOTES, 'UTF-8'));
			$mail->setText(html_entity_decode($message, ENT_QUOTES, 'UTF-8'));
			$mail->send();

			// Send to additional alert emails if new account email is enabled
			$emails = explode(',', $this->config->get('config_mail_alert'));

			foreach ($emails as $email) {
				if (utf8_strlen($email) > 0 && preg_match('/^[^\@]+@.*.[a-z]{2,15}$/i', $email)) {
					$mail->setTo($email);
					$mail->send();
				}
			}
		}

		$this->event->trigger('post.customer.add', $customer_id);

		return $customer_id;
	}

	public function editCustomer($data)
	{
		$this->event->trigger('pre.customer.edit', $data);

		$customer_id = $this->customer->getId();
		$this->db->where('customer_id', (int) $customer_id);
		$this->db->update('customer', $data);

		$this->event->trigger('post.customer.edit', $customer_id);
	}

	public function editPassword($email, $password) {
		$this->event->trigger('pre.customer.edit.password');

		$this->db->query("UPDATE " . DB_PREFIX . "customer SET salt = " . $this->db->escape($salt = substr(md5(uniqid(rand(), true)), 0, 9)) . ", password = " . $this->db->escape(sha1($salt . sha1($salt . sha1($password)))) . " WHERE LOWER(email) = " . $this->db->escape(utf8_strtolower($email)));

		$this->event->trigger('post.customer.edit.password');
	}

	public function editNewsletter($newsletter) {
		$this->event->trigger('pre.customer.edit.newsletter');

		$this->db->query("UPDATE " . DB_PREFIX . "customer SET newsletter = '" . (int)$newsletter . "' WHERE customer_id = '" . (int)$this->customer->getId() . "'");

		$this->event->trigger('post.customer.edit.newsletter');
	}

	/**
	 * Get a Customer by ID
	 * @author SUN
	 * @param $customer_id
	 * @return mixed
	 */
	public function getCustomer($customer_id)
	{
		$customer = $this->db->select('*')
			->from('customer')
			->where('customer_id = ' . (int) $customer_id)
			->get()->row_array();

		return $customer;
	}

	/**
	 * Get a Customer by email
	 * @param $email
	 * @return mixed
	 */
	public function getCustomerByEmail($email)
	{
		$customer = $this->db->select('*')
			->from('customer')
			->where('LOWER(email) = ' . $this->db->escape(utf8_strtolower($email)))
			->get()
			->row_array();

		return $customer;
	}

	public function getCustomerByToken($token) {
		$this->db->select('*')
			->from('customer')
			->where('token', $token)
			->where('token <>', '');
		$data = $this->db->get()->row_array();

		// Reset token
		if(isset($data)) {
			$this->db->where('customer_id', (int)$data['customer_id']);
			$this->db->update('customer');
		}

		return $data;

		/*$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "customer WHERE token = " . $this->db->escape($token) . " AND token != ''");

		$this->db->query("UPDATE " . DB_PREFIX . "customer SET token = ''");

		return $query->row;*/
	}

	public function getTotalCustomersByEmail($email)
	{
		$row = $this->db->select('COUNT(*) AS total')
			->from('customer')
			->where('LOWER(email) = ' . $this->db->escape(utf8_strtolower($email)))
			->get()
			->row_array();

		if (!$row) return 0;
		return $row['total'];
	}

	public function getIps($customer_id) {
		$this->db->select('*')
			->from('customer_ip')
			->where('customer_id', (int)$customer_id);
		return $this->db->get()->result_array();

		/*$query = $this->db->query("SELECT * FROM `" . DB_PREFIX . "customer_ip` WHERE customer_id = '" . (int)$customer_id . "'");

		return $query->rows;*/
	}

	public function isBanIp($ip) {
		$this->db->select('COUNT(*) AS total')
			->from('customer_ban_ip')
			->where('ip', $ip);

		$data = $this->db->get()->row_array();
		return $data['total'];

		/*
		$query = $this->db->query("SELECT * FROM `" . DB_PREFIX . "customer_ban_ip` WHERE ip = " . $this->db->escape($ip));

		return $query->num_rows;*/
	}
	
	public function addLoginAttempt($email) {
		$this->db->select('*')
			->from('customer_login')
			->where('email', utf8_strtolower($email))
			->where('ip', $this->request->server['REMOTE_ADDR']);
		$data = $this->db->get()->row_array();

		/*$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "customer_login WHERE email = " . $this->db->escape(utf8_strtolower((string)$email))
			. " AND ip = " . $this->db->escape($this->request->server['REMOTE_ADDR']));*/
		
		if (!isset($data['customer_login_id'])) {
			$this->db->set('email', utf8_strtolower((string)$email));
			$this->db->set('ip', $this->request->server['REMOTE_ADDR']);
			$this->db->set('total', 1);
			$this->db->set('date_added', date('Y-m-d H:i:s'));
			$this->db->set('date_modified', date('Y-m-d H:i:s'));
			$this->db->update('customer_login');

			/*$this->db->query("INSERT INTO " . DB_PREFIX . "customer_login SET email = " . $this->db->escape(utf8_strtolower((string)$email))
				. ", ip = " . $this->db->escape($this->request->server['REMOTE_ADDR']) . ", total = 1, date_added = " . $this->db->escape(date('Y-m-d H:i:s'))
				. ", date_modified = " . $this->db->escape(date('Y-m-d H:i:s')));*/
		} else {
			$this->db->set('total', '(total + 1)', FALSE);
			$this->db->set('date_modified', date('Y-m-d H:i:s'));
			$this->db->where('customer_login_id', (int)$data['customer_login_id']);
			$this->db->update('customer_login');

			/*$this->db->query("UPDATE " . DB_PREFIX . "customer_login SET total = (total + 1), date_modified = " . $this->db->escape(date('Y-m-d H:i:s'))
				. " WHERE customer_login_id = '" . (int)$query->row['customer_login_id'] . "'");*/
		}			
	}	
	
	public function getLoginAttempts($email) {
		$this->db->select('*')
			->from('customer_login')
			->where('email', utf8_strtolower($email));

		return $this->db->get()->row_array();
		/*
		$query = $this->db->query("SELECT * FROM `" . DB_PREFIX . "customer_login` WHERE email = " . $this->db->escape(utf8_strtolower($email)));

		return $query->row;*/
	}
	
	public function deleteLoginAttempts($email) {
		$this->db->where('email', utf8_strtolower($email));
		$this->db->delete('customer_login');

		//$this->db->query("DELETE FROM `" . DB_PREFIX . "customer_login` WHERE email = " . $this->db->escape(utf8_strtolower($email)));
	}

	public function addCustomerAjax($data) {
		$tmp = $this->initData($data, FALSE, array('email', 'password'));
		$tmp['salt'] = substr(md5(uniqid(rand(), true)), 0, 9);
		$tmp['password'] = sha1($salt . sha1($salt . sha1($data['password'])));
		
		$this->db->set('date_added', 'NOW()', FALSE);
		$this->db->insert('customer', $tmp);
	}

	public function editCustomerAjax($data)
	{
		$tmp = $this->initData($data, FALSE, array('firstname', 'lastname','email', 'telephone'));
		$this->event->trigger('pre.customer.edit', $tmp);

		$customer_id = $this->customer->getId();
		$this->db->where('customer_id', (int) $customer_id);
		$this->db->update('customer', $tmp);

		$this->event->trigger('post.customer.edit', $customer_id);
	}
}