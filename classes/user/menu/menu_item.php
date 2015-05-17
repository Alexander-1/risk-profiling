<?php

class menu_item {

	private $id, $name, $keywords, $title, $content, $deleted, $active, $order, $description, $last_update, $is_default, $is_news;
	private $is_init = false;
	private $submenu, $menu_parent, $id_menu;

	public function __construct($values = array()) {
		if (!is_array($values)) {
			$values = db::init()->query()->from('menu')->where(array('id', '=', $values))->where(array('deleted', '=', '0'))->where(array('active', '=', '1'))->get_row();
		}
		$this->init_by_array($values);
	}

	public function init_by_name($name) {
		$values = db::init()->query()
			->from('menu')
			->where(array('name', '=', $name))
			->where(array('deleted', '=', '0'))
			->where(array('active', '=', '1'));

		if (user::init()->get_admin()) {
			$values->where(array('role', '=', 'admin'));
		} elseif (user::init()->get_ifa()) {
			$values->where(array('role', '=', 'ifa'));
		} else {
			$values->where(array('role', '=', 'user'));
		}
		$values = $values->get_row();


		$this->init_by_array($values);
	}

	public function init_default_item() {
		$values = db::init()->query()->from('menu')->where(array('is_default', '=', '1'))->where(array('deleted', '=', '0'))->where(array('active', '=', '1'))->get_row();
		$this->init_by_array($values);
	}

	private function init_by_array($values) {
		if ($values)
			foreach ($values as $name => $value) {
				$method = 'set_' . $name;
				if (is_callable(array($this, $method)) !== false) {
					$this->$method($value);
				}
				$this->is_init = true;
			}
	}

	public function remember() {
		if ($this->id) {
			db::init()->exec('menu')->values(array(
				'name' => $this->name
				, 'keywords' => $this->keywords
				, 'title' => $this->title
				, 'description' => $this->description
				, 'content' => $this->content
				, 'deleted' => $this->deleted ? 1 : '0'
				, 'active' => $this->active ? 1 : '0'
				, 'order' => $this->order
				, 'id_menu' => $this->id_menu ? $this->id_menu : 'NULL'
				, 'is_default' => $this->is_default ? 1 : '0'
				, 'is_news' => $this->is_news ? 1 : '0'
			))->where(array('id', '=', $this->id))->update();
		} else {
			$this->order = 0;
			$row = db::init()->query('order')->from('menu')->order('order', 'desc')->get_row();
			if ($row) {
				$this->order = $row['order'] + 1;
			}
			$values = array(
						'name' => $this->name
						, 'keywords' => $this->keywords
						, 'title' => $this->title
						, 'description' => $this->description
						, 'content' => $this->content
						, 'order' => $this->order
						, 'is_default' => $this->is_default ? 1 : '0'
						, 'is_news' => $this->is_news ? 1 : '0'
					);
			if ($this->id_menu) {
				$values['id_menu'] = $this->id_menu;
			}

			$this->id = db::init()->exec('menu')->values($values)->return_id('id')->insert();
		}
	}

	public function is_init() {
		return $this->is_init;
	}

	public function set_id($id) {
		$this->id = $id;
	}

	public function set_id_menu($id_menu) {
		$this->id_menu = $id_menu;
	}

	public function set_name($name) {
		$this->name = $name;
	}

	public function set_last_update($last_update) {
		$this->last_update = $last_update;
	}

	public function set_keywords($keywords) {
		$this->keywords = $keywords;
	}

	public function set_title($title) {
		$this->title = $title;
	}

	public function set_description($description) {
		$this->description = $description;
	}

	public function set_content($content) {
		$this->content = str_replace('`', "'", str_replace('``', '"', $content));
	}

	public function set_is_default($is_default) {
		$this->is_default = $is_default ? true : false;
	}

	public function set_is_news($is_news) {
		$this->is_news = $is_news ? true : false;
	}

	public function set_order($order) {
		$this->order = $order;
	}

	public function set_deleted($deleted) {
		$this->deleted = $deleted ? true : false;
	}

	public function set_active($active) {
		$this->active = $active ? true : false;
	}

	public function get_id() {
		return $this->id;
	}

	public function get_id_menu() {
		return $this->id_menu;
	}

	public function get_name() {
		return $this->name;
	}

	public function get_last_update() {
		return $this->last_update;
	}

	public function get_title() {
		return $this->title;
	}

	public function get_description() {
		return $this->description;
	}

	public function get_keywords() {
		return $this->keywords;
	}

	public function get_content() {
		return $this->content;
	}

	public function is_default() {
		return $this->is_default;
	}

	public function is_news() {
		return $this->is_news;
	}

	public function get_order() {
		return $this->order;
	}

	public function is_deleted() {
		return $this->deleted;
	}

	public function is_active() {
		return $this->active;
	}

	private function init_submenu() {
		if (is_null($this->submenu)) {
			$this->init_menu_parent();
			if ($this->id_menu) {
				$this->submenu = $this->menu_parent->get_submenu();
			} else {
				$this->submenu = array();
				$rows = db::init()->query()
						->from('menu')
						->where(array('deleted', '=', '0'))
						->where(array('active', '=', '1'))
						->where(array('id_menu', '=', $this->id))
						->order('order')
						->get_all();
				if ($rows)
					foreach ($rows as $row) {
						$item = new menu_item($row);
						$this->submenu[] = $item;
					}
			}
		}
	}

	public function get_submenu() {
		$this->init_submenu();
		return $this->submenu;
	}

	private function init_menu_parent() {
		if (is_null($this->menu_parent)) {
			$this->menu_parent = new menu_item($this->id_menu);
		}
	}

	public function get_menu_parent() {
		$this->init_menu_parent();
		return $this->menu_parent;
	}

	public function move_up (){
		$row = db::init()->query()->from('menu')
				->where(array('order','<',$this->order))
				->where(array('deleted','=','0'))
				->order('order','desc');
		if ($this->id_menu) {
			$row->where(array('id_menu','=',$this->id_menu));
		}
		else{
			$row->where(array('id_menu','isnull'));
		}
		$row = $row->get_row();

		if ($row) {
			db::init()->exec('menu')->values(array('order' => $this->order))
					->where(array('id','=',$row['id']))
					->update();
			$this->order = $row['order'];
			$this->remember();
		}
	}

	public function move_down (){
		$row = db::init()->query()->from('menu')
				->where(array('order','>',$this->order))
				->where(array('deleted','=','0'))
				->order('order','asc');
		if ($this->id_menu) {
			$row->where(array('id_menu','=',$this->id_menu));
		}
		else{
			$row->where(array('id_menu','isnull'));
		}
		$row = $row->get_row();

		if ($row) {
			db::init()->exec('menu')->values(array('order' => $this->order))
					->where(array('id','=',$row['id']))
					->update();
			$this->order = $row['order'];
			$this->remember();
		}
	}

}

?>