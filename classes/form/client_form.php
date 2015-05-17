<?
class client_form {

	public $id, $id_client, $date, $comment, $id_user;
	public $user;

	public $answers;

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
			$this->init_answers();
		}
	}

	public function get_id() {
		return $this->id;
	}

	public function get_id_client() {
		return $this->id_client;
	}

	private function init_answers() {
		if (!isset($this->answers)) {
			$this->answers = array();
			$answers = db::init()->query(array('question' => 'q.text', 'answer' => 'a.text', 'id_answer' => 'a.id', 'id_question' => 'q.id'))
					->from(array('cfa' => 'client_form_answer'))
					->inner_join(array('a' => 'form_answer'), array('cfa.id_form_answer', 'a.id'))
					->inner_join(array('q' => 'form_question'), array('cfa.id_form_question', 'q.id'))
					->where(array('cfa.id_client_form', '=', $this->id))
					->get_all();
			if ($answers) {
				foreach ($answers as $key => $value) {
					$this->answers[$value['id_question']] = $value;
				}
			}
		}
		return $this->answers;
	}


	public function is_full() {
		$questions = client_form::get_all_questions();

		return (count($this->answers) == count($questions));
	}

	public function save() {
		if (!$this->id) {
			$values = array('id_client' => $this->id_client, 'comment' => $this->comment);
			if ($this->date) {
				$values['date'] = $this->date;
			}
			$this->id = db::init()->exec('client_form')->values($values)->return_id('id')->insert();
			foreach ($this->answers as $id_question => $id_answer) {
				db::init()->exec('client_form_answer')->values(array(
					'id_client_form' => $this->id,
					'id_form_question' => $id_question,
					'id_form_answer' => $id_answer
				))->insert();
			}
		} else {
			db::init()->exec('client_form')->values(array(
					'comment' => $this->comment
				))->where(array('id','=',$this->id))->update();

			foreach ($this->answers as $id_question => $id_answer) {
				$id = db::init()->exec('client_form_answer')->values(array('id_form_answer' => $id_answer))
								->where(array('id_client_form', '=', $this->id))
								->where(array('id_form_question', '=', $id_question))
								->return_id('id')->update();
				if (!$id) {
					db::init()->exec('client_form_answer')->values(array(
						'id_client_form' => $this->id,
						'id_form_question' => $id_question,
						'id_form_answer' => $id_answer
					))->insert();
				}
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

	public static function get_all_questions() {
		$questions = db::init()->query()->from('form_question')->get_all();
		foreach ($questions as $key => $question) {
			$questions[$key]['answers'] = db::init()->query()->from('form_answer')->where(array('id_form_question', '=', $question['id']))->get_all();
		}
		return $questions;
	}

	public function delete() {
		db::init()->exec('client_form_answer')
			->where(array('id_client_form', '=', $this->id))
			->delete();
		db::init()->exec('client_form')
			->where(array('id', '=', $this->id))
			->delete();
	}

}
?>
