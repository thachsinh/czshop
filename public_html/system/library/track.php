<?php
define('LOG_ACTION_ADD', 1);
define('LOG_ACTION_MODIFY', 2);
define('LOG_ACTION_DELETE', 3);
class LOG_FUNCTION {
    public static $catalog_category = 1;
    public static $catalog_custom_field = 2;
    public static $catalog_custom_field_group = 3;
    public static $catalog_download = 4;
    public static $catalog_information = 5;
    public static $catalog_manufacturer = 6;
    public static $catalog_option = 7;
    public static $catalog_product = 8;
    public static $catalog_recurring = 9;
    public static $catalog_review = 10;

    public static $design_banner = 11;
    public static $localisation_country = 12;
    public static $localisation_currency = 13;
    public static $localisation_geo_zone = 14;
    public static $localisation_language = 15;
    public static $localisation_length_class = 16;
    public static $localisation_location = 17;
    public static $localisation_order_status = 18;
    public static $localisation_return_action = 19;
    public static $localisation_return_season = 20;
    public static $localisation_return_status = 21;
    public static $localisation_stock_status = 22;
    public static $localisation_tax_class = 23;
    public static $localisation_tax_rate = 24;
    public static $localisation_weight_class = 25;
    public static $localisation_zone = 26;

    public static $logictics_driver = 27;
    public static $logictics_vehicle = 28;

    public static $marketing_affilite = 29;
    public static $marketing_coupon = 30;
    public static $marketing_marketing = 31;

    public static $sale_custom_field = 32;
    public static $sale_customer = 33;
    public static $sale_customer_ban_ip = 34;
    public static $sale_customer_group = 35;
    public static $sale_fraud = 36;
    public static $sale_order = 37;
    public static $sale_recurring = 38;
    public static $sale_return = 39;
    public static $sale_voucher = 40;
    public static $sale_voucher_theme = 41;

    public static $setting_setting = 42;
    public static $setting_store = 43;

    public static $user_user = 44;
    public static $user_group =45;
}

class Track {
    public $db = null;
    public $user = null;
    public $userId = null;
    public $table = 'log';
    public $primaryKey = 'log_id';
    public $fields = array('log_id', 'user_id', 'function_id', 'action_id', 'item_id', 'time');

    public function __construct($registry) {
        $this->db = $registry->get('db');
        $this->user = $registry->get('user');
        if(!$this->user->getId()) return false;
        $this->userId = $this->user->getId();
    }

    public function log($functionId, $actionId, $itemId)
    {
        if($functionId <= 0 || $actionId <= 0 || $itemId <= 0) return false;

        $this->db->set('time', 'NOW()', FALSE);
        $this->db->set('user_id', $this->userId);
        $this->db->set('function_id', $functionId);
        $this->db->set('action_id', (int)$actionId);
        $this->db->set('item_id', (int)$itemId);
        $this->db->insert($this->table);
        return $this->db->insert_id();
    }
}