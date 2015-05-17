<?php

class menu {

	private static $init;
	private $all_items, $first_level_items;

	private function __construct() {

	}

	public static function init() {
		if (self::$init === null) {
			self::$init = new self();
		}
		return self::$init;
	}

	public function get_menu($name) {
		$menu_item = new menu_item();
		$menu_item->init_by_name($name);
		if ($menu_item->is_init()) {
			return $menu_item;
		}
		return false;
	}

	public function get_default_item() {
		$menu_item = new menu_item();
		$menu_item->init_default_item();
		if ($menu_item->is_init()) {
			return $menu_item;
		}
		return false;
	}

	public function get_first_level_items() {
		if (!isset($this->first_level_items)) {
			$this->first_level_items = array();
			$rows = db::init()->query()
				->from('menu')
				->where(array('deleted', '=', '0'))
				->where(array('active', '=', '1'))
				->where(array('id_menu', 'isnull'))
				->order('order');
			if (user::init()->get_admin()) {
				$rows->where(array('role', '=', 'admin'));
			} elseif (user::init()->get_ifa()) {
				$rows->where(array('role', '=', 'ifa'));
			} else {
				$rows->where(array('role', '=', 'user'));
			}
			$rows = $rows->get_all();

			if ($rows)
				foreach ($rows as $row) {
					$item = new menu_item($row);
                                        $access = user::init()->get_documents_access();
                                        if (strnatcasecmp($item->get_name(), 'documents') == 0 && $access == false)
                                        {
                                            continue;
                                        }
					$this->first_level_items[] = $item;
				}
		}
		return $this->first_level_items;
	}

	public function get_all_items() {
		if (!isset($this->all_items)) {
			$this->all_items = array();
			$rows = db::init()->query()
							->from('menu')
							->where(array('deleted', '=', '0'))
							->where(array('active', '=', '1'))
							->order('order')->get_all();
			if ($rows)
				foreach ($rows as $row) {
					$item = new menu_item($row);
					$this->all_items[] = $item;
				}
		}
		return $this->all_items;
	}

}

?>