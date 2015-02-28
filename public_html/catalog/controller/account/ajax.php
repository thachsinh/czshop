<?php
class ControllerAccountAjax extends Controller
{
  function add() {
    $this->load->model('account/customer');
    $this->load->language('account/success');


    $this->model_account_customer->addCustomerAjax($this->request->post);
    $this->session->data['message']['success'] = $this->language->get('text_register_ajax');

    $this->response->redirect('/');
  }
}