<?php
final class Loader {
	private $registry;

	public function __construct($registry) {
		$this->registry = $registry;
	}

	public function __get($key) {
		return $this->registry->get($key);
	}

	public function controller($route, $args = array()) {
		$action = new Action($route, $args);

		return $action->execute($this->registry);
	}

	public function model($model) {
		$file = DIR_APPLICATION . 'model/' . $model . '.php';
		$class = 'Model' . preg_replace('/[^a-zA-Z0-9]/', '', $model);

		if (file_exists($file)) {
			include_once($file);

			$this->registry->set('model_' . str_replace('/', '_', $model), new $class($this->registry));
		} else {
			trigger_error('Error: Could not load model ' . $file . '!');
			exit();
		}
	}

	public function view($template, $data = array()) {
		$file = DIR_TEMPLATE . $template;

		if (file_exists($file)) {
			extract($data);

			ob_start();

			require($file);

			$output = ob_get_contents();

			ob_end_clean();

			return $output;
		} else {
			trigger_error('Error: Could not load template ' . $file . '!');
			exit();
		}
	}

	public function frontView($template, $data = array()) {
		$rs = null;

		if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/' . $template . '.tpl')) {
			return $this->load->view($this->config->get('config_template') . '/template/' . $template . '.tpl', $data);
		} else {
			return $this->load->view('default/template/'. $template . '.tpl', $data);
		}
	}

	public function layout($data, $layout = 'default') {
		if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/layout/' . $layout . '.tpl')) {
			$this->response->setOutput($this->load->view($this->config->get('config_template') . '/template/layout/'. $layout . '.tpl', $data));
		} else {
			$this->response->setOutput($this->load->view('default/template/layout/default.tpl', $data));
		}
	}

	public function library($library) {
		$file = DIR_SYSTEM . 'library/' . $library . '.php';

		if (file_exists($file)) {
			include_once($file);
		} else {
			trigger_error('Error: Could not load library ' . $file . '!');
			exit();
		}
	}

	public function helper($helper) {
		$file = DIR_SYSTEM . 'helper/' . $helper . '.php';

		if (file_exists($file)) {
			include_once($file);
		} else {
			trigger_error('Error: Could not load helper ' . $file . '!');
			exit();
		}
	}

	public function config($config) {
		$this->registry->get('config')->load($config);
	}

	public function language($language) {
		return $this->registry->get('language')->load($language);
	}
}