<?php
class ModelCatalogReview extends Model {
	public $table = 'review';
	public $fields = array('review_id', 'product_id', 'customer_id', 'author', 'text', 'rating', 'status', 'date_added', 'date_modified');
	public $primaryKey = 'review_id';
	public function addReview($data) {
		$this->event->trigger('pre.admin.review.add', $data);
		$data = $this->initData($data, TRUE);
		$data['text'] = strip_tags($data['text']);
		
		$this->db->set('date_added', 'NOW()', FALSE);
		$this->db->insert($this->table, $data);
		
		/*$this->db->query("INSERT INTO " . DB_PREFIX . "review SET author = '" . $this->db->escape($data['author']) . "', product_id = '" . (int)$data['product_id'] . "', text = '" . $this->db->escape(strip_tags($data['text'])) . "', rating = '" . (int)$data['rating'] . "', status = '" . (int)$data['status'] . "', date_added = NOW()");*/

		$review_id = $this->db->insert_id();

		$this->cache->delete('product');

		$this->event->trigger('post.admin.review.add', $review_id);

		return $review_id;
	}

	public function editReview($review_id, $data) {
		$this->event->trigger('pre.admin.review.edit', $data);
		
		$data = $this->initData($data, TRUE);
		$data['text'] = strip_tags($data['text']);
		$this->db->where($this->primaryKey, (int)$review_id);
		$this->db->update($this->table, $data);
		/*$this->db->query("UPDATE " . DB_PREFIX . "review SET author = '" . $this->db->escape($data['author']) . "', product_id = '" . (int)$data['product_id'] . "', text = '" . $this->db->escape(strip_tags($data['text'])) . "', rating = '" . (int)$data['rating'] . "', status = '" . (int)$data['status'] . "', date_modified = NOW() WHERE review_id = '" . (int)$review_id . "'");*/

		$this->cache->delete('product');

		$this->event->trigger('post.admin.review.edit', $review_id);
	}

	public function deleteReview($review_id) {
		$this->event->trigger('pre.admin.review.delete', $review_id);
		$this->db->where($this->primaryKey, (int)$review_id);
		$this->db->delete($this->table);
		//$this->db->query("DELETE FROM " . DB_PREFIX . "review WHERE review_id = '" . (int)$review_id . "'");

		$this->cache->delete('product');

		$this->event->trigger('post.admin.review.delete', $review_id);
	}

	public function getReview($review_id) {
		$query = $this->db->query("SELECT DISTINCT *, (SELECT pd.name FROM " . DB_PREFIX . "product_description pd WHERE pd.product_id = r.product_id AND pd.language_id = '" . (int)$this->config->get('config_language_id') . "') AS product FROM " . DB_PREFIX . "review r WHERE r.review_id = '" . (int)$review_id . "'");

		return $query->row_array();
	}

	public function getReviews($data = array()) {
		$this->db->select('r.review_id, pd.name, r.author, r.rating, r.status, r.date_added');
		$this->db->from($this->table . ' r');
		$this->db->join('product_description pd', 'r.product_id = pd.product_id', 'left');
		$this->db->where('pd.language_id', (int)$this->config->get('config_language_id'));
		//$sql = "SELECT r.review_id, pd.name, r.author, r.rating, r.status, r.date_added FROM " . DB_PREFIX . "review r LEFT JOIN " . DB_PREFIX . "product_description pd ON (r.product_id = pd.product_id) WHERE pd.language_id = '" . (int)$this->config->get('config_language_id') . "'";

		if (!empty($data['filter_product'])) {
			//$sql .= " AND pd.name LIKE '" . $this->db->escape($data['filter_product']) . "%'";
			$this->db->like('pd.name', $this->db->escape($data['filter_product']));
		}

		if (!empty($data['filter_author'])) {
			//$sql .= " AND r.author LIKE '" . $this->db->escape($data['filter_author']) . "%'";
			$this->db->like('r.author', $this->db->escape($data['filter_author']));
		}

		if (isset($data['filter_status']) && !is_null($data['filter_status'])) {
			//$sql .= " AND r.status = '" . (int)$data['filter_status'] . "'";
			$this->db->where('r.status', $this->db->escape($data['filter_status']));
		}

		if (!empty($data['filter_date_added'])) {
			//$sql .= " AND DATE(r.date_added) = DATE('" . $this->db->escape($data['filter_date_added']) . "')";
			//$this->db->where('r.status', $this->db->escape($data['filter_date_added']));
		}

		$sort_data = array(
			'pd.name',
			'r.author',
			'r.rating',
			'r.status',
			'r.date_added'
		);
		
		$order = 'ASC';
		if (isset($data['order']) && ($data['order'] == 'DESC')) {
			//$sql .= " DESC";
			$order = 'DESC';
		}

		if (isset($data['sort']) && in_array($data['sort'], $sort_data)) {
			//$sql .= " ORDER BY " . $data['sort'];
			$this->db->order_by($data['sort'], $order);
		} else {
			$this->db->order_by('r.date_added', $order);
			//$sql .= " ORDER BY r.date_added";
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

		//return $query->rows;
		
		return $this->db->get()->result_array();
	}

	public function getTotalReviews($data = array()) {
		$this->db->select('COUNT(*) AS total');
		$this->db->from($this->table . ' r');
		$this->db->join('product_description pd', 'r.product_id = pd.product_id', 'left');
		$this->db->where('pd.language_id', (int)$this->config->get('config_language_id'));
		
		//$sql = "SELECT COUNT(*) AS total FROM " . DB_PREFIX . "review r LEFT JOIN " . DB_PREFIX . "product_description pd ON (r.product_id = pd.product_id) WHERE pd.language_id = '" . (int)$this->config->get('config_language_id') . "'";

		if (!empty($data['filter_product'])) {
			//$sql .= " AND pd.name LIKE '" . $this->db->escape($data['filter_product']) . "%'";
			$this->db->like('pd.name', $this->db->escape($data['filter_product']));
		}

		if (!empty($data['filter_author'])) {
			//$sql .= " AND r.author LIKE '" . $this->db->escape($data['filter_author']) . "%'";
			$this->db->like('r.author', $this->db->escape($data['filter_author']));
		}

		if (!empty($data['filter_status'])) {
			//$sql .= " AND r.status = '" . (int)$data['filter_status'] . "'";
			$this->db->where('r.status', $this->db->escape($data['filter_status']));
		}

		if (!empty($data['filter_date_added'])) {
			//$sql .= " AND DATE(r.date_added) = DATE('" . $this->db->escape($data['filter_date_added']) . "')";
			$this->db->where("DATE(r.date_added) = DATE(" . $this->db->escape($data['filter_date_added']) . ")", NULL, FALSE);
		}

		//$query = $this->db->query($sql);
		$row = $this->db->get()->row_array();

		return $row['total'];
	}

	public function getTotalReviewsAwaitingApproval() {
		$this->db->select('COUNT(*) AS `total`');
		$this->db->where('status', 0);
		$query = $this->db->get($this->table)->row_array();
		return $query['total'];
	}
}