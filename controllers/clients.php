<?php

class controller_clients extends private_controller {

        private $allowed_size = 20;

	public function index($args = array()) {
		$page = post::passed('page') ? post::get_unsigned_integer('page') : 1;

		$this->set('first_name', post::passed('first_name') ? post::get_string('first_name') : '');
		$this->set('last_name', post::passed('last_name') ? post::get_string('last_name') : '');
		$this->set('birth_date', post::passed('birth_date') ? post::get_date_without_time('birth_date') : '');
		$this->set('page', $page);

		$this->set('count', clients::get_count_of_users_clients());
		$this->set('clients', clients::get_users_clients($page));

		$this->set('title', 'Table of clients');

		$this->show_template();
	}

	public function edit() {
		$id = get::passed('id') ? get::get_unsigned_integer('id') : (post::passed('id') ? post::get_unsigned_integer('id') : 0);

		$client = new client($id);
		$this->set('client', $client);
		$this->set('statuses', clients::get_statuses());

		$this->index();
	}

	public function add() {
		if (post::passed_not_empty(array('first_name', 'last_name', 'birth_date'))) {
			if (!post::is_string('first_name')) {
				user::init()->set_error('Incorrect data (First name)');
			} elseif (!post::is_string('last_name')) {
				user::init()->set_error('Incorrect data (Last name)');
			} elseif (!post::is_date('birth_date')) {
				user::init()->set_error('Incorrect data (Birth date)');
			} else {
				$first_name = post::get_string('first_name');
				$last_name = post::get_string('last_name');
				$birth_date = post::get_date_without_time_to_db('birth_date');

				$client = new client(array(
					'first_name' => $first_name,
					'last_name' => $last_name,
					'birth_date' => $birth_date,
					'id_user' => user::init()->get_id()
				));
				$client->save();
				user::init()->add_action_log("Add client", $client->get_id());

				$this->redirect('clients?success&add');
			}
		} else {
			user::init()->set_error('Not enough data');
		}
		$this->index();
	}

	public function save() {
		if (post::passed_not_empty(array('id', 'first_name', 'last_name', 'birth_date'))) {
			if (!post::is_unsigned_integer('id')) {
				user::init()->set_error('Incorrect data (Id)');
//			} elseif (!post::is_unsigned_integer('id_status')) {
//				user::init()->set_error('Incorrect data (Status)');
			} elseif (!post::is_string('first_name')) {
				user::init()->set_error('Incorrect data (First name)');
			} elseif (!post::is_string('last_name')) {
				user::init()->set_error('Incorrect data (Last name)');
			} elseif (!post::is_date('birth_date')) {
				user::init()->set_error('Incorrect data (Birth date)');
			} else {
				$id = post::get_unsigned_integer('id');
				$first_name = post::get_string('first_name');
				$last_name = post::get_string('last_name');
				$birth_date = post::get_date_without_time_to_db('birth_date');
				$id_status = post::passed('id_status') ? post::get_unsigned_integer('id_status') : 0;

				$client = new client($id);
				$client->birth_date = $birth_date;
				$client->last_name = $last_name;
				$client->first_name = $first_name;
				if ($id_status) {
					$client->id_status = $id_status;
				}
				$client->save();
				user::init()->add_action_log("Edit client", $client->get_id());

				$this->redirect('clients?success&edit');
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

				$client = new client($id);
				$client->delete();
				user::init()->add_action_log("Delete client", $id);

				$this->redirect('clients?success&delete');
			}
		} else {
			user::init()->set_error('Not enough data');
		}
		$this->index();
	}

        function return_bytes($val) {
            $val = trim($val);
            $last = strtolower($val[strlen($val) - 1]);
            switch ($last) {
                // The 'G' modifier is available since PHP 5.1.0
                case 'g':
                    $val *= 1024;
                case 'm':
                    $val *= 1024;
                case 'k':
                    $val *= 1024;
            }

            return $val;
        }

	public function upload() {
		if (isset($_FILES) && $_FILES) {
			$file_name_arr = explode('.', $_FILES['file']['name']);
			$extension = strtolower(isset($file_name_arr[count($file_name_arr) - 1]) ? $file_name_arr[count($file_name_arr) - 1] : '');
                        $upload_max_filesize = $this->return_bytes(ini_get('upload_max_filesize'));
			if (strtolower($extension) == 'xml') {
                                if ($_FILES['file']['size'] <= $upload_max_filesize) {
				//if ($_FILES['file']['size'] <= $this->allowed_size * 1024 * 1024) {
					$xml = new DomDocument('1.0','utf-8');
					$xml->loadXML(file_get_contents($_FILES['file']['tmp_name']));

					$clientXML = $xml->documentElement;
					$propertiesXML = $clientXML->childNodes;

					$client_arr = array();

					for ($i = 0; $i < $propertiesXML->length; $i++) {
						switch ($propertiesXML->item($i)->tagName) {
							case 'id':
								$client_arr['id'] = control::get_unsigned_integer($propertiesXML->item($i)->nodeValue);
								break;
							case 'first_name':
								$client_arr['first_name'] = $propertiesXML->item($i)->nodeValue;
								break;
							case 'last_name':
								$client_arr['last_name'] = $propertiesXML->item($i)->nodeValue;
								break;
							case 'birth_date':
								$client_arr['birth_date'] = $propertiesXML->item($i)->nodeValue;
								break;
							case 'date':
								$form_arr['date'] = control::get_date_from_db($propertiesXML->item($i)->nodeValue);
								break;
							case 'comment':
								$form_arr['comment'] = $propertiesXML->item($i)->nodeValue;
								break;
							case 'rows':
								$rowsXML = $propertiesXML->item($i)->childNodes;
								for ($j = 0; $j < $rowsXML->length; $j++) {
									$rowXML = $rowsXML->item($j)->childNodes;
									for ($k = 0; $k < $rowXML->length; $k++) {
										switch ($rowXML->item($k)->tagName) {
											case 'question_id':
												$form_arr['answers'][$j]['id_question'] = control::get_unsigned_integer($rowXML->item($k)->nodeValue);
												break;
											case 'answer_id':
												$form_arr['answers'][$j]['id_answer'] = control::get_unsigned_integer($rowXML->item($k)->nodeValue);
												break;
										}
									}
								}
								break;
						}
					}

					if (!$client_arr || !$form_arr || !$form_arr['answers']) {
						user::init()->set_error('Wrong XML content.');
					} else {
						$client = new client($client_arr['id']);
						if (!$client->id) {
							user::init()->set_error('Wrong client.');
						} else {
							$client->restore();

							$form = new client_form();
							$form->id_client = $client_arr['id'];
							$form->comment = $form_arr['comment'];
							$form->date = control::get_date_to_db($form_arr['date']);

							$answers = array();
							foreach ($form_arr['answers'] as $answer) {
								$answers[$answer['id_question']] = $answer['id_answer'];
							}
							$form->answers = $answers;
							$form->save();

							$this->redirect('clients?success&upload');
						}

					}

				} else {
					user::init()->set_error('The file is too large.');
				}
			} else {
				user::init()->set_error('Wrong file extension (XML expected).');
			}
		}
		$this->index();
	}

}

?>
