<?php

abstract class private_controller {

	private $template;

	public function __construct() {
		if (user::init()->is_authorized()) {
			$this->template = new template();
			$this->set('controller_name', $this->get_controller_name());
			$this->load_css_files();
			$this->load_script_files();
		} else {
			$this->redirect('authorization');
		}
	}

	protected function get_controller_name() {
		return str_replace('controller_', '', get_class($this));
	}

	private function load_css_files() {
		$this->template->add_css('index');
	}

	private function load_script_files() {
		$this->template->add_script('jquery-1.7.1.min');
		$this->template->add_script('jquery-ui');
		$this->template->add_script('jquery-ui-select');
		$this->template->add_script('jquery-ui-spinner');
		$this->template->add_script('jquery.customInput');
		$this->template->add_script('jquery.dataTables');
		$this->template->add_script('jquery.smartwizard-2.0.min');
		$this->template->add_script('jquery.alerts');
		$this->template->add_script('jquery.flot');
		$this->template->add_script('jquery.graphtable-0.2');
		$this->template->add_script('jquery.flot.pie.min');
		$this->template->add_script('jquery.flot.resize.min');
		$this->template->add_script('jquery.filestyle.mini');
		$this->template->add_script('prettify');
		$this->template->add_script('elfinder.min');
		$this->template->add_script('jquery.jgrowl');
		$this->template->add_script('colorpicker');
		$this->template->add_script('jquery.tipsy');
		$this->template->add_script('fullcalendar.min');
		$this->template->add_script('pirobox.extended.min');
		$this->template->add_script('jquery.validate.min');
		$this->template->add_script('jquery.metadata');
//		$this->template->add_script('jquery.wysiwyg');
//		$this->template->add_script('controls/wysiwyg.image');
//		$this->template->add_script('controls/wysiwyg.link');
//		$this->template->add_script('controls/wysiwyg.table');
//		$this->template->add_script('plugins/wysiwyg.rmFormat');
		$this->template->add_script('costum');
		$this->template->add_script('index');
	}

	protected function set($name, $value) {
		$this->template->set($name, $value);
	}

	protected function add_css($name) {
		$this->template->add_css($name);
	}

	protected function add_script($name) {
		$this->template->add_script($name);
	}

	protected function show_template($path = '') {
		if ($path == '') {
			$this->template->show(registry::get('path'));
		} else {
			$this->template->show($path);
		}
	}

	protected function redirect($controller = '') {
		$subdomain = '';
		if (registry::get('subdomain') == 'admin') {
			$subdomain = 'admin.';
		}
		if ($controller == '') {
			header::set_location('http://' . WWW . $subdomain . SITE_HOST . '/' . registry::get('default_controller'));
			die();
		}
		header::set_location('http://' . WWW . $subdomain . SITE_HOST . '/' . $controller);
		die();
	}

	abstract public function index($args=array());

	public function is_right_url($url) {
		$menu_item = menu::init()->get_menu($url);
		return !$menu_item ? false : true;
	}

}

?>