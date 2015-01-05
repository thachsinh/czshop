<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class User extends CI_Controller {
    
    public $data = array();
    public $limit = 10;
    
    public function __construct() {
        parent::__construct();
        $this->load->model('user_model');
    }
    
    public function index() {
        $this->getList();
    }
    
    public function getList() {
        $page = $this->input->get('page', 1);
        if($page < 1) $page = 1;
        $this->data['main_content'] = $this->user_model->getList();
        $this->_buildTemplate();
    }
    
    public function add() {
        print_r($this->input->get());
    }
    
    public function edit() {
        
    }
    
    public function delete() {
        
    }
    
    private function _buildTemplate()
    {
        $this->data['header'] = $this->load->view('header', $this->data, true);
        $this->data['footer'] = $this->load->view('footer', $this->data, true);
        $this->data['menu'] = $this->load->view('menu', $this->data, true);
        $this->load->view('layout', $this->data);
    }
}