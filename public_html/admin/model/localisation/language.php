<?php
class ModelLocalisationLanguage extends Model {

	public $table = 'language';
	public $primaryKey = 'language_id';
	public $fields = array('language_id', 'name', 'code', 'locale', 'image', 'directory', 'column', 'sort_order', 'status');

	public function addLanguage($data) {

		$data = $this->initData($data, TRUE);
		$this->db->insert($this->table, $data);
		//$this->db->query("INSERT INTO " . DB_PREFIX . "language SET name = '" . ($data['name']) . "', code = '" . ($data['code']) . "', locale = '" . ($data['locale']) . "', directory = '" . ($data['directory']) . "', image = '" . ($data['image']) . "', sort_order = '" . ($data['sort_order']) . "', status = '" . (int)$data['status'] . "'");

		$this->cache->delete('language');

		//$language_id = $this->db->getLastId();
		$language_id = $this->db->insert_id();

		// Attribute
		//$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "attribute_description WHERE language_id = '" . (int)$this->config->get('config_language_id') . "'");
		$query = $this->_getCurrentItemLanguage('attribute_description');

		foreach ($query->result_array() as $attribute) {
			$this->db->set('attribute_id', (int)$attribute['attribute_id']);
			$this->db->set('language_id', (int)$language_id);
			$this->db->set('name', ($attribute['name']));
			$this->db->insert('attribute_description');
			//$this->db->query("INSERT INTO " . DB_PREFIX . "attribute_description SET attribute_id = '" . (int)$attribute['attribute_id'] . "', language_id = '" . (int)$language_id . "', name = '" . ($attribute['name']) . "'");
		}

		// Attribute Group
		$query = $this->_getCurrentItemLanguage('attribute_group_description');
		//$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "attribute_group_description WHERE language_id = '" . (int)$this->config->get('config_language_id') . "'");

		foreach ($query->result_array() as $attribute_group) {
			$this->db->set('attribute_group_id', (int)$attribute_group['attribute_group_id']);
			$this->db->set('language_id', (int)$language_id);
			$this->db->set('name', ($attribute_group['name']));
			$this->db->insert('attribute_group_description');
			//$this->db->query("INSERT INTO " . DB_PREFIX . "attribute_group_description SET attribute_group_id = '" . (int)$attribute_group['attribute_group_id'] . "', language_id = '" . (int)$language_id . "', name = '" . ($attribute_group['name']) . "'");
		}

		$this->cache->delete('attribute');

		// Banner
		$query = $this->_getCurrentItemLanguage('banner_image_description');

		//$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "banner_image_description WHERE language_id = '" . (int)$this->config->get('config_language_id') . "'");

		foreach ($query->result_array() as $banner_image) {
			$this->db->set('banner_image_id', (int)$banner_image['banner_image_id']);
			$this->db->set('banner_id', (int)$banner_image['banner_id']);
			$this->db->set('language_id', (int)$language_id);
			$this->db->set('title', ($banner_image['title']));
			$this->db->insert('banner_image_description');

			//$this->db->query("INSERT INTO " . DB_PREFIX . "banner_image_description SET banner_image_id = '" . (int)$banner_image['banner_image_id'] . "', banner_id = '" . (int)$banner_image['banner_id'] . "', language_id = '" . (int)$language_id . "', title = '" . ($banner_image['title']) . "'");
		}

		$this->cache->delete('banner');

		// Category
		$query = $this->_getCurrentItemLanguage('category_description');
		//$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "category_description WHERE language_id = '" . (int)$this->config->get('config_language_id') . "'");

		foreach ($query->result_array() as $category) {
			$this->db->set('language_id', (int)$language_id);
			$this->db->set('category_id', (int)$category['category_id']);
			$this->db->set('name', ($category['name']));
			$this->db->set('meta_description', ($category['meta_description']));
			$this->db->set('meta_keyword', ($category['meta_keyword']));
			$this->db->set('description', ($category['description']));
			$this->db->insert('category_description');
			//$this->db->query("INSERT INTO " . DB_PREFIX . "category_description SET category_id = '" . (int)$category['category_id'] . "', language_id = '" . (int)$language_id . "', name = '" . ($category['name']) . "', meta_description = '" . ($category['meta_description']) . "', meta_keyword = '" . ($category['meta_keyword']) . "', description = '" . ($category['description']) . "'");
		}

		$this->cache->delete('category');

		// Customer Group
		$query = $this->_getCurrentItemLanguage('customer_group_description');

		//$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "customer_group_description WHERE language_id = '" . (int)$this->config->get('config_language_id') . "'");

		foreach ($query->result_array() as $customer_group) {
			$this->db->set('language_id', (int)$language_id);
			$this->db->set('customer_group_id', (int)$customer_group['customer_group_id']);
			$this->db->set('name', ($customer_group['name']));
			$this->db->set('description', ($customer_group['description']));
			$this->db->insert('customer_group_description');
			//$this->db->query("INSERT INTO " . DB_PREFIX . "customer_group_description SET customer_group_id = '" . (int)$customer_group['customer_group_id'] . "', language_id = '" . (int)$language_id . "', name = '" . ($customer_group['name']) . "', description = '" . ($customer_group['description']) . "'");
		}

		// Custom Field
		// Customer Group
		$query = $this->_getCurrentItemLanguage('custom_field_description');

		//$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "custom_field_description WHERE language_id = '" . (int)$this->config->get('config_language_id') . "'");

		foreach ($query->result_array() as $custom_field) {
			$this->db->set('custom_field_id', (int)$custom_field['custom_field_id']);
			$this->db->set('language_id', (int)$language_id);
			$this->db->set('name', ($custom_field['name']));
			$this->db->insert('custom_field_description');
			//$this->db->query("INSERT INTO " . DB_PREFIX . "custom_field_description SET custom_field_id = '" . (int)$custom_field['custom_field_id'] . "', language_id = '" . (int)$language_id . "', name = '" . ($custom_field['name']) . "'");
		}

		// Custom Field Value
		$query = $this->_getCurrentItemLanguage('custom_field_value_description');

		//$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "custom_field_value_description WHERE language_id = '" . (int)$this->config->get('config_language_id') . "'");

		foreach ($query->result_array() as $custom_field_value) {
			$this->db->set('language_id', (int)$language_id);
			$this->db->set('custom_field_value_id', (int)$custom_field_value['custom_field_value_id']);
			$this->db->set('custom_field_id', (int)$custom_field_value['custom_field_id']);
			$this->db->set('name', ($custom_field_value['name']));
			$this->db->insert('custom_field_value_description');
			//$this->db->query("INSERT INTO " . DB_PREFIX . "custom_field_value_description SET custom_field_value_id = '" . (int)$custom_field_value['custom_field_value_id'] . "', language_id = '" . (int)$language_id . "', custom_field_id = '" . (int)$custom_field_value['custom_field_id'] . "', name = '" . ($custom_field_value['name']) . "'");
		}

		// Download
		$query = $this->_getCurrentItemLanguage('download_description');
		//$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "download_description WHERE language_id = '" . (int)$this->config->get('config_language_id') . "'");

		foreach ($query->result_array() as $download) {
			$this->db->set('language_id', (int)$language_id);
			$this->db->set('download_id', (int)$download['download_id']);
			$this->db->set('name', ($download['name']));
			$this->db->insert('download_description');
			//$this->db->query("INSERT INTO " . DB_PREFIX . "download_description SET download_id = '" . (int)$download['download_id'] . "', language_id = '" . (int)$language_id . "', name = '" . ($download['name']) . "'");
		}

		// Filter
		$query = $this->_getCurrentItemLanguage('filter_description');

		//$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "filter_description WHERE language_id = '" . (int)$this->config->get('config_language_id') . "'");

		foreach ($query->result_array() as $filter) {
			$this->db->set('language_id', (int)$language_id);
			$this->db->set('filter_id', (int)$filter['filter_id']);
			$this->db->set('filter_group_id', (int)$filter['filter_group_id']);
			$this->db->set('name', ($filter['name']));
			$this->db->insert('filter_description');
			//$this->db->query("INSERT INTO " . DB_PREFIX . "filter_description SET filter_id = '" . (int)$filter['filter_id'] . "', language_id = '" . (int)$language_id . "', filter_group_id = '" . (int)$filter['filter_group_id'] . "', name = '" . ($filter['name']) . "'");
		}

		// Filter Group
		$query = $this->_getCurrentItemLanguage('filter_group_description');

		//$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "filter_group_description WHERE language_id = '" . (int)$this->config->get('config_language_id') . "'");

		foreach ($query->result_array() as $filter_group) {
			$this->db->set('language_id', (int)$language_id);
			$this->db->set('filter_group_id', (int)$filter_group['filter_group_id']);
			$this->db->set('name', ($filter_group['name']));
			$this->db->insert('filter_group_description');
			//$this->db->query("INSERT INTO " . DB_PREFIX . "filter_group_description SET filter_group_id = '" . (int)$filter_group['filter_group_id'] . "', language_id = '" . (int)$language_id . "', name = '" . ($filter_group['name']) . "'");
		}

		// Information
		$query = $this->_getCurrentItemLanguage('information_description');

		//$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "information_description WHERE language_id = '" . (int)$this->config->get('config_language_id') . "'");

		foreach ($query->result_array() as $information) {
			$this->db->set('language_id', (int)$language_id);
			$this->db->set('information_id', (int)$information['information_id']);
			$this->db->set('title', ($information['title']));
			$this->db->set('description', ($information['description']));
			$this->db->insert('information_description');

			//$this->db->query("INSERT INTO " . DB_PREFIX . "information_description SET information_id = '" . (int)$information['information_id'] . "', language_id = '" . (int)$language_id . "', title = '" . ($information['title']) . "', description = '" . ($information['description']) . "'");
		}

		$this->cache->delete('information');

		// Length
		$query = $this->_getCurrentItemLanguage('length_class_description');

		//$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "length_class_description WHERE language_id = '" . (int)$this->config->get('config_language_id') . "'");

		foreach ($query->result_array() as $length) {
			$this->db->set('language_id', (int)$language_id);
			$this->db->set('length_class_id', (int)$length['length_class_id']);
			$this->db->set('title', ($length['title']));
			$this->db->set('unit', ($length['unit']));
			$this->db->insert('length_class_description');

			//$this->db->query("INSERT INTO " . DB_PREFIX . "length_class_description SET length_class_id = '" . (int)$length['length_class_id'] . "', language_id = '" . (int)$language_id . "', title = '" . ($length['title']) . "', unit = '" . ($length['unit']) . "'");
		}

		$this->cache->delete('length_class');

		// Option
		$query = $this->_getCurrentItemLanguage('option_description');

		//$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "option_description WHERE language_id = '" . (int)$this->config->get('config_language_id') . "'");

		foreach ($query->result_array() as $option) {
			$this->db->set('language_id', (int)$language_id);
			$this->db->set('option_id', (int)$option['option_id']);
			$this->db->set('name', ($option['name']));
			$this->db->insert('option_description');
			//$this->db->query("INSERT INTO " . DB_PREFIX . "option_description SET option_id = '" . (int)$option['option_id'] . "', language_id = '" . (int)$language_id . "', name = '" . ($option['name']) . "'");
		}

		// Option Value
		$query = $this->_getCurrentItemLanguage('option_value_description');
		//$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "option_value_description WHERE language_id = '" . (int)$this->config->get('config_language_id') . "'");

		foreach ($query->result_array() as $option_value) {
			$this->db->set('language_id', (int)$language_id);
			$this->db->set('option_value_id', (int)$option_value['option_value_id']);
			$this->db->set('option_id', (int)$option_value['option_id']);
			$this->db->set('name', ($option_value['name']));
			$this->db->insert('option_value_description');
			//$this->db->query("INSERT INTO " . DB_PREFIX . "option_value_description SET option_value_id = '" . (int)$option_value['option_value_id'] . "', language_id = '" . (int)$language_id . "', option_id = '" . (int)$option_value['option_id'] . "', name = '" . ($option_value['name']) . "'");
		}

		// Order Status
		$query = $this->_getCurrentItemLanguage('order_status');
		//$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "order_status WHERE language_id = '" . (int)$this->config->get('config_language_id') . "'");

		foreach ($query->result_array() as $order_status) {
			$this->db->set('language_id', (int)$language_id);
			$this->db->set('order_status_id', (int)$order_status['order_status_id']);
			$this->db->set('name', ($order_status['name']));
			$this->db->insert('order_status');
			//$this->db->query("INSERT INTO " . DB_PREFIX . "order_status SET order_status_id = '" . (int)$order_status['order_status_id'] . "', language_id = '" . (int)$language_id . "', name = '" . ($order_status['name']) . "'");
		}

		$this->cache->delete('order_status');

		// Product
		$query = $this->_getCurrentItemLanguage('product_description');

		//$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "product_description WHERE language_id = '" . (int)$this->config->get('config_language_id') . "'");

		foreach ($query->result_array() as $product) {
			$this->db->set('language_id', (int)$language_id);
			$this->db->set('product_id', (int)$product['product_id']);
			$this->db->set('name', ($product['name']));
			$this->db->set('meta_description', ($product['meta_description']));
			$this->db->set('meta_keyword', ($product['meta_keyword']));
			$this->db->set('description', ($product['description']));
			$this->db->set('tag', ($product['tag']));
			$this->db->insert('product_description');
			//$this->db->query("INSERT INTO " . DB_PREFIX . "product_description SET product_id = '" . (int)$product['product_id'] . "', language_id = '" . (int)$language_id . "', name = '" . ($product['name']) . "', meta_description = '" . ($product['meta_description']) . "', meta_keyword = '" . ($product['meta_keyword']) . "', description = '" . ($product['description']) . "', tag = '" . ($product['tag']) . "'");
		}

		$this->cache->delete('product');

		// Product Attribute
		$query = $this->_getCurrentItemLanguage('product_attribute');

		//$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "product_attribute WHERE language_id = '" . (int)$this->config->get('config_language_id') . "'");

		foreach ($query->result_array() as $product_attribute) {
			$this->db->set('language_id', (int)$language_id);
			$this->db->set('product_id', (int)$product_attribute['product_id']);
			$this->db->set('attribute_id', (int)$product_attribute['attribute_id']);
			$this->db->set('text', ($product_attribute['text']));
			$this->db->insert('product_attribute');

			//$this->db->query("INSERT INTO " . DB_PREFIX . "product_attribute SET product_id = '" . (int)$product_attribute['product_id'] . "', attribute_id = '" . (int)$product_attribute['attribute_id'] . "', language_id = '" . (int)$language_id . "', text = '" . ($product_attribute['text']) . "'");
		}

		// Return Action
		$query = $this->_getCurrentItemLanguage('return_action');

		//$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "return_action WHERE language_id = '" . (int)$this->config->get('config_language_id') . "'");

		foreach ($query->result_array() as $return_action) {
			$this->db->set('language_id', (int)$language_id);
			$this->db->set('return_action_id', (int)$return_action['return_action_id']);
			$this->db->set('name', ($return_action['name']));
			$this->db->insert('return_action');
			//$this->db->query("INSERT INTO " . DB_PREFIX . "return_action SET return_action_id = '" . (int)$return_action['return_action_id'] . "', language_id = '" . (int)$language_id . "', name = '" . ($return_action['name']) . "'");
		}

		// Return Reason
		$query = $this->_getCurrentItemLanguage('return_reason');

		//$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "return_reason WHERE language_id = '" . (int)$this->config->get('config_language_id') . "'");

		foreach ($query->result_array() as $return_reason) {
			$this->db->set('language_id', (int)$language_id);
			$this->db->set('return_reason_id', (int)$return_reason['return_reason_id']);
			$this->db->set('name', ($return_reason['name']));
			$this->db->insert('return_reason');
			//$this->db->query("INSERT INTO " . DB_PREFIX . "return_reason SET return_reason_id = '" . (int)$return_reason['return_reason_id'] . "', language_id = '" . (int)$language_id . "', name = '" . ($return_reason['name']) . "'");
		}

		// Return Status
		$query = $this->_getCurrentItemLanguage('return_status');
		//$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "return_status WHERE language_id = '" . (int)$this->config->get('config_language_id') . "'");

		foreach ($query->result_array() as $return_status) {
			$this->db->set('language_id', (int)$language_id);
			$this->db->set('return_status_id', (int)$return_status['return_status_id']);
			$this->db->set('name', ($return_status['name']));
			$this->db->insert('return_status');
			//$this->db->query("INSERT INTO " . DB_PREFIX . "return_status SET return_status_id = '" . (int)$return_status['return_status_id'] . "', language_id = '" . (int)$language_id . "', name = '" . ($return_status['name']) . "'");
		}

		// Stock Status
		$query = $this->_getCurrentItemLanguage('stock_status');
		//$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "stock_status WHERE language_id = '" . (int)$this->config->get('config_language_id') . "'");

		foreach ($query->result_array() as $stock_status) {
			$this->db->set('language_id', (int)$language_id);
			$this->db->set('stock_status_id', (int)$stock_status['stock_status_id']);
			$this->db->set('name', ($stock_status['name']));
			$this->db->insert('stock_status');
			//$this->db->query("INSERT INTO " . DB_PREFIX . "stock_status SET stock_status_id = '" . (int)$stock_status['stock_status_id'] . "', language_id = '" . (int)$language_id . "', name = '" . ($stock_status['name']) . "'");
		}

		$this->cache->delete('stock_status');

		// Voucher Theme
		$query = $this->_getCurrentItemLanguage('voucher_theme_description');
		//$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "voucher_theme_description WHERE language_id = '" . (int)$this->config->get('config_language_id') . "'");

		foreach ($query->result_array() as $voucher_theme) {
			$this->db->set('language_id', (int)$language_id);
			$this->db->set('voucher_theme_id', (int)$voucher_theme['voucher_theme_id']);
			$this->db->set('name', ($voucher_theme['name']));
			$this->db->insert('voucher_theme_description');

			//$this->db->query("INSERT INTO " . DB_PREFIX . "voucher_theme_description SET voucher_theme_id = '" . (int)$voucher_theme['voucher_theme_id'] . "', language_id = '" . (int)$language_id . "', name = '" . ($voucher_theme['name']) . "'");
		}

		$this->cache->delete('voucher_theme');

		// Weight Class
		$query = $this->_getCurrentItemLanguage('weight_class_description');

		//$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "weight_class_description WHERE language_id = '" . (int)$this->config->get('config_language_id') . "'");

		foreach ($query->result_array() as $weight_class) {
			$this->db->set('language_id', (int)$language_id);
			$this->db->set('weight_class_id', (int)$weight_class['weight_class_id']);
			$this->db->set('title', ($weight_class['title']));
			$this->db->set('unit', ($weight_class['unit']));
			$this->db->insert('weight_class_description');
			//$this->db->query("INSERT INTO " . DB_PREFIX . "weight_class_description SET weight_class_id = '" . (int)$weight_class['weight_class_id'] . "', language_id = '" . (int)$language_id . "', title = '" . ($weight_class['title']) . "', unit = '" . ($weight_class['unit']) . "'");
		}

		$this->cache->delete('weight_class');

		// Profiles
		$query = $this->_getCurrentItemLanguage('recurring_description');
		//$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "recurring_description WHERE language_id = '" . (int)$this->config->get('config_language_id') . "'");

		foreach ($query->result_array() as $recurring) {
			$this->db->set('language_id', (int)$language_id);
			$this->db->set('recurring_id', (int)$recurring['recurring_id']);
			$this->db->set('name', ($recurring['name']));
			$this->db->insert('recurring_description');
			//$this->db->query("INSERT INTO " . DB_PREFIX . "recurring_description SET recurring_id = '" . (int)$recurring['recurring_id'] . "', language_id = '" . (int)$language_id . "', name = '" . ($recurring['name']));
		}
	}

	private function _getCurrentItemLanguage($table) {
		$this->db->select('*');
		$this->db->from($table);
		$this->db->where($this->primaryKey, (int)$this->config->get('config_language_id'));
		return $this->db->get();
	}

	public function editLanguage($language_id, $data) {

		$data = $this->initData($data);
		$this->db->where($this->primaryKey, (int)$language_id);
		$this->db->update($this->table, $data);
		$this->cache->delete('language');
	}

	public function editStatus($language_id, $status) {

		$this->db->set('status', (int) $status);
		$this->db->where($this->primaryKey, (int) $language_id);
		$this->db->update($this->table);
	}

	public function deleteLanguage($language_id) {
		$this->db->where($this->primaryKey, (int)$language_id);
		$tables = array($this->table, 'attribute_description', 'attribute_group_description', 'banner_image_description', 'category_description', 'customer_group_description', 'download_description', 'filter_description', 'filter_group_description', 'information_description', 'length_class_description', 'option_description', 'option_value_description', 'order_status', 'product_attribute', 'product_description', 'return_action', 'return_reason', 'return_status', 'stock_status', 'voucher_theme_description', 'weight_class_description', 'recurring_description');
		$this->db->delete($tables);

		// Clean cache
		$this->cache->delete('language');
		$this->cache->delete('category');
		$this->cache->delete('information');
		$this->cache->delete('length_class');
		$this->cache->delete('product');
		$this->cache->delete('return_action');
		$this->cache->delete('return_reason');
		$this->cache->delete('return_status');
		$this->cache->delete('stock_status');
		$this->cache->delete('voucher_theme');
		$this->cache->delete('weight_class');		


		/*
		$this->db->query("DELETE FROM " . DB_PREFIX . "language WHERE language_id = '" . (int)$language_id . "'");

		$this->cache->delete('language');

		$this->db->query("DELETE FROM " . DB_PREFIX . "attribute_description WHERE language_id = '" . (int)$language_id . "'");
		$this->db->query("DELETE FROM " . DB_PREFIX . "attribute_group_description WHERE language_id = '" . (int)$language_id . "'");

		$this->db->query("DELETE FROM " . DB_PREFIX . "banner_image_description WHERE language_id = '" . (int)$language_id . "'");

		$this->db->query("DELETE FROM " . DB_PREFIX . "category_description WHERE language_id = '" . (int)$language_id . "'");

		$this->cache->delete('category');

		$this->db->query("DELETE FROM " . DB_PREFIX . "customer_group_description WHERE language_id = '" . (int)$language_id . "'");
		$this->db->query("DELETE FROM " . DB_PREFIX . "download_description WHERE language_id = '" . (int)$language_id . "'");
		$this->db->query("DELETE FROM " . DB_PREFIX . "filter_description WHERE language_id = '" . (int)$language_id . "'");
		$this->db->query("DELETE FROM " . DB_PREFIX . "filter_group_description WHERE language_id = '" . (int)$language_id . "'");
		$this->db->query("DELETE FROM " . DB_PREFIX . "information_description WHERE language_id = '" . (int)$language_id . "'");

		$this->cache->delete('information');

		$this->db->query("DELETE FROM " . DB_PREFIX . "length_class_description WHERE language_id = '" . (int)$language_id . "'");

		$this->cache->delete('length_class');

		$this->db->query("DELETE FROM " . DB_PREFIX . "option_description WHERE language_id = '" . (int)$language_id . "'");
		$this->db->query("DELETE FROM " . DB_PREFIX . "option_value_description WHERE language_id = '" . (int)$language_id . "'");
		$this->db->query("DELETE FROM " . DB_PREFIX . "order_status WHERE language_id = '" . (int)$language_id . "'");

		$this->cache->delete('order_status');

		$this->db->query("DELETE FROM " . DB_PREFIX . "product_attribute WHERE language_id = '" . (int)$language_id . "'");
		$this->db->query("DELETE FROM " . DB_PREFIX . "product_description WHERE language_id = '" . (int)$language_id . "'");

		$this->cache->delete('product');

		$this->db->query("DELETE FROM " . DB_PREFIX . "return_action WHERE language_id = '" . (int)$language_id . "'");

		$this->cache->delete('return_action');

		$this->db->query("DELETE FROM " . DB_PREFIX . "return_reason WHERE language_id = '" . (int)$language_id . "'");

		$this->cache->delete('return_reason');

		$this->db->query("DELETE FROM " . DB_PREFIX . "return_status WHERE language_id = '" . (int)$language_id . "'");

		$this->cache->delete('return_status');

		$this->db->query("DELETE FROM " . DB_PREFIX . "stock_status WHERE language_id = '" . (int)$language_id . "'");

		$this->cache->delete('stock_status');

		$this->db->query("DELETE FROM " . DB_PREFIX . "voucher_theme_description WHERE language_id = '" . (int)$language_id . "'");

		$this->cache->delete('voucher_theme');

		$this->db->query("DELETE FROM " . DB_PREFIX . "weight_class_description WHERE language_id = '" . (int)$language_id . "'");

		$this->cache->delete('weight_class');

		$this->db->query("DELETE FROM " . DB_PREFIX . "recurring_description WHERE language_id = '" . (int)$language_id . "'");
		*/
	}

	public function getLanguage($language_id) {
		$this->db->distinct();
		$this->db->from($this->table);
		$this->db->where($this->primaryKey, (int)$language_id);
		return $this->db->get()->row_array();
		//$query = $this->db->query("SELECT DISTINCT * FROM " . DB_PREFIX . "language WHERE language_id = '" . (int)$language_id . "'");

		//return $query->row;
	}

	public function getLanguages($data = array()) {

		if ($data) {
			$this->db->select('*');
			$this->db->from($this->table);

			$sort_data = array(
				'name',
				'code',
				'sort_order',
				'status'
			);

			$order = 'ASC';
			if (isset($data['order']) && ($data['order'] == 'DESC')) {
				$order = 'DESC';
			}

			if (isset($data['sort']) && in_array($data['sort'], $sort_data)) {
				//$sql .= " ORDER BY " . $data['sort'];
				$this->db->order_by($data['sort'], $order);
			} else {
				//$sql .= " ORDER BY sort_order, name";
				$this->db->order_by('name', $order);
				$this->db->order_by('sort_order', $order);
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

			//$query = $this->db->query($sql);

			//return $query->rows;
			return $this->db->get()->result_array();
		}
		else {
			$language_data = $this->cache->get('language');

			if (!$language_data) {
				$language_data = array();

				$this->db->select('*');
				$this->db->from($this->table);
				$this->db->order_by('sort_order');
				$this->db->order_by('name');
				$query = $this->db->get();
				//$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "language ORDER BY sort_order, name");

				foreach ($query->result_array() as $result) {
					$language_data[$result['code']] = array(
						'language_id' => $result['language_id'],
						'name'        => $result['name'],
						'code'        => $result['code'],
						'locale'      => $result['locale'],
						'image'       => $result['image'],
						'directory'   => $result['directory'],
						'sort_order'  => $result['sort_order'],
						'status'      => $result['status']
					);
				}

				$this->cache->set('language', $language_data);
			}

			return $language_data;
		}
	}

	public function getTotalLanguages() {
		$this->db->select('COUNT(*) AS `total`');
		$query = $this->db->get($this->table)->row_array();
		return $query['total'];
		//$query = $this->db->query("SELECT COUNT(*) AS total FROM " . DB_PREFIX . "language");

		//return $query->row['total'];
	}
}