<?php
abstract class Model {
	protected $registry;

	public function __construct($registry) {
		$this->registry = $registry;
	}

	public function __get($key) {
		return $this->registry->get($key);
	}

	public function __set($key, $value) {
		$this->registry->set($key, $value);
	}
	
	public function initData($data, $clean = FALSE, $fields = null) {
		if(!is_array($data)) return FALSE;
		
		if($fields == null) {
			$fields = $this->fields;
		}
		//var_dump($fields);
		if(empty($fields)) return FALSE;

		$tmp = FALSE;
		foreach($fields as $field) {
			if(isset($data[$field])) {
				if($clean == TRUE) {
					if($data[$field] >= 0) {
						$tmp[$field] = $data[$field];
					} else {
						$tmp[$fields] = $this->db->escape($data[$field]);
					}
				} else {
					$tmp[$field] = $data[$field];
				}
			}
		}
		
		return $tmp;
	}
}