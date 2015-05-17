<?php

class form_question {

	/**
	 * @var int
	 */
	private $id;

	/**
	 * @var string
	 */
	private $text;

	/**
	 * @var string
	 */
	private $type;

	/**
	 * @var array
	 */
	private $answers;

	/**
	 * @var array
	 */
	private $table_columns;

	public function __construct($values) {
		if (!is_array($values)) {
			$values = db::init()->query()->from('form_question')
				->where(array('id', '=', $values))
				->get_row();
		}
		if ($values) foreach ($values as $key => $value) {
			$this->$key = $value;
		}
	}

	public function getAnswers() {
		if (!isset($this->answers)) {
			$this->answers = array();

			if ($this->type == 'select') {
				$rows = db::init()->query()->from('form_answer')
					->where(array('id_form_question', '=', $this->id))
					->get_all();
				if ($rows) foreach ($rows as $row) {
					$answer = new form_answer($row);
					$this->answers[] = $answer;
				}
			}

		}
		return $this->answers;
	}

	public function getTableColumns() {
		if (!isset($this->table_columns)) {
			$this->table_columns = array();

			if ($this->type == 'table') {
				$rows = db::init()->query()->from('form_table_column')
					->where(array('id_form_question', '=', $this->id))
					->get_all();
				if ($rows) foreach ($rows as $row) {
					$table_column = new form_table_column($row);
					$this->table_columns[] = $table_column;
				}
			}

		}
		return $this->table_columns;
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