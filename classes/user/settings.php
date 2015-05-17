<?php

class settings {

	private static $is_init;
	private static $setting;
	private static $usr_settings = array();

	private function __construct() {
		$rows = db::init()->query(array('name', 'value'))->from('settings')->order('order')->get_all();
		if ($rows)
			foreach ($rows as $row) {
				self::$setting[$row['name']] = str_replace('`', "'", str_replace('``', '"', $row['value']));
			}
	}

	public static function init() {
		if (self::$is_init === null) {
			self::$is_init = new self();
		}
		return self::$is_init;
	}

	public function get($param) {
		if (isset(self::$setting[$param])) {
			return self::$setting[$param];
		} else {
			return false;
		}
	}

	public function get_all_names() {
		return array_keys(self::$setting);
	}

	public function get_all_settings() {
		return db::init()->query()->from('settings')->order('order')->get_all();
	}

	public function set($param, $value) {
		if (isset(self::$setting[$param])) {
			self::$setting[$param] = $value;
		}
	}

	public function remember() {
		if (self::$setting)
			foreach (self::$setting as $name => $value) {
				db::init()->exec('settings')->values(array('value' => $value))->where(array('name', '=', $name))->update();
			}
	}

}

?>
