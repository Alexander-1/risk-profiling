<?
class client_form {

	public $id, $id_client, $date, $comment, $id_user;
	public $user;

	/**
	 * @var array
	 */
	private $answers;

	/**
	 * @var array
	 */
	private $areas;

	public function __construct($values = null) {
		if (!is_array($values)) {
			if (user::init()->get_ifa()) {
				$values = db::init()->query(array('cf.id', 'cf.id_client', 'cf.date', 'cf.comment', 'c.id_user'))->from(array('cf' => 'client_form'))
						->inner_join(array('c' => 'client'), array('c.id', 'cf.id_client'))
						->where(array('cf.id', '=', $values))
						->get_row();
			} else {
				$values = db::init()->query(array('cf.id', 'cf.id_client', 'cf.date', 'cf.comment', 'c.id_user'))->from(array('cf' => 'client_form'))
						->inner_join(array('c' => 'client'), array('c.id', 'cf.id_client'))
						->where(array('cf.id', '=', $values))
						->where(array('c.id_user', '=', user::init()->get_id()))
						->get_row();
			}
		}
		if ($values) {
			foreach ($values as $key => $value) {
				$this->$key = $value;
			}
			$this->getAnswers();
		}
	}

	public function get_id() {
		return $this->id;
	}

	public function get_id_client() {
		return $this->id_client;
	}

	/**
	 * @param array $answers
	 */
	public function setAnswers($answers)
	{
		$this->answers = $answers;
	}

	public function getAnswers() {
		if (!isset($this->answers)) {
			$this->answers = array();

			// client_form_answer
			$rows = db::init()->query()
					->from('client_form_answer')
					->where(array('id_client_form', '=', $this->id))
					->get_all();
			if ($rows) {
				foreach ($rows as $row) {
					$answer = new client_form_answer($row);
					$this->answers[$answer->getIdFormQuestion()] = $answer;
				}
			}

			// client_form_table_column
			$rows = db::init()->query()
				->from('client_form_table_column')
				->where(array('id_client_form', '=', $this->id))
				->get_all();
			if ($rows) {
				foreach ($rows as $row) {
					$table_column = new client_form_table_column($row);
					$this->answers[$answer->getIdFormQuestion()] = $table_column;
				}
			}
		}
		return $this->answers;
	}

	/**
	 * @return array
	 */
	public function getAreas()
	{
		if (!isset($this->areas)) {
			$this->areas = array();

			$rows = db::init()->query()
				->from('client_form_area')
				->where(array('id_client_form', '=', $this->id))
				->get_all();
			if ($rows) {
				foreach ($rows as $row) {
					$area = new client_form_area($row);
					$this->areas[] = $area;
				}
			}

		}
		return $this->areas;
	}

	/**
	 * @param array $areas
	 */
	public function setAreas($areas)
	{
		$this->areas = $areas;
	}

	/**
	 * @return array
	 */
	public function getAreasIds()
	{
		$areas_ids = array();
		$areas = $this->getAreas();
		if ($areas) foreach ($areas as $area) {
			if ($area instanceof client_form_area)
			$areas_ids[] = $area->getIdFormArea();
		}
		return $areas_ids;
	}

	public function is_full() {
		return (count($this->getAnswers()) == count(client_form::get_count_all_questions()));
	}

	public function save() {
		if (!$this->id) {
			$values = array('id_client' => $this->id_client, 'comment' => $this->comment);
			if ($this->date) {
				$values['date'] = $this->date;
			}
			$this->id = db::init()->exec('client_form')->values($values)->return_id('id')->insert();

		} else {
			db::init()->exec('client_form')->values(array(
					'comment' => $this->comment
				))->where(array('id','=',$this->id))->update();
		}

		$this->saveAnswers();
		$this->saveAreas();
	}

	private function saveAnswers() {
		if ($this->answers) {
			foreach ($this->answers as $id_question => $answer) {
				$question = new form_question((int) $id_question);

				if ($question->getId()) {
					switch ($question->getType()) {
						case 'select':
							if ($answer instanceof client_form_answer) {
								$id_answer = $answer->getIdFormAnswer();
							} else {
								$id_answer = $answer;
							}

							$id = db::init()->exec('client_form_answer')
								->values(array(
									'id_form_answer' => $id_answer
								))
								->where(array('id_client_form', '=', $this->id))
								->where(array('id_form_question', '=', $question->getId()))
								->return_id('id')->update();
							if (!$id) {
								db::init()->exec('client_form_answer')->values(array(
									'id_client_form' => $this->id,
									'id_form_question' => $question->getId(),
									'id_form_answer' => $id_answer
								))->insert();
							}
							break;

						case 'text':
						case 'date':
						case 'textarea':
							if ($answer instanceof client_form_answer) {
								$text = $answer->getText();
							} else {
								$text = $answer;
							}

							$id = db::init()->exec('client_form_answer')
								->values(array('text' => $text))
								->where(array('id_client_form', '=', $this->id))
								->where(array('id_form_question', '=', $question->getId()))
								->return_id('id')->update();
							if (!$id) {
								db::init()->exec('client_form_answer')->values(array(
									'id_client_form' => $this->id,
									'id_form_question' => $question->getId(),
									'text' => $text
								))->insert();
							}
							break;

						case 'table':
							break;
					}
				}

			}
		}
	}

	private function saveAreas() {
		db::init()->exec('client_form_area')
			->where(array('id_client_form','=',$this->id))
			->delete();
		if ($this->areas) {
			foreach ($this->areas as $area) {
				if ($area instanceof client_form_area) {
					$id_area = $area->getIdFormArea();
				} else {
					$id_area = $area;
				}

				db::init()->exec('client_form_area')->values(array(
					'id_client_form' => $this->id,
					'id_form_area' => $id_area
				))->insert();
			}
		}
	}

	public function get_user() {
		if (is_null($this->user)) {
			$row = db::init()->query()->from('user')
					->where(array('id', '=', $this->id_user))
					->get_row();
			if ($row) {
				$this->user = new stdClass();
				$this->user->name = $row['name'];
			}
		}
		return $this->user;
	}

	public function delete() {
		db::init()->exec('client_form_answer')
			->where(array('id_client_form', '=', $this->id))
			->delete();
		db::init()->exec('client_form')
			->where(array('id', '=', $this->id))
			->delete();
	}


	// Static functions

	public static function get_count_all_questions() {
		$row = db::init()->query(array('cnt' => 'COUNT(*)'))->from('form_question')->get_row();
		return $row['cnt'];
	}

	public static function get_form_groups() {
		$groups = array();

		$rows = db::init()->query()
			->from('form_group')
			->where(array('id_form_group', 'IS NULL'))
			->get_all();

		foreach ($rows as $row) {
			$group = new form_group($row);
			$groups[] = $group;
		}

		return $groups;

	}
	public static function get_form_areas() {
		$areas = array();

		$rows = db::init()->query()
			->from('form_area')
			->get_all();

		foreach ($rows as $row) {
			$area = new form_area($row);
			$areas[] = $area;
		}

		return $areas;

	}

}
?>
