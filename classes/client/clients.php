<?php

class clients {

	public static function get_users_clients($page = 0) {
		if (user::init()->get_ifa()) {
			$clients = db::init()->query(array('c.id', 'c.first_name', 'c.last_name', 'c.birth_date', 'c.id_user', 'status' => 'cs.title', 'c.deleted', 'user_name' => 'u.name'))
					->from(array('c' => 'client'))
					->inner_join(array('u' => 'user'), array('u.id', 'c.id_user'))
					->inner_join(array('cs' => 'client_status'), array('cs.id', 'c.id_status'))
					->where(array('c.deleted', '=', '0'))
					->order('c.id')->limit(user::init()->get_show_count(), ($page - 1) * user::init()->get_show_count())->get_all();
		} else {
			$clients = db::init()->query(array('c.id', 'c.first_name', 'c.last_name', 'c.birth_date', 'c.id_user', 'status' => 'cs.title', 'c.deleted', 'user_name' => 'u.name'))
					->from(array('c' => 'client'))
					->inner_join(array('u' => 'user'), array('u.id', 'c.id_user'))
					->inner_join(array('cs' => 'client_status'), array('cs.id', 'c.id_status'))
					->where(array('c.deleted', '=', '0'))
					->where(array('c.id_user', '=', user::init()->get_id()))
					->order('c.id')->limit(user::init()->get_show_count(), ($page - 1) * user::init()->get_show_count())->get_all();
		}
		return $clients;
	}

	public static function get_count_of_users_clients() {
		if (user::init()->get_ifa()) {
			$count = db::init()->query(array('cnt' => 'COUNT(*)'))->from('client')
				->where(array('deleted', '=', '0'))
				->get_row();
		} else {
			$count = db::init()->query(array('cnt' => 'COUNT(*)'))->from('client')
				->where(array('deleted', '=', '0'))
				->where(array('id_user', '=', user::init()->get_id()))
				->get_row();
		}

		return $count['cnt'];
	}

	public static function get_statuses() {
		$statuses = array();

		$rows = db::init()->query()->from('client_status')->get_all();
		if ($rows) foreach ($rows as $row) {
			$statuses[$row['id']] = $row;
		}

		return $statuses;
	}

}

?>
