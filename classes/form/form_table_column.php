<?php

class form_table_column {

	/**
	 * @var int
	 */
	private $id;

	/**
	 * @var int
	 */
	private $id_form_question;

	/**
	 * @var string
	 */
	private $title;

	/**
	 * @var string
	 */
	private $type;

	public function __construct($values) {
		if (!is_array($values)) {
			$values = db::init()->query()->from('form_table_column')
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