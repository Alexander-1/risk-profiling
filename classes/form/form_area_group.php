<?php

class form_area_group {

	/**
	 * @var int
	 */
	private $id;

	/**
	 * @var int
	 */
	private $id_form_area;

	/**
	 * @var int
	 */
	private $id_form_group;

	public function __construct($values) {
		if (!is_array($values)) {
			$values = db::init()->query()->from('form_area_group')
				->where(array('id', '=', $values))
				->get_row();
		}
		if ($values) foreach ($values as $key => $value) {
			$this->$key = $value;
		}
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

	/**
	 * @return int
	 */
	public function getIdFormGroup()
	{
		return $this->id_form_group;
	}

	/**
	 * @param int $id_form_group
	 */
	public function setIdFormGroup($id_form_group)
	{
		$this->id_form_group = $id_form_group;
	}

}