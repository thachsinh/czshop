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

  function edit() {
    if (!$this->customer->isLogged()) {
      $this->session->data['redirect'] = $this->url->link('account/account', '', 'SSL');
      $this->response->redirect($this->url->link('account/login', '', 'SSL'));
    }

    $this->load->model('account/customer');
    if ($this->request->server['REQUEST_METHOD'] == 'POST') {
      $this->model_account_customer->editCustomerAjax($this->request->post);

      $this->response->addHeader('Content-Type: application/json');
      $this->response->setOutput(json_encode(array('redirect' => $this->url->link('account/account', '', 'SSL'))));
      return;
    }

    $this->load->language('account/edit');
    $data = $this->model_account_customer->getCustomer($this->customer->getId());
    $data['entry_firstname']          = $this->language->get('entry_firstname');
    $data['entry_lastname']           = $this->language->get('entry_lastname');
    $data['entry_email']              = $this->language->get('entry_email');
    $data['entry_telephone']          = $this->language->get('entry_telephone');
    $data['entry_save']               = $this->language->get('entry_save');
    $data['entry_cancel']             = $this->language->get('entry_cancel');
    $data['error_firstname']          = $this->language->get('error_firstname');
    $data['error_lastname']           = $this->language->get('error_lastname');
    $data['error_email']              = $this->language->get('error_email');
    $data['error_telephone']          = $this->language->get('error_telephone');
    $data['error_telephone_pattern']  = $this->language->get('error_telephone_pattern');

    $json = array(
      'state' => array(
        'backlink' => null,
        'locale' => null
      ),
      'snippets' => array(
        'snippet--editing' => $this->load->frontView('account/edit_ajax', $data)
      )
    );
    $this->response->addHeader('Content-Type: application/json');
    $this->response->setOutput(json_encode($json));
  }

  function view() {
    if (!$this->customer->isLogged()) {
      $this->session->data['redirect'] = $this->url->link('account/account', '', 'SSL');
      $this->response->redirect($this->url->link('account/login', '', 'SSL'));
    }

    $this->load->model('account/customer');
    $this->load->language('account/edit');
    $data = $this->model_account_customer->getCustomer($this->customer->getId());
    $data['entry_firstname']          = $this->language->get('entry_firstname');
    $data['entry_lastname']           = $this->language->get('entry_lastname');
    $data['entry_email']              = $this->language->get('entry_email');
    $data['entry_telephone']          = $this->language->get('entry_telephone');

    $json = array(
        'state' => array(
          'backlink' => null,
          'locale' => null
        ),
        'snippets' => array(
          'snippet--editing' => $this->load->frontView('account/view_ajax', $data),
          'snippet--changePassword' => '',
          'snippet--changeBillingInfo' => '<p> <a class="ajax" href="/uzivatel/profil?do=changeBillingInfo"> nastavit fakturační údaje </a> </p>',
          'snippet--flashMessages' => ''
        )
      );
      $this->response->addHeader('Content-Type: application/json');
      $this->response->setOutput(json_encode($json));
  }

  function password() {
    if (!$this->customer->isLogged()) {
      $this->session->data['redirect'] = $this->url->link('account/account', '', 'SSL');
      $this->response->redirect($this->url->link('account/login', '', 'SSL'));
    }

    if ($this->request->server['REQUEST_METHOD'] == 'POST') {
      $this->load->model('account/customer');
      $this->model_account_customer->editPassword($this->customer->getEmail(), $this->request->post['newPassword']);

      $this->response->addHeader('Content-Type: application/json');
      $this->response->setOutput(json_encode(array('redirect' => $this->url->link('account/account', '', 'SSL'))));
      return;
    }

    $this->load->language('account/password');
    $data['entry_password'] = $this->language->get('entry_password');
    $data['entry_confirm']  = $this->language->get('entry_confirm');
    $data['entry_save']     = $this->language->get('entry_save');
    $data['entry_cancel']   = $this->language->get('entry_cancel');
    $data['error_password'] = $this->language->get('error_password');
    $data['error_confirm']  = $this->language->get('error_confirm');

    $json = array(
      'state' => array(
        'backlink' => null,
        'locale' => null
      ),
      'snippets' => array(
        'snippet--editing' => '',
        'snippet--changePassword' => $this->load->frontView('account/password_ajax', $data)
      )
    );
    $this->response->addHeader('Content-Type: application/json');
    $this->response->setOutput(json_encode($json));
  }
}