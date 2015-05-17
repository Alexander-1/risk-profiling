<?php

class controller_authorization extends public_controller {

	public function index($args = array()) {
		$this->show_template();
	}

	public function authorize() {
		if (post::passed(array('login', 'password'))) {
			if (!post::is_only_char('login')) {
				user::init()->set_error('Incorrect username format');
			} else {
				user::init()->authorize(post::get_only_char('login'), post::get_as_is('password'));
			}
		}

		if (user::init()->is_authorized()) {
			$this->redirect();
		} else {
			$this->index();
		}
	}

}

?>
