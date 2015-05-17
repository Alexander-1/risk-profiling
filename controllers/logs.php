<?php

class controller_logs extends private_controller {

	public function index($args = array()) {
		$page = post::passed('page') ? post::get_unsigned_integer('page') : 1;
		$this->set('page', $page);

		$logs_list = new action_logs();

		$this->set('count', $logs_list->get_all_count());
		$this->set('logs', $logs_list->get_page_of_list($page));

		$this->show_template();
	}

}

?>
