<?php
class ModelUserUser extends Model {
	public $table = 'user';
	public $primaryKey = 'user_id';
	public $fields = array('user_id', 'user_group_id', 'username', 'password', 'salt', 'firstname', 'lastname', 'email', 'image', 'code', 'ip', 'status', 'date_added');

	public function addUser($data) {
		$tmp = $this->initData($data, TRUE);
		$tmp['salt'] = ($salt = substr(md5(uniqid(rand(), true)), 0, 9));
		$tmp['password'] = (sha1($salt . sha1($salt . sha1($data['password']))));
		$this->db->set('date_added', 'NOW()', FALSE);
		$this->db->insert($this->table, $tmp);

		//$this->db->query("INSERT INTO `" . DB_PREFIX . "user` SET username = '" . ($data['username']) . "', user_group_id = '" . (int)$data['user_group_id'] . "', salt = '" . ($salt = substr(md5(uniqid(rand(), true)), 0, 9)) . "', password = '" . (sha1($salt . sha1($salt . sha1($data['password'])))) . "', firstname = '" . ($data['firstname']) . "', lastname = '" . ($data['lastname']) . "', email = '" . ($data['email']) . "', image = '" . ($data['image']) . "', status = '" . (int)$data['status'] . "', date_added = NOW()");
	}

	public function editUser($user_id, $data) {
		$tmp = $this->initData($data);
		$this->db->where($this->primaryKey, (int)$user_id);
		$this->db->update($this->table, $tmp);

		//$this->db->query("UPDATE `" . DB_PREFIX . "user` SET username = '" . ($data['username']) . "', user_group_id = '" . (int)$data['user_group_id'] . "', firstname = '" . ($data['firstname']) . "', lastname = '" . ($data['lastname']) . "', email = '" . ($data['email']) . "', image = '" . ($data['image']) . "', status = '" . (int)$data['status'] . "' WHERE user_id = '" . (int)$user_id . "'");

		if ($data['password']) {
			$this->db->set('salt', ($salt = substr(md5(uniqid(rand(), true)), 0, 9)));
			$this->db->set('password', (sha1($salt . sha1($salt . sha1($data['password'])))));
			$this->db->where($this->primaryKey, (int)$user_id);
			$this->db->update($this->table);

			//$this->db->query("UPDATE `" . DB_PREFIX . "user` SET salt = '" . ($salt = substr(md5(uniqid(rand(), true)), 0, 9)) . "', password = '" . (sha1($salt . sha1($salt . sha1($data['password'])))) . "' WHERE user_id = '" . (int)$user_id . "'");
		}
	}

	public function editPassword($user_id, $password) {
		$this->db->set('salt', ($salt = substr(md5(uniqid(rand(), true)), 0, 9)));
		$this->db->set('password', (sha1($salt . sha1($salt . sha1($password)))));
		$this->db->where($this->primaryKey, (int)$user_id);
		$this->db->update($this->table);
		//$this->db->query("UPDATE `" . DB_PREFIX . "user` SET salt = '" . ($salt = substr(md5(uniqid(rand(), true)), 0, 9)) . "', password = '" . (sha1($salt . sha1($salt . sha1($password)))) . "', code = '' WHERE user_id = '" . (int)$user_id . "'");
	}

	public function editCode($email, $code) {
		$this->db->where('email', (utf8_strtolower($email)));
		$this->db->set('code', ($code));
		$this->db->update($this->table);
		//$this->db->query("UPDATE `" . DB_PREFIX . "user` SET code = '" . ($code) . "' WHERE LCASE(email) = '" . (utf8_strtolower($email)) . "'");
	}

	public function deleteUser($user_id) {
		$this->db->where($this->primaryKey, (int)$user_id);
		$this->db->delete($this->table);
		//$this->db->query("DELETE FROM `" . DB_PREFIX . "user` WHERE user_id = '" . (int)$user_id . "'");
	}

	public function getUser($user_id) {
		$query = $this->db->query("SELECT *, (SELECT ug.name FROM `" . DB_PREFIX . "user_group` ug WHERE ug.user_group_id = u.user_group_id) AS user_group FROM `" . DB_PREFIX . "user` u WHERE u.user_id = '" . (int)$user_id . "'");

		//return $query->row;
		return $query->row_array();
	}

	public function getUserByUsername($username) {
		$this->db->where('username', ($username));
		return $this->db->get($this->table)->row_array();

		//$query = $this->db->query("SELECT * FROM `" . DB_PREFIX . "user` WHERE username = '" . ($username) . "'");

		//return $query->row;
	}

	public function getUserByCode($code) {
		$this->db->where('code', ($code));
		$this->db->where('code !=', '');
		return $this->db->get($this->table)->row_array();

		//$query = $this->db->query("SELECT * FROM `" . DB_PREFIX . "user` WHERE code = '" . ($code) . "' AND code != ''");

		//return $query->row;
	}

	public function getUsers($data = array()) {
		$this->db->select('*');
		$this->db->from($this->table);

		//$sql = "SELECT * FROM `" . DB_PREFIX . "user`";

		$sort_data = array(
			'username',
			'status',
			'date_added'
		);

		$order = 'ASC';
		if (isset($data['order']) && ($data['order'] == 'DESC')) {
			$order = 'DESC';
		}
		if (isset($data['sort']) && in_array($data['sort'], $sort_data)) {
			//$sql .= " ORDER BY " . $data['sort'];
			$this->db->order_by($data['sort'], $order);
		} else {
			//$sql .= " ORDER BY username";
			$this->db->order_by('username', $order);
		}

		/*if (isset($data['order']) && ($data['order'] == 'DESC')) {
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

	public function getTotalUsers() {
		$this->db->select('COUNT(*) AS `total`');
		$query = $this->db->get($this->table)->row_array();
		return $query['total'];

		//$query = $this->db->query("SELECT COUNT(*) AS total FROM `" . DB_PREFIX . "user`");

		//return $query->row['total'];
	}

	public function getTotalUsersByGroupId($user_group_id) {
		$this->db->select('COUNT(*) AS `total`');
		$this->db->where('user_group_id', (int)$user_group_id);
		$query = $this->db->get($this->table)->row_array();
		return $query['total'];
		//$query = $this->db->query("SELECT COUNT(*) AS total FROM `" . DB_PREFIX . "user` WHERE user_group_id = '" . (int)$user_group_id . "'");

		//return $query->row['total'];
	}

	public function getTotalUsersByEmail($email) {
		$this->db->select('COUNT(*) AS `total`');
		$this->db->where('email', (utf8_strtolower($email)));
		$query = $this->db->get($this->table)->row_array();
		return $query['total'];

		//$query = $this->db->query("SELECT COUNT(*) AS total FROM `" . DB_PREFIX . "user` WHERE LCASE(email) = '" . (utf8_strtolower($email)) . "'");

		//return $query->row['total'];
	}
}