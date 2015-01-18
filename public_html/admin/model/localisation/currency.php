<?php
class ModelLocalisationCurrency extends Model {
	public $table = 'currency';
	public $primaryKey = 'currency_id';
	public $fields = array('currency_id', 'title', 'code', 'symbol_left', 'symbol_right', 'decimal_place', 'value', 'status', 'date_modified');
	
	public function addCurrency($data) {
		$data = $this->initData($data);
		$this->db->set('date_modified', 'NOW()', FALSE);
		$this->db->insert($this->table, $data);
		//$this->db->query("INSERT INTO " . DB_PREFIX . "currency SET title = '" . $this->db->escape($data['title']) . "', code = '" . $this->db->escape($data['code']) . "', symbol_left = '" . $this->db->escape($data['symbol_left']) . "', symbol_right = '" . $this->db->escape($data['symbol_right']) . "', decimal_place = '" . $this->db->escape($data['decimal_place']) . "', value = '" . $this->db->escape($data['value']) . "', status = '" . (int)$data['status'] . "', date_modified = NOW()");

		if ($this->config->get('config_currency_auto')) {
			$this->refresh(true);
		}

		$this->cache->delete('currency');
	}

	public function editCurrency($currency_id, $data) {

		$data = $this->initData($data);
		$this->db->where($this->primaryKey, (int)$currency_id);
		$this->db->set('date_modified', 'NOW()', FALSE);
		$this->db->update($this->table, $data);

		$this->cache->delete('currency');
	}

	public function editStatus($language_id, $status) {

		$this->db->set('status', (int) $status);
		$this->db->where($this->primaryKey, (int) $language_id);
		$this->db->update($this->table);
	}

	public function deleteCurrency($currency_id) {
		$this->db->where($this->primaryKey, (int)$currency_id);
		$this->db->delete($this->table);
		//$this->db->query("DELETE FROM " . DB_PREFIX . "currency WHERE currency_id = '" . (int)$currency_id . "'");

		$this->cache->delete('currency');
	}

	public function getCurrency($currency_id) {
		$this->db->distinct();
		$this->db->where($this->primaryKey, (int)$currency_id);
		$this->db->from($this->table);
		return $this->db->get()->row_array();
		//$query = $this->db->query("SELECT DISTINCT * FROM " . DB_PREFIX . "currency WHERE currency_id = '" . (int)$currency_id . "'");

		//return $query->row;
	}

	public function getCurrencyByCode($currency) {
		$this->db->distinct();
		$this->db->where('code', $this->db->escape($currency));
		$this->db->from($this->table);
		//var_dump($this->db->last_query);
		return $this->db->get()->row_array();
		//$query = $this->db->query("SELECT DISTINCT * FROM " . DB_PREFIX . "currency WHERE code = '" . $this->db->escape($currency) . "'");

		//return $query->row;
	}

	public function getCurrencies($data = array()) {

		if ($data) {
			$this->db->select('*');
			$this->db->from($this->table);

			$sort_data = array(
				'title',
				'code',
				'value',
				'date_modified',
				'status'
			);
			
			$order = 'ASC';
			if (isset($data['order']) && ($data['order'] == 'DESC')) {
				$order = 'DESC';
			}

			if (isset($data['sort']) && in_array($data['sort'], $sort_data)) {
				$this->db->order_by($data['sort'], $order);
			} else {
				$this->db->order_by('title', $order);
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
		} else {
			$currency_data = $this->cache->get('currency');

			if (!$currency_data) {
				$currency_data = array();

				//$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "currency ORDER BY title ASC");
				$this->db->select('*');
				$this->db->from($this->table);
				$this->db->order_by('title', 'ASC');
				$query = $this->db->get();
				
				foreach ($query->result_array() as $result) {
					$currency_data[$result['code']] = array(
						'currency_id'   => $result['currency_id'],
						'title'         => $result['title'],
						'code'          => $result['code'],
						'symbol_left'   => $result['symbol_left'],
						'symbol_right'  => $result['symbol_right'],
						'decimal_place' => $result['decimal_place'],
						'value'         => $result['value'],
						'status'        => $result['status'],
						'date_modified' => $result['date_modified']
					);
				}

				$this->cache->set('currency', $currency_data);
			}

			return $currency_data;
		}
	}

	public function refresh($force = false) {
		if (extension_loaded('curl')) {
			$data = array();

			$this->db->select('*');
			$this->db->from($this->table);
			if ($force) {
				//$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "currency WHERE code != '" . $this->db->escape($this->config->get('config_currency')) . "'");
				$this->db->where('code !=', $this->db->escape($this->config->get('config_currency')));
			} else {
				//$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "currency WHERE code != '" . $this->db->escape($this->config->get('config_currency')) . "' AND date_modified < '" .  $this->db->escape(date('Y-m-d H:i:s', strtotime('-1 day'))) . "'");
				$this->db->where('code !=', $this->db->escape($this->config->get('config_currency')));
				$this->db->where('date_modified <', $this->db->escape(date('Y-m-d H:i:s', strtotime('-1 day'))));
			}

			$query = $this->db->get();
			foreach ($query->result_array() as $result) {
				$data[] = $this->config->get('config_currency') . $result['code'] . '=X';
			}

			$curl = curl_init();

			curl_setopt($curl, CURLOPT_URL, 'http://download.finance.yahoo.com/d/quotes.csv?s=' . implode(',', $data) . '&f=sl1&e=.csv');
			curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
			curl_setopt($curl, CURLOPT_HEADER, false);
			curl_setopt($curl, CURLOPT_CONNECTTIMEOUT, 30);
			curl_setopt($curl, CURLOPT_TIMEOUT, 30);

			$content = curl_exec($curl);

			curl_close($curl);

			$lines = explode("\n", trim($content));

			foreach ($lines as $line) {
				$currency = utf8_substr($line, 4, 3);
				$value = utf8_substr($line, 11, 6);

				if ((float)$value) {
					//$this->db->query("UPDATE " . DB_PREFIX . "currency SET value = '" . (float)$value . "', date_modified = '" .  $this->db->escape(date('Y-m-d H:i:s')) . "' WHERE code = '" . $this->db->escape($currency) . "'");
					$this->db->set('value', (float)$value);
					$this->db->set('date_modified', 'NOW()', FALSE);
					$this->db->where('code', $this->db->escape($currency));
					$this->db->update($this->table, $data);
				}
			}

			//$this->db->query("UPDATE " . DB_PREFIX . "currency SET value = '1.00000', date_modified = '" .  $this->db->escape(date('Y-m-d H:i:s')) . "' WHERE code = '" . $this->db->escape($this->config->get('config_currency')) . "'");
			$this->db->set('value', '1.00000');
			$this->db->set('date_modified', 'NOW()', FALSE);
			$this->db->where('code', $this->db->escape($this->config->get('config_currency')));
			$this->db->update($this->table);

			$this->cache->delete('currency');
		}
	}

	public function getTotalCurrencies() {
		$this->db->select('COUNT(*) AS `total`');
		$query = $this->db->get($this->table)->row_array();
		return $query['total'];
		//$query = $this->db->query("SELECT COUNT(*) AS total FROM " . DB_PREFIX . "currency");

		//return $query->row['total'];
	}
}