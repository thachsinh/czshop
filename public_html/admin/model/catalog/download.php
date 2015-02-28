<?php
class ModelCatalogDownload extends Model {
	public $table = '';
	public $fields = array();
	public $desc_fields = array();
	public $primaryKey = '';

	public function addDownload($data) {
		$this->event->trigger('pre.admin.download.add', $data);

		$tmp = $this->initData($data, TRUE);
		$this->db->set('date_added', 'NOW()', FALSE);
		$this->db->insert($this->table, $tmp);
		//$this->db->query("INSERT INTO " . DB_PREFIX . "download SET filename = '" . $this->db->escape($data['filename']) . "', mask = '" . $this->db->escape($data['mask']) . "', date_added = NOW()");

		//$download_id = $this->db->getLastId();
		$download_id = $this->db->insert_id();

		foreach ($data['download_description'] as $language_id => $value) {
			//$this->db->query("INSERT INTO " . DB_PREFIX . "download_description SET download_id = '" . (int)$download_id . "', language_id = '" . (int)$language_id . "', name = '" . $this->db->escape($value['name']) . "'");
			$tmp = $this->initData($data['download_description'][$language_id], TRUE, $this->desc_fields);
			$this->db->set($this->primaryKey, (int)$download_id);
			$this->db->set('language_id', (int)$language_id);
			$this->db->insert('download_description', $tmp);
		}

		$this->event->trigger('post.admin.download.add', $download_id);

		return $download_id;
	}

	public function editDownload($download_id, $data) {
		$this->event->trigger('pre.admin.download.edit', $data);

		$tmp = $this->initData($data, TRUE);
		$this->db->where($this->primaryKey, (int)$download_id);
		$this->db->update($this->table, $tmp);

		//$this->db->query("UPDATE " . DB_PREFIX . "download SET filename = '" . $this->db->escape($data['filename']) . "', mask = '" . $this->db->escape($data['mask']) . "' WHERE download_id = '" . (int)$download_id . "'");

		$this->db->where($this->primaryKey, (int)$download_id);
		$this->db->delete('download_description');

		//$this->db->query("DELETE FROM " . DB_PREFIX . "download_description WHERE download_id = '" . (int)$download_id . "'");

		foreach ($data['download_description'] as $language_id => $value) {
			//

			//$this->db->query("INSERT INTO " . DB_PREFIX . "download_description SET download_id = '" . (int)$download_id . "', language_id = '" . (int)$language_id . "', name = '" . $this->db->escape($value['name']) . "'");
		}

		$this->event->trigger('post.admin.download.edit', $download_id);
	}

	public function deleteDownload($download_id) {
		$this->event->trigger('pre.admin.download.delete', $download_id);
		$this->db->where($this->primaryKey, (int)$download_id);
		$this->db->delete(array($this->table, 'download_description'));

		/*
		$this->db->query("DELETE FROM " . DB_PREFIX . "download WHERE download_id = '" . (int)$download_id . "'");
		$this->db->query("DELETE FROM " . DB_PREFIX . "download_description WHERE download_id = '" . (int)$download_id . "'");
		*/

		$this->event->trigger('post.admin.download.delete', $download_id);
	}

	public function getDownload($download_id) {

		$this->db->distinct();
		$this->db->from($this->table . ' d');
		$this->db->join('download_description dd', 'd.download_id = dd.download_id) WHERE dd.language_id', 'left');
		$this->db->where('dd.language_id', (int)$this->config->get('config_language_id'));
		$this->db->where('d.download_id', (int)$download_id);
		return $this->db->get()->row_array();
		//$query = $this->db->query("SELECT DISTINCT * FROM " . DB_PREFIX . "download d LEFT JOIN " . DB_PREFIX . "download_description dd ON (d.download_id = dd.download_id) WHERE d.download_id = '" . (int)$download_id . "' AND dd.language_id = '" . (int)$this->config->get('config_language_id') . "'");

		//return $query->row;
	}

	public function getDownloads($data = array()) {

		$this->db->select('*');
		$this->db->from($this->table . ' d');
		$this->db->join('download_description dd', 'd.download_id = dd.download_id) WHERE dd.language_id', 'left');
		$this->db->where('dd.language_id', (int)$this->config->get('config_language_id'));

		//$sql = "SELECT * FROM " . DB_PREFIX . "download d LEFT JOIN " . DB_PREFIX . "download_description dd ON (d.download_id = dd.download_id) WHERE dd.language_id = '" . (int)$this->config->get('config_language_id') . "'";

		if (!empty($data['filter_name'])) {
			$this->db->like('dd.name', $data['filter_name']);

			//$sql .= " AND dd.name LIKE '" . $this->db->escape($data['filter_name']) . "%'";
		}

		$order =  'ASC';
		if (isset($data['order']) && ($data['order'] == 'DESC')) {
			$order = 'DESC';
		}

		$sort_data = array(
			'dd.name',
			'd.date_added'
		);

		if (isset($data['sort']) && in_array($data['sort'], $sort_data)) {
			$this->db->order_by($data['sort'], $order);
		} else {
			$this->db->order_by('dd.name', $order);
		}

		if (isset($data['start']) || isset($data['limit'])) {
			if ($data['start'] < 0) {
				$data['start'] = 0;
			}

			if ($data['limit'] < 1) {
				$data['limit'] = 20;
			}

			$this->db->limit($data['limit'], $data['start']);
		}

		return $this->db->get()->result_array();

		//$query = $this->db->query($sql);

		//return $query->rows;
	}

	public function getDownloadDescriptions($download_id) {
		$download_description_data = array();

		$this->db->select('*')->from('download_description')->where($this->primaryKey, (int)$download_id);


		//$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "download_description WHERE download_id = '" . (int)$download_id . "'");

		foreach ($this->db->get()->result_array() as $result) {
			$download_description_data[$result['language_id']] = array('name' => $result['name']);
		}

		return $download_description_data;
	}

	public function getTotalDownloads() {
		$this->db->select('COUNT(*) AS `total`');
		$query = $this->db->get($this->table)->row_array();
		return $query['total'];
	}
}