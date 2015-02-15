<?php
class ModelMarketingCoupon extends Model {
	public $table = '';
	public $primaryKey = '';
	public $fields = array();
	public $product_table = '';
	public $category_table = '';
	public $history_table = '';

	public function addCoupon($data) {
		$this->event->trigger('pre.admin.coupon.add', $data);
		$tmp = $this->initData($data, TRUE);
		$this->db->set('discount', (float)$data['discount']);
		$this->db->set('total', (float)$data['total']);
		$this->db->set('date_added', 'NOW()', FALSE);
		$this->db->insert($this->table, $tmp);


		//$this->db->query("INSERT INTO " . DB_PREFIX . "coupon SET name = '" . $this->db->escape($data['name']) . "', code = '" . $this->db->escape($data['code']) . "', discount = '" . (float)$data['discount'] . "', type = '" . $this->db->escape($data['type']) . "', total = '" . (float)$data['total'] . "', logged = '" . (int)$data['logged'] . "', shipping = '" . (int)$data['shipping'] . "', date_start = '" . $this->db->escape($data['date_start']) . "', date_end = '" . $this->db->escape($data['date_end']) . "', uses_total = '" . (int)$data['uses_total'] . "', uses_customer = '" . (int)$data['uses_customer'] . "', status = '" . (int)$data['status'] . "', date_added = NOW()");

		$coupon_id = $this->db->insert_id();

		if (isset($data['coupon_product'])) {
			foreach ($data['coupon_product'] as $product_id) {
				$this->db->set($this->primaryKey, (int)$coupon_id);
				$this->db->set('product_id', (int)$product_id);
				$this->db->insert($this->product_table);

				//$this->db->query("INSERT INTO " . DB_PREFIX . "coupon_product SET coupon_id = '" . (int)$coupon_id . "', product_id = '" . (int)$product_id . "'");
			}
		}

		if (isset($data['coupon_category'])) {
			foreach ($data['coupon_category'] as $category_id) {
				$this->db->set($this->primaryKey, (int)$coupon_id);
				$this->db->set('category_id', (int)$category_id);
				$this->db->insert($this->category_table);

				//$this->db->query("INSERT INTO " . DB_PREFIX . "coupon_category SET coupon_id = '" . (int)$coupon_id . "', category_id = '" . (int)$category_id . "'");
			}
		}

		$this->event->trigger('post.admin.coupon.add', $coupon_id);

		return $coupon_id;
	}

	public function editCoupon($coupon_id, $data) {
		$this->event->trigger('pre.admin.coupon.edit', $data);

		$tmp = $this->initData($data, TRUE);
		$this->db->set('discount', (float)$data['discount']);
		$this->db->set('total', (float)$data['total']);
		$this->db->set('date_added', 'NOW()', FALSE);
		$this->db->where($this->primaryKey, (int)$coupon_id);
		$this->db->insert($this->table, $tmp);

		//$this->db->query("UPDATE " . DB_PREFIX . "coupon SET name = '" . $this->db->escape($data['name']) . "', code = '" . $this->db->escape($data['code']) . "', discount = '" . (float)$data['discount'] . "', type = '" . $this->db->escape($data['type']) . "', total = '" . (float)$data['total'] . "', logged = '" . (int)$data['logged'] . "', shipping = '" . (int)$data['shipping'] . "', date_start = '" . $this->db->escape($data['date_start']) . "', date_end = '" . $this->db->escape($data['date_end']) . "', uses_total = '" . (int)$data['uses_total'] . "', uses_customer = '" . (int)$data['uses_customer'] . "', status = '" . (int)$data['status'] . "' WHERE coupon_id = '" . (int)$coupon_id . "'");

		$this->db->where($this->primaryKey, (int)$coupon_id);
		$this->db->delete(array($this->table, $this->product_table, $this->category_table));

		//$this->db->query("DELETE FROM " . DB_PREFIX . "coupon_product WHERE coupon_id = '" . (int)$coupon_id . "'");

		if (isset($data['coupon_product'])) {
			foreach ($data['coupon_product'] as $product_id) {
				//$this->db->query("INSERT INTO " . DB_PREFIX . "coupon_product SET coupon_id = '" . (int)$coupon_id . "', product_id = '" . (int)$product_id . "'");
				$this->db->set($this->primaryKey, (int)$coupon_id);
				$this->db->set('product_id', (int)$product_id);
				$this->db->insert($this->product_table);
			}
		}

		//$this->db->query("DELETE FROM " . DB_PREFIX . "coupon_category WHERE coupon_id = '" . (int)$coupon_id . "'");

		if (isset($data['coupon_category'])) {
			foreach ($data['coupon_category'] as $category_id) {
				//$this->db->query("INSERT INTO " . DB_PREFIX . "coupon_category SET coupon_id = '" . (int)$coupon_id . "', category_id = '" . (int)$category_id . "'");
				$this->db->set($this->primaryKey, (int)$coupon_id);
				$this->db->set('category_id', (int)$category_id);
				$this->db->insert($this->category_table);
			}
		}

		$this->event->trigger('post.admin.coupon.edit', $coupon_id);
	}

	public function deleteCoupon($coupon_id) {
		$this->event->trigger('pre.admin.coupon.delete', $coupon_id);

		$this->db->where($this->primaryKey, (int)$coupon_id);
		$this->db->delete(array($this->table, $this->category_table, $this->product_table, $this->history_table));

		/*$this->db->query("DELETE FROM " . DB_PREFIX . "coupon WHERE coupon_id = '" . (int)$coupon_id . "'");
		$this->db->query("DELETE FROM " . DB_PREFIX . "coupon_product WHERE coupon_id = '" . (int)$coupon_id . "'");
		$this->db->query("DELETE FROM " . DB_PREFIX . "coupon_category WHERE coupon_id = '" . (int)$coupon_id . "'");
		$this->db->query("DELETE FROM " . DB_PREFIX . "coupon_history WHERE coupon_id = '" . (int)$coupon_id . "'");*/

		$this->event->trigger('post.admin.coupon.delete', $coupon_id);
	}

	public function getCoupon($coupon_id) {
		$this->db->distinct('*');
		$this->db->from($this->table);
		$this->db->where($this->primaryKey, (int)$coupon_id);
		return $this->db->get()->row_array();

		//$query = $this->db->query("SELECT DISTINCT * FROM " . DB_PREFIX . "coupon WHERE coupon_id = '" . (int)$coupon_id . "'");

		return $query->row;
	}

	public function getCouponByCode($code) {
		$this->db->distinct('*');
		$this->db->from($this->table);
		$this->db->where('code', $code);
		return $this->db->get()->row_array();

		//$query = $this->db->query("SELECT DISTINCT * FROM " . DB_PREFIX . "coupon WHERE code = '" . $this->db->escape($code) . "'");

		//return $query->row;
	}

	public function getCoupons($data = array()) {
		$this->db->select('coupon_id, name, code, discount, date_start, date_end, status');
		$this->db->from($this->table);

		//$sql = "SELECT coupon_id, name, code, discount, date_start, date_end, status FROM " . DB_PREFIX . "coupon";

		$sort_data = array(
			'name',
			'code',
			'discount',
			'date_start',
			'date_end',
			'status'
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

	public function getCouponProducts($coupon_id) {
		$coupon_product_data = array();

		$this->db->select('*')->from($this->product_table)->where($this->primaryKey, (int)$coupon_id);

		//$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "coupon_product WHERE coupon_id = '" . (int)$coupon_id . "'");

		foreach ($this->db->get()->result_array() as $result) {
			$coupon_product_data[] = $result['product_id'];
		}

		return $coupon_product_data;
	}

	public function getCouponCategories($coupon_id) {
		$coupon_category_data = array();

		//$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "coupon_category WHERE coupon_id = '" . (int)$coupon_id . "'");
		$this->db->select('*')->from($this->category_table)->where($this->primaryKey, (int)$coupon_id);

		foreach ($this->db->get()->result_array() as $result) {
			$coupon_category_data[] = $result['category_id'];
		}

		return $coupon_category_data;
	}

	public function getTotalCoupons() {
		$this->db->select('COUNT(*) AS total');
		$this->db->from($this->table);
		$data = $this->db->get()->row_array();
		return $data['total'];

		//$query = $this->db->query("SELECT COUNT(*) AS total FROM " . DB_PREFIX . "coupon");

		//return $query->row['total'];
	}

	public function getCouponHistories($coupon_id, $start = 0, $limit = 10) {
		if ($start < 0) {
			$start = 0;
		}

		if ($limit < 1) {
			$limit = 10;
		}

		$this->db->select('ch.order_id, CONCAT(c.firstname, \' \', c.lastname) AS customer, ch.amount, ch.date_added', FALSE);
		$this->db->from($this->history_table. ' ch');
		$this->db->join('customer c', 'ch.customer_id = c.customer_id', 'left');
		$this->db->where('ch.coupon_id', (int)$coupon_id);
		$this->db->order_by('ch.date_added', 'ASC');
		$this->db->limit($limit, $start);

		//$query = $this->db->query("SELECT ch.order_id, CONCAT(c.firstname, ' ', c.lastname) AS customer, ch.amount, ch.date_added FROM " . DB_PREFIX . "coupon_history ch LEFT JOIN " . DB_PREFIX . "customer c ON (ch.customer_id = c.customer_id) WHERE ch.coupon_id = '" . (int)$coupon_id . "' ORDER BY ch.date_added ASC LIMIT " . (int)$start . "," . (int)$limit);

		//return $query->rows;
		return $this->db->get()->result_array();
	}

	public function getTotalCouponHistories($coupon_id) {
		$this->db->select('COUNT(*) AS total');
		$this->db->from($this->history_table);
		$this->db->where($this->primaryKey, (int)$coupon_id);

		$data = $this->db->get()->row_array();
		return $data['total'];

		//$query = $this->db->query("SELECT COUNT(*) AS total FROM " . DB_PREFIX . "coupon_history WHERE coupon_id = '" . (int)$coupon_id . "'");

		//return $query->row['total'];
	}
}