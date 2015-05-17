<?php

class controller_index extends private_controller {

	public function index($args = array()) {
		$this->redirect('dashboard');
	}

}

?>