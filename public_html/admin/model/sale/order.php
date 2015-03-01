<?php
class ModelSaleOrder extends Model {
	public $table = 'order';
	public $primaryKey = 'order_id';

	public function getOrder($order_id) {
		$this->db->select('*, CONCAT(c.firstname, \' \', c.lastname)');
		$this->db->from($this->table . ' o');
		$this->db->join('customer c', 'c.customer_id = o.customer_id', 'left');
		$this->db->where('o.order', (int)$order_id);

		//$order_query = $this->db->query("SELECT *, (SELECT CONCAT(c.firstname, ' ', c.lastname) FROM " . DB_PREFIX . "customer c WHERE c.customer_id = o.customer_id) AS customer FROM `" . DB_PREFIX . "order` o WHERE o.order_id = '" . (int)$order_id . "'");
		$order = $this->db->get()->row_array();
		if (!empty($order)) {
			$reward = 0;

			$this->db->select('*')->from('order_product')->where($this->primaryKey, (int)$order_id);

			//$order_product_query = $this->db->query("SELECT * FROM " . DB_PREFIX . "order_product WHERE order_id = '" . (int)$order_id . "'");

			foreach ($this->db->get()->result_array() as $product) {
				$reward += $product['reward'];
			}

			//$country_query = $this->db->query("SELECT * FROM `" . DB_PREFIX . "country` WHERE country_id = '" . (int)$order['payment_country_id'] . "'");
			$this->db->select('*')->from('country')->where('country_id', (int)$order['payment_country_id']);

			$country = $this->db->get()->row_array();
			if (!empty($country)) {
				$payment_iso_code_2 = $country['iso_code_2'];
				$payment_iso_code_3 = $country['iso_code_3'];
			} else {
				$payment_iso_code_2 = '';
				$payment_iso_code_3 = '';
			}

			$this->db->select('*')->from('zone')->where('zone_id', (int)$order['payment_zone_id']);

			//$zone_query = $this->db->query("SELECT * FROM `" . DB_PREFIX . "zone` WHERE zone_id = '" . (int)$order['payment_zone_id'] . "'");
			$zone = $this->db->get()->row_array();
			if (!empty($zone)) {
				$payment_zone_code = $zone['code'];
			} else {
				$payment_zone_code = '';
			}

			$this->db->select('*')->from('country')->where('country_id', (int)$order['shipping_country_id']);

			//$country_query = $this->db->query("SELECT * FROM `" . DB_PREFIX . "country` WHERE country_id = '" . (int)$order['shipping_country_id'] . "'");

			$country = $this->db->get()->row_array();
			if (!empty($country)) {
				$shipping_iso_code_2 = $country['iso_code_2'];
				$shipping_iso_code_3 = $country['iso_code_3'];
			} else {
				$shipping_iso_code_2 = '';
				$shipping_iso_code_3 = '';
			}

			$this->db->select('*')->from('zone')->where('zone_id', (int)$order['shipping_zone_id']);

			$zone = $this->db->get()->row_array();
			//$zone_query = $this->db->query("SELECT * FROM `" . DB_PREFIX . "zone` WHERE zone_id = '" . (int)$order['shipping_zone_id'] . "'");

			if (!empty($zone)) {
				$shipping_zone_code = $zone['code'];
			} else {
				$shipping_zone_code = '';
			}

			if ($order['affiliate_id']) {
				$affiliate_id = $order['affiliate_id'];
			} else {
				$affiliate_id = 0;
			}

			$this->load->model('marketing/affiliate');

			$affiliate_info = $this->model_marketing_affiliate->getAffiliate($affiliate_id);

			if ($affiliate_info) {
				$affiliate_firstname = $affiliate_info['firstname'];
				$affiliate_lastname = $affiliate_info['lastname'];
			} else {
				$affiliate_firstname = '';
				$affiliate_lastname = '';
			}

			$this->load->model('localisation/language');

			$language_info = $this->model_localisation_language->getLanguage($order['language_id']);

			if ($language_info) {
				$language_code = $language_info['code'];
				$language_directory = $language_info['directory'];
			} else {
				$language_code = '';
				$language_directory = '';
			}

			return array(
				'order_id'                => $order['order_id'],
				'invoice_no'              => $order['invoice_no'],
				'invoice_prefix'          => $order['invoice_prefix'],
				'store_id'                => $order['store_id'],
				'store_name'              => $order['store_name'],
				'store_url'               => $order['store_url'],
				'customer_id'             => $order['customer_id'],
				'customer'                => $order['customer'],
				'customer_group_id'       => $order['customer_group_id'],
				'firstname'               => $order['firstname'],
				'lastname'                => $order['lastname'],
				'email'                   => $order['email'],
				'telephone'               => $order['telephone'],
				'fax'                     => $order['fax'],
				'custom_field'            => unserialize($order['custom_field']),
				'payment_firstname'       => $order['payment_firstname'],
				'payment_lastname'        => $order['payment_lastname'],
				'payment_company'         => $order['payment_company'],
				'payment_address_1'       => $order['payment_address_1'],
				'payment_address_2'       => $order['payment_address_2'],
				'payment_postcode'        => $order['payment_postcode'],
				'payment_city'            => $order['payment_city'],
				'payment_zone_id'         => $order['payment_zone_id'],
				'payment_zone'            => $order['payment_zone'],
				'payment_zone_code'       => $payment_zone_code,
				'payment_country_id'      => $order['payment_country_id'],
				'payment_country'         => $order['payment_country'],
				'payment_iso_code_2'      => $payment_iso_code_2,
				'payment_iso_code_3'      => $payment_iso_code_3,
				'payment_address_format'  => $order['payment_address_format'],
				'payment_custom_field'    => unserialize($order['payment_custom_field']),
				'payment_method'          => $order['payment_method'],
				'payment_code'            => $order['payment_code'],
				'shipping_firstname'      => $order['shipping_firstname'],
				'shipping_lastname'       => $order['shipping_lastname'],
				'shipping_company'        => $order['shipping_company'],
				'shipping_address_1'      => $order['shipping_address_1'],
				'shipping_address_2'      => $order['shipping_address_2'],
				'shipping_postcode'       => $order['shipping_postcode'],
				'shipping_city'           => $order['shipping_city'],
				'shipping_zone_id'        => $order['shipping_zone_id'],
				'shipping_zone'           => $order['shipping_zone'],
				'shipping_zone_code'      => $shipping_zone_code,
				'shipping_country_id'     => $order['shipping_country_id'],
				'shipping_country'        => $order['shipping_country'],
				'shipping_iso_code_2'     => $shipping_iso_code_2,
				'shipping_iso_code_3'     => $shipping_iso_code_3,
				'shipping_address_format' => $order['shipping_address_format'],
				'shipping_custom_field'   => unserialize($order['shipping_custom_field']),
				'shipping_method'         => $order['shipping_method'],
				'shipping_code'           => $order['shipping_code'],
				'comment'                 => $order['comment'],
				'total'                   => $order['total'],
				'reward'                  => $reward,
				'order_status_id'         => $order['order_status_id'],
				'affiliate_id'            => $order['affiliate_id'],
				'affiliate_firstname'     => $affiliate_firstname,
				'affiliate_lastname'      => $affiliate_lastname,
				'commission'              => $order['commission'],
				'language_id'             => $order['language_id'],
				'language_code'           => $language_code,
				'language_directory'      => $language_directory,
				'currency_id'             => $order['currency_id'],
				'currency_code'           => $order['currency_code'],
				'currency_value'          => $order['currency_value'],
				'ip'                      => $order['ip'],
				'forwarded_ip'            => $order['forwarded_ip'],
				'user_agent'              => $order['user_agent'],
				'accept_language'         => $order['accept_language'],
				'date_added'              => $order['date_added'],
				'date_modified'           => $order['date_modified']
			);
		} else {
			return;
		}
	}

	public function getOrders($data = array()) {
		$this->db->select('o.shipping_code, o.total, o.currency_code, o.currency_value, o.date_added, o.date_modified, o.order_id, o.order_status_id , CONCAT(o.firstname, \' \', o.lastname) AS customer', FALSE);
		$this->db->from($this->table . ' o');

		//$sql = "SELECT o.order_id, CONCAT(o.firstname, ' ', o.lastname) AS customer, (SELECT os.name FROM " . DB_PREFIX . "order_status os WHERE os.order_status_id = o.order_status_id AND os.language_id = '" . (int)$this->config->get('config_language_id') . "') AS status, o.shipping_code, o.total, o.currency_code, o.currency_value, o.date_added, o.date_modified FROM `" . DB_PREFIX . "order` o";

		if (isset($data['filter_order_status'])) {
			$this->db->where_in('o.order_status_id', $data['filter_order_status']);
			/*
			$implode = array();

			$order_statuses = explode(',', $data['filter_order_status']);

			foreach ($order_statuses as $order_status_id) {
				$implode[] = "o.order_status_id = '" . (int)$order_status_id . "'";
			}

			if ($implode) {
				$sql .= " WHERE (" . implode(" OR ", $implode) . ")";
			} else {

			}*/

		} else {
			$this->db->where('o.order_status_id >', 0);

			//$sql .= " WHERE o.order_status_id > '0'";
		}

		if (!empty($data['filter_order_id'])) {
			$this->db->where('o.order_id', (int)$data['filter_order_id']);

			//$sql .= " AND o.order_id = '" . (int)$data['filter_order_id'] . "'";
		}

		if (!empty($data['filter_customer'])) {
			$this->db->like('CONCAT(o.firstname, \' \', o.lastname)', $data['filter_customer']);

			//$sql .= " AND CONCAT(o.firstname, ' ', o.lastname) LIKE '%" . $this->db->escape($data['filter_customer']) . "%'";
		}

		if (!empty($data['filter_date_added'])) {
			//$sql .= " AND DATE(o.date_added) = DATE('" . $this->db->escape($data['filter_date_added']) . "')";
		}

		if (!empty($data['filter_date_modified'])) {
			//$sql .= " AND DATE(o.date_modified) = DATE('" . $this->db->escape($data['filter_date_modified']) . "')";
		}

		if (!empty($data['filter_total'])) {
			//$sql .= " AND o.total = '" . (float)$data['filter_total'] . "'";
			$this->db->where('o.total', (float)$data['filter_total']);
		}

		$sort_data = array(
			'o.order_id',
			'customer',
			'status',
			'o.date_added',
			'o.date_modified',
			'o.total'
		);

		$order = 'ASC';
		if (isset($data['order']) && ($data['order'] == 'DESC')) {
			$order = 'DESC';
		}

		if (isset($data['sort']) && in_array($data['sort'], $sort_data)) {
			//$sql .= " ORDER BY " . $data['sort'];
			$this->db->order_by($data['sort'], $order);
		} else {
			//$sql .= " ORDER BY o.order_id";
			$this->db->order_by('o.order_id', $order);
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

	public function getOrderProducts($order_id) {
		$this->db->select('*')->from('order_product')->where($this->primaryKey, (int)$order_id);
		return $this->db->get()->result_array();

		//$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "order_product WHERE order_id = '" . (int)$order_id . "'");

		//return $query->rows;
	}

	public function getOrderOption($order_id, $order_option_id) {
		$this->db->select('*')->from('order_option')->where($this->primaryKey, (int)$order_id);
		$this->db->where('order_option_id', (int)$order_option_id);

		return $this->db->get()->result_array();

		//$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "order_option WHERE order_id = '" . (int)$order_id . "' AND order_option_id = '" . (int)$order_option_id . "'");

		//return $query->row;
	}

	public function getOrderOptions($order_id, $order_product_id) {
		$this->db->select('*')->from('order_option')->where($this->primaryKey, (int)$order_id);
		$this->db->where('order_product_id', (int)$order_product_id);

		return $this->db->get()->result_array();

		//$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "order_option WHERE order_id = '" . (int)$order_id . "' AND order_product_id = '" . (int)$order_product_id . "'");

		//return $query->rows;
	}

	public function getOrderVouchers($order_id) {
		$this->db->select('*')->from('order_voucher')->where($this->primaryKey, (int)$order_id);

		return $this->db->get()->result_array();

		//$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "order_voucher WHERE order_id = '" . (int)$order_id . "'");

		//return $query->rows;
	}

	public function getOrderVoucherByVoucherId($voucher_id) {
		$this->db->select('*')->from('order_voucher')->where('voucher_id', (int)$voucher_id);

		return $this->db->get()->row_array();

		//$query = $this->db->query("SELECT * FROM `" . DB_PREFIX . "order_voucher` WHERE voucher_id = '" . (int)$voucher_id . "'");

		//return $query->row;
	}

	public function getOrderTotals($order_id) {
		$this->db->select('*')->from('order_total')->where($this->primaryKey, (int)$order_id);
		$this->db->order_by('sort_order', 'ASC');

		return $this->db->get()->result_array();

		//$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "order_total WHERE order_id = '" . (int)$order_id . "' ORDER BY sort_order");

		//return $query->rows;
	}

	public function getTotalOrders($data = array()) {
		$this->db->select('COUNT(*) AS total');
		$this->db->from($this->table . ' o');

		//$sql = "SELECT COUNT(*) AS total FROM `" . DB_PREFIX . "order`";

		if (isset($data['filter_order_status'])) {
			$this->db->where_in('o.order_status_id', $data['filter_order_status']);
			/*
			$implode = array();

			$order_statuses = explode(',', $data['filter_order_status']);

			foreach ($order_statuses as $order_status_id) {
				$implode[] = "o.order_status_id = '" . (int)$order_status_id . "'";
			}

			if ($implode) {
				$sql .= " WHERE (" . implode(" OR ", $implode) . ")";
			} else {

			}*/

		} else {
			$this->db->where('o.order_status_id >', 0);

			//$sql .= " WHERE o.order_status_id > '0'";
		}

		if (!empty($data['filter_order_id'])) {
			$this->db->where('o.order_id', (int)$data['filter_order_id']);

			//$sql .= " AND o.order_id = '" . (int)$data['filter_order_id'] . "'";
		}

		if (!empty($data['filter_customer'])) {
			$this->db->like('CONCAT(o.firstname, \' \', o.lastname)', $data['filter_customer']);

			//$sql .= " AND CONCAT(o.firstname, ' ', o.lastname) LIKE '%" . $this->db->escape($data['filter_customer']) . "%'";
		}

		if (!empty($data['filter_date_added'])) {
			//$sql .= " AND DATE(o.date_added) = DATE('" . $this->db->escape($data['filter_date_added']) . "')";
		}

		if (!empty($data['filter_date_modified'])) {
			//$sql .= " AND DATE(o.date_modified) = DATE('" . $this->db->escape($data['filter_date_modified']) . "')";
		}

		if (!empty($data['filter_total'])) {
			//$sql .= " AND o.total = '" . (float)$data['filter_total'] . "'";
			$this->db->where('o.total', (float)$data['filter_total']);
		}

		//$query = $this->db->query($sql);

		//return $query->row['total'];
		$data = $this->db->get()->row_array();
		return $data['total'];
	}

	public function getTotalOrdersByStoreId($store_id) {
		$this->db->select('COUNT(*) AS total')->from('order_history');
		$this->db->where('store_id', (int)$store_id);
		//$this->db->where('order_status_id >', 0);
		$data = $this->db->get()->row_array();
		return $data['total'];

		//$query = $this->db->query("SELECT COUNT(*) AS total FROM `" . DB_PREFIX . "order` WHERE store_id = '" . (int)$store_id . "'");

		//return $query->row['total'];
	}

	public function getTotalOrdersByOrderStatusId($order_status_id) {

		$this->db->select('COUNT(*) AS total')->from('order_history');
		$this->db->where('order_status_id', (int)$order_status_id);
		$this->db->where('order_status_id >', 0);
		$data = $this->db->get()->row_array();
		return $data['total'];

		//$query = $this->db->query("SELECT COUNT(*) AS total FROM `" . DB_PREFIX . "order` WHERE order_status_id = '" . (int)$order_status_id . "' AND order_status_id > '0'");

		//return $query->row['total'];
	}

	public function getTotalOrdersByProcessingStatus() {
		$implode = array();

		$order_statuses = $this->config->get('config_processing_status');

		/*foreach ($order_statuses as $order_status_id) {
			$implode[] = "order_status_id = '" . (int)$order_status_id . "'";
		}

		if ($implode) {
			$query = $this->db->query("SELECT COUNT(*) AS total FROM `" . DB_PREFIX . "order` WHERE " . implode(" OR ", $implode));

			return $query->row['total'];
		} else {
			return 0;
		}*/

		$this->db->select('COUNT(*) AS total');
		$this->db->from($this->table);
		$this->db->where_in('order_status_id', $order_statuses);
		$data = $this->db->get()->row_array();
		return $data['total'];
	}

	public function getTotalOrdersByCompleteStatus() {
		$implode = array();

		$order_statuses = $this->config->get('config_complete_status');

		/*foreach ($order_statuses as $order_status_id) {
			$implode[] = "order_status_id = '" . (int)$order_status_id . "'";
		}

		if ($implode) {
			$query = $this->db->query("SELECT COUNT(*) AS total FROM `" . DB_PREFIX . "order` WHERE " . implode(" OR ", $implode) . "");

			return $query->row['total'];
		} else {
			return 0;
		}*/
		$this->db->select('COUNT(*) AS total');
		$this->db->from($this->table);
		$this->db->where_in('order_status_id', $order_statuses);
		$data = $this->db->get()->row_array();
		return $data['total'];
	}

	public function getTotalOrdersByLanguageId($language_id) {
		$this->db->select('COUNT(*) AS total')->from($this->table);
		$this->db->where('language_id', (int)$language_id);
		$this->db->where('order_status_id >', 0);
		$data = $this->db->get()->row_array();
		return $data['total'];

		//$query = $this->db->query("SELECT COUNT(*) AS total FROM `" . DB_PREFIX . "order` WHERE language_id = '" . (int)$language_id . "' AND order_status_id > '0'");

		//return $query->row['total'];
	}

	public function getTotalOrdersByCurrencyId($currency_id) {

		$this->db->select('COUNT(*) AS total')->from('order_history');
		$this->db->where('currency_id', (int)$currency_id);
		$this->db->where('order_status_id >', 0);
		$data = $this->db->get()->row_array();
		return $data['total'];

		//$query = $this->db->query("SELECT COUNT(*) AS total FROM `" . DB_PREFIX . "order` WHERE currency_id = '" . (int)$currency_id . "' AND order_status_id > '0'");

		//return $query->row['total'];
	}

	public function createInvoiceNo($order_id) {
		$order_info = $this->getOrder($order_id);

		if ($order_info && !$order_info['invoice_no']) {
			$query = $this->db->query("SELECT MAX(invoice_no) AS invoice_no FROM `" . DB_PREFIX . "order` WHERE invoice_prefix = '" . $this->db->escape($order_info['invoice_prefix']) . "'");

			if ($query->row['invoice_no']) {
				$invoice_no = $query->row['invoice_no'] + 1;
			} else {
				$invoice_no = 1;
			}

			$this->db->query("UPDATE `" . DB_PREFIX . "order` SET invoice_no = '" . (int)$invoice_no . "', invoice_prefix = '" . $this->db->escape($order_info['invoice_prefix']) . "' WHERE order_id = '" . (int)$order_id . "'");

			return $order_info['invoice_prefix'] . $invoice_no;
		}
	}

	public function getOrderHistories($order_id, $start = 0, $limit = 10) {
		if ($start < 0) {
			$start = 0;
		}

		if ($limit < 1) {
			$limit = 10;
		}

		$query = $this->db->query("SELECT oh.date_added, os.name AS status, oh.comment, oh.notify FROM " . DB_PREFIX . "order_history oh LEFT JOIN " . DB_PREFIX . "order_status os ON oh.order_status_id = os.order_status_id WHERE oh.order_id = '" . (int)$order_id . "' AND os.language_id = '" . (int)$this->config->get('config_language_id') . "' ORDER BY oh.date_added ASC LIMIT " . (int)$start . "," . (int)$limit);

		return $query->rows;
	}

	public function getTotalOrderHistories($order_id) {
		$this->db->select('COUNT(*) AS total')->from('order_history');
		$this->db->where('order_id', (int)$order_id);
		$data = $this->db->get()->row_array();
		return $data['total'];

		//$query = $this->db->query("SELECT COUNT(*) AS total FROM " . DB_PREFIX . "order_history WHERE order_id = '" . (int)$order_id . "'");

		//return $query->row['total'];
	}

	public function getTotalOrderHistoriesByOrderStatusId($order_status_id) {
		$this->db->select('COUNT(*) AS total')->from('order_history');
		$this->db->where('order_status_id', (int)$order_status_id);
		$data = $this->db->get()->row_array();
		return $data['total'];

		//$query = $this->db->query("SELECT COUNT(*) AS total FROM " . DB_PREFIX . "order_history WHERE order_status_id = '" . (int)$order_status_id . "'");

		//return $query->row['total'];
	}

	public function getEmailsByProductsOrdered($products, $start, $end) {
		//$query = $this->db->query("SELECT DISTINCT email FROM `" . DB_PREFIX . "order` o LEFT JOIN " . DB_PREFIX . "order_product op ON (o.order_id = op.order_id) WHERE (" . implode(" OR ", $implode) . ") AND o.order_status_id <> '0' LIMIT " . (int)$start . "," . (int)$end);

		//return $query->rows;

		$this->db->distinct('email');
		$this->db->from($this->table . ' o');
		$this->db->join('order_product op', 'o.order_id = op.order_id', 'left');
		$this->db->where_in('op.product_id', $products);
		$this->db->where('o.order_status_id <>', 0);
		$this->db->group_by('o.email');
		$thid->db->limit($start, $end);
		$data = $this->db->get()->result_array();
	}

	public function getTotalEmailsByProductsOrdered($products) {

		$this->db->select('COUNT(*) AS total');
		$this->db->from($this->table . ' o');
		$this->db->join('order_product op', 'o.order_id = op.order_id', 'left');
		$this->db->where_in('op.product_id', $products);
		$this->db->where('o.order_status_id <>', 0);
		$this->db->group_by('o.email');
		$data = $this->db->get()->row_array();
		return $data['total'];

		//$query = $this->db->query("SELECT DISTINCT email FROM `" . DB_PREFIX . "order` o LEFT JOIN " . DB_PREFIX . "order_product op ON (o.order_id = op.order_id) WHERE (" . implode(" OR ", $implode) . ") AND o.order_status_id <> '0'");
	}
}