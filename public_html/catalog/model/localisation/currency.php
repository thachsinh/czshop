<?php

/**
 * @modified SUN
 * Class ModelLocalisationCurrency
 */
class ModelLocalisationCurrency extends Model {
	public $table = 'currency';

	/**
	 * @param $currency
	 * @return mixed
	 */
	public function getCurrencyByCode($currency)
	{
		$this->db->distinct('*')
			->from($this->table)
			->where('code', $currency);

		return $this->db->get()->row_array();
		/*
		$query = $this->db->query("SELECT DISTINCT * FROM " . DB_PREFIX . "currency WHERE code = '" . $this->db->escape($currency) . "'");
		return $query->row_array();*/
	}

	/**
	 * @return array
	 */
	public function getCurrencies()
	{
		$currency_data = $this->cache->get('currency');

		if (!$currency_data) {
			$currency_data = array();

			$this->db->select('*')
				->from($this->table)
				->order_by('title', 'ASC');

			//$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "currency ORDER BY title ASC");

			foreach ($this->db->get()->result_array() as $result) {
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