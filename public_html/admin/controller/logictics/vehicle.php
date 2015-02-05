<?php
class ControllerLogicticsVehicle extends Controller {
	private $error = array();

	public function index() {
		$this->load->language('logictics/vehicle');

		$this->document->setTitle($this->language->get('heading_title'));

		$this->load->model('logictics/vehicle');

		$this->getList();
	}

	public function add() {
		$this->load->language('logictics/vehicle');

		$this->document->setTitle($this->language->get('heading_title'));

		$this->load->model('logictics/vehicle');

		if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validateForm()) {
			if ($date = date_create_from_format('Y-m', $this->request->post['production_date'])) {
				$this->request->post['production_date'] = $date->format('Y-m-d');
			}
			
			$this->model_logictics_vehicle->addVehicle($this->request->post);

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

			$this->response->redirect($this->url->link('logictics/vehicle', 'token=' . $this->session->data['token'] . $url, 'SSL'));
		}

		$this->getForm();
	}

	public function edit() {
		$this->load->language('logictics/vehicle');

		$this->document->setTitle($this->language->get('heading_title'));

		$this->load->model('logictics/vehicle');

		if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validateForm()) {
			if ($date = date_create_from_format('Y-m', $this->request->post['production_date'])) {
				$this->request->post['production_date'] = $date->format('Y-m-d');
			}
			$this->model_logictics_vehicle->editVehicle($this->request->get['vehicle_id'], $this->request->post);

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

			$this->response->redirect($this->url->link('logictics/vehicle', 'token=' . $this->session->data['token'] . $url, 'SSL'));
		}

		$this->getForm();
	}

	public function delete() {
		$this->load->language('logictics/vehicle');

		$this->document->setTitle($this->language->get('heading_title'));

		$this->load->model('logictics/vehicle');

		if (isset($this->request->post['selected']) && $this->validateDelete()) {
			foreach ($this->request->post['selected'] as $vehicle_id) {
				$this->model_logictics_vehicle->deleteVehicle($vehicle_id);
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

			$this->response->redirect($this->url->link('logictics/vehicle', 'token=' . $this->session->data['token'] . $url, 'SSL'));
		}

		$this->getList();
	}

	/**
	 * This method to delete a vehicle record by ajax
	 *
	 * @author SUN
	 * @return mixed
	 */
	public function ajaxDelete() {

		if (!$this->user->hasPermission('modify', 'logictics/vehicle')) {
			$this->error['warning'] = $this->language->get('error_permission');
			exit;
		}

		$this->load->language('logictics/vehicle');
		$vehicle_id = $this->request->post['vehicle_id'];
		if($vehicle_id > 0) {
			$this->load->model('logictics/vehicle');
			$this->model_logictics_vehicle->deleteVehicle($vehicle_id);
			$this->session->data['success'] = $this->language->get('text_success');
		}

		die("{'msg': 'The vehicle record has been deleted.', 'error': 0}");
	}

	/**
	 * This method to change a vehicle status by ajax
	 *
	 * @author SUN
	 * @return mixed
	 */
	public function ajaxStatus() {

		if (!$this->user->hasPermission('modify', 'logictics/vehicle')) {
			$this->error['warning'] = $this->language->get('error_permission');
			exit;
		}

		$vehicle_id = (int) $this->request->post['vehicle_id'];
		$status = (int) $this->request->post['status'];
		if($vehicle_id > 0) {
			$this->load->model('logictics/vehicle');
			$this->model_logictics_vehicle->editStatus($vehicle_id, $status);
		}

		die("{'msg': 'The vehicle status has been changed.', 'error': 0}");
	}

	protected function getList() {

		if (isset($this->request->get['sort'])) {
			$sort = $this->request->get['sort'];
		} else {
			$sort = 'vin';
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
			'href' => $this->url->link('logictics/vehicle', 'token=' . $this->session->data['token'] . $url, 'SSL')
		);
		
		$data['add'] = $this->url->link('logictics/vehicle/add', 'token=' . $this->session->data['token'] . $url, 'SSL');
		$data['delete'] = $this->url->link('logictics/vehicle/delete', 'token=' . $this->session->data['token'] . $url, 'SSL');
		$data['ajax_delete'] = $this->url->linkajax('logictics/vehicle/ajaxdelete', 'token=' . $this->session->data['token'] . $url, 'SSL');
		$data['ajax_status'] = $this->url->linkajax('logictics/vehicle/ajaxstatus', 'token=' . $this->session->data['token'] . $url, 'SSL');

		$data['vehicles'] = array();

		$filter_data = array(
			'sort'  => $sort,
			'order' => $order,
			'start' => ($page - 1) * $this->config->get('config_limit_admin'),
			'limit' => $this->config->get('config_limit_admin')
		);

		$vehicle_total = $this->model_logictics_vehicle->getTotalVehicles();

		$results = $this->model_logictics_vehicle->getVehicles($filter_data);

		foreach ($results as $result) {
			$data['vehicles'][] = array(
				'vehicle_id' => $result['vehicle_id'],
				'vin'       => $result['vin'],
				'model' => $result['model'],
				'status' => $result['status'],
				'edit'       => $this->url->link('logictics/vehicle/edit', 'token=' . $this->session->data['token'] . '&vehicle_id=' . $result['vehicle_id'] . $url, 'SSL')
			);
		}

		$data['heading_title'] = $this->language->get('heading_title');
		
		$data['text_list'] = $this->language->get('text_list');
		$data['text_no_results'] = $this->language->get('text_no_results');
		$data['text_confirm'] = $this->language->get('text_confirm');

		$data['column_vin'] = $this->language->get('column_vin');
		$data['column_model'] = $this->language->get('column_model');
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

		$data['sort_vin'] = $this->url->link('logictics/vehicle', 'token=' . $this->session->data['token'] . '&sort=vin' . $url, 'SSL');
		$data['sort_status'] = $this->url->link('logictics/vehicle', 'token=' . $this->session->data['token'] . '&sort=status' . $url, 'SSL');

		$url = '';

		if (isset($this->request->get['sort'])) {
			$url .= '&sort=' . $this->request->get['sort'];
		}

		if (isset($this->request->get['order'])) {
			$url .= '&order=' . $this->request->get['order'];
		}

		$pagination = new Pagination();
		$pagination->total = $vehicle_total;
		$pagination->page = $page;
		$pagination->limit = $this->config->get('config_limit_admin');
		$pagination->url = $this->url->link('logictics/vehicle', 'token=' . $this->session->data['token'] . $url . '&page={page}', 'SSL');

		$data['pagination'] = $pagination->render();

		$data['results'] = sprintf($this->language->get('text_pagination'), ($vehicle_total) ? (($page - 1) * $this->config->get('config_limit_admin')) + 1 : 0, ((($page - 1) * $this->config->get('config_limit_admin')) > ($vehicle_total - $this->config->get('config_limit_admin'))) ? $vehicle_total : ((($page - 1) * $this->config->get('config_limit_admin')) + $this->config->get('config_limit_admin')), $vehicle_total, ceil($vehicle_total / $this->config->get('config_limit_admin')));

		$data['sort'] = $sort;
		$data['order'] = $order;

		$data['header'] = $this->load->controller('common/header');
		$data['column_left'] = $this->load->controller('common/column_left');
		$data['footer'] = $this->load->controller('common/footer');

		$this->response->setOutput($this->load->view('logictics/vehicle_list.tpl', $data));
	}

	protected function getForm() {
		$data['heading_title'] 	= $this->language->get('heading_title');
		
		$data['text_form'] 			= !isset($this->request->get['vehicle_id']) ? $this->language->get('text_add') : $this->language->get('text_edit');
		$data['text_enabled'] 	= $this->language->get('text_enabled');
		$data['text_disabled'] 	= $this->language->get('text_disabled');

		$data['entry_vin'] 								= $this->language->get('entry_vin');
		$data['entry_make'] 							= $this->language->get('entry_make');
		$data['entry_model'] 							= $this->language->get('entry_model');
		$data['entry_production_date'] 		= $this->language->get('entry_production_date');
		$data['entry_model_number'] 			= $this->language->get('entry_model_number');
		$data['entry_drive_type'] 				= $this->language->get('entry_drive_type');
		$data['entry_chassis'] 						= $this->language->get('entry_chassis');
		$data['entry_engine'] 						= $this->language->get('entry_engine');
		$data['entry_transmission'] 			= $this->language->get('entry_transmission');
		$data['entry_body'] 							= $this->language->get('entry_body');
		$data['entry_odometer'] 					= $this->language->get('entry_odometer');
		$data['entry_note'] 							= $this->language->get('entry_note');
		$data['entry_status'] 						= $this->language->get('entry_status');

		$data['button_save'] 		= $this->language->get('button_save');
		$data['button_cancel'] 	= $this->language->get('button_cancel');

		if (isset($this->error['warning'])) {
			$data['error_warning'] = $this->error['warning'];
		} else {
			$data['error_warning'] = '';
		}

		if (isset($this->error['vin'])) {
			$data['error_vin'] = $this->error['vin'];
		} else {
			$data['error_vin'] = '';
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
			'href' => $this->url->link('logictics/vehicle', 'token=' . $this->session->data['token'] . $url, 'SSL')
		);
		
		if (!isset($this->request->get['vehicle_id'])) {
			$data['action'] = $this->url->link('logictics/vehicle/add', 'token=' . $this->session->data['token'] . $url, 'SSL');
		} else {
			$data['action'] = $this->url->link('logictics/vehicle/edit', 'token=' . $this->session->data['token'] . '&vehicle_id=' . $this->request->get['vehicle_id'] . $url, 'SSL');
		}

		$data['cancel'] = $this->url->link('logictics/vehicle', 'token=' . $this->session->data['token'] . $url, 'SSL');

		if (isset($this->request->get['vehicle_id']) && ($this->request->server['REQUEST_METHOD'] != 'POST')) {
			$vehicle_info = $this->model_logictics_vehicle->getVehicle($this->request->get['vehicle_id']);
		}

		if (isset($this->request->post['vin'])) {
			$data['vin'] = $this->request->post['vin'];
		} elseif (!empty($vehicle_info)) {
			$data['vin'] = $vehicle_info['vin'];
		} else {
			$data['vin'] = '';
		}

		if (isset($this->request->post['make'])) {
			$data['make'] = $this->request->post['make'];
		} elseif (!empty($vehicle_info)) {
			$data['make'] = $vehicle_info['make'];
		} else {
			$data['make'] = '';
		}

		if (isset($this->request->post['model'])) {
			$data['model'] = $this->request->post['model'];
		} elseif (!empty($vehicle_info)) {
			$data['model'] = $vehicle_info['model'];
		} else {
			$data['model'] = '';
		}

		if (isset($this->request->post['production_date'])) {
			$data['production_date'] = $this->request->post['production_date'];
		} elseif (!empty($vehicle_info)) {
			if ($vehicle_info['production_date'] != '0000-00-00') {
				$date = date_create_from_format('Y-m-d', $vehicle_info['production_date']);
				$data['production_date'] = $date->format('Y-m');
			} else {
				$data['production_date'] = '';
			}
			
		} else {
			$data['production_date'] = '';
		}

		if (isset($this->request->post['model_number'])) {
			$data['model_number'] = $this->request->post['model_number'];
		} elseif (!empty($vehicle_info)) {
			$data['model_number'] = $vehicle_info['model_number'];
		} else {
			$data['model_number'] = '';
		}

		if (isset($this->request->post['drive_type'])) {
			$data['drive_type'] = $this->request->post['drive_type'];
		} elseif (!empty($vehicle_info)) {
			$data['drive_type'] = $vehicle_info['drive_type'];
		} else {
			$data['drive_type'] = '';
		}

		if (isset($this->request->post['chassis'])) {
			$data['chassis'] = $this->request->post['chassis'];
		} elseif (!empty($vehicle_info)) {
			$data['chassis'] = $vehicle_info['chassis'];
		} else {
			$data['chassis'] = '';
		}

		if (isset($this->request->post['engine'])) {
			$data['engine'] = $this->request->post['engine'];
		} elseif (!empty($vehicle_info)) {
			$data['engine'] = $vehicle_info['engine'];
		} else {
			$data['engine'] = '';
		}

		if (isset($this->request->post['transmission'])) {
			$data['transmission'] = $this->request->post['transmission'];
		} elseif (!empty($vehicle_info)) {
			$data['transmission'] = $vehicle_info['transmission'];
		} else {
			$data['transmission'] = '';
		}
		if (isset($this->request->post['body'])) {
			$data['body'] = $this->request->post['body'];
		} elseif (!empty($vehicle_info)) {
			$data['body'] = $vehicle_info['body'];
		} else {
			$data['body'] = '';
		}
		if (isset($this->request->post['odometer'])) {
			$data['odometer'] = $this->request->post['odometer'];
		} elseif (!empty($vehicle_info)) {
			$data['odometer'] = $vehicle_info['odometer'];
		} else {
			$data['odometer'] = '';
		}

		if (isset($this->request->post['note'])) {
			$data['note'] = $this->request->post['note'];
		} elseif (!empty($vehicle_info)) {
			$data['note'] = $vehicle_info['note'];
		} else {
			$data['note'] = '';
		}

		if (isset($this->request->post['status'])) {
			$data['status'] = $this->request->post['status'];
		} elseif (!empty($vehicle_info)) {
			$data['status'] = $vehicle_info['status'];
		} else {
			$data['status'] = '1';
		}

		$data['header'] = $this->load->controller('common/header');
		$data['column_left'] = $this->load->controller('common/column_left');
		$data['footer'] = $this->load->controller('common/footer');

		$this->response->setOutput($this->load->view('logictics/vehicle_form.tpl', $data));
	}

	protected function validateForm() {
		if (!$this->user->hasPermission('modify', 'logictics/vehicle')) {
			$this->error['warning'] = $this->language->get('error_permission');
		}

		if ((utf8_strlen($this->request->post['vin']) < 0) || (utf8_strlen($this->request->post['vin']) > 7)) {
			$this->error['vin'] = $this->language->get('error_vin');
		}

		return !$this->error;
	}

	protected function validateDelete() {
		if (!$this->user->hasPermission('modify', 'logictics/vehicle')) {
			$this->error['warning'] = $this->language->get('error_permission');
		}

		return !$this->error;
	}
}