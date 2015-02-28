<?php
class ModelUserUserGroup extends Model {
	public $table = 'user_group';
	public $primaryKey = 'user_group_id';
	public $fields = array('user_group_id', 'name', 'permission');

	public function addUserGroup($data) {
		$tmp = $this->initData($data, TRUE);
		if(isset($data['permission'])) {
			$tmp['permission'] = serialize($data['permission']);
		}
		$this->db->insert($this->table, $tmp);
		$user_group_id = $this->db->insert_id();

		$this->tracking->log(LOG_FUNCTION::$user_group, LOG_ACTION_ADD, $user_group_id);
		//$this->db->query("INSERT INTO " . DB_PREFIX . "user_group SET name = '" . ($data['name']) . "', permission = '" . (isset($data['permission']) ? (serialize($data['permission'])) : '') . "'");
	}

	public function editUserGroup($user_group_id, $data) {
		$tmp = $this->initData($data, TRUE);
		if(isset($data['permission'])) {
			$tmp['permission'] = (serialize($data['permission']));
		}
		$this->db->where($this->primaryKey, (int)$user_group_id);
		$this->db->update($this->table, $tmp);

		$this->tracking->log(LOG_FUNCTION::$user_group, LOG_ACTION_MODIFY, $user_group_id);

		/*$this->db->where($this->primaryKey, (int)$user_group_id);
		$this->db->query("UPDATE " . DB_PREFIX . "user_group SET name = '" . ($data['name']) . "', permission = '" . (isset($data['permission']) ? (serialize($data['permission'])) : '') . "' WHERE user_group_id = '" . (int)$user_group_id . "'");*/
	}

	public function deleteUserGroup($user_group_id) {
		$this->db->where($this->primaryKey, (int)$user_group_id);
		$this->db->delete($this->table);

		$this->tracking->log(LOG_FUNCTION::$user_group, LOG_ACTION_DELETE, $user_group_id);
		//$this->db->query("DELETE FROM " . DB_PREFIX . "user_group WHERE user_group_id = '" . (int)$user_group_id . "'");
	}

	public function getUserGroup($user_group_id) {
		$this->db->distinct();
		$this->db->from($this->table);
		$this->db->where($this->primaryKey, (int)$user_group_id);
		$query = $this->db->get()->row_array();
		//$query = $this->db->query("SELECT DISTINCT * FROM " . DB_PREFIX . "user_group WHERE user_group_id = '" . (int)$user_group_id . "'");

		$user_group = array(
			'name'       => $query['name'],
			'permission' => unserialize($query['permission'])
		);

		return $user_group;
	}

	public function getUserGroups($data = array()) {
		$this->db->select('*');
		$this->db->from($this->table);

		//$sql = "SELECT * FROM " . DB_PREFIX . "user_group";

		//$sql .= " ORDER BY name";
		$order = 'ASC';
		if (isset($data['order']) && ($data['order'] == 'DESC')) {
			//$sql .= " DESC";
			$order = 'DESC';
		}

		$this->db->order_by('name', $order);

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

		//return $query->rows;

		return $this->db->get()->result_array();
	}

	public function getTotalUserGroups() {
		$this->db->select('COUNT(*) AS `total`');
		$query = $this->db->get($this->table)->row_array();
		return $query['total'];
		//$query = $this->db->query("SELECT COUNT(*) AS total FROM " . DB_PREFIX . "user_group");

		//return $query->row['total'];
	}

	public function addPermission($user_group_id, $type, $route) {
		$this->db->distinct();
		$this->db->from($this->table);
		$this->db->where($this->primaryKey, (int)$user_group_id);

		//$user_group_query = $this->db->query("SELECT DISTINCT * FROM " . DB_PREFIX . "user_group WHERE user_group_id = '" . (int)$user_group_id . "'");
		$user_group_query = $this->db->get()->row_array();

		if (is_array($user_group_query)) {
			$data = unserialize($user_group_query['permission']);

			$data[$type][] = $route;
			$this->db->set('permission', (serialize($data)));
			$this->db->where($this->primaryKey, (int)$user_group_id);
			$this->db->update($this->table);

			$this->tracking->log(LOG_FUNCTION::$user_group, LOG_ACTION_MODIFY, $user_group_id);
			//$this->db->query("UPDATE " . DB_PREFIX . "user_group SET permission = '" . (serialize($data)) . "' WHERE user_group_id = '" . (int)$user_group_id . "'");
		}
	}

	public function removePermission($user_group_id, $type, $route) {
		$this->db->distinct();
		$this->db->from($this->table);
		$this->db->where($this->primaryKey, (int)$user_group_id);
		//$user_group_query = $this->db->query("SELECT DISTINCT * FROM " . DB_PREFIX . "user_group WHERE user_group_id = '" . (int)$user_group_id . "'");
		$user_group_query = $this->db->get()->row_array();

		if (is_array($user_group_query)) {
			$data = unserialize($user_group_query['permission']);

			$data[$type] = array_diff($data[$type], array($route));
			$this->db->set('permission', (serialize($data)));
			$this->db->where($this->primaryKey, (int)$user_group_id);
			$this->db->update($this->table);

			$this->tracking->log(LOG_FUNCTION::$user_group, LOG_ACTION_MODIFY, $user_group_id);
			//$this->db->query("UPDATE " . DB_PREFIX . "user_group SET permission = '" . (serialize($data)) . "' WHERE user_group_id = '" . (int)$user_group_id . "'");
		}
	}
}