<?php

class client {
	public $id, $first_name, $last_name, $birth_date, $id_user, $id_status, $status;

	public function __construct($values) {
		if (!is_array($values)) {
			if (user::init()->get_ifa()) {
				$values = db::init()->query()->from('client')
						->where(array('id', '=', $values))
						->get_row();
			} else {
				$values = db::init()->query()->from('client')
						->where(array('id', '=', $values))
						->where(array('id_user', '=', user::init()->get_id()))
						->get_row();
			}
		}
		if ($values) foreach ($values as $key => $value) {
			$this->$key = $value;
		}
	}

	public function get_id() {
		return $this->id;
	}

	public function save() {
		if (!$this->id) {
			$this->id = db::init()->exec('client')->values(array(
					'first_name' => $this->first_name,
					'last_name' => $this->last_name,
					'birth_date' => $this->birth_date,
					'id_user' => $this->id_user
				))->return_id('id')->insert();

			$this->add_status_changes(1);
		} else {
			$row = db::init()->query('id_status')->from('client')
				->where(array('id', '=', $this->id))
				->get_row();

			if (user::init()->get_ifa()) {
				db::init()->exec('client')->values(array(
						'first_name' => $this->first_name,
						'last_name' => $this->last_name,
						'birth_date' => $this->birth_date,
						'id_status' => $this->id_status
					))
					->where(array('id', '=', $this->id))
					->update();
			} else {
				db::init()->exec('client')->values(array(
						'first_name' => $this->first_name,
						'last_name' => $this->last_name,
						'birth_date' => $this->birth_date,
						'id_status' => $this->id_status
					))
					->where(array('id', '=', $this->id))
					->where(array('id_user', '=', user::init()->get_id()))
					->update();
			}

			if ($row['id_status'] != $this->id_status) {
				$this->add_status_changes($this->id_status);
			}
		}
	}

	private function add_status_changes($id_status) {
		db::init()->exec('client_status_changes')
			->values(array(
				'id_client' => $this->id,
				'id_status' => $id_status,
				'id_user' => user::init()->get_id()
			))->insert();


	}

	public function delete() {
		if ($this->id) {
			if (user::init()->get_ifa()) {
				$rows = db::init()->query('cf.id')
						->from(array('cf' => 'client_form'))
						->inner_join(array('c' => 'client'), array('c.id', 'cf.id_client'))
						->where(array('c.id', '=', $this->id))
						->get_all();
			} else {
				$rows = db::init()->query('cf.id')
						->from(array('cf' => 'client_form'))
						->inner_join(array('c' => 'client'), array('c.id', 'cf.id_client'))
						->where(array('c.id', '=', $this->id))
						->where(array('c.id_user', '=', user::init()->get_id()))
						->get_all();
			}
			if ($rows) {
				foreach ($rows as $row) {
					db::init()->exec('client_form_answer')
							->where(array('id_client_form', '=', $row['id']))
							->delete();
					db::init()->exec('client_form')
							->where(array('id', '=', $row['id']))
							->delete();
				}
			}

			if (user::init()->get_ifa()) {
				db::init()->exec('client')->values(array('deleted' => '1'))
					->where(array('id', '=', $this->id))
					->update();
			} else {
				db::init()->exec('client')->values(array('deleted' => '1'))
					->where(array('id', '=', $this->id))
					->where(array('id_user', '=', user::init()->get_id()))
					->update();
			}
		}
	}

	public function restore() {
		if ($this->id) {
			if (user::init()->get_ifa()) {
				db::init()->exec('client')->values(array('deleted' => '0'))
						->where(array('id', '=', $this->id))
						->return_id('id')->update();
			} else {
				db::init()->exec('client')->values(array('deleted' => '0'))
						->where(array('id', '=', $this->id))
						->where(array('id_user', '=', user::init()->get_id()))
						->return_id('id')->update();
			}
		}
	}

	public function get_user_forms() {
		if (user::init()->get_ifa()) {
			$rows = db::init()->query(array('cf.id', 'cf.id_client', 'cf.date', 'c.id_user'))->from(array('cf' => 'client_form'))
				->inner_join(array('c' => 'client'), array('c.id', 'cf.id_client'))
				->where(array('c.id', '=', $this->id))
				->order('cf.date', 'desc')
				->get_all();
		} else {
			$rows = db::init()->query(array('cf.id', 'cf.id_client', 'cf.date', 'c.id_user'))->from(array('cf' => 'client_form'))
				->inner_join(array('c' => 'client'), array('c.id', 'cf.id_client'))
				->where(array('c.id', '=', $this->id))
				->where(array('c.id_user', '=', user::init()->get_id()))
				->order('cf.date', 'desc')
				->get_all();
		}

		$forms = array();
		if ($rows) {
			foreach ($rows as $row) {
				$forms[] = new client_form($row);
			}
		}

		return $forms;
	}

	public function get_user_files() {
		if (user::init()->get_ifa()) {
			$rows = db::init()->query(array('cf.id', 'cf.id_client', 'cf.title', 'cf.path', 'cf.date', 'cf.is_loa', 'c.id_user'))
				->from(array('cf' => 'client_file'))
				->inner_join(array('c' => 'client'), array('c.id', 'cf.id_client'))
				->where(array('c.id', '=', $this->id))
				->where(array('cf.is_ifa', '=', '0'))
				->order('cf.date', 'desc')
				->get_all();
		} else {
			$rows = db::init()->query(array('cf.id', 'cf.id_client', 'cf.title', 'cf.path', 'cf.date', 'cf.is_loa', 'c.id_user'))
				->from(array('cf' => 'client_file'))
				->inner_join(array('c' => 'client'), array('c.id', 'cf.id_client'))
				->where(array('c.id', '=', $this->id))
				->where(array('c.id_user', '=', user::init()->get_id()))
				->where(array('cf.is_ifa', '=', '0'))
				->order('cf.date', 'desc')
				->get_all();
		}

		$files = array();
		if ($rows) {
			foreach ($rows as $row) {
				$files[] = new client_file($row);
			}
		}

		return $files;
	}

	public function get_user_ifa_files() {
		if (user::init()->get_ifa()) {
			$rows = db::init()->query(array('cf.id', 'cf.id_client', 'cf.title', 'cf.path', 'cf.date', 'cf.is_loa', 'c.id_user'))
				->from(array('cf' => 'client_file'))
				->inner_join(array('c' => 'client'), array('c.id', 'cf.id_client'))
				->where(array('c.id', '=', $this->id))
				->where(array('cf.is_ifa', '=', '1'))
				->order('cf.date', 'desc')
				->get_all();
		} else {
			$rows = db::init()->query(array('cf.id', 'cf.id_client', 'cf.title', 'cf.path', 'cf.date', 'cf.is_loa', 'c.id_user'))
				->from(array('cf' => 'client_file'))
				->inner_join(array('c' => 'client'), array('c.id', 'cf.id_client'))
				->where(array('c.id', '=', $this->id))
				->where(array('c.id_user', '=', user::init()->get_id()))
				->where(array('cf.is_ifa', '=', '1'))
				->order('cf.date', 'desc')
				->get_all();
		}

		$files = array();
		if ($rows) {
			foreach ($rows as $row) {
				$files[] = new client_file($row);
			}
		}

		return $files;
	}

	public function get_status() {
		if (is_null($this->status)) {
			$row = db::init()->query('title')
				->from('client_status')
				->where(array('id', '=', $this->id_status))
				->get_row();
			if ($row) {
				$this->status = $row['title'];
			}
		}

		return $this->status;
	}

	public function get_status_changes() {
		return db::init()->query(array('cs.title', 'csc.date'))
			->from(array('csc' => 'client_status_changes'))
			->inner_join(array('cs' => 'client_status'), array('cs.id', 'csc.id_status'))
			->where(array('id_client', '=', $this->id))
			->order('csc.date', 'desc')
			->get_all();
	}
}

?>