<?php

class user {

	private static $init;
	private $session;
	private $error;
	private $last_login, $last_ip;
	private $id, $login, $show_count, $name, $email, $phone, $ifa, $admin;
        private $documents_access;
        private $documents_tab_index;

        private function __construct() {
		$this->session = new session();
		$this->session->start();
		$this->show_count = settings::init()->get('show_count');
		$this->is_authorized();
		$this->fix_logs();
                $this->documents_tab = 0;
	}

	public static function init() {
		if (self::$init === null) {
			self::$init = new self();
		}
		return self::$init;
	}

	public final function check_captcha($captcha) {
		$user_captcha = $this->get_session_param('captcha');
		return strnatcmp($user_captcha, $captcha) == 0 ? true : false;
	}

	public function set_error($error) {
		$this->error = $error;
	}

	public function has_error() {
		if (is_null($this->error)) {
			return false;
		}
		return true;
	}

	public function get_error_message() {
		return $this->error;
	}

	private function encrypt($password, $salt) {
		return md5(md5($password . md5($salt)));
	}

	public function check_password($password) {
		$row = db::init()->query(array('password', 'salt'))->from('user')
				->where(array('id', '=', $this->id))
				->get_row();
		if ($row) {
			if (strnatcmp($this->encrypt($password, $row['salt']), $row['password']) == 0) {
				return true;
			}
		}
		return false;
	}

	public function authorize($login, $password) {
		if ($login != '') {
			$login = control::get_string($login); 
			$row = db::init()->query(array('password', 'salt', 'id', 'login', 'show_count', 'name', 'email', 'phone', 'ifa', 'admin', 'documents_access'))->from('user')
					->where(array('login', '=', $login))
					->where(array('deleted', '=', '0'))
					->where(array('active', '=', '1'))
					->get_row();
			if ($row && strnatcmp($row['password'], $this->encrypt($password, $row['salt'])) == 0) {
				$this->set_session_param('is_authorized', 1);
				$this->set_session_param('id_user', $row['id']);
				$this->id = $row['id'];
				$this->login = $row['login'];
				$this->show_count = $row['show_count'];
				$this->name = $row['name'];
				$this->email = $row['email'];
				$this->phone = $row['phone'];
				$this->ifa = $row['ifa'];
				$this->admin = $row['admin'];
                                $this->documents_access = $row['documents_access'];
				$this->create_logs();
			} else {
				$this->set_error('Incorrect username or password');
			}
		} else {
			$this->set_error('Username can not be empty');
		}
	}

	public function is_authorized() {
		if ($this->session->get('is_authorized') !== false && $this->session->get('id_user') !== false) {
			if (is_null($this->id)) {
				$row = db::init()->query(array('id', 'login', 'show_count', 'name', 'email', 'phone', 'ifa', 'admin', 'documents_access'))->from('user')
						->where(array('id', '=', $this->session->get('id_user')))
						->get_row();
				if (!$row) {
					return false;
				}
				$this->id = $row['id'];
				$this->login = $row['login'];
				$this->show_count = $row['show_count'];
				$this->name = $row['name'];
				$this->email = $row['email'];
				$this->phone = $row['phone'];
				$this->ifa= $row['ifa'];
				$this->admin = $row['admin'];
                                $this->documents_access = $row['documents_access'];
			}
			return true;
		}
		return false;
	}

	public function is_user() {
		return true;
	}

	public function logout() {
		$this->session->del('is_authorized');
	}

	public function set_session_param($name, $value) {
		$this->session->set($name, $value);
	}

	public function get_session_param($name) {
		return $this->session->get($name);
	}

	public function get_session_id() {
		return $this->session->get_id();
	}

	public function get_id() {
		return $this->id;
	}

	public function get_login() {
		return $this->login;
	}

	public function get_name() {
		return $this->name;
	}
	public function get_email() {
		return $this->email;
	}

	public function get_phone() {
		return $this->phone;
	}

	public function get_show_count() {
		return $this->show_count;
	}

	public function get_ifa() {
		if ($this->get_admin()) {
			return true;
		}
		return (boolean)$this->ifa;
	}

	public function get_admin() {
		return (boolean)$this->admin;
	}
        
        public function get_documents_access() {
		return (boolean)$this->documents_access;
        }

        public function get_documents_tab()
        {
            return $this->documents_tab_index;
        }
        
        public function set_documents_tab($tab_index)
        {
            $this->documents_tab_index = $tab_index;
        }
        
        private function init_last_login() {
		if (is_null($this->last_login)) {
			$row = db::init()->query(array('start_ts', 'ip'))->from('logs')
					->where(array('id_user','=',$this->id))
//					->where(array('id_session','!=',$this->session->get_id()))
					->order('start_ts','desc')->get_row();
			if ($row) {
				$this->last_login = $row['start_ts'];
				$this->last_ip = $row['ip'];
			}
			else {
				$this->last_login = false;
				$this->last_ip = false;
			}
		}
	}

	public function get_last_login() {
		$this->init_last_login();
		return $this->last_login;
	}

	public function get_last_ip() {
		$this->init_last_login();
		return $this->last_ip;
	}

	private function fix_logs() {
		if (strnatcmp($this->id, '') != 0) {
			$row = db::init()->query()->from('logs')->where(array('id_session', '=', $this->session->get_id()))->where(array('id_user', '=', $this->id))->order('id','desc')->get_row();
			if ($row) {
				db::init()->exec('logs')->values(array('end_ts' => time()))->where(array('id_session', '=', $this->session->get_id()))->where(array('id_user', '=', $this->id))->update();
			} else {
				$this->create_logs();
			}
		}
	}

	private function create_logs() {
		db::init()->exec('logs')->values(array(
			'id_user' => $this->id
			, 'start_ts' => time()
			, 'end_ts' => time()
			, 'ip' => $_SERVER['REMOTE_ADDR']
			, 'id_session' => $this->session->get_id()
		))->insert();
	}

	public function check_login($login) {
		$row = db::init()->query()->from('user')
				->where(array('id','!=', user::init()->get_id()))
				->where(array('login','=', $login))
				->get_row();
		return $row ? false : true;
	}

	public function save($values) {
		if ($values) {
			foreach ($values as $key => $value) {
				$this->$key = $value;
			}
			db::init()->exec('user')->values($values)
				->where(array('id','=', user::init()->get_id()))
				->update();
		}
	}

	public function add_action_log($action, $id_client = '', $id_client_form = '') {
		db::init()->exec('action_log')->values(array(
			'id_user' => $this->id
		, 'id_client' => $id_client
		, 'id_client_form' => $id_client_form
		, 'action' => $action
		, 'post' => isset($_POST) ? serialize($_POST) : array()
		))->insert();
	}

}

?>