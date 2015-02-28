<?php
class ControllerCommonMessage extends Controller {
  public function index() {
    $message = isset($this->session->data['message']) ? $this->session->data['message'] : array();
    return $this->load->view('default/template/common/message.tpl', $message);
  }
}