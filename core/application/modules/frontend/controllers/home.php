<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Home extends CI_Controller {
	
	public function __construct()
	{
            parent::__construct();
            
            $this->load->model('user_model');
	}
	
	public function index()
	{
		echo "Home controller";
                print_r($this->user_model->getRow(array('user_id' => 13)));
                print_r($this->user_model->countList());
                print_r($this->user_model->getList('user_id = 1 OR user_id = 2', array('user_id' => 'DESC')));
                print_r($this->user_model->deleteByID(1));
                //print_r($this->user_model->update(array('username' => rand(1, 100))));
	}
}