<?php

class controller_downloads extends private_controller {

	private $allowed_extensions = array('jpg', 'png', 'jpeg', 'gif', 'pdf');
	private $allowed_size = 20;

	public function index($args = array()) {
		$id = get::passed('id') ? get::get_unsigned_integer('id') : (post::passed('id') ? post::get_unsigned_integer('id') : 0);

		$client = new client($id);
		$files = $client->get_user_files();
		$ifa_files = $client->get_user_ifa_files();

		$this->set('files', $files);
		$this->set('ifa_files', $ifa_files);
		$this->set('client', $client);
		$this->set('status_changes', $client->get_status_changes());
		$this->set('statuses', clients::get_statuses());

		$this->show_template();
	}

	public function download() {
		if (get::passed_not_empty(array('id', 'id_file'))) {
			if (!get::is_unsigned_integer('id')) {
				user::init()->set_error('Incorrect data');
			} elseif (!get::is_unsigned_integer('id_file')) {
				user::init()->set_error('Incorrect data');
			} else {
				$id_client = get::get_unsigned_integer('id');
				$id_file = get::get_unsigned_integer('id_file');

				$file = new client_file($id_file);
				$client = new client($id_client);

				if ($client->id) {
					$path = SITE_PATH . str_replace('/', DIRSEP,$file->path);
					$arr = explode(DIRSEP, $path);
					$file_name = array_pop($arr);
					$arr = explode('.', $file_name);
					$extension = strtolower(array_pop($arr));
					if ($extension == 'pdf') {
						$content_type = 'application/pdf';
					} else {
						if ($extension == 'jpg') {
							$content_type = 'image/jpeg';
						} else {
							$content_type = 'image/' . $extension;
						}
					}

					if (file_exists($path)) {
						Header('Content-Type: ' . $content_type);
						Header('Content-Length: '.filesize($path));
						Header("Content-Disposition: attachment; filename=" . $file_name);
						readfile($path);

						die();
					} else {
						user::init()->set_error('File does not exist');
					}
				} else {
					user::init()->set_error('Incorrect data');
				}
			}
		} else {
			user::init()->set_error('Not enough data');
		}
		$this->index();
	}

        public function upload() {
		if (isset($_FILES) && $_FILES) {
			$file_name_arr = explode('.', $_FILES['file']['name']);
			$extension = strtolower(isset($file_name_arr[count($file_name_arr) - 1]) ? $file_name_arr[count($file_name_arr) - 1] : '');
                        $upload_max_filesize = $this->return_bytes(ini_get('upload_max_filesize'));                       
			if (in_array($extension, $this->allowed_extensions)) {
                                if ($_FILES['file']['size'] <= $upload_max_filesize) {
				//if ($_FILES['file']['size'] <= $this->allowed_size * 1024 * 1024) {
					$id_client = post::passed('id') ? post::get_unsigned_integer('id') : 0;
					$title = post::passed('title') ? post::get_string('title') : '';
					$is_loa = post::passed('loa');

					$client = new client($id_client);
					if (!$client->id) {
						user::init()->set_error('Wrong client.');
					} else {
						$file_name = time(). '_' . $_FILES['file']['name'];
						$path = '/images/uploads/'.$file_name;
                                                $src_path = $_FILES['file']['tmp_name'];
                                                $dst_path = SITE_PATH.'images'.DIRSEP.'uploads'.DIRSEP.$file_name;
                                                if (copy($src_path, $dst_path)) {
						//if (copy($_FILES['file']['tmp_name'], SITE_PATH.'images'.DIRSEP.'uploads'.DIRSEP.$file_name)) {

							$client->restore();

							$file = new client_file(array(
								'id_client' => $client->get_id(),
								'title' => $title,
								'path' => $path,
								'is_loa' => $is_loa,
								'id_user' => user::init()->get_id()
							));
							$file->save();

							$_POST['id_client_file'] = $file->get_id();
							$_POST['path'] = $path;
							user::init()->add_action_log("Add file to client", $client->get_id());

							$this->redirect('downloads?success&add&id=' . $client->get_id());
						} else {
							user::init()->set_error('An error occurred while copying the file.');
						}
					}

				} else {
					user::init()->set_error('The file is too large. Allowed size - ' . $this->allowed_size . ' MB.');
				}
			} else {
				user::init()->set_error('Wrong file extension (allowed extensions: ' . implode(', ', $this->allowed_extensions) . ').');
			}
		}
		$this->index();
	}

	public function save() {
		if (post::passed_not_empty('id')) {
			if (!post::is_unsigned_integer('id')) {
				user::init()->set_error('Incorrect data (Id)');
			} else {
				$id = post::get_unsigned_integer('id');
				$id_status = post::passed('id_status') ? post::get_unsigned_integer('id_status') : 0;

				$client = new client($id);
				if ($id_status) {
					$client->id_status = $id_status;
				}
				$client->save();
				user::init()->add_action_log("Edit client", $client->get_id());

				$this->redirect('downloads?success&edit&id=' . $client->get_id());
			}
		} else {
			user::init()->set_error('Not enough data');
		}
		$this->edit();
	}

	public function delete() {
		if (get::passed_not_empty(array('id', 'id_file'))) {
			if (!get::is_unsigned_integer('id') || !get::is_unsigned_integer('id_file')) {
				user::init()->set_error('Incorrect data (Id)');
			} else {
				$id = get::get_unsigned_integer('id');
				$id_file = get::get_unsigned_integer('id_file');

				$file = new client_file($id_file);
				$client = new client($id);

				if ($file->get_id() && $client->get_id()) {
					$_POST['id_client_file'] = $file->get_id();
					$_POST['title'] = $file->get_title();
					$_POST['path'] = $file->get_path();
					$file->delete();
					user::init()->add_action_log("Delete client file", $id);

					$this->redirect('downloads?success&delete&id=' . $client->get_id());
				} else {
					user::init()->set_error('Wrong data (Id)');
				}
			}
		} else {
			user::init()->set_error('Not enough data');
		}
		$this->index();
	}

	public function upload_xml() {
		if (isset($_FILES) && $_FILES) {
			$file_name_arr = explode('.', $_FILES['file']['name']);
			$extension = strtolower(isset($file_name_arr[count($file_name_arr) - 1]) ? $file_name_arr[count($file_name_arr) - 1] : '');
			if (strtolower($extension) == 'xml') {
				if ($_FILES['file']['size'] <= 1 * 1024 * 1024) {

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
//							$form->answers = $answers;
							$form->save();

							$this->redirect('downloads?success&upload&id=' . $client->get_id());
						}

					}

				} else {
					user::init()->set_error('The file is too lagre.');
				}
			} else {
				user::init()->set_error('Wrong file extension (XML expected).');
			}
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

}

?>
