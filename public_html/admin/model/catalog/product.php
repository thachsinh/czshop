<?php
class ModelCatalogProduct extends Model {
	public $table = 'product';
	public $primaryKey = 'product_id';
	public $desc_table = 'product_description';
	public $fields = array('product_id', 'category_id', 'sku', 'upc', 'ean', 'jan', 'isbn', 'mpn', 'location', 'quantity', 'stock_status_id', 'image', 'manufacturer_id', 'shipping', 'price', 'points', 'tax_class_id', 'date_available', 'weight', 'weight_class_id', 'length', 'width', 'height', 'length_class_id', 'subtract', 'minimum', 'sort_order', 'viewed', 'date_added', 'date_modified');
	public $desc_fields = array('product_id', 'language_id', 'name', 'description', 'tag', 'meta_title', 'meta_description', 'meta_keyword');
	public $discount_table = 'product_discount';
	public $discount_fields = array('product_id', 'customer_group_id', 'quantity', 'priority', 'price', 'date_start', 'date_end');
	public $special_table = 'product_special';
	public $special_fields = array('product_id', 'customer_group_id', 'priority', 'price', 'date_start', 'date_end');
	public $image_table = 'product_image';
	public $download_table = 'product_to_download';
	public $related_table = 'product_related';
	public $reward_table = 'product_reward';
	public $reward_fields = array('product_id', 'customer_group_id', 'points');
	public $recurrings_table = 'product_recurring';



	public function addProduct($data) {
		$this->event->trigger('pre.admin.product.add', $data);

		$tmp = $this->initData($data, TRUE);
		$tmp['price'] = (float)$data['price'];
		$tmp['weight'] = (float)$data['weight'];
		$tmp['width'] = (float)$data['width'];
		$tmp['height'] = (float)$data['height'];
		$tmp['length'] = (float)$data['length'];
		$tmp['height'] = (float)$data['height'];
		$this->db->set('date_added', 'NOW()', FALSE);
		$this->db->insert($this->table, $tmp);

		//$this->db->query("INSERT INTO " . DB_PREFIX . "product SET model = '" . $this->db->escape($data['model']) . "', sku = '" . $this->db->escape($data['sku']) . "', upc = '" . $this->db->escape($data['upc']) . "', ean = '" . $this->db->escape($data['ean']) . "', jan = '" . $this->db->escape($data['jan']) . "', isbn = '" . $this->db->escape($data['isbn']) . "', mpn = '" . $this->db->escape($data['mpn']) . "', location = '" . $this->db->escape($data['location']) . "', quantity = '" . (int)$data['quantity'] . "', minimum = '" . (int)$data['minimum'] . "', subtract = '" . (int)$data['subtract'] . "', stock_status_id = '" . (int)$data['stock_status_id'] . "', date_available = '" . $this->db->escape($data['date_available']) . "', manufacturer_id = '" . (int)$data['manufacturer_id'] . "', shipping = '" . (int)$data['shipping'] . "', price = '" . (float)$data['price'] . "', points = '" . (int)$data['points'] . "', weight = '" . (float)$data['weight'] . "', weight_class_id = '" . (int)$data['weight_class_id'] . "', length = '" . (float)$data['length'] . "', width = '" . (float)$data['width'] . "', height = '" . (float)$data['height'] . "', length_class_id = '" . (int)$data['length_class_id'] . "', status = '" . (int)$data['status'] . "', tax_class_id = '" . $this->db->escape($data['tax_class_id']) . "', sort_order = '" . (int)$data['sort_order'] . "', date_added = NOW()");

		$product_id = $this->db->insert_id();

		//$product_id = $this->db->getLastId();

		if (isset($data['image'])) {
			$this->db->where($this->primaryKey, $product_id);
			$this->db->set('image', $data['image']);
			$this->db->update($this->table);

			//$this->db->query("UPDATE " . DB_PREFIX . "product SET image = '" . $this->db->escape($data['image']) . "' WHERE product_id = '" . (int)$product_id . "'");
		}

		foreach ($data['product_description'] as $language_id => $value) {
			$tmp = $this->initData($data['product_description'][$language_id], TRUE, $this->desc_fields);
			$this->db->set('language_id', (int)$language_id);
			$this->db->set($this->primaryKey, $product_id);
			$this->db->insert($this->desc_table, $tmp);

			//$this->db->query("INSERT INTO " . DB_PREFIX . "product_description SET product_id = '" . (int)$product_id . "', language_id = '" . (int)$language_id . "', name = '" . $this->db->escape($value['name']) . "', description = '" . $this->db->escape($value['description']) . "', tag = '" . $this->db->escape($value['tag']) . "', meta_title = '" . $this->db->escape($value['meta_title']) . "', meta_description = '" . $this->db->escape($value['meta_description']) . "', meta_keyword = '" . $this->db->escape($value['meta_keyword']) . "'");
		}

		/*
		if (isset($data['product_store'])) {
			foreach ($data['product_store'] as $store_id) {
				$this->db->query("INSERT INTO " . DB_PREFIX . "product_to_store SET product_id = '" . (int)$product_id . "', store_id = '" . (int)$store_id . "'");
			}
		}*/

		/*if (isset($data['product_attribute'])) {
			foreach ($data['product_attribute'] as $product_attribute) {
				if ($product_attribute['attribute_id']) {
					$this->db->query("DELETE FROM " . DB_PREFIX . "product_attribute WHERE product_id = '" . (int)$product_id . "' AND attribute_id = '" . (int)$product_attribute['attribute_id'] . "'");

					foreach ($product_attribute['product_attribute_description'] as $language_id => $product_attribute_description) {
						$this->db->query("INSERT INTO " . DB_PREFIX . "product_attribute SET product_id = '" . (int)$product_id . "', attribute_id = '" . (int)$product_attribute['attribute_id'] . "', language_id = '" . (int)$language_id . "', text = '" .  $this->db->escape($product_attribute_description['text']) . "'");
					}
				}
			}
		}*/

		/*if (isset($data['product_option'])) {
			foreach ($data['product_option'] as $product_option) {
				if ($product_option['type'] == 'select' || $product_option['type'] == 'radio' || $product_option['type'] == 'checkbox' || $product_option['type'] == 'image') {
					if (isset($product_option['product_option_value'])) {
						$this->db->query("INSERT INTO " . DB_PREFIX . "product_option SET product_id = '" . (int)$product_id . "', option_id = '" . (int)$product_option['option_id'] . "', required = '" . (int)$product_option['required'] . "'");

						$product_option_id = $this->db->insert_id();

						foreach ($product_option['product_option_value'] as $product_option_value) {
							$this->db->query("INSERT INTO " . DB_PREFIX . "product_option_value SET product_option_id = '" . (int)$product_option_id . "', product_id = '" . (int)$product_id . "', option_id = '" . (int)$product_option['option_id'] . "', option_value_id = '" . (int)$product_option_value['option_value_id'] . "', quantity = '" . (int)$product_option_value['quantity'] . "', subtract = '" . (int)$product_option_value['subtract'] . "', price = '" . (float)$product_option_value['price'] . "', price_prefix = '" . $this->db->escape($product_option_value['price_prefix']) . "', points = '" . (int)$product_option_value['points'] . "', points_prefix = '" . $this->db->escape($product_option_value['points_prefix']) . "', weight = '" . (float)$product_option_value['weight'] . "', weight_prefix = '" . $this->db->escape($product_option_value['weight_prefix']) . "'");
						}
					}
				} else {
					$this->db->query("INSERT INTO " . DB_PREFIX . "product_option SET product_id = '" . (int)$product_id . "', option_id = '" . (int)$product_option['option_id'] . "', value = '" . $this->db->escape($product_option['value']) . "', required = '" . (int)$product_option['required'] . "'");
				}
			}
		}*/

		if (isset($data['product_discount'])) {
			foreach ($data['product_discount'] as $product_discount) {
				$tmp = $this->initData($product_discount, TRUE, $this->discount_fields);
				$this->db->set($this->primaryKey, (int)$product_id);
				$this->db->insert($this->discount_table, $tmp);

				//$this->db->query("INSERT INTO " . DB_PREFIX . "product_discount SET product_id = '" . (int)$product_id . "', customer_group_id = '" . (int)$product_discount['customer_group_id'] . "', quantity = '" . (int)$product_discount['quantity'] . "', priority = '" . (int)$product_discount['priority'] . "', price = '" . (float)$product_discount['price'] . "', date_start = '" . $this->db->escape($product_discount['date_start']) . "', date_end = '" . $this->db->escape($product_discount['date_end']) . "'");
			}
		}

		if (isset($data['product_special'])) {
			foreach ($data['product_special'] as $product_special) {

				$tmp = $this->initData($product_special, TRUE, $this->special_fields);
				$this->db->set($this->primaryKey, (int)$product_id);
				$this->db->insert($this->special_table, $tmp);

				//$this->db->query("INSERT INTO " . DB_PREFIX . "product_special SET product_id = '" . (int)$product_id . "', customer_group_id = '" . (int)$product_special['customer_group_id'] . "', priority = '" . (int)$product_special['priority'] . "', price = '" . (float)$product_special['price'] . "', date_start = '" . $this->db->escape($product_special['date_start']) . "', date_end = '" . $this->db->escape($product_special['date_end']) . "'");
			}
		}

		if (isset($data['product_image'])) {
			foreach ($data['product_image'] as $product_image) {

				$tmp = $this->initData($product_image, TRUE, $this->image_fields);
				$this->db->set($this->primaryKey, (int)$product_id);
				$this->db->insert($this->image_table, $tmp);

				//$this->db->query("INSERT INTO " . DB_PREFIX . "product_image SET product_id = '" . (int)$product_id . "', image = '" . $this->db->escape($product_image['image']) . "', sort_order = '" . (int)$product_image['sort_order'] . "'");
			}
		}

		if (isset($data['product_download'])) {
			foreach ($data['product_download'] as $download_id) {
				$this->db->set($this->primaryKey, (int)$product_id);
				$this->db->set('download_id', (int)$download_id);
				$this->db->insert($this->download_table);

				//$this->db->query("INSERT INTO " . DB_PREFIX . "product_to_download SET product_id = '" . (int)$product_id . "', download_id = '" . (int)$download_id . "'");
			}
		}

		/*if (isset($data['product_category'])) {
			foreach ($data['product_category'] as $category_id) {
				$this->db->query("INSERT INTO " . DB_PREFIX . "product_to_category SET product_id = '" . (int)$product_id . "', category_id = '" . (int)$category_id . "'");
			}
		}

		if (isset($data['product_filter'])) {
			foreach ($data['product_filter'] as $filter_id) {
				$this->db->query("INSERT INTO " . DB_PREFIX . "product_filter SET product_id = '" . (int)$product_id . "', filter_id = '" . (int)$filter_id . "'");
			}
		}*/

		if (isset($data['product_related'])) {
			foreach ($data['product_related'] as $related_id) {
				$this->db->where($this->primaryKey, (int)$product_id);
				$this->db->where('related_id', (int)$related_id);
				$this->db->delete($this->related_table);

				$this->db->where($this->primaryKey, (int)$related_id);
				$this->db->where('related_id', (int)$product_id);
				$this->db->delete($this->related_table);

				$this->db->set($this->primaryKey, (int)$product_id);
				$this->db->set('related_id', (int)$related_id);
				$this->db->insert($this->related_table);

				$this->db->set($this->primaryKey, (int)$related_id);
				$this->db->set('related_id', (int)$product_id);
				$this->db->insert($this->related_table);

				//$this->db->query("DELETE FROM " . DB_PREFIX . "product_related WHERE product_id = '" . (int)$product_id . "' AND related_id = '" . (int)$related_id . "'");
				//$this->db->query("INSERT INTO " . DB_PREFIX . "product_related SET product_id = '" . (int)$product_id . "', related_id = '" . (int)$related_id . "'");
				//$this->db->query("DELETE FROM " . DB_PREFIX . "product_related WHERE product_id = '" . (int)$related_id . "' AND related_id = '" . (int)$product_id . "'");
				//$this->db->query("INSERT INTO " . DB_PREFIX . "product_related SET product_id = '" . (int)$related_id . "', related_id = '" . (int)$product_id . "'");
			}
		}

		if (isset($data['product_reward'])) {
			foreach ($data['product_reward'] as $customer_group_id => $product_reward) {
				$tmp = $this->initData($product_reward, TRUE, $this->reward_fields);
				$this->db->set('customer_group_id', (int)$customer_group_id);
				$this->db->set($this->primaryKey, (int)$product_id);
				$this->db->insert($this->reward_table, $tmp);


				//$this->db->query("INSERT INTO " . DB_PREFIX . "product_reward SET product_id = '" . (int)$product_id . "', customer_group_id = '" . (int)$customer_group_id . "', points = '" . (int)$product_reward['points'] . "'");
			}
		}

		/*if (isset($data['product_layout'])) {
			foreach ($data['product_layout'] as $store_id => $layout_id) {
				$this->db->query("INSERT INTO " . DB_PREFIX . "product_to_layout SET product_id = '" . (int)$product_id . "', store_id = '" . (int)$store_id . "', layout_id = '" . (int)$layout_id . "'");
			}
		}*/

		if (isset($data['keyword'])) {
			$this->db->set('keyword', $data['keyword']);
			$this->db->set('query', 'product_id=' . (int)$product_id);
			$this->db->insert('url_alias');

			//$this->db->query("INSERT INTO " . DB_PREFIX . "url_alias SET query = 'product_id=" . (int)$product_id . "', keyword = '" . $this->db->escape($data['keyword']) . "'");
		}

		if (isset($data['product_recurrings'])) {
			foreach ($data['product_recurrings'] as $recurring) {
				$this->db->set($this->primaryKey, (int)$product_id);
				$this->db->set('customer_group_id', (int)$recurring['customer_group_id']);
				$this->db->set('recurring_id', (int)$recurring['recurring_id']);
				$this->db->insert($this->recurrings_table);

				//$this->db->query("INSERT INTO `" . DB_PREFIX . "product_recurring` SET `product_id` = " . (int)$product_id . ", customer_group_id = " . (int)$recurring['customer_group_id'] . ", `recurring_id` = " . (int)$recurring['recurring_id']);
			}
		}

		$this->cache->delete('product');

		$this->event->trigger('post.admin.product.add', $product_id);

		return $product_id;
	}

	public function editProduct($product_id, $data) {
		$this->event->trigger('pre.admin.product.edit', $data);

		$tmp = $this->initData($data, TRUE);
		$tmp['price'] = (float)$data['price'];
		$tmp['weight'] = (float)$data['weight'];
		$tmp['width'] = (float)$data['width'];
		$tmp['height'] = (float)$data['height'];
		$tmp['length'] = (float)$data['length'];
		$tmp['height'] = (float)$data['height'];
		$this->db->set('date_modified', 'NOW()', FALSE);
		$this->db->where($this->primaryKey, (int)$product_id);
		$this->db->update($this->table, $tmp);


		//$this->db->query("UPDATE " . DB_PREFIX . "product SET model = '" . $this->db->escape($data['model']) . "', sku = '" . $this->db->escape($data['sku']) . "', upc = '" . $this->db->escape($data['upc']) . "', ean = '" . $this->db->escape($data['ean']) . "', jan = '" . $this->db->escape($data['jan']) . "', isbn = '" . $this->db->escape($data['isbn']) . "', mpn = '" . $this->db->escape($data['mpn']) . "', location = '" . $this->db->escape($data['location']) . "', quantity = '" . (int)$data['quantity'] . "', minimum = '" . (int)$data['minimum'] . "', subtract = '" . (int)$data['subtract'] . "', stock_status_id = '" . (int)$data['stock_status_id'] . "', date_available = '" . $this->db->escape($data['date_available']) . "', manufacturer_id = '" . (int)$data['manufacturer_id'] . "', shipping = '" . (int)$data['shipping'] . "', price = '" . (float)$data['price'] . "', points = '" . (int)$data['points'] . "', weight = '" . (float)$data['weight'] . "', weight_class_id = '" . (int)$data['weight_class_id'] . "', length = '" . (float)$data['length'] . "', width = '" . (float)$data['width'] . "', height = '" . (float)$data['height'] . "', length_class_id = '" . (int)$data['length_class_id'] . "', status = '" . (int)$data['status'] . "', tax_class_id = '" . $this->db->escape($data['tax_class_id']) . "', sort_order = '" . (int)$data['sort_order'] . "', date_modified = NOW() WHERE product_id = '" . (int)$product_id . "'");

		if (isset($data['image'])) {
			//$this->db->query("UPDATE " . DB_PREFIX . "product SET image = '" . $this->db->escape($data['image']) . "' WHERE product_id = '" . (int)$product_id . "'");
			$this->db->set('image', $data['image']);
			$this->db->where($this->primaryKey, (int)$product_id);
			$this->db->update($this->table);
		}

		$this->db->where($this->primaryKey, (int)$product_id);
		$this->db->delete($this->desc_table);

		//$this->db->query("DELETE FROM " . DB_PREFIX . "product_description WHERE product_id = '" . (int)$product_id . "'");

		foreach ($data['product_description'] as $language_id => $value) {
			$tmp = $this->initData($data['product_description'][$language_id], TRUE, $this->desc_fields);
			$this->db->set('language_id', (int)$language_id);
			$this->db->set($this->primaryKey, $product_id);
			$this->db->insert($this->desc_table, $tmp);

			//$this->db->query("INSERT INTO " . DB_PREFIX . "product_description SET product_id = '" . (int)$product_id . "', language_id = '" . (int)$language_id . "', name = '" . $this->db->escape($value['name']) . "', description = '" . $this->db->escape($value['description']) . "', tag = '" . $this->db->escape($value['tag']) . "', meta_title = '" . $this->db->escape($value['meta_title']) . "', meta_description = '" . $this->db->escape($value['meta_description']) . "', meta_keyword = '" . $this->db->escape($value['meta_keyword']) . "'");
		}



		//$this->db->query("DELETE FROM " . DB_PREFIX . "product_to_store WHERE product_id = '" . (int)$product_id . "'");

		/*if (isset($data['product_store'])) {
			foreach ($data['product_store'] as $store_id) {
				$this->db->query("INSERT INTO " . DB_PREFIX . "product_to_store SET product_id = '" . (int)$product_id . "', store_id = '" . (int)$store_id . "'");
			}
		}

		$this->db->query("DELETE FROM " . DB_PREFIX . "product_attribute WHERE product_id = '" . (int)$product_id . "'");

		if (!empty($data['product_attribute'])) {
			foreach ($data['product_attribute'] as $product_attribute) {
				if ($product_attribute['attribute_id']) {
					$this->db->query("DELETE FROM " . DB_PREFIX . "product_attribute WHERE product_id = '" . (int)$product_id . "' AND attribute_id = '" . (int)$product_attribute['attribute_id'] . "'");

					foreach ($product_attribute['product_attribute_description'] as $language_id => $product_attribute_description) {
						$this->db->query("INSERT INTO " . DB_PREFIX . "product_attribute SET product_id = '" . (int)$product_id . "', attribute_id = '" . (int)$product_attribute['attribute_id'] . "', language_id = '" . (int)$language_id . "', text = '" .  $this->db->escape($product_attribute_description['text']) . "'");
					}
				}
			}
		}*/

		/*$this->db->query("DELETE FROM " . DB_PREFIX . "product_option WHERE product_id = '" . (int)$product_id . "'");
		$this->db->query("DELETE FROM " . DB_PREFIX . "product_option_value WHERE product_id = '" . (int)$product_id . "'");

		if (isset($data['product_option'])) {
			foreach ($data['product_option'] as $product_option) {
				if ($product_option['type'] == 'select' || $product_option['type'] == 'radio' || $product_option['type'] == 'checkbox' || $product_option['type'] == 'image') {
					if (isset($product_option['product_option_value'])) {
						$this->db->query("INSERT INTO " . DB_PREFIX . "product_option SET product_option_id = '" . (int)$product_option['product_option_id'] . "', product_id = '" . (int)$product_id . "', option_id = '" . (int)$product_option['option_id'] . "', required = '" . (int)$product_option['required'] . "'");

						$product_option_id = $this->db->insert_id();

						foreach ($product_option['product_option_value'] as $product_option_value) {
							$this->db->query("INSERT INTO " . DB_PREFIX . "product_option_value SET product_option_value_id = '" . (int)$product_option_value['product_option_value_id'] . "', product_option_id = '" . (int)$product_option_id . "', product_id = '" . (int)$product_id . "', option_id = '" . (int)$product_option['option_id'] . "', option_value_id = '" . (int)$product_option_value['option_value_id'] . "', quantity = '" . (int)$product_option_value['quantity'] . "', subtract = '" . (int)$product_option_value['subtract'] . "', price = '" . (float)$product_option_value['price'] . "', price_prefix = '" . $this->db->escape($product_option_value['price_prefix']) . "', points = '" . (int)$product_option_value['points'] . "', points_prefix = '" . $this->db->escape($product_option_value['points_prefix']) . "', weight = '" . (float)$product_option_value['weight'] . "', weight_prefix = '" . $this->db->escape($product_option_value['weight_prefix']) . "'");
						}
					}
				} else {
					$this->db->query("INSERT INTO " . DB_PREFIX . "product_option SET product_option_id = '" . (int)$product_option['product_option_id'] . "', product_id = '" . (int)$product_id . "', option_id = '" . (int)$product_option['option_id'] . "', value = '" . $this->db->escape($product_option['value']) . "', required = '" . (int)$product_option['required'] . "'");
				}
			}
		}*/

		$this->db->where($this->primaryKey, (int)$product_id);
		$this->db->delete($this->discount_table);

		//$this->db->query("DELETE FROM " . DB_PREFIX . "product_discount WHERE product_id = '" . (int)$product_id . "'");

		if (isset($data['product_discount'])) {
			foreach ($data['product_discount'] as $product_discount) {
				//$this->db->query("INSERT INTO " . DB_PREFIX . "product_discount SET product_id = '" . (int)$product_id . "', customer_group_id = '" . (int)$product_discount['customer_group_id'] . "', quantity = '" . (int)$product_discount['quantity'] . "', priority = '" . (int)$product_discount['priority'] . "', price = '" . (float)$product_discount['price'] . "', date_start = '" . $this->db->escape($product_discount['date_start']) . "', date_end = '" . $this->db->escape($product_discount['date_end']) . "'");
				$tmp = $this->initData($product_discount, TRUE, $this->discount_fields);
				$this->db->set($this->primaryKey, (int)$product_id);
				$this->db->insert($this->discount_table, $tmp);
			}
		}

		//$this->db->query("DELETE FROM " . DB_PREFIX . "product_special WHERE product_id = '" . (int)$product_id . "'");
		$this->db->where($this->primaryKey, (int)$product_id);
		$this->db->delete($this->special_table);

		if (isset($data['product_special'])) {
			foreach ($data['product_special'] as $product_special) {
				//$this->db->query("INSERT INTO " . DB_PREFIX . "product_special SET product_id = '" . (int)$product_id . "', customer_group_id = '" . (int)$product_special['customer_group_id'] . "', priority = '" . (int)$product_special['priority'] . "', price = '" . (float)$product_special['price'] . "', date_start = '" . $this->db->escape($product_special['date_start']) . "', date_end = '" . $this->db->escape($product_special['date_end']) . "'");
				$tmp = $this->initData($product_special, TRUE, $this->special_fields);
				$this->db->set($this->primaryKey, (int)$product_id);
				$this->db->insert($this->special_table, $tmp);
			}
		}

		//$this->db->query("DELETE FROM " . DB_PREFIX . "product_image WHERE product_id = '" . (int)$product_id . "'");
		$this->db->where($this->primaryKey, (int)$product_id);
		$this->db->delete($this->image_table);

		if (isset($data['product_image'])) {
			foreach ($data['product_image'] as $product_image) {
				//$this->db->query("INSERT INTO " . DB_PREFIX . "product_image SET product_id = '" . (int)$product_id . "', image = '" . $this->db->escape($product_image['image']) . "', sort_order = '" . (int)$product_image['sort_order'] . "'");
				$tmp = $this->initData($product_image, TRUE, $this->image_fields);
				$this->db->set($this->primaryKey, (int)$product_id);
				$this->db->insert($this->image_table, $tmp);
			}
		}

		//$this->db->query("DELETE FROM " . DB_PREFIX . "product_to_download WHERE product_id = '" . (int)$product_id . "'");
		$this->db->where($this->primaryKey, (int)$product_id);
		$this->db->delete($this->download_table);

		if (isset($data['product_download'])) {
			foreach ($data['product_download'] as $download_id) {
				//$this->db->query("INSERT INTO " . DB_PREFIX . "product_to_download SET product_id = '" . (int)$product_id . "', download_id = '" . (int)$download_id . "'");
				$this->db->set($this->primaryKey, (int)$product_id);
				$this->db->set('download_id', (int)$download_id);
				$this->db->insert($this->download_table);
			}
		}

		/*$this->db->query("DELETE FROM " . DB_PREFIX . "product_to_category WHERE product_id = '" . (int)$product_id . "'");

		if (isset($data['product_category'])) {
			foreach ($data['product_category'] as $category_id) {
				$this->db->query("INSERT INTO " . DB_PREFIX . "product_to_category SET product_id = '" . (int)$product_id . "', category_id = '" . (int)$category_id . "'");
			}
		}*/

		/*$this->db->query("DELETE FROM " . DB_PREFIX . "product_filter WHERE product_id = '" . (int)$product_id . "'");

		if (isset($data['product_filter'])) {
			foreach ($data['product_filter'] as $filter_id) {
				$this->db->query("INSERT INTO " . DB_PREFIX . "product_filter SET product_id = '" . (int)$product_id . "', filter_id = '" . (int)$filter_id . "'");
			}
		}*/

		$this->db->where($this->primaryKey, (int)$product_id);
		$this->db->delete($this->related_table);

		$this->db->where('related_id', (int)$product_id);
		$this->db->delete($this->related_table);

		//$this->db->query("DELETE FROM " . DB_PREFIX . "product_related WHERE product_id = '" . (int)$product_id . "'");
		//$this->db->query("DELETE FROM " . DB_PREFIX . "product_related WHERE related_id = '" . (int)$product_id . "'");

		if (isset($data['product_related'])) {
			foreach ($data['product_related'] as $related_id) {

				$this->db->where($this->primaryKey, (int)$product_id);
				$this->db->where('related_id', (int)$related_id);
				$this->db->delete($this->related_table);

				$this->db->where($this->primaryKey, (int)$related_id);
				$this->db->where('related_id', (int)$product_id);
				$this->db->delete($this->related_table);

				$this->db->set($this->primaryKey, (int)$product_id);
				$this->db->set('related_id', (int)$related_id);
				$this->db->insert($this->related_table);

				$this->db->set($this->primaryKey, (int)$related_id);
				$this->db->set('related_id', (int)$product_id);
				$this->db->insert($this->related_table);
				/*
				$this->db->query("DELETE FROM " . DB_PREFIX . "product_related WHERE product_id = '" . (int)$product_id . "' AND related_id = '" . (int)$related_id . "'");
				$this->db->query("INSERT INTO " . DB_PREFIX . "product_related SET product_id = '" . (int)$product_id . "', related_id = '" . (int)$related_id . "'");
				$this->db->query("DELETE FROM " . DB_PREFIX . "product_related WHERE product_id = '" . (int)$related_id . "' AND related_id = '" . (int)$product_id . "'");
				$this->db->query("INSERT INTO " . DB_PREFIX . "product_related SET product_id = '" . (int)$related_id . "', related_id = '" . (int)$product_id . "'");*/
			}
		}

		$this->db->where($this->primaryKey, (int)$product_id);
		$this->db->delete($this->reward_table);

		//$this->db->query("DELETE FROM " . DB_PREFIX . "product_reward WHERE product_id = '" . (int)$product_id . "'");

		if (isset($data['product_reward'])) {
			foreach ($data['product_reward'] as $customer_group_id => $value) {
				//$this->db->query("INSERT INTO " . DB_PREFIX . "product_reward SET product_id = '" . (int)$product_id . "', customer_group_id = '" . (int)$customer_group_id . "', points = '" . (int)$value['points'] . "'");

				$tmp = $this->initData($value, TRUE, $this->reward_fields);
				$this->db->set('customer_group_id', (int)$customer_group_id);
				$this->db->set($this->primaryKey, (int)$product_id);
				$this->db->insert($this->reward_table, $tmp);
			}
		}

		/*$this->db->query("DELETE FROM " . DB_PREFIX . "product_to_layout WHERE product_id = '" . (int)$product_id . "'");

		if (isset($data['product_layout'])) {
			foreach ($data['product_layout'] as $store_id => $layout_id) {
				$this->db->query("INSERT INTO " . DB_PREFIX . "product_to_layout SET product_id = '" . (int)$product_id . "', store_id = '" . (int)$store_id . "', layout_id = '" . (int)$layout_id . "'");
			}
		}*/

		$this->db->where('query', 'product_id=' .(int)$product_id);
		$this->db->delete('url_alias');

		//$this->db->query("DELETE FROM " . DB_PREFIX . "url_alias WHERE query = 'product_id=" . (int)$product_id . "'");

		if ($data['keyword']) {
			//$this->db->query("INSERT INTO " . DB_PREFIX . "url_alias SET query = 'product_id=" . (int)$product_id . "', keyword = '" . $this->db->escape($data['keyword']) . "'");
			$this->db->set('keyword', $data['keyword']);
			$this->db->set('query', 'product_id=' . (int)$product_id);
			$this->db->insert('url_alias');
		}

		$this->db->where($this->primaryKey, (int)$product_id);
		$this->db->delete($this->recurrings_table);

		//$this->db->query("DELETE FROM `" . DB_PREFIX . "product_recurring` WHERE product_id = " . (int)$product_id);

		if (isset($data['product_recurrings'])) {
			foreach ($data['product_recurrings'] as $recurring) {
				//$this->db->query("INSERT INTO `" . DB_PREFIX . "product_recurring` SET `product_id` = " . (int)$product_id . ", customer_group_id = " . (int)$recurring['customer_group_id'] . ", `recurring_id` = " . (int)$recurring['recurring_id']);
				$this->db->set($this->primaryKey, (int)$product_id);
				$this->db->set('customer_group_id', (int)$recurring['customer_group_id']);
				$this->db->set('recurring_id', (int)$recurring['recurring_id']);
				$this->db->insert($this->recurrings_table);
			}
		}

		$this->cache->delete('product');

		$this->event->trigger('post.admin.product.edit', $product_id);
	}

	public function copyProduct($product_id) {

		$this->db->select('DISTINCT *');
		$this->db->from($this->table . ' p');
		$this->db->join($this->desc_table . ' pd', 'p.product_id = pd.product_id', 'left');
		$this->db->where('pd.language_id', (int)$this->config->get('config_language_id'));

		//$query = $this->db->query("SELECT DISTINCT * FROM " . DB_PREFIX . "product p LEFT JOIN " . DB_PREFIX . "product_description pd ON (p.product_id = pd.product_id) WHERE p.product_id = '" . (int)$product_id . "' AND pd.language_id = '" . (int)$this->config->get('config_language_id') . "'");
		$query = $this->db->get();

		if ($query->num_rows()) {
			$data = array();

			$data = $query->row_array();

			$data['sku'] = '';
			$data['upc'] = '';
			$data['viewed'] = '0';
			$data['keyword'] = '';
			$data['status'] = '0';

			$data = array_merge($data, array('product_attribute' => $this->getProductAttributes($product_id)));
			$data = array_merge($data, array('product_description' => $this->getProductDescriptions($product_id)));
			$data = array_merge($data, array('product_discount' => $this->getProductDiscounts($product_id)));
			$data = array_merge($data, array('product_filter' => $this->getProductFilters($product_id)));
			$data = array_merge($data, array('product_image' => $this->getProductImages($product_id)));
			$data = array_merge($data, array('product_option' => $this->getProductOptions($product_id)));
			$data = array_merge($data, array('product_related' => $this->getProductRelated($product_id)));
			$data = array_merge($data, array('product_reward' => $this->getProductRewards($product_id)));
			$data = array_merge($data, array('product_special' => $this->getProductSpecials($product_id)));
			$data = array_merge($data, array('product_category' => $this->getProductCategories($product_id)));
			$data = array_merge($data, array('product_download' => $this->getProductDownloads($product_id)));
			$data = array_merge($data, array('product_layout' => $this->getProductLayouts($product_id)));
			$data = array_merge($data, array('product_store' => $this->getProductStores($product_id)));
			$data = array_merge($data, array('product_recurrings' => $this->getRecurrings($product_id)));

			$this->addProduct($data);
		}
	}

	public function deleteProduct($product_id) {
		$this->event->trigger('pre.admin.product.delete', $product_id);

		$this->db->query("DELETE FROM " . DB_PREFIX . "product WHERE product_id = '" . (int)$product_id . "'");
		$this->db->query("DELETE FROM " . DB_PREFIX . "product_attribute WHERE product_id = '" . (int)$product_id . "'");
		$this->db->query("DELETE FROM " . DB_PREFIX . "product_description WHERE product_id = '" . (int)$product_id . "'");
		$this->db->query("DELETE FROM " . DB_PREFIX . "product_discount WHERE product_id = '" . (int)$product_id . "'");
		$this->db->query("DELETE FROM " . DB_PREFIX . "product_filter WHERE product_id = '" . (int)$product_id . "'");
		$this->db->query("DELETE FROM " . DB_PREFIX . "product_image WHERE product_id = '" . (int)$product_id . "'");
		$this->db->query("DELETE FROM " . DB_PREFIX . "product_option WHERE product_id = '" . (int)$product_id . "'");
		$this->db->query("DELETE FROM " . DB_PREFIX . "product_option_value WHERE product_id = '" . (int)$product_id . "'");
		$this->db->query("DELETE FROM " . DB_PREFIX . "product_related WHERE product_id = '" . (int)$product_id . "'");
		$this->db->query("DELETE FROM " . DB_PREFIX . "product_related WHERE related_id = '" . (int)$product_id . "'");
		$this->db->query("DELETE FROM " . DB_PREFIX . "product_reward WHERE product_id = '" . (int)$product_id . "'");
		$this->db->query("DELETE FROM " . DB_PREFIX . "product_special WHERE product_id = '" . (int)$product_id . "'");
		$this->db->query("DELETE FROM " . DB_PREFIX . "product_to_category WHERE product_id = '" . (int)$product_id . "'");
		$this->db->query("DELETE FROM " . DB_PREFIX . "product_to_download WHERE product_id = '" . (int)$product_id . "'");
		$this->db->query("DELETE FROM " . DB_PREFIX . "product_to_layout WHERE product_id = '" . (int)$product_id . "'");
		$this->db->query("DELETE FROM " . DB_PREFIX . "product_to_store WHERE product_id = '" . (int)$product_id . "'");
		$this->db->query("DELETE FROM " . DB_PREFIX . "review WHERE product_id = '" . (int)$product_id . "'");
		$this->db->query("DELETE FROM " . DB_PREFIX . "product_recurring WHERE product_id = " . (int)$product_id);
		$this->db->query("DELETE FROM " . DB_PREFIX . "url_alias WHERE query = 'product_id=" . (int)$product_id . "'");

		$this->cache->delete('product');

		$this->event->trigger('post.admin.product.delete', $product_id);
	}

	public function getProduct($product_id) {
		$query = $this->db->query("SELECT DISTINCT *, (SELECT keyword FROM " . DB_PREFIX . "url_alias WHERE query = 'product_id=" . (int)$product_id . "') AS keyword FROM " . DB_PREFIX . "product p LEFT JOIN " . DB_PREFIX . "product_description pd ON (p.product_id = pd.product_id) WHERE p.product_id = '" . (int)$product_id . "' AND pd.language_id = '" . (int)$this->config->get('config_language_id') . "'");

		return $query->row_array();
	}

	public function getProducts($data = array()) {
		//$sql = "SELECT * FROM " . DB_PREFIX . "product p LEFT JOIN " . DB_PREFIX . "product_description pd ON (p.product_id = pd.product_id) WHERE pd.language_id = '" . (int)$this->config->get('config_language_id') . "'";

		$this->db->select('*');
		$this->db->from($this->table . ' p');
		$this->db->join($this->desc_table . ' pd', 'p.product_id = pd.product_id', 'left');
		$this->db->where('pd.language_id', (int)$this->config->get('config_language_id'));
		//$sql = "SELECT COUNT(DISTINCT p.product_id) AS total FROM " . DB_PREFIX . "product p LEFT JOIN " . DB_PREFIX . "product_description pd ON (p.product_id = pd.product_id)";

		//$sql .= " WHERE pd.language_id = '" . (int)$this->config->get('config_language_id') . "'";

		if (!empty($data['filter_name'])) {
			//$sql .= " AND pd.name LIKE '" . $this->db->escape($data['filter_name']) . "%'";
			$this->db->like('pd.name', $data['filter_name']);
		}

		if (!empty($data['filter_model'])) {
			$this->db->like('p.model', $data['filter_model']);
			//$sql .= " AND p.model LIKE '" . $this->db->escape($data['filter_model']) . "%'";
		}

		if (isset($data['filter_price']) && !is_null($data['filter_price'])) {
			$this->db->like('p.price', $data['filter_price']);
			//$sql .= " AND p.price LIKE '" . $this->db->escape($data['filter_price']) . "%'";
		}

		if (isset($data['filter_quantity']) && !is_null($data['filter_quantity'])) {
			$this->db->where('p.quantity', $data['filter_quantity']);
			//$sql .= " AND p.quantity = '" . (int)$data['filter_quantity'] . "'";
		}

		if (isset($data['filter_status']) && !is_null($data['filter_status'])) {
			$this->db->where('p.status', (int)$data['filter_status']);

			//$sql .= " AND p.status = '" . (int)$data['filter_status'] . "'";
		}
		/*
		if (!empty($data['filter_name'])) {
			$sql .= " AND pd.name LIKE '" . $this->db->escape($data['filter_name']) . "%'";
		}

		if (!empty($data['filter_model'])) {
			$sql .= " AND p.model LIKE '" . $this->db->escape($data['filter_model']) . "%'";
		}

		if (isset($data['filter_price']) && !is_null($data['filter_price'])) {
			$sql .= " AND p.price LIKE '" . $this->db->escape($data['filter_price']) . "%'";
		}

		if (isset($data['filter_quantity']) && !is_null($data['filter_quantity'])) {
			$sql .= " AND p.quantity = '" . (int)$data['filter_quantity'] . "'";
		}

		if (isset($data['filter_status']) && !is_null($data['filter_status'])) {
			$sql .= " AND p.status = '" . (int)$data['filter_status'] . "'";
		}
		*/
		$this->db->group_by('p.product_id');
		//$sql .= " GROUP BY p.product_id";

		$sort_data = array(
			'pd.name',
			'p.model',
			'p.price',
			'p.quantity',
			'p.status',
			'p.sort_order'
		);

		$order = 'ASC';
		if (isset($data['order']) && ($data['order'] == 'DESC')) {
			$order = 'DESC';
		}

		if (isset($data['sort']) && in_array($data['sort'], $sort_data)) {
			//$sql .= " ORDER BY " . $data['sort'];
			$this->db->order_by($data['sort'] , $order);
		} else {
			$this->db->order_by('pd.name', $order);

			//$sql .= " ORDER BY pd.name";
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

		//return $query->result_array();
		return $this->db->get()->result_array();
	}

	public function getProductsByCategoryId($category_id) {
		$this->db->select('*');
		$this->db->from($this->table . ' p');
		$this->db->join($this->desc_table . ' pd', 'p.product_id = pd.product_id', 'left');
		$this->db->where('pd.language_id', (int)$this->config->get('config_language_id'));
		$this->db->where('category_id', (int)$category_id);
		$this->db->order_by('pd.name', 'ASC');
		return $this->db->get()->result_array();

		//$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "product p LEFT JOIN " . DB_PREFIX . "product_description pd ON (p.product_id = pd.product_id) LEFT JOIN " . DB_PREFIX . "product_to_category p2c ON (p.product_id = p2c.product_id) WHERE pd.language_id = '" . (int)$this->config->get('config_language_id') . "' AND p2c.category_id = '" . (int)$category_id . "' ORDER BY pd.name ASC");

		//return $query->result_array();
	}

	public function getProductDescriptions($product_id) {
		$product_description_data = array();

		$this->db->select('*');
		$this->db->from($this->desc_table);
		$this->db->where($this->primaryKey, (int)$product_id);

		//$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "product_description WHERE product_id = '" . (int)$product_id . "'");
		$query = $this->db->get();

		foreach ($query->result_array() as $result) {
			$product_description_data[$result['language_id']] = array(
				'name'             => $result['name'],
				'description'      => $result['description'],
				'meta_title'       => $result['meta_title'],
				'meta_description' => $result['meta_description'],
				'meta_keyword'     => $result['meta_keyword'],
				'tag'              => $result['tag']
			);
		}

		return $product_description_data;
	}

	/*public function getProductCategories($product_id) {
		$product_category_data = array();

		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "product_to_category WHERE product_id = '" . (int)$product_id . "'");

		foreach ($query->rows as $result) {
			$product_category_data[] = $result['category_id'];
		}

		return $product_category_data;
	}*/

	/*public function getProductFilters($product_id) {
		$product_filter_data = array();

		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "product_filter WHERE product_id = '" . (int)$product_id . "'");

		foreach ($query->rows as $result) {
			$product_filter_data[] = $result['filter_id'];
		}

		return $product_filter_data;
	}*/

	/*public function getProductAttributes($product_id) {
		$product_attribute_data = array();

		$product_attribute_query = $this->db->query("SELECT attribute_id FROM " . DB_PREFIX . "product_attribute WHERE product_id = '" . (int)$product_id . "' GROUP BY attribute_id");

		foreach ($product_attribute_query->rows as $product_attribute) {
			$product_attribute_description_data = array();

			$product_attribute_description_query = $this->db->query("SELECT * FROM " . DB_PREFIX . "product_attribute WHERE product_id = '" . (int)$product_id . "' AND attribute_id = '" . (int)$product_attribute['attribute_id'] . "'");

			foreach ($product_attribute_description_query->rows as $product_attribute_description) {
				$product_attribute_description_data[$product_attribute_description['language_id']] = array('text' => $product_attribute_description['text']);
			}

			$product_attribute_data[] = array(
				'attribute_id'                  => $product_attribute['attribute_id'],
				'product_attribute_description' => $product_attribute_description_data
			);
		}

		return $product_attribute_data;
	}*/

	public function getProductOptions($product_id) {
		$product_option_data = array();

		$product_option_query = $this->db->query("SELECT * FROM `" . DB_PREFIX . "product_option` po LEFT JOIN `" . DB_PREFIX . "option` o ON (po.option_id = o.option_id) LEFT JOIN `" . DB_PREFIX . "option_description` od ON (o.option_id = od.option_id) WHERE po.product_id = '" . (int)$product_id . "' AND od.language_id = '" . (int)$this->config->get('config_language_id') . "'");

		foreach ($product_option_query->result_array() as $product_option) {
			$product_option_value_data = array();

			$product_option_value_query = $this->db->query("SELECT * FROM " . DB_PREFIX . "product_option_value WHERE product_option_id = '" . (int)$product_option['product_option_id'] . "'");

			foreach ($product_option_value_query->result_array() as $product_option_value) {
				$product_option_value_data[] = array(
					'product_option_value_id' => $product_option_value['product_option_value_id'],
					'option_value_id'         => $product_option_value['option_value_id'],
					'quantity'                => $product_option_value['quantity'],
					'subtract'                => $product_option_value['subtract'],
					'price'                   => $product_option_value['price'],
					'price_prefix'            => $product_option_value['price_prefix'],
					'points'                  => $product_option_value['points'],
					'points_prefix'           => $product_option_value['points_prefix'],
					'weight'                  => $product_option_value['weight'],
					'weight_prefix'           => $product_option_value['weight_prefix']
				);
			}

			$product_option_data[] = array(
				'product_option_id'    => $product_option['product_option_id'],
				'product_option_value' => $product_option_value_data,
				'option_id'            => $product_option['option_id'],
				'name'                 => $product_option['name'],
				'type'                 => $product_option['type'],
				'value'                => $product_option['value'],
				'required'             => $product_option['required']
			);
		}

		return $product_option_data;
	}

	public function getProductImages($product_id) {
		$this->db->select('*')->from($this->image_table)->where($this->primaryKey, (int)$product_id);
		$this->db->order_by('sort_order', 'ASC');
		return $this->db->get()->result_array();
		//$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "product_image WHERE product_id = '" . (int)$product_id . "' ORDER BY sort_order ASC");

		//return $query->rows;
	}

	public function getProductDiscounts($product_id) {
		$this->db->select('*')->from($this->discount_table)->where($this->primaryKey, (int)$product_id);
		$this->db->order_by('quantity', 'ASC');
		$this->db->order_by('priority', 'ASC');
		$this->db->order_by('price', 'ASC');
		return $this->db->get()->result_array();

		//$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "product_discount WHERE product_id = '" . (int)$product_id . "' ORDER BY quantity, priority, price");

		//return $query->rows;
	}

	public function getProductSpecials($product_id) {
		$this->db->select('*')->from($this->special_table)->where($this->primaryKey, (int)$product_id);

		//$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "product_special WHERE product_id = '" . (int)$product_id . "' ORDER BY priority, price");

		//return $query->rows;
		return $this->db->get()->result_array();
	}

	public function getProductRewards($product_id) {
		$product_reward_data = array();

		$this->db->select('*')->from($this->reward_table)->where($this->primaryKey, (int)$product_id);

		//$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "product_reward WHERE product_id = '" . (int)$product_id . "'");

		foreach ($this->db->get()->result_array() as $result) {
			$product_reward_data[$result['customer_group_id']] = array('points' => $result['points']);
		}

		return $product_reward_data;
	}

	public function getProductDownloads($product_id) {
		$product_download_data = array();

		$this->db->select('download_id')->from($this->download_table)->where($this->primaryKey, (int)$product_id);

		//$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "product_to_download WHERE product_id = '" . (int)$product_id . "'");

		foreach ($this->db->get()->result_array() as $result) {
			$product_download_data[] = $result['download_id'];
		}

		return $product_download_data;
	}

	/*public function getProductStores($product_id) {
		$product_store_data = array();

		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "product_to_store WHERE product_id = '" . (int)$product_id . "'");

		foreach ($query->rows as $result) {
			$product_store_data[] = $result['store_id'];
		}

		return $product_store_data;
	}*/

	/*public function getProductLayouts($product_id) {
		$product_layout_data = array();

		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "product_to_layout WHERE product_id = '" . (int)$product_id . "'");

		foreach ($query->rows as $result) {
			$product_layout_data[$result['store_id']] = $result['layout_id'];
		}

		return $product_layout_data;
	}*/

	public function getProductRelated($product_id) {
		$product_related_data = array();

		$this->db->select('related_id')->from($this->related_table)->where($this->primaryKey, (int)$product_id);

		//$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "product_related WHERE product_id = '" . (int)$product_id . "'");

		foreach ($this->db->get()->result_array() as $result) {
			$product_related_data[] = $result['related_id'];
		}

		return $product_related_data;
	}

	public function getRecurrings($product_id) {
		$this->db->select('*')->from($this->recurrings_table)->where($this->primaryKey, (int)$product_id);

		//$query = $this->db->query("SELECT * FROM `" . DB_PREFIX . "product_recurring` WHERE product_id = '" . (int)$product_id . "'");

		//return $query->rows;
		return $this->db->get()->result_array();
	}

	public function getTotalProducts($data = array()) {
		$this->db->select('COUNT(DISTINCT p.product_id) AS total');
		$this->db->from($this->table . ' p');
		$this->db->join($this->desc_table . ' pd', 'p.product_id = pd.product_id', 'left');
		$this->db->where('pd.language_id', (int)$this->config->get('config_language_id'));
		//$sql = "SELECT COUNT(DISTINCT p.product_id) AS total FROM " . DB_PREFIX . "product p LEFT JOIN " . DB_PREFIX . "product_description pd ON (p.product_id = pd.product_id)";

		//$sql .= " WHERE pd.language_id = '" . (int)$this->config->get('config_language_id') . "'";

		if (!empty($data['filter_name'])) {
			//$sql .= " AND pd.name LIKE '" . $this->db->escape($data['filter_name']) . "%'";
			$this->db->like('pd.name', $data['filter_name']);
		}

		if (!empty($data['filter_model'])) {
			$this->db->like('p.model', $data['filter_model']);
			//$sql .= " AND p.model LIKE '" . $this->db->escape($data['filter_model']) . "%'";
		}

		if (isset($data['filter_price']) && !is_null($data['filter_price'])) {
			$this->db->like('p.price', $data['filter_price']);
			//$sql .= " AND p.price LIKE '" . $this->db->escape($data['filter_price']) . "%'";
		}

		if (isset($data['filter_quantity']) && !is_null($data['filter_quantity'])) {
			$this->db->where('p.quantity', $data['filter_quantity']);
			//$sql .= " AND p.quantity = '" . (int)$data['filter_quantity'] . "'";
		}

		if (isset($data['filter_status']) && !is_null($data['filter_status'])) {
			$this->db->where('p.status', (int)$data['filter_status']);

			//$sql .= " AND p.status = '" . (int)$data['filter_status'] . "'";
		}

		$query = $this->db->get()->row_array();
		return $query['total'];
		//$query = $this->db->query($sql);

		//return $query->row['total'];
	}

	public function getTotalProductsByTaxClassId($tax_class_id) {

		$this->db->select('COUNT(*) AS `total`');
		$this->db->where('tax_class_id', (int)tax_class_id);
		$query = $this->db->get($this->table)->row_array();
		return $query['total'];

		//$query = $this->db->query("SELECT COUNT(*) AS total FROM " . DB_PREFIX . "product WHERE tax_class_id = '" . (int)$tax_class_id . "'");

		//return $query->row['total'];
	}

	public function getTotalProductsByStockStatusId($stock_status_id) {
		$this->db->select('COUNT(*) AS `total`');
		$this->db->where('stock_status_id', (int)$stock_status_id);
		$query = $this->db->get($this->table)->row_array();
		return $query['total'];

		//$query = $this->db->query("SELECT COUNT(*) AS total FROM " . DB_PREFIX . "product WHERE stock_status_id = '" . (int)$stock_status_id . "'");

		//return $query->row['total'];
	}

	public function getTotalProductsByWeightClassId($weight_class_id) {
		$this->db->select('COUNT(*) AS `total`');
		$this->db->where('weight_class_id', (int)$weight_class_id);
		$query = $this->db->get($this->table)->row_array();
		return $query['total'];

		//$query = $this->db->query("SELECT COUNT(*) AS total FROM " . DB_PREFIX . "product WHERE weight_class_id = '" . (int)$weight_class_id . "'");

		//return $query->row['total'];
	}

	public function getTotalProductsByLengthClassId($length_class_id) {
		$this->db->select('COUNT(*) AS `total`');
		$this->db->where('length_class_id', (int)$length_class_id);
		$query = $this->db->get($this->table)->row_array();
		return $query['total'];

		//$query = $this->db->query("SELECT COUNT(*) AS total FROM " . DB_PREFIX . "product WHERE length_class_id = '" . (int)$length_class_id . "'");

		//return $query->row['total'];
	}

	public function getTotalProductsByDownloadId($download_id) {
		$this->db->select('COUNT(*) AS `total`');
		$this->db->where('download_id', (int)$download_id);
		$query = $this->db->get($this->download_table)->row_array();
		return $query['total'];

		//$query = $this->db->query("SELECT COUNT(*) AS total FROM " . DB_PREFIX . "product_to_download WHERE download_id = '" . (int)$download_id . "'");

		//return $query->row['total'];
	}

	public function getTotalProductsByManufacturerId($manufacturer_id) {
		$this->db->select('COUNT(*) AS `total`');
		$this->db->where('manufacturer_id', (int)$manufacturer_id);
		$query = $this->db->get($this->table)->row_array();
		return $query['total'];

		//$query = $this->db->query("SELECT COUNT(*) AS total FROM " . DB_PREFIX . "product WHERE manufacturer_id = '" . (int)$manufacturer_id . "'");

		//return $query->row['total'];
	}

	/*public function getTotalProductsByAttributeId($attribute_id) {
		$query = $this->db->query("SELECT COUNT(*) AS total FROM " . DB_PREFIX . "product_attribute WHERE attribute_id = '" . (int)$attribute_id . "'");

		return $query->row['total'];
	}*/

	public function getTotalProductsByOptionId($option_id) {
		$this->db->select('COUNT(*) AS `total`');
		$this->db->where('option_id', (int)$option_id);
		$query = $this->db->get($this->option_table)->row_array();
		return $query['total'];

		//$query = $this->db->query("SELECT COUNT(*) AS total FROM " . DB_PREFIX . "product_option WHERE option_id = '" . (int)$option_id . "'");

		//return $query->row['total'];
	}

	public function getTotalProductsByProfileId($recurring_id) {
		$this->db->select('COUNT(*) AS `total`');
		$this->db->where('recurring_id', (int)$recurring_id);
		$query = $this->db->get($this->recurrings_table)->row_array();
		return $query['total'];
		//$query = $this->db->query("SELECT COUNT(*) AS total FROM " . DB_PREFIX . "product_recurring WHERE recurring_id = '" . (int)$recurring_id . "'");

		//return $query->row['total'];
	}

	/*public function getTotalProductsByLayoutId($layout_id) {

		$query = $this->db->query("SELECT COUNT(*) AS total FROM " . DB_PREFIX . "product_to_layout WHERE layout_id = '" . (int)$layout_id . "'");

		return $query->row['total'];
	}*/
}