<?
class client_file {

	public $id, $id_client, $title, $path, $date, $is_loa, $is_ifa, $id_user;
	public $user;

	public function __construct($values = null) {
		if (!is_array($values)) {
			if (user::init()->get_ifa()) {
				$values = db::init()->query(array('cf.id', 'cf.id_client', 'cf.title', 'cf.path', 'cf.date', 'cf.is_loa', 'cf.is_ifa', 'c.id_user'))
					->from(array('cf' => 'client_file'))
					->inner_join(array('c' => 'client'), array('c.id', 'cf.id_client'))
					->where(array('cf.id', '=', $values))
					->get_row();
			} else {
				$values = db::init()->query(array('cf.id', 'cf.id_client', 'cf.title', 'cf.path', 'cf.date', 'cf.is_loa', 'cf.is_ifa', 'c.id_user'))
					->from(array('cf' => 'client_file'))
					->inner_join(array('c' => 'client'), array('c.id', 'cf.id_client'))
					->where(array('cf.id', '=', $values))
					->where(array('c.id_user', '=', user::init()->get_id()))
					->get_row();
			}
		}
		if ($values) {
			foreach ($values as $key => $value) {
				$this->$key = $value;
			}
		}
	}

	public function get_id() {
		return $this->id;
	}

	public function get_title() {
		return $this->title;
	}

	public function get_path() {
		return $this->path;
	}

	public function get_id_client() {
		return $this->id_client;
	}

	public function save() {
		if (!$this->id) {
			$values = array(
				'id_client' => $this->id_client,
				'title' => $this->title,
				'path' => $this->path,
				'is_loa' => $this->is_loa,
				'is_ifa' => user::init()->get_ifa()
			);
			if ($this->date) {
				$values['date'] = $this->date;
			}
			$this->id = db::init()->exec('client_file')
				->values($values)
				->return_id('id')->insert();
		} else {
			db::init()->exec('client_file')->values(array(
					'title' => $this->title,
					'path' => $this->path,
					'is_loa' => $this->is_loa
				))->where(array('id','=',$this->id))->update();
		}
	}

	public function get_user() {
		if (is_null($this->user)) {
			$row = db::init()->query()->from('user')
					->where(array('id', '=', $this->id_user))
					->get_row();
			if ($row) {
				$this->user = new stdClass();
				$this->user->name = $row['name'];
			}
		}
		return $this->user;
	}

	public function delete() {
		$path = SITE_PATH . str_replace('/', DIRSEP, $this->path);
		unlink($path);

		db::init()->exec('client_file')
			->where(array('id', '=', $this->id))
			->delete();
	}

}
?>
