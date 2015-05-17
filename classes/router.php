<?php

class router {

	private $path;
	private $args = array();

	public function __construct() {
		$this->set_path();
	}

	private function set_path() {
		$path = trim(registry::get('controllers_directory')) . DIRSEP;

		if (is_dir($path) == false) {
			die('Invalid controller path: `' . $path . '`');
		}

		$this->path = $path;
	}

	public function delegate() {
		$this->get_controller($file, $controller, $action, $args);

		$route = is_null(registry::get('route')) ? '' : registry::get('route');

		if (empty($route)) {
			$route = 'index';
		}

		$route = trim($route, '/\\');
		$parts = explode('/', $route);

		$cmd_path = $this->path;

		$path = '';
		if (isset($parts[0]) && $parts[0] != '') {
			$fullpath = $cmd_path . $parts[0];
			if (is_file($fullpath . '.php')) {
				$path .= $parts[0];
				$controller = $parts[0];
				array_shift($parts);
			}
			else {
				$path = 'error';
				$controller = 'error';
				$action = 'index';
			}
		}
		else {
			$path = 'index';
			$controller = 'index';
			$action = 'index';
		}

		registry::set('path', $path);
		$action = array_shift($parts);
		if (empty($action)) {
			$action = 'index';
		}

		$file = $cmd_path . $controller . '.php';
		$args = $parts;

		require_once (SITE_PATH . $file);
		$class = 'controller_' . $controller;
		$controller = new $class();

		if (is_callable(array($controller, $action)) === false) {
			$path = 'error';
			registry::set('path', $path);
			$controller = 'error';
			$action = 'index';
			$file = $cmd_path . $controller . '.php';
			require_once $file;
			$class = 'controller_' . $controller;
			$controller = new $class();
		}

		$controller->$action($args);
	}

	public function show_error($code, $error = '') {
		registry::remove('path');
		registry::set('path', 'error');
		require_once $this->path . 'error.php';
		$controller = new controller_error();
		$controller->index();
	}

	private function get_controller(&$file, &$controller, &$action, &$args) {
	}

}

?>