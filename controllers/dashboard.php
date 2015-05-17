<?php

class controller_dashboard extends private_controller {

	public function index($args = array()) {
		$this->set('count', clients::get_count_of_users_clients());
		$this->show_template();
	}

}

?>