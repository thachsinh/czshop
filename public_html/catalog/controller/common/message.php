<?php
class ControllerCommonMessage extends Controller {
  public function index() {
    $message = array();
    if (isset($this->session->data['message'])) {
      $message = $this->session->data['message'];
      unset($this->session->data['message']);
    }
    
    return $this->load->view('default/template/common/message.tpl', $message);
  }
}