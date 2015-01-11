<?php
class User {
	private $user_id;
	private $username;
	private $permission = array();

	public function __construct($registry) {
		$this->db = $registry->get('db');
		$this->request = $registry->get('request');
		$this->session = $registry->get('session');

		if (isset($this->session->data['user_id'])) {
			$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "user WHERE user_id = '" . (int)$this->session->data['user_id'] . "' AND status = '1'");
			if ($query->num_rows()) {
				$row = $query->row_array();
				$this->user_id = $row['user_id'];
				$this->username = $row['username'];
				$this->user_group_id = $row['user_group_id'];

				$ip = $this->request->server['REMOTE_ADDR'];
				$this->db->query("UPDATE " . DB_PREFIX . "user SET ip = " . $this->db->escape($ip) . " WHERE user_id = '" . (int)$this->session->data['user_id'] . "'");

				$user_group_query = $this->db->query("SELECT permission FROM " . DB_PREFIX . "user_group WHERE user_group_id = '" . (int)$row['user_group_id'] . "'")->row_array();
				
				$permissions = unserialize($user_group_query['permission']);

				if (is_array($permissions)) {
					foreach ($permissions as $key => $value) {
						$this->permission[$key] = $value;
					}
				}
			} else {
				$this->logout();
			}
		}
	}

	public function login($username, $password) {
		$user_query = $this->db->query("SELECT * FROM " . DB_PREFIX . "user WHERE username = " . $this->db->escape($username) . " AND (password = SHA1(CONCAT(salt, SHA1(CONCAT(salt, SHA1(" . $this->db->escape($password) . "))))) OR password = " . $this->db->escape(md5($password)) . ") AND status = '1'");
		if ($user_query->num_rows()) {
			$user_query = $user_query->row_array();
			$this->session->data['user_id'] = $user_query['user_id'];

			$this->user_id = $user_query['user_id'];
			$this->username = $user_query['username'];
			$this->user_group_id = $user_query['user_group_id'];

			$user_group_query = $this->db->query("SELECT permission FROM " . DB_PREFIX . "user_group WHERE user_group_id = '" . (int)$user_query['user_group_id'] . "'")->row_array();

			$permissions = unserialize($user_group_query['permission']);

			if (is_array($permissions)) {
				foreach ($permissions as $key => $value) {
					$this->permission[$key] = $value;
				}
			}

			return true;
		} else {
			return false;
		}
	}

	public function logout() {
		unset($this->session->data['user_id']);

		$this->user_id = '';
		$this->username = '';
	}

	public function hasPermission($key, $value) {
		if (isset($this->permission[$key])) {
			return in_array($value, $this->permission[$key]);
		} else {
			return false;
		}
	}

	public function isLogged() {
		return $this->user_id;
	}

	public function getId() {
		return $this->user_id;
	}

	public function getUserName() {
		return $this->username;
	}
	
	public function getGroupId() {
		return $this->user_group_id;
	}	
}