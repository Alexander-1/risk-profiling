<?php

class form_group_question {

	/**
	 * @var int
	 */
	private $id;

	/**
	 * @var int
	 */
	private $id_form_group;

	/**
	 * @var int
	 */
	private $id_form_question;

	public function __construct($values) {
		if (!is_array($values)) {
			$values = db::init()->query()->from('form_group_question')
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

	/**
	 * @return int
	 */
	public function getIdFormQuestion()
	{
		return $this->id_form_question;
	}

	/**
	 * @param int $id_form_question
	 */
	public function setIdFormQuestion($id_form_question)
	{
		$this->id_form_question = $id_form_question;
	}

}