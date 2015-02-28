<?php
class ModelReportLog extends Model {
    public $table = 'log';
    public $primaryKey = 'log_id';
    public $fields = array('log_id', 'user_id', 'function_id', 'action_id', 'item_id', 'time');


    public function getLog($logId)
    {
        if($logId <= 0) return false;
        $this->db->select('*')
            ->from($this->table)
            ->where($this->primaryKey, (int)$logId);

        return $this->db->get()->row_array();
    }

    public function getLogs($data)
    {
        $data = $this->initData($data);
        if(empty($data)) return false;

        $this->db->select('*')
            ->from($this->table);

        foreach($data as $field => $value) {
            $this->db->where($field, $value);
        }

        if (isset($data['order']) && ($data['order'] == 'DESC')) {
            $order = 'DESC';
        } else {
            $order = 'ASC';
        }

        $sort_data = $this->fields;

        if (isset($data['sort']) && in_array($data['sort'], $sort_data)) {
            $this->db->order_by($data['sort'], $order);
        } else {
            $this->db->order_by('sort_order', $order);
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
    }

    public function delete($logId)
    {
        if($logId <= 0) return false;
        $this->db->where($this->primaryKey, (int)$logId);
        return $this->db->delete($this->table);
    }

    public function deleteByUserId($userId)
    {
        if($userId <= 0) return false;
        return $this->db->where('user_id', (int)$userId)
            ->delete($this->table);
    }
}