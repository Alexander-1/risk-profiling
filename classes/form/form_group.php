<?php

class form_group {

	/**
	 * @var int
	 */
	private $id;

	/**
	 * @var int
	 */
	private $id_form_group;

	/**
	 * @var string
	 */
	private $text;

	/**
	 * @var int
	 */
	private $order;

	/**
	 * @var array
	 */
	private $questions;

	/**
	 * @var array
	 */
	private $subgroups;

	public function __construct($values) {
		if (!is_array($values)) {
			$values = db::init()->query()->from('form_group')
				->where(array('id', '=', $values))
				->get_row();
		}
		if ($values) foreach ($values as $key => $value) {
			$this->$key = $value;
		}
	}

	public function getQuestions() {
		if (!isset($this->questions)) {
			$this->questions = array();

			$rows = db::init()->query('id_form_question')->from('form_group_question')
				->where(array('id_form_group', '=', $this->id))
				->get_all();
			if ($rows) foreach ($rows as $row) {
				$question = new form_question($row['id_form_question']);
				$this->questions[] = $question;
			}

		}
		return $this->questions;
	}

	public function getSubgroups() {
		if (!isset($this->subgroups)) {
			$this->subgroups = array();

			$rows = db::init()->query()
				->from('form_group')
				->where(array('id_form_group', '=', $this->id))
				->get_all();

			foreach ($rows as $row) {
				$group = new form_group($row);
				$this->subgroups[] = $group;
			}
		}

		return $this->subgroups;
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
	 * @return int
	 */
	public function getOrder()
	{
		return $this->order;
	}

	/**
	 * @param int $order
	 */
	public function setOrder($order)
	{
		$this->order = $order;
	}

}