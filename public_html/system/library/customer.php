<?php
class Customer {
	private $customer_id;
	private $firstname;
	private $lastname;
	private $email;
	private $telephone;
	private $fax;
	private $newsletter;
	private $customer_group_id;
	private $address_id;

	public function __construct($registry)
	{
		$this->config = $registry->get('config');
		$this->db = $registry->get('db');
		$this->request = $registry->get('request');
		$this->session = $registry->get('session');

		if (isset($this->session->data['customer_id'])) {
			$customer = $this->db->select('*')
				->from('customer')
				->where('customer_id =' . (int)$this->session->data['customer_id'])
				->where('status = 1');
			$customer_query = (object) array(
				'row' => $this->db->get()->row_array(),
				'num_rows' => ($customer) ? 1 : 0
			);

			// @todo: should be removed
			//$customer_query = $this->db->query("SELECT * FROM " . DB_PREFIX . "customer WHERE customer_id = '"
			//	. (int)$this->session->data['customer_id'] . "' AND status = '1'");

			if ($customer_query->num_rows) {
				$this->customer_id = $customer_query->row['customer_id'];
				$this->firstname = $customer_query->row['firstname'];
				$this->lastname = $customer_query->row['lastname'];
				$this->email = $customer_query->row['email'];
				$this->telephone = $customer_query->row['telephone'];
				$this->fax = $customer_query->row['fax'];
				$this->newsletter = $customer_query->row['newsletter'];
				$this->customer_group_id = $customer_query->row['customer_group_id'];
				$this->address_id = $customer_query->row['address_id'];

				$this->db->query("UPDATE " . DB_PREFIX . "customer SET cart = " .
					$this->db->escape(isset($this->session->data['cart']) ? serialize($this->session->data['cart']) : '') .
					", wishlist = " . $this->db->escape(isset($this->session->data['wishlist']) ? serialize($this->session->data['wishlist']) : '') .
					", ip = " . $this->db->escape($this->request->server['REMOTE_ADDR']) . " WHERE customer_id = " . (int)$this->customer_id);

				$query = $this->db->select('*')
					->from('customer_ip')
					->where('customer_id = ', (int)$this->session->data['customer_id'])
					->where('ip = ', $this->db->escape($this->request->server['REMOTE_ADDR']))
					->get()
					->row_array();
				if (!$query) {
					$this->db->query("INSERT INTO " . DB_PREFIX . "customer_ip SET customer_id = '" . (int)$this->session->data['customer_id'] .
						"', ip = " . $this->db->escape($this->request->server['REMOTE_ADDR']) . ", date_added = NOW()");
				}
			} else {
				$this->logout();
			}
		}
	}

	public function login($email, $password, $override = false)
	{
		if ($override) {
			$this->db->select('*')
				->from('customer')
				->where('LOWER(email) = ' . $this->db->escape(utf8_strtolower($email)), NULL, FALSE)
				->where('status', 1);

			//$customer_query = $this->db->query("SELECT * FROM " . DB_PREFIX . "customer WHERE LOWER(email) = " .
			//	$this->db->escape(utf8_strtolower($email)) . " AND status = '1'");
		} else {
			$this->db->select('*')
				->from('customer')
				->where('LOWER(email) = ' . $this->db->escape(utf8_strtolower($email)))
				->where('(password = SHA1(CONCAT(salt, SHA1(CONCAT(salt, SHA1(' .
					$this->db->escape($password) . '))))) OR password = ' . $this->db->escape(md5($password)) . ')', NULL, FALSE)
				->where('status', 1)
				->where('approved', 1);
			$customer = $this->db->get()->row_array();
		}

		if (isset($customer['customer_id'])) {
			$this->session->data['customer_id'] = $customer['customer_id'];

			if ($customer['cart'] && is_string($customer['cart'])) {
				$cart = unserialize($customer['cart']);

				foreach ($cart as $key => $value) {
					if (!array_key_exists($key, $this->session->data['cart'])) {
						$this->session->data['cart'][$key] = $value;
					} else {
						$this->session->data['cart'][$key] += $value;
					}
				}
			}

			if ($customer['wishlist'] && is_string($customer['wishlist'])) {
				if (!isset($this->session->data['wishlist'])) {
					$this->session->data['wishlist'] = array();
				}

				$wishlist = unserialize($customer['wishlist']);

				foreach ($wishlist as $product_id) {
					if (!in_array($product_id, $this->session->data['wishlist'])) {
						$this->session->data['wishlist'][] = $product_id;
					}
				}
			}

			$this->customer_id = $customer['customer_id'];
			$this->firstname = $customer['firstname'];
			$this->lastname = $customer['lastname'];
			$this->email = $customer['email'];
			$this->telephone = $customer['telephone'];
			$this->fax = $customer['fax'];
			$this->newsletter = $customer['newsletter'];
			$this->customer_group_id = $customer['customer_group_id'];
			$this->address_id = $customer['address_id'];

			$this->db->set('ip', $this->request->server['REMOTE_ADDR']);
			$this->db->where('customer_id', (int)$this->customer_id);
			$this->db->update('customer');
			//$this->db->query("UPDATE " . DB_PREFIX . "customer SET ip = " .
			//	$this->db->escape($this->request->server['REMOTE_ADDR']) .
			//	" WHERE customer_id = '" . (int) $this->customer_id . "'");

			return true;
		} else {
			return false;
		}
	}

	public function logout() {
		$this->db->query("UPDATE " . DB_PREFIX . "customer SET cart = '" .
			$this->db->escape(isset($this->session->data['cart']) ? serialize($this->session->data['cart']) : '') . "',
			wishlist = '" . $this->db->escape(isset($this->session->data['wishlist']) ? serialize($this->session->data['wishlist']) : '') . "'
			WHERE customer_id = '" . (int)$this->customer_id . "'");

		unset($this->session->data['customer_id']);

		$this->customer_id = '';
		$this->firstname = '';
		$this->lastname = '';
		$this->email = '';
		$this->telephone = '';
		$this->fax = '';
		$this->newsletter = '';
		$this->customer_group_id = '';
		$this->address_id = '';
	}

	public function isLogged() {
		return $this->customer_id;
	}

	public function getId() {
		return $this->customer_id;
	}

	public function getFirstName() {
		return $this->firstname;
	}

	public function getLastName() {
		return $this->lastname;
	}

	public function getEmail() {
		return $this->email;
	}

	public function getTelephone() {
		return $this->telephone;
	}

	public function getFax() {
		return $this->fax;
	}

	public function getNewsletter() {
		return $this->newsletter;
	}

	public function getGroupId() {
		return $this->customer_group_id;
	}

	public function getAddressId() {
		return $this->address_id;
	}

	/**
	 * @modified SUN
	 * @return mixed
	 */
	public function getBalance()
	{
		$query = $this->db->query("SELECT SUM(amount) AS total FROM " . DB_PREFIX . "customer_transaction
				WHERE customer_id = '" . (int)$this->customer_id . "'")
			->row_array();
		return $query['total'];
	}

	public function getRewardPoints()
	{
		$row = $this->db->query("SELECT SUM(points) AS total FROM " . DB_PREFIX . "customer_reward WHERE customer_id = '" . (int)$this->customer_id . "'");

		return $row['total'];
	}
}