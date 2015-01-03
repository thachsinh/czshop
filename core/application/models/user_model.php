<?php
class User_model extends CI_Model
{
    public $table = 'users';
    public $primaryKey = 'user_id';
    public $fields = array('username');
    
    public function __construct() {
        parent::__construct();
    }
    
    private function _init($data) {
        $tmp = null;
        if(!is_array($data)) return false;
        foreach($this->fields as $item) {
            if(isset($data[$item])) {
                $tmp[$item] = $data[$item];
            }
        }
        return $tmp;
    }
    
    public function getRow($where) {
        $this->db->select();
        $this->db->from($this->table);
        $this->db->where($where);
        return $this->db->get()->row();
    }
    
    public function update($data, $where = null) {
        $data = $this->_init($data);
        if(!empty($where)) {
            // Update
            $this->db->where($where);
            return $this->db->update($this->table, $data);
        } else {
            // Insert
            $this->db->insert($this->table, $data);
            return $this->db->insert_id();
        }
    }
    
    public function deleteByID($id) {
        if(is_array($id)) {
            $this->db->or_where_in($this->primaryKey, $id);
            return $this->db->delete($this->table);
        } else {
            if($id > 0) {
                return $this->db->delete($this->table, array($this->primaryKey => $id));
            }
        }
        return false;
    }
    
    public function getList($where = null, $order = null, $limit = 10, $offset = 0) {
        $this->db->select();       
        if(!empty($where)) $this->db->where($where);
        if(is_array($order)) {
            foreach($order as $k => $item) {
                $this->db->order_by($k , $item);    
            }
        }
        return $this->db->get($this->table, $limit, $offset)->result();
    }
    
    public function countList($where = null) {
        $this->db->select('COUNT(*) AS `numrows`');
        $this->db->from($this->table);
        if(!empty($where)) {
            $this->db->where($where);
        }
        return $this->db->get()->row()->numrows;
    }    
}

