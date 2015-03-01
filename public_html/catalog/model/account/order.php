<?php
class ModelAccountOrder extends Model {
	public function getOrder($order_id) {
		$this->db->select('*')
			->from('order')
			->where('order_id', (int)$order_id)
			->where('customer_id', (int)$this->customer->getId())
			->where('order_status_id >', 0);

		$order = $this->db->get()->row_array();

		//$order_query = $this->db->query("SELECT * FROM `" . DB_PREFIX . "order` WHERE order_id = '" . (int)$order_id . "' AND customer_id = '" . (int)$this->customer->getId() . "' AND order_status_id > '0'");

		if (isset($order['order_id'])) {
			$this->db->select('*')
				->from('country')
				->where('country_id', (int)$order['payment_country_id']);
			$country = $this->db->get()->row_array();


			//$country_query = $this->db->query("SELECT * FROM `" . DB_PREFIX . "country` WHERE country_id = '" . (int)$order['payment_country_id'] . "'");

			if (isset($country['country_id'])) {
				$payment_iso_code_2 = $country['iso_code_2'];
				$payment_iso_code_3 = $country['iso_code_3'];
			} else {
				$payment_iso_code_2 = '';
				$payment_iso_code_3 = '';
			}

			$this->db->select('*')
				->from('zone')
				->where('zone_id', $order['payment_zone_id']);
			$zone = $this->db->get()->row_array();

			//$zone_query = $this->db->query("SELECT * FROM `" . DB_PREFIX . "zone` WHERE zone_id = '" . (int)$order['payment_zone_id'] . "'");

			if (isset($zone['zone_id'])) {
				$payment_zone_code = $zone['code'];
			} else {
				$payment_zone_code = '';
			}

			$this->db->select('*')
				->from('country')
				->where('country_id', (int)$order['shipping_country_id']);
			$country = $this->db->get()->row_array();

			//$country_query = $this->db->query("SELECT * FROM `" . DB_PREFIX . "country` WHERE country_id = '" . (int)$order['shipping_country_id'] . "'");

			if (isset($country['country_id'])) {
				$shipping_iso_code_2 = $country['iso_code_2'];
				$shipping_iso_code_3 = $country['iso_code_3'];
			} else {
				$shipping_iso_code_2 = '';
				$shipping_iso_code_3 = '';
			}

			//$zone_query = $this->db->query("SELECT * FROM `" . DB_PREFIX . "zone` WHERE zone_id = '" . (int)$order['shipping_zone_id'] . "'");
			$this->db->select('*')
				->from('zone')
				->where('zone_id', $order['shipping_zone_id']);
			$zone = $this->db->get()->row_array();

			if (isset($zone['zone_id'])) {
				$shipping_zone_code = $zone['code'];
			} else {
				$shipping_zone_code = '';
			}

			return array(
				'order_id'                => $order['order_id'],
				'invoice_no'              => $order['invoice_no'],
				'invoice_prefix'          => $order['invoice_prefix'],
				'store_id'                => $order['store_id'],
				'store_name'              => $order['store_name'],
				'store_url'               => $order['store_url'],
				'customer_id'             => $order['customer_id'],
				'firstname'               => $order['firstname'],
				'lastname'                => $order['lastname'],
				'telephone'               => $order['telephone'],
				'fax'                     => $order['fax'],
				'email'                   => $order['email'],
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
				'payment_method'          => $order['payment_method'],
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
				'shipping_method'         => $order['shipping_method'],
				'comment'                 => $order['comment'],
				'total'                   => $order['total'],
				'order_status_id'         => $order['order_status_id'],
				'language_id'             => $order['language_id'],
				'currency_id'             => $order['currency_id'],
				'currency_code'           => $order['currency_code'],
				'currency_value'          => $order['currency_value'],
				'date_modified'           => $order['date_modified'],
				'date_added'              => $order['date_added'],
				'ip'                      => $order['ip']
			);
		} else {
			return false;
		}
	}

	public function getOrders($start = 0, $limit = 20) {
		if ($start < 0) {
			$start = 0;
		}

		if ($limit < 1) {
			$limit = 1;
		}

		$this->db->select('order_status_id, name')
			->from('order_status')
			->where('language_id', (int)$this->config->get('config_language_id'));
		$query = $this->db->get()->result_array();
		$orderStatus = array();
		foreach($query as $item) {
			$orderStatus[$item['order_status_id']] = $item['name'];
		}

		$this->db->select('*')
			->from('order')
			->where('customer_id', $this->customer->getId())
			->where('order_status_id >', 0)
			->order_by('order_id', 'DESC')
			->limit($limit, $start);
		$query = $this->db->get()->result_array();

		foreach($query as &$item) {
			$item['status'] = $orderStatus[$item['order_status_id']];
		}

		return $query;

		/*
		$query = $this->db->query("SELECT o.order_id, o.firstname, o.lastname, os.name as status, o.date_added, o.total, o.currency_code, o.currency_value FROM `" . DB_PREFIX . "order` o LEFT JOIN " . DB_PREFIX . "order_status os ON (o.order_status_id = os.order_status_id) WHERE o.customer_id = '" . (int)$this->customer->getId() . "' AND o.order_status_id > '0' AND o.store_id = '" . (int)$this->config->get('config_store_id') . "' AND os.language_id = '" . (int)$this->config->get('config_language_id') . "' ORDER BY o.order_id DESC LIMIT " . (int)$start . "," . (int)$limit);

		return $query->rows;*/
	}

	public function getOrderProduct($order_id, $order_product_id) {
		$this->db->select('*')
			->from('order_product')
			->where('order_id', (int)$order_id)
			->where('order_product_id', (int)$order_product_id);

		return $this->db->get()->row_array();

		//$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "order_product WHERE order_id = '" . (int)$order_id . "' AND order_product_id = '" . (int)$order_product_id . "'");

		//return $query->row;
	}

	public function getOrderProducts($order_id) {
		$this->db->select('*')
			->from('order_product')
			->where('order_id', (int)$order_id);

		return $this->db->get()->result_array();

		//$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "order_product WHERE order_id = '" . (int)$order_id . "'");

		//return $query->rows;
	}

	public function getOrderOptions($order_id, $order_product_id) {
		$this->db->select('*')
			->from('order_option')
			->where('order_id', (int)$order_id)
			->where('order_product_id', (int)$order_product_id);

		return $this->db->get()->result_array();

		//$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "order_option WHERE order_id = '" . (int)$order_id . "' AND order_product_id = '" . (int)$order_product_id . "'");

		//return $query->rows;
	}

	public function getOrderVouchers($order_id) {
		$this->db->select('*')
			->from('order_voucher')
			->where('order_id', (int)$order_id);

		return $this->db->get()->result_array();

		//$query = $this->db->query("SELECT * FROM `" . DB_PREFIX . "order_voucher` WHERE order_id = '" . (int)$order_id . "'");

		//return $query->rows;
	}

	public function getOrderTotals($order_id) {
		$this->db->select('*')
			->from('order_total')
			->where('order_id', (int)$order_id)
			->order_by('sort_order');

		return $this->db->get()->result_array();

		//$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "order_total WHERE order_id = '" . (int)$order_id . "' ORDER BY sort_order");

		//return $query->rows;
	}

	public function getOrderHistories($order_id) {
		$this->db->select('order_status_id, name')
			->from('order_status')
			->where('language_id', (int)$this->config->get('config_language_id'));
		$query = $this->db->get()->result_array();
		$orderStatus = array();
		foreach($query as $item) {
			$orderStatus[$item['order_status_id']] = $item['name'];
		}

		$this->db->select('date_added, comment, notify, order_status_id')
			->from('order_history')
			->where('order_id', (int)$order_id)
			->order_by('date_added', 'ASC');

		$query = $this->db->get()->result_array();
		$orders = array();
		foreach($query as &$item) {
				$item['status'] = $orderStatus[$item['order_status_id']];
		}

		return $query;

		/*
		$query = $this->db->query("SELECT date_added, os.name AS status, oh.comment, oh.notify FROM " . DB_PREFIX . "order_history oh LEFT JOIN " . DB_PREFIX . "order_status os ON oh.order_status_id = os.order_status_id WHERE oh.order_id = '" . (int)$order_id . "' AND os.language_id = '" . (int)$this->config->get('config_language_id') . "' ORDER BY oh.date_added");

		return $query->rows;*/
	}

	public function getTotalOrders() {
		$this->db->select('COUNT(*) AS total')
			->from('order')
			->where('customer_id', (int)$this->customer->getId())
			->where('order_status_id >', 0);
		$data = $this->db->get()->row_array();
		return $data['total'];

		/*
		$query = $this->db->query("SELECT COUNT(*) AS total FROM `" . DB_PREFIX . "order` o WHERE customer_id = '" . (int)$this->customer->getId() . "' AND o.order_status_id > '0' AND o.store_id = '" . (int)$this->config->get('config_store_id') . "'");

		return $query->row['total'];*/
	}

	public function getTotalOrderProductsByOrderId($order_id) {
		$this->db->select('COUNT(*) AS total')
			->from('order_product')
			->where('order_id', (int)$order_id);

		$data = $this->db->get()->row_array();
		return $data['total'];

		//$query = $this->db->query("SELECT COUNT(*) AS total FROM " . DB_PREFIX . "order_product WHERE order_id = '" . (int)$order_id . "'");

		//return $query->row['total'];
	}

	public function getTotalOrderVouchersByOrderId($order_id) {
		$this->db->select('COUNT(*) AS total')
			->from('order_voucher')
			->where('order_id', (int)$order_id);
		$data = $this->db->get()->row_array();
		return $data['total'];

		//$query = $this->db->query("SELECT COUNT(*) AS total FROM `" . DB_PREFIX . "order_voucher` WHERE order_id = '" . (int)$order_id . "'");

		//return $query->row['total'];
	}
}