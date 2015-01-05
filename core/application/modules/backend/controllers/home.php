<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Home extends CI_Controller {
    
    private $data = array();
	
    public function __construct()
    {
        parent::__construct();
    }

    public function index()
    {
        $this->data['main_content'] = 'Main Content';
        $this->_buildTemplate();
    }
    
    private function _buildTemplate()
    {
        $this->data['header'] = $this->load->view('header', $this->data, true);
        $this->data['footer'] = $this->load->view('footer', $this->data, true);
        $this->data['menu'] = $this->load->view('menu', $this->data, true);
        $this->load->view('layout', $this->data);
    }
}