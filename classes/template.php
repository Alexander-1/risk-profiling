<?php

class template {

	private $css = array();
	private $scripts = array();
	private $vars = array();

//   private $menu;

	public function __construct() {
//     $this->menu = new menu(registry::get('path'));
	}

	public function add_css($path, $media = '') {
		if (!in_array($path, $this->css)) {
			$css = new css($media);
			$css->set_path($path);
			$this->css[] = $css;
		}
	}

	public function add_script($path, $defer = '') {
		if (!in_array($path, $this->scripts)) {
			$script = new script();
			$script->set_path($path);
			if (strnatcmp($defer, '') != 0) {
				$script->set_defer();
			}
			$this->scripts[] = $script;
		}
	}

	public function set($varname, $value, $overwrite = false) {
		if (isset($this->vars[$varname]) == true AND $overwrite == false) {
			die('Unable to set var `' . $varname . '`. Already set, and overwrite not allowed.');
		}
		$this->vars[$varname] = $value;
	}

	public function remove($varname) {
		unset($this->vars[$varname]);
	}

	public function show($name) {
		if (!is_array($name)) {
			$name = array($name);
		}
		$path = registry::get('templates_directory') . DIRSEP . implode(DIRSEP, $name) . '.php';
		if (file_exists($path) == false) {
			die('Template `' . implode(DIRSEP, $name) . '` does not exist.');
			return false;
		}

		foreach ($this->vars as $key => $value) {
			$$key = $value;
		};

		$current_menu = menu::init()->get_menu($name[0]);
		if (!$current_menu) {
			unset($current_menu);
		}

		ob_start();
		include ($path);
		$content = ob_get_contents();
		ob_end_clean();

		$key_words = $this->get_key_words();
		$css_files = $this->get_css_files();
		$script_files = $this->get_script_files();
		$script_defer_files = $this->get_defer_script_files();
		$page_url = '/' . registry::get('path');

		include (registry::get('templates_directory') . DIRSEP . 'main_page.php');
	}

	public function get_css_files() {
		$css_files = '';
		if ($this->css) {
			foreach ($this->css as $css) {
				$css_files .= $css->get_html_include();
			}
		}
		return $css_files;
	}

	public function get_script_files() {
		$script_files = '';
		if ($this->scripts) {
			foreach ($this->scripts as $script) {
				if (!$script->is_defer()) {
					$script_files .= $script->get_html_include();
				}
			}
		}
		return $script_files;
	}

	private function get_defer_script_files() {
		$script_files = '';
		if ($this->scripts) {
			foreach ($this->scripts as $script) {
				if ($script->is_defer()) {
					$script_files .= $script->get_html_include();
				}
			}
		}
		return $script_files;
	}

	private function get_key_words() {
		$key_words = array();
		$rows = db::init()->query()->from('key_words')->get_all();
		foreach ($rows as $row) {
			$key_words[] = $row['name'];
		}
		return implode(',', $key_words);
	}

}

?>