<?php

class controller_profile extends private_controller {

	public function index($args = array()) {
		$this->show_template();
	}

	public function remember_settings() {
		if (post::passed_not_empty(array('login', 'name', 'email', 'show_count'))) {
			if (!post::is_only_char('login')) {
				user::init()->set_error('Incorrect data (Username)');
			} elseif (!post::is_string('name')) {
				user::init()->set_error('Incorrect data (Name)');
			} elseif (!post::is_email('email')) {
				user::init()->set_error('Incorrect data (Email)');
			} elseif (!post::is_unsigned_integer('show_count')) {
				user::init()->set_error('Incorrect data (Count)');
			} else {
				$login = post::get_only_char('login');
				$name = post::get_string('name');
				$email = post::get_email('email');
				$show_count = post::get_unsigned_integer('show_count');

				if (!user::init()->check_login($login)) {
					user::init()->set_error('Username "' . $login . '" alredy exists. Choose another one.');
				} else {
					user::init()->save(array(
						'login' => $login,
						'name' => $name,
						'email' => $email,
						'show_count' => $show_count
					));

					$this->redirect('profile?success');
				}
			}
		} else {
			user::init()->set_error('Not enough data');
		}
		$this->index();
	}

}

?>
