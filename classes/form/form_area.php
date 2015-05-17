<?php

class form_area {

	/**
	 * @var int
	 */
	private $id;

	/**
	 * @var string
	 */
	private $title;

	/**
	 * @var string
	 */
	private $type;

	/**
	 * @var array
	 */
	private $groups;

	public function __construct($values) {
		if (!is_array($values)) {
			$values = db::init()->query()->from('form_area')
				->where(array('id', '=', $values))
				->get_row();
		}
		if ($values) foreach ($values as $key => $value) {
			$this->$key = $value;
		}
	}

	public function getGroups() {
		if (!isset($this->groups)) {
			$this->groups = array();

			$rows = db::init()->query('id_form_group')
				->from('form_area_group')
				->where(array('id_form_area', '=', $this->id))
				->get_all();

			foreach ($rows as $row) {
				$group = new form_group($row['id_form_group']);
				$this->groups[] = $group;
			}
		}

		return $this->groups;
	}

	public function getGroupsIds() {
		$groups_ids = array();
		$groups = $this->getGroups();
		if ($groups) foreach ($groups as $group) {
			$groups_ids[] = $group->getId();
		}
		return $groups_ids;
	}

	/**
	 * @return int
	 */
	public function getId()
	{
		return $this->id;
	}

	/**
	 * @param int $id
	 */
	public function setId($id)
	{
		$this->id = $id;
	}

	/**
	 * @return string
	 */
	public function getTitle()
	{
		return $this->title;
	}

	/**
	 * @param string $title
	 */
	public function setTitle($title)
	{
		$this->title = $title;
	}

	/**
	 * @return string
	 */
	public function getType()
	{
		return $this->type;
	}

	/**
	 * @param string $type
	 */
	public function setType($type)
	{
		$this->type = $type;
	}

}