<?php

class controller_forms extends private_controller {

	public function index($args = array()) {
		$id = get::passed('id') ? get::get_unsigned_integer('id') : (post::passed('id') ? post::get_unsigned_integer('id') : 0);

		$client = new client($id);
		$forms = $client->get_user_forms();

		$this->set('questions', client_form::get_all_questions());
		$this->set('forms', $forms);
		$this->set('client', $client);
		$this->set('comment', post::passed('comment') ? post::get_string('comment') : '');
		$this->set('form_question', post::passed('question') ? post::get_as_is('question') : '');

		$this->show_template();
	}

	public function edit() {
		$id_form = get::passed('id_form') ? get::get_unsigned_integer('id_form') : (post::passed('id_form') ? post::get_unsigned_integer('id_form') : 0);

		$form = new client_form($id_form);
		$this->set('form', $form);

		$this->index();
	}

	public function analyze() {
		$id_form = get::passed('id_form') ? get::get_unsigned_integer('id_form') : (post::passed('id_form') ? post::get_unsigned_integer('id_form') : 0);

		$form = new client_form($id_form);

		$data = analyzer::get_data($form);

		if (!user::init()->has_error()) {
			if (!$data || !$data->CatMbship || !$data->IPMbship || !$data->RedFlag/* || !$data->Questions*/) {
				user::init()->set_error('An error has occurred');
			} else {
				$portfolio['return'] = 0;
				$portfolio['volatility'] = 0;
				for ($i = 0; $i < 5; $i++) {
					$portfolio['return'] += settings::init()->get('return_' . $i) * $data->IPMbship[$i];
					$portfolio['volatility'] += (settings::init()->get('volatility_' . $i)) * ($data->IPMbship[$i]) ;
				}
				$portfolio['return'] = round($portfolio['return'], 2);
				$portfolio['volatility'] = round(($portfolio['volatility']), 2);

				foreach ($data->IPMbship as $key => $value) {
					$data->IPMbship[$key] = round($value * 100, 2);
				}

				$this->set('data', $data);
				$this->set('portfolio', $portfolio);
			}
		}

		$this->set('form', $form);
		$this->set('analyze', true);

		$this->index();
	}

	public function add() {
		if (post::passed_not_empty(array('question', 'id'))) {
			if (!post::is_unsigned_integer('id')) {
				user::init()->set_error('Incorrect data');
			} elseif (!post::is_array_of_unsigned_integer('question')) {
				user::init()->set_error('Incorrect data');
			} else {
				$id_client = post::get_unsigned_integer('id');
				$questions = post::get_array_of_unsigned_integer('question');
				$comment = post::passed('comment') ? post::get_string('comment') : '';

				$client = new client($id_client);

				if (!$client->id) {
					user::init()->set_error('Incorrect data');
				} else {
					$form = new client_form();
					$form->id_client = $client->id;
					if (user::init()->get_ifa()) {
						$form->comment = $comment;
					}

					$answers = array();
					foreach ($questions as $id_question => $id_answer) {
						$answers[$id_question] = $id_answer;
					}
					$form->answers = $answers;
					$form->save();
					user::init()->add_action_log("Add client form", $client->get_id(), $form->get_id());

					$this->redirect("forms/edit?id={$client->id}&id_form={$form->id}&success&add");
				}
			}
		} else {
			user::init()->set_error('Not enough data');
		}
		$this->index();
	}

	public function save() {
		if (post::passed_not_empty(array('question', 'id', 'id_form'))) {
			if (!post::is_unsigned_integer('id')) {
				user::init()->set_error('Incorrect data');
			} elseif (!post::is_unsigned_integer('id_form')) {
				user::init()->set_error('Incorrect data');
			} elseif (!post::is_array_of_unsigned_integer('question')) {
				user::init()->set_error('Incorrect data');
			} else {
				$id_form = post::get_unsigned_integer('id_form');
				$questions = post::get_array_of_unsigned_integer('question');
				$comment = post::passed('comment') ? post::get_string('comment') : '';

				$form = new client_form($id_form);

				if (!$form->id) {
					user::init()->set_error('Incorrect data');
				} else {
					if (user::init()->get_ifa()) {
						$form->comment = $comment;
					}
					$form->answers = $questions;
					$form->save();
					user::init()->add_action_log("Edit client form", $form->get_id_client(), $form->get_id());

					$form = new client_form($id_form);

					if (file_exists($this->get_xml_file_path($form))) {
						unlink($this->get_xml_file_path($form));
					}
					if (file_exists($this->get_pdf_file_path($form, false))) {
						unlink($this->get_pdf_file_path($form, false));
					}
					if (file_exists($this->get_pdf_file_path($form, true))) {
						unlink($this->get_pdf_file_path($form, true));
					}

					$this->create_xml($form);
					$this->create_pdf($form, false);
					$this->create_pdf($form, true);

					$this->redirect("forms/edit?id={$form->id_client}&id_form={$form->id}&success&edit");
				}
			}
		} else {
			user::init()->set_error('Not enough data');
		}
		$this->edit();
	}

	public function delete() {
		if (get::passed_not_empty(array('id'))) {
			if (!get::is_unsigned_integer('id')) {
				user::init()->set_error('Incorrect data (Id)');
			} else {
				$id = get::get_unsigned_integer('id');

				$form = new client_form($id);

				if (!$form->id) {
					user::init()->set_error('Incorrect data (Id)');
				} else {
					$form->delete();
					user::init()->add_action_log("Delete client form", $form->get_id_client(), $form->get_id());

					$this->redirect("forms?id={$form->id_client}&success&delete");
				}
			}
		} else {
			user::init()->set_error('Not enough data');
		}
		$this->index();
	}

	public function xml() {
		if (get::passed_not_empty(array('id', 'id_form'))) {
			if (!get::is_unsigned_integer('id')) {
				user::init()->set_error('Incorrect data');
			} elseif (!get::is_unsigned_integer('id_form')) {
				user::init()->set_error('Incorrect data');
			} else {
				$id_client = get::get_unsigned_integer('id');
				$id_form = get::get_unsigned_integer('id_form');

				$form = new client_form($id_form);
				$client = new client($id_client);

				if (!$client->id) {
					user::init()->set_error('Incorrect data');
				} else {
					if (!file_exists($this->get_xml_file_path($form))) {
						$this->create_xml($form);
					}

					Header('Content-Type: application/xml');
					Header('Content-Length: '.filesize($this->get_xml_file_path($form)));
					Header("Content-Disposition: attachment; filename=" . $this->get_xml_file_name($form));
					readfile($this->get_xml_file_path($form));

					die();
				}
			}
		} else {
			user::init()->set_error('Not enough data');
		}
		$this->index();
	}

	public function pdf() {
		if (get::passed_not_empty(array('id', 'id_form'))) {
			if (!get::is_unsigned_integer('id')) {
				user::init()->set_error('Incorrect data');
			} elseif (!get::is_unsigned_integer('id_form')) {
				user::init()->set_error('Incorrect data');
			} else {
				$id_client = get::get_unsigned_integer('id');
				$id_form = get::get_unsigned_integer('id_form');
				$ifa = (user::init()->get_ifa() && get::passed('ifa'));

				$form = new client_form($id_form);
				$client = new client($id_client);

				if (!$client->id) {
					user::init()->set_error('Incorrect data');
				} else {
					if (!file_exists($this->get_pdf_file_path($form, $ifa))) {
						$this->create_pdf($form, $ifa);
					}

					Header('Content-Type: application/pdf');
					Header('Content-Length: '.filesize($this->get_pdf_file_path($form, $ifa)));
					Header("Content-Disposition: attachment; filename=" . $this->get_pdf_file_name($form, $ifa));
					readfile($this->get_pdf_file_path($form, $ifa));

					die();
				}
			}
		} else {
			user::init()->set_error('Not enough data');
		}
		$this->index();
	}

	private function get_xml_file_name($form) {
		$client = new client($form->id_client);
		return str_replace(' ','-',trim($client->last_name)) . '-' . str_replace(' ','-',trim($client->first_name)) . '-' . str_replace(' ','-',$form->date) . '.xml';
	}

	private function get_pdf_file_name($form, $ifa) {
		$client = new client($form->id_client);
		if ($ifa) {
			return str_replace(' ','-',trim($client->last_name)) . '-' . str_replace(' ','-',trim($client->first_name)) . '-' . str_replace(' ','-',$form->date) . '-IFA.pdf';
		} else {
			return str_replace(' ','-',trim($client->last_name)) . '-' . str_replace(' ','-',trim($client->first_name)) . '-' . str_replace(' ','-',$form->date) . '.pdf';
		}
	}

	private function get_xml_file_path($form) {
		return SITE_PATH . DIRECTORY_SEPARATOR . 'files' . DIRECTORY_SEPARATOR . 'xml' . DIRECTORY_SEPARATOR . $this->get_xml_file_name($form);
	}

	private function get_pdf_file_path($form, $ifa) {
		return SITE_PATH . DIRECTORY_SEPARATOR . 'files' . DIRECTORY_SEPARATOR . 'pdf' . DIRECTORY_SEPARATOR . $this->get_pdf_file_name($form, $ifa);
	}

	private function create_xml($form) {
		$client = new client($form->id_client);

		$xml = new DomDocument('1.0','utf-8');

		$clientXML = $xml->appendChild($xml->createElement('client'));

		$idXML = $clientXML->appendChild($xml->createElement('id'));
		$idXML->appendChild($xml->createTextNode($client->id));

		$firstNameXML = $clientXML->appendChild($xml->createElement('first_name'));
		$firstNameXML->appendChild($xml->createTextNode($client->first_name));

		$lastNameXML = $clientXML->appendChild($xml->createElement('last_name'));
		$lastNameXML->appendChild($xml->createTextNode($client->last_name));

		$birthDateXML = $clientXML->appendChild($xml->createElement('birth_date'));
		$birthDateXML->appendChild($xml->createTextNode($client->birth_date));

		$dateXML = $clientXML->appendChild($xml->createElement('date'));
		$dateXML->appendChild($xml->createTextNode($form->date));

		$commentXML = $clientXML->appendChild($xml->createElement('comment'));
		$commentXML->appendChild($xml->createTextNode($form->comment));

		$rowsXML = $clientXML->appendChild($xml->createElement('rows'));

		if ($form->answers) {
			foreach ($form->answers as $answer) {
				$rowXML = $rowsXML->appendChild($xml->createElement('row'));

				$questionIdXML = $rowXML->appendChild($xml->createElement('question_id'));
				$questionIdXML->appendChild($xml->createTextNode($answer['id_question']));
				$questionTextXML = $rowXML->appendChild($xml->createElement('question_text'));
				$questionTextXML->appendChild($xml->createTextNode($answer['question']));

				$answerIdXML = $rowXML->appendChild($xml->createElement('answer_id'));
				$answerIdXML->appendChild($xml->createTextNode($answer['id_answer']));
				$answerTextXML = $rowXML->appendChild($xml->createElement('answer_text'));
				$answerTextXML->appendChild($xml->createTextNode($answer['answer']));
			}
		}

		$xml->save($this->get_xml_file_path($form));
	}

	private function create_pdf($form, $ifa) {
		$client = new client($form->id_client);

		$url = "http://" . WWW . SITE_HOST ."/pdf/show?id={$client->id}&id_form={$form->id}";
		if ($ifa) {
			$url .= "&ifa";
		}

		$report = array();
		exec( sprintf("/usr/local/bin/wkhtmltopdf --footer-spacing 3 --footer-center '%s' --footer-font-size 5 --no-stop-slow-scripts --javascript-delay 1000 --debug-javascript %s %s 2>&1", settings::init()->get('pdf_footer'), escapeshellarg($url), escapeshellarg($this->get_pdf_file_path($form, $ifa))) , $report);
		$resultString = implode(' ',$report);

		if(!file_exists($this->get_pdf_file_path($form, $ifa)) || mb_stripos($resultString, 'Done') === false || mb_stripos($resultString, 'warning') !== false || mb_stripos($resultString, 'error') !== false ) {
			file_put_contents(SITE_PATH . DIRECTORY_SEPARATOR . 'files' . DIRECTORY_SEPARATOR . 'pdf' . DIRECTORY_SEPARATOR . 'log.txt', $resultString, FILE_APPEND);
		}
	}

}

?>
