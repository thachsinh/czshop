<?php
class ControllerLogicticsDriver extends Controller {
	private $error = array();

	public function index() {
		$this->load->language('logictics/driver');

		$this->document->setTitle($this->language->get('heading_title'));

		$this->load->model('logictics/driver');

		$this->getList();
	}

	public function add() {
		$this->load->language('logictics/driver');

		$this->document->setTitle($this->language->get('heading_title'));

		$this->load->model('logictics/driver');

		if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validateForm()) {
			$this->model_logictics_driver->addDriver($this->request->post);

			$this->session->data['success'] = $this->language->get('text_success');

			$url = '';

			if (isset($this->request->get['sort'])) {
				$url .= '&sort=' . $this->request->get['sort'];
			}

			if (isset($this->request->get['order'])) {
				$url .= '&order=' . $this->request->get['order'];
			}

			if (isset($this->request->get['page'])) {
				$url .= '&page=' . $this->request->get['page'];
			}

			$this->response->redirect($this->url->link('logictics/driver', 'token=' . $this->session->data['token'] . $url, 'SSL'));
		}

		$this->getForm();
	}

	public function edit() {
		$this->load->language('logictics/driver');

		$this->document->setTitle($this->language->get('heading_title'));

		$this->load->model('logictics/driver');

		if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validateForm()) {
			$this->model_logictics_driver->editDriver($this->request->get['driver_id'], $this->request->post);

			$this->session->data['success'] = $this->language->get('text_success');

			$url = '';

			if (isset($this->request->get['sort'])) {
				$url .= '&sort=' . $this->request->get['sort'];
			}

			if (isset($this->request->get['order'])) {
				$url .= '&order=' . $this->request->get['order'];
			}

			if (isset($this->request->get['page'])) {
				$url .= '&page=' . $this->request->get['page'];
			}

			$this->response->redirect($this->url->link('logictics/driver', 'token=' . $this->session->data['token'] . $url, 'SSL'));
		}

		$this->getForm();
	}

	public function delete() {
		$this->load->language('logictics/driver');

		$this->document->setTitle($this->language->get('heading_title'));

		$this->load->model('logictics/driver');

		if (isset($this->request->post['selected']) && $this->validateDelete()) {
			foreach ($this->request->post['selected'] as $driver_id) {
				$this->model_logictics_driver->deleteDriver($driver_id);
			}

			$this->session->data['success'] = $this->language->get('text_success');

			$url = '';

			if (isset($this->request->get['sort'])) {
				$url .= '&sort=' . $this->request->get['sort'];
			}

			if (isset($this->request->get['order'])) {
				$url .= '&order=' . $this->request->get['order'];
			}

			if (isset($this->request->get['page'])) {
				$url .= '&page=' . $this->request->get['page'];
			}

			$this->response->redirect($this->url->link('logictics/driver', 'token=' . $this->session->data['token'] . $url, 'SSL'));
		}

		$this->getList();
	}

	/**
	 * This method to delete a driver record by ajax
	 *
	 * @author SUN
	 * @return mixed
	 */
	public function ajaxDelete() {

		if (!$this->user->hasPermission('modify', 'logictics/driver')) {
			$this->error['warning'] = $this->language->get('error_permission');
			exit;
		}

		$this->load->language('logictics/driver');
		$driver_id = $this->request->post['driver_id'];
		if($driver_id > 0) {
			$this->load->model('logictics/driver');
			$this->model_logictics_driver->deleteDriver($driver_id);
			$this->session->data['success'] = $this->language->get('text_success');
		}

		die("{'msg': 'The driver record has been deleted.', 'error': 0}");
	}

	/**
	 * This method to change a driver status by ajax
	 *
	 * @author SUN
	 * @return mixed
	 */
	public function ajaxStatus() {

		if (!$this->user->hasPermission('modify', 'logictics/driver')) {
			$this->error['warning'] = $this->language->get('error_permission');
			exit;
		}

		$driver_id = (int) $this->request->post['driver_id'];
		$status = (int) $this->request->post['status'];
		if($driver_id > 0) {
			$this->load->model('logictics/driver');
			$this->model_logictics_driver->editStatus($driver_id, $status);
		}

		die("{'msg': 'The driver status has been changed.', 'error': 0}");
	}

	protected function getList() {

		if (isset($this->request->get['sort'])) {
			$sort = $this->request->get['sort'];
		} else {
			$sort = 'name';
		}

		if (isset($this->request->get['order'])) {
			$order = $this->request->get['order'];
		} else {
			$order = 'ASC';
		}

		if (isset($this->request->get['page'])) {
			$page = $this->request->get['page'];
		} else {
			$page = 1;
		}

		$url = '';

		if (isset($this->request->get['sort'])) {
			$url .= '&sort=' . $this->request->get['sort'];
		}

		if (isset($this->request->get['order'])) {
			$url .= '&order=' . $this->request->get['order'];
		}

		if (isset($this->request->get['page'])) {
			$url .= '&page=' . $this->request->get['page'];
		}
		
		$data['breadcrumbs'] = array();

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('text_home'),
			'href' => $this->url->link('common/dashboard', 'token=' . $this->session->data['token'], 'SSL')
		);

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('heading_title'),
			'href' => $this->url->link('logictics/driver', 'token=' . $this->session->data['token'] . $url, 'SSL')
		);
		
		$data['add'] = $this->url->link('logictics/driver/add', 'token=' . $this->session->data['token'] . $url, 'SSL');
		$data['delete'] = $this->url->link('logictics/driver/delete', 'token=' . $this->session->data['token'] . $url, 'SSL');
		$data['ajax_delete'] = $this->url->linkajax('logictics/driver/ajaxdelete', 'token=' . $this->session->data['token'] . $url, 'SSL');
		$data['ajax_status'] = $this->url->linkajax('logictics/driver/ajaxstatus', 'token=' . $this->session->data['token'] . $url, 'SSL');

		$data['drivers'] = array();

		$filter_data = array(
			'sort'  => $sort,
			'order' => $order,
			'start' => ($page - 1) * $this->config->get('config_limit_admin'),
			'limit' => $this->config->get('config_limit_admin')
		);

		$driver_total = $this->model_logictics_driver->getTotalDrivers();

		$results = $this->model_logictics_driver->getDrivers($filter_data);

		foreach ($results as $result) {
			$data['drivers'][] = array(
				'driver_id' => $result['driver_id'],
				'name'       => $result['name'],
				'driving_licence_number' => $result['driving_licence_number'],
				'status' => $result['status'],
				'edit'       => $this->url->link('logictics/driver/edit', 'token=' . $this->session->data['token'] . '&driver_id=' . $result['driver_id'] . $url, 'SSL')
			);
		}

		$data['heading_title'] = $this->language->get('heading_title');
		
		$data['text_list'] = $this->language->get('text_list');
		$data['text_no_results'] = $this->language->get('text_no_results');
		$data['text_confirm'] = $this->language->get('text_confirm');

		$data['column_name'] = $this->language->get('column_name');
		$data['column_driving_licence_number'] = $this->language->get('column_driving_licence_number');
		$data['column_action'] = $this->language->get('column_action');
		$data['column_status'] = $this->language->get('column_status');

		$data['button_add'] = $this->language->get('button_add');
		$data['button_edit'] = $this->language->get('button_edit');
		$data['button_delete'] = $this->language->get('button_delete');

		if (isset($this->error['warning'])) {
			$data['error_warning'] = $this->error['warning'];
		} else {
			$data['error_warning'] = '';
		}

		if (isset($this->session->data['success'])) {
			$data['success'] = $this->session->data['success'];

			unset($this->session->data['success']);
		} else {
			$data['success'] = '';
		}

		if (isset($this->request->post['selected'])) {
			$data['selected'] = (array)$this->request->post['selected'];
		} else {
			$data['selected'] = array();
		}

		$url = '';

		if ($order == 'ASC') {
			$url .= '&order=DESC';
		} else {
			$url .= '&order=ASC';
		}

		if (isset($this->request->get['page'])) {
			$url .= '&page=' . $this->request->get['page'];
		}

		$data['sort_name'] = $this->url->link('logictics/driver', 'token=' . $this->session->data['token'] . '&sort=name' . $url, 'SSL');
		$data['sort_status'] = $this->url->link('logictics/driver', 'token=' . $this->session->data['token'] . '&sort=status' . $url, 'SSL');

		$url = '';

		if (isset($this->request->get['sort'])) {
			$url .= '&sort=' . $this->request->get['sort'];
		}

		if (isset($this->request->get['order'])) {
			$url .= '&order=' . $this->request->get['order'];
		}

		$pagination = new Pagination();
		$pagination->total = $driver_total;
		$pagination->page = $page;
		$pagination->limit = $this->config->get('config_limit_admin');
		$pagination->url = $this->url->link('logictics/driver', 'token=' . $this->session->data['token'] . $url . '&page={page}', 'SSL');

		$data['pagination'] = $pagination->render();

		$data['results'] = sprintf($this->language->get('text_pagination'), ($driver_total) ? (($page - 1) * $this->config->get('config_limit_admin')) + 1 : 0, ((($page - 1) * $this->config->get('config_limit_admin')) > ($driver_total - $this->config->get('config_limit_admin'))) ? $driver_total : ((($page - 1) * $this->config->get('config_limit_admin')) + $this->config->get('config_limit_admin')), $driver_total, ceil($driver_total / $this->config->get('config_limit_admin')));

		$data['sort'] = $sort;
		$data['order'] = $order;

		$data['header'] = $this->load->controller('common/header');
		$data['column_left'] = $this->load->controller('common/column_left');
		$data['footer'] = $this->load->controller('common/footer');

		$this->response->setOutput($this->load->view('logictics/driver_list.tpl', $data));
	}

	protected function getForm() {
		$data['heading_title'] = $this->language->get('heading_title');
		
		$data['text_form'] = !isset($this->request->get['driver_id']) ? $this->language->get('text_add') : $this->language->get('text_edit');
		$data['text_enabled'] = $this->language->get('text_enabled');
		$data['text_disabled'] = $this->language->get('text_disabled');
		$data['text_yes'] = $this->language->get('text_yes');
		$data['text_no'] = $this->language->get('text_no');

		$data['entry_name'] = $this->language->get('entry_name');
		$data['entry_birthday'] = $this->language->get('entry_birthday');
		$data['entry_gender'] = $this->language->get('entry_gender');
		$data['entry_address'] = $this->language->get('entry_address');
		$data['entry_driving_status'] = $this->language->get('entry_driving_status');
		$data['entry_licence_valid_from'] = $this->language->get('entry_licence_valid_from');
		$data['entry_licence_valid_to'] = $this->language->get('entry_licence_valid_to');
		$data['entry_driving_licence_number'] = $this->language->get('entry_driving_licence_number');
		$data['entry_status'] = $this->language->get('entry_status');
		$data['entry_group_licence'] = $this->language->get('entry_group_licence');

		$data['button_save'] = $this->language->get('button_save');
		$data['button_cancel'] = $this->language->get('button_cancel');

		if (isset($this->error['warning'])) {
			$data['error_warning'] = $this->error['warning'];
		} else {
			$data['error_warning'] = '';
		}

		if (isset($this->error['name'])) {
			$data['error_name'] = $this->error['name'];
		} else {
			$data['error_name'] = '';
		}

		if (isset($this->error['birthday'])) {
			$data['error_birthday'] = $this->error['birthday'];
		} else {
			$data['error_birthday'] = '';
		}

		if (isset($this->error['licence_valid_from'])) {
			$data['error_licence_valid_from'] = $this->error['licence_valid_from'];
		} else {
			$data['error_licence_valid_from'] = '';
		}

		if (isset($this->error['licence_valid_to'])) {
			$data['error_licence_valid_to'] = $this->error['licence_valid_to'];
		} else {
			$data['error_licence_valid_to'] = '';
		}

		$url = '';

		if (isset($this->request->get['sort'])) {
			$url .= '&sort=' . $this->request->get['sort'];
		}

		if (isset($this->request->get['order'])) {
			$url .= '&order=' . $this->request->get['order'];
		}

		if (isset($this->request->get['page'])) {
			$url .= '&page=' . $this->request->get['page'];
		}

		$data['breadcrumbs'] = array();

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('text_home'),
			'href' => $this->url->link('common/dashboard', 'token=' . $this->session->data['token'], 'SSL')
		);

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('heading_title'),
			'href' => $this->url->link('logictics/driver', 'token=' . $this->session->data['token'] . $url, 'SSL')
		);
		
		if (!isset($this->request->get['driver_id'])) {
			$data['action'] = $this->url->link('logictics/driver/add', 'token=' . $this->session->data['token'] . $url, 'SSL');
		} else {
			$data['action'] = $this->url->link('logictics/driver/edit', 'token=' . $this->session->data['token'] . '&driver_id=' . $this->request->get['driver_id'] . $url, 'SSL');
		}

		$data['cancel'] = $this->url->link('logictics/driver', 'token=' . $this->session->data['token'] . $url, 'SSL');

		if (isset($this->request->get['driver_id']) && ($this->request->server['REQUEST_METHOD'] != 'POST')) {
			$driver_info = $this->model_logictics_driver->getDriver($this->request->get['driver_id']);
		}

		if (isset($this->request->post['name'])) {
			$data['name'] = $this->request->post['name'];
		} elseif (!empty($driver_info)) {
			$data['name'] = $driver_info['name'];
		} else {
			$data['name'] = '';
		}

		if (isset($this->request->post['birthday'])) {
			$data['birthday'] = $this->request->post['birthday'];
		} elseif (!empty($driver_info)) {
			$data['birthday'] = $driver_info['birthday'];
		} else {
			$data['birthday'] = '';
		}

		if (isset($this->request->post['gender'])) {
			$data['gender'] = $this->request->post['gender'];
		} elseif (!empty($driver_info)) {
			$data['gender'] = $driver_info['gender'];
		} else {
			$data['gender'] = '';
		}

		if (isset($this->request->post['address'])) {
			$data['address'] = $this->request->post['address'];
		} elseif (!empty($driver_info)) {
			$data['address'] = $driver_info['address'];
		} else {
			$data['address'] = '';
		}

		if (isset($this->request->post['driving_status'])) {
			$data['driving_status'] = $this->request->post['driving_status'];
		} elseif (!empty($driver_info)) {
			$data['driving_status'] = $driver_info['driving_status'];
		} else {
			$data['driving_status'] = '';
		}

		if (isset($this->request->post['driving_licence_number'])) {
			$data['driving_licence_number'] = $this->request->post['driving_licence_number'];
		} elseif (!empty($driver_info)) {
			$data['driving_licence_number'] = $driver_info['driving_licence_number'];
		} else {
			$data['driving_licence_number'] = '';
		}

		if (isset($this->request->post['licence_valid_from'])) {
			$data['licence_valid_from'] = $this->request->post['licence_valid_from'];
		} elseif (!empty($driver_info)) {
			$data['licence_valid_from'] = $driver_info['licence_valid_from'];
		} else {
			$data['licence_valid_from'] = '';
		}

		if (isset($this->request->post['licence_valid_to'])) {
			$data['licence_valid_to'] = $this->request->post['licence_valid_to'];
		} elseif (!empty($driver_info)) {
			$data['licence_valid_to'] = $driver_info['licence_valid_to'];
		} else {
			$data['licence_valid_to'] = '';
		}

		if (isset($this->request->post['note'])) {
			$data['note'] = $this->request->post['note'];
		} elseif (!empty($driver_info)) {
			$data['note'] = $driver_info['note'];
		} else {
			$data['note'] = '';
		}

		if (isset($this->request->post['status'])) {
			$data['status'] = $this->request->post['status'];
		} elseif (!empty($driver_info)) {
			$data['status'] = $driver_info['status'];
		} else {
			$data['status'] = '1';
		}

		$data['header'] = $this->load->controller('common/header');
		$data['column_left'] = $this->load->controller('common/column_left');
		$data['footer'] = $this->load->controller('common/footer');

		$this->response->setOutput($this->load->view('logictics/driver_form.tpl', $data));
	}

	protected function validateForm() {
		if (!$this->user->hasPermission('modify', 'logictics/driver')) {
			$this->error['warning'] = $this->language->get('error_permission');
		}

		if ((utf8_strlen($this->request->post['name']) < 3) || (utf8_strlen($this->request->post['name']) > 255)) {
			$this->error['name'] = $this->language->get('error_name');
		}

		if (date_create_from_format($this->language->get('date_input_format'), $this->request->post['birthday']) == false) {
			$this->error['birthday'] = $this->language->get('error_date_format');
		}

		if (date_create_from_format($this->language->get('date_input_format'), $this->request->post['licence_valid_from']) == false) {
			$this->error['licence_valid_from'] = $this->language->get('error_date_format');
		}

		if (date_create_from_format($this->language->get('date_input_format'), $this->request->post['licence_valid_to']) == false) {
			$this->error['licence_valid_to'] = $this->language->get('error_date_format');
		}

		return !$this->error;
	}

	protected function validateDelete() {
		if (!$this->user->hasPermission('modify', 'logictics/driver')) {
			$this->error['warning'] = $this->language->get('error_permission');
		}

		return !$this->error;
	}
}