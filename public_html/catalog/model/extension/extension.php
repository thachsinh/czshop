<?php
class ModelExtensionExtension extends Model {
	function getExtensions($type) {
		// the query has been edited by SUN
		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "extension WHERE `type` = " . $this->db->escape($type));

		return $query->rows;
	}
}