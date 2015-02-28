<?php
class ModelSaleVoucherTheme extends Model {
	public $table = 'voucher_theme';
	public $primaryKey = 'voucher_theme_id';
	public $desc_table = 'voucher_theme_description';

	public function addVoucherTheme($data) {
		$this->db->set('image', $data['image']);
		$this->db->insert($this->table);

		//$this->db->query("INSERT INTO " . DB_PREFIX . "voucher_theme SET image = '" . $this->db->escape($data['image']) . "'");

		//$voucher_theme_id = $this->db->getLastId();
		$voucher_theme_id = $this->db->insert_id();

		foreach ($data['voucher_theme_description'] as $language_id => $value) {
			$this->db->set('language_id', (int)$language_id);
			$this->db->set($this->primaryKey, $voucher_theme_id);
			$this->db->set('name', $data['name']);
			$this->db->update($this->desc_table);
			//$this->db->query("INSERT INTO " . DB_PREFIX . "voucher_theme_description SET voucher_theme_id = '" . (int)$voucher_theme_id . "', language_id = '" . (int)$language_id . "', name = '" . $this->db->escape($value['name']) . "'");
		}

		$this->cache->delete('voucher_theme');

		$this->tracking->log(LOG_FUNCTION::$sale_voucher_theme, LOG_ACTION_ADD, $voucher_theme_id);
	}

	public function editVoucherTheme($voucher_theme_id, $data) {

		$this->db->set('image', $data['image']);
		$this->db->where($this->primaryKey, (int)$voucher_theme_id);
		$this->db->update($this->table);

		//$this->db->query("UPDATE " . DB_PREFIX . "voucher_theme SET image = '" . $this->db->escape($data['image']) . "' WHERE voucher_theme_id = '" . (int)$voucher_theme_id . "'");

		//$this->db->query("DELETE FROM " . DB_PREFIX . "voucher_theme_description WHERE voucher_theme_id = '" . (int)$voucher_theme_id . "'");

		foreach ($data['voucher_theme_description'] as $language_id => $value) {
			$this->db->set('language_id', (int)$language_id);
			$this->db->set($this->primaryKey, $voucher_theme_id);
			$this->db->set('name', $data['name']);
			$this->db->update($this->desc_table);
			//$this->db->query("INSERT INTO " . DB_PREFIX . "voucher_theme_description SET voucher_theme_id = '" . (int)$voucher_theme_id . "', language_id = '" . (int)$language_id . "', name = '" . $this->db->escape($value['name']) . "'");
		}

		$this->cache->delete('voucher_theme');

		$this->tracking->log(LOG_FUNCTION::$sale_voucher_theme, LOG_ACTION_MODIFY, $voucher_theme_id);
	}

	public function deleteVoucherTheme($voucher_theme_id) {
		$this->db->where($this->primaryKey, (int)$voucher_theme_id);
		$this->db->delete(array($this->table, $this->desc_table));

		//$this->db->query("DELETE FROM " . DB_PREFIX . "voucher_theme WHERE voucher_theme_id = '" . (int)$voucher_theme_id . "'");
		//$this->db->query("DELETE FROM " . DB_PREFIX . "voucher_theme_description WHERE voucher_theme_id = '" . (int)$voucher_theme_id . "'");

		$this->cache->delete('voucher_theme');

		$this->tracking->log(LOG_FUNCTION::$sale_voucher_theme, LOG_ACTION_DELETE, $voucher_theme_id);
	}

	public function getVoucherTheme($voucher_theme_id) {
		$this->db->select('*');
		$this->db->from($this->table . ' vt');
		$this->db->join($this->desc_table . ' vtd', 'vt.voucher_theme_id = vtd.voucher_theme_id', 'left');
		$this->db->where('vt.voucher_theme_id', (int)$voucher_theme_id);
		$this->db->where('language_id', (int)$this->config->get('config_language_id'));
		return $this->db->get()->row_array();

		//$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "voucher_theme vt LEFT JOIN " . DB_PREFIX . "voucher_theme_description vtd ON (vt.voucher_theme_id = vtd.voucher_theme_id) WHERE vt.voucher_theme_id = '" . (int)$voucher_theme_id . "' AND vtd.language_id = '" . (int)$this->config->get('config_language_id') . "'");

		//return $query->row;
	}

	public function getVoucherThemes($data = array()) {
		if ($data) {
			//$sql = "SELECT * FROM " . DB_PREFIX . "voucher_theme vt LEFT JOIN " . DB_PREFIX . "voucher_theme_description vtd ON (vt.voucher_theme_id = vtd.voucher_theme_id) WHERE vtd.language_id = '" . (int)$this->config->get('config_language_id') . "' ORDER BY vtd.name";
			$this->db->select('*');
			$this->db->from($this->table . ' vt');
			$this->db->join($this->desc_table . ' vtd', 'vt.voucher_theme_id = vtd.voucher_theme_id', 'left');
			$this->db->where('language_id', (int)$this->config->get('config_language_id'));

			$order = 'ASC';

			if (isset($data['order']) && ($data['order'] == 'DESC')) {
				$order = 'DESC';
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
			$voucher_theme_data = $this->cache->get('voucher_theme.' . (int)$this->config->get('config_language_id'));

			if (!$voucher_theme_data) {
				//$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "voucher_theme vt LEFT JOIN " . DB_PREFIX . "voucher_theme_description vtd ON (vt.voucher_theme_id = vtd.voucher_theme_id) WHERE vtd.language_id = '" . (int)$this->config->get('config_language_id') . "' ORDER BY vtd.name");
				$this->db->select('*');
				$this->db->from($this->table . ' vt');
				$this->db->join($this->desc_table . ' vtd', 'vt.voucher_theme_id = vtd.voucher_theme_id', 'left');
				$this->db->order_by('vtd.name', 'ASC');
				$this->db->where('language_id', (int)$this->config->get('config_language_id'));

				$voucher_theme_data = $this->db->get()->result_array();

				$this->cache->set('voucher_theme.' . (int)$this->config->get('config_language_id'), $voucher_theme_data);
			}

			return $voucher_theme_data;
		}
	}

	public function getVoucherThemeDescriptions($voucher_theme_id) {
		$voucher_theme_data = array();

		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "voucher_theme_description WHERE voucher_theme_id = '" . (int)$voucher_theme_id . "'");

		foreach ($query->rows as $result) {
			$voucher_theme_data[$result['language_id']] = array('name' => $result['name']);
		}

		return $voucher_theme_data;
	}

	public function getTotalVoucherThemes() {
		$query = $this->db->query("SELECT COUNT(*) AS total FROM " . DB_PREFIX . "voucher_theme");

		return $query->row['total'];
	}
}