<?php

class client_form_area {

	/**
	 * @var int
	 */
	private $id;

	/**
	 * @var int
	 */
	private $id_client_form;

	/**
	 * @var int
	 */
	private $id_form_area;

	/**
	 * @var array
	 */
	private $groups;

	public function __construct($values) {
		if (!is_array($values)) {
			$values = db::init()->query()->from('client_form_area')
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
				->where(array('id_form_area', '=', $this->id_form_area))
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
	 * @return int
	 */
	public function getIdClientForm()
	{
		return $this->id_client_form;
	}

	/**
	 * @param int $id_client_form
	 */
	public function setIdClientForm($id_client_form)
	{
		$this->id_client_form = $id_client_form;
	}

	/**
	 * @return int
	 */
	public function getIdFormArea()
	{
		return $this->id_form_area;
	}

	/**
	 * @param int $id_form_area
	 */
	public function setIdFormArea($id_form_area)
	{
		$this->id_form_area = $id_form_area;
	}

}