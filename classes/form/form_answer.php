<?php

class form_answer {

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
	private $text;

	public function __construct($values) {
		if (!is_array($values)) {
			$values = db::init()->query()->from('form_answer')
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
	public function getText()
	{
		return $this->text;
	}

	/**
	 * @param string $text
	 */
	public function setText($text)
	{
		$this->text = $text;
	}

}