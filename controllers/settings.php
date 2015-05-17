<?php

class controller_settings extends private_controller {

	public function index($args = array()) {
		if (isset($args[0])) {
			$menu_item = $args[0];
			$this->set('current_menu', $menu_item);
		}

		$this->show_template();
	}

	public function remember_settings() {
		$settings = settings::init()->get_all_names();
		if ($settings) {
			foreach ($settings as $setting) {
				if (post::passed($setting)) {
					settings::set($setting, $_POST[$setting]);
				}
			}
			settings::init()->remember();
		}
		$this->redirect('settings?success');
	}

}

?>
