<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of documents
 *
 * @author Александр
 */
class controller_documents extends private_controller {

	private static $types = array(
		'Lead Generation Docs' => 0,
		'Application Forms and T&Cs' => 1,
		'Manuals and Scripts' => 2
	);

	public function index($args = array()) {

		$this->set('title', 'Documents');

		$docs = array();
		foreach (controller_documents::$types as $type_name => $type) {

			$docs[$type_name] = $this->get_documents_by_type($type);
		}
		$this->set('documents', $docs);

		$this->show_template();
	}

	private function get_documents_by_type($type) {

		$documents = array();

		$rows = db::init()
			->query(document::$fields)
			->from(document::$table_name)
			->where(array('type', '=', $type))
			->get_all();

		foreach ($rows as $row) {

			$documents[] = new document($row);
		}

		return $documents;
	}

	public function upload() {

		$this->try_exec_method('upload_impl', 'uploading');
	}

	private function upload_impl() {

		if (!get::passed_not_empty(array('type'))) {

			throw new InvalidArgumentException('Missing arguemnt: ');
		}

		if (!get::is_unsigned_integer('type')) {

			throw new InvalidArgumentException('Inavlid argument: ');
		}

		if (!isset($_FILES) || !$_FILES) {

			throw new Exception('There is no fies set for uploading');
		}

		$error_code = $_FILES['document']['error'];
		if ($error_code !== UPLOAD_ERR_OK) {

			throw new UploadException($error_code);
		}

		$file_name = time() . '_' . $_FILES['document']['name'];
		$path = '/images/uploads/' . $file_name;
		$src_path = $_FILES['document']['tmp_name'];
		$dst_path = SITE_PATH . 'images' . DIRSEP . 'uploads' . DIRSEP . $file_name;
		if (!copy($src_path, $dst_path)) {

			throw new ErrorException("internal server error while copying files");
		}

		$document = new document(array(
			'type' => get::get_unsigned_integer('type'),
			'title' => post::passed('title') ? post::get_string('title') : '',
			'path' => $path,
		));
		$document->save();

		user::init()->add_action_log("Uplload document: ", $document->get_path());
	}

	public function download() {

		$this->try_exec_method('download_impl', 'downloading');
	}

	private function download_impl() {

		if (!get::passed_not_empty(array('id'))) {

			throw new InvalidArgumentException('No document ID was provided');
		}

		if (!get::is_unsigned_integer('id')) {

			throw new InvalidArgumentException('Invalid documents ID');
		}

		$id = get::get_unsigned_integer('id');
		$document = document::load($id);
		$path = realpath(SITE_PATH . $document->get_path());
		if (!file_exists($path)) {

			throw new ErrorException('File does not exsist on the server');
		}

		if (ob_get_level()) {

			ob_end_clean();
		}

		list($timespamp, $filename) = explode('_', basename($path), 2);
		header('Content-Description: File Transfer');
		header('Content-Type: application/octet-stream');
		header('Content-Disposition: attachment; filename="' . $filename . '"');
		header('Content-Transfer-Encoding: binary');
		header('Expires: 0');
		header('Cache-Control: must-revalidate');
		header('Pragma: public');
		header('Content-Length: ' . filesize($path));

		readfile($path);
	}

	public function delete() {

		$this->try_exec_method('delete_impl', 'deleting');
	}

	private function delete_impl() {

		if (!get::passed_not_empty(array('id'))) {

			throw new Exception('Not enough data');
		}

		if (!get::is_unsigned_integer('id')) {

			throw new Exception('Incorrect data');
		}

		$id = get::get_unsigned_integer('id');

		$document = document::load($id);
		$document->delete();
		$path = realpath(SITE_PATH . $document->get_path());
		unlink($path);

		user::init()->add_action_log('Delete document "' . $document->get_path() . '"');
	}

	private function try_exec_method($method, $action_name) {

		try {

			$this->$method();
			$this->index();

			return TRUE;

		} catch (Exception $ex) {

			user::init()->set_error('Error ' . $action_name . ' document : ' . $ex->getMessage());

			return FALSE;
		}
	}

}
