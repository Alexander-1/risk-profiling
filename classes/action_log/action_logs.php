<?php

class action_logs {

	private $all_list, $all_count;
	private $list_by_pages;
	private $day, $month, $year;

	public function __construct() {
		$this->day = date('j');
		$this->month = date('n');
		$this->year = date('Y');
	}

	public function set_day($day) {
		if ($this->day == $day) {
			$this->all_list = null;
			$this->list_by_pages = null;
		}
		$this->day = $day;
	}

	public function set_month($month) {
		if ($this->month == $month) {
			$this->all_list = null;
			$this->list_by_pages = null;
		}
		$this->month = $month;
	}

	public function set_year($year) {
		if ($this->year == $year) {
			$this->all_list = null;
			$this->list_by_pages = null;
		}
		$this->year = $year;
	}

	private function init_all_list() {
		if (is_null($this->all_list)) {
			$this->all_list = array();

			$rows = action_log::get_query_to_construct()
					->get_all();
			if ($rows)
				foreach ($rows as $row) {
					$action_log = new action_log();
					$action_log->init_from_array($row);
					$this->all_list[] = $action_log;
				}
		}
	}

	private function init_page_of_list($page) {
		$show_count = user::init()->get_show_count();
		if (is_null($this->list_by_pages[$page])) {
			$this->all_list = array();

			$rows = action_log::get_query_to_construct()
					->limit($show_count, ($page - 1) * $show_count)->get_all();
			if ($rows)
				foreach ($rows as $row) {
					$action_log = new action_log();
					$action_log->init_from_array($row);
					$this->list_by_pages[$page][] = $action_log;
				}
		}
	}

	private function init_all_count() {
		if (is_null($this->all_count)) {
			$this->all_count = 0;

			$row = db::init()->query(array('cnt' => 'COUNT(*)'))->from('action_log')
					->get_row();
			if ($row) {
				$this->all_count = $row['cnt'];
			}
		}
	}

	public function get_all_list() {
		$this->init_all_list();
		return $this->all_list;
	}

	public function get_all_count() {
		$this->init_all_count();
		return $this->all_count;
	}

	public function get_page_of_list($page) {
		$show_count = user::init()->get_show_count();
		$all_count = $this->get_all_count();
		$count_pages = ceil($all_count / $show_count);
		if ($page > $count_pages) {
			$page = $count_pages;
		}
		if ($page < 1) {
			$page = 1;
		}

		$this->init_page_of_list($page);
		return $this->list_by_pages[$page];
	}

}

?>