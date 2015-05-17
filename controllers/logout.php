<?php

class controller_logout extends private_controller {

	public function index($args = array()) {
		user::init()->logout();
		$this->redirect();
	}

}

?>