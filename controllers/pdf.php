<?php

class controller_pdf extends public_controller {

	public function index($args = array()) {
		$this->redirect();
	}

	public function show() {
		$this->show_pdf('pdf');
	}

	public function show_header() {
		$css_files = $this->template->get_css_files();
		$script_files = $this->template->get_script_files();

		include (registry::get('templates_directory') . DIRSEP . 'forms' . DIRSEP . 'pdf_header.php');
	}

	public function show_footer() {
		$css_files = $this->template->get_css_files();
		$script_files = $this->template->get_script_files();

		include (registry::get('templates_directory') . DIRSEP . 'forms' . DIRSEP . 'pdf_footer.php');
	}

	public function show_pdf($template) {
		$id_form = get::passed('id_form') ? get::get_unsigned_integer('id_form') : (post::passed('id_form') ? post::get_unsigned_integer('id_form') : 0);
		$id = get::passed('id') ? get::get_unsigned_integer('id') : (post::passed('id') ? post::get_unsigned_integer('id') : 0);

		$client = new client($id);

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
					$portfolio['volatility'] += settings::init()->get('volatility_' . $i) * $data->IPMbship[$i];
				}
				$portfolio['return'] = round($portfolio['return'], 2);
				$portfolio['volatility'] = round($portfolio['volatility'], 2);

				foreach ($data->IPMbship as $key => $value) {
					$data->IPMbship[$key] = round($value * 100, 2);
				}

				$this->set('data', $data);
				$this->set('portfolio', $portfolio);
			}
		}

		$this->set('form', $form);

		$css_files = $this->template->get_css_files();
		$script_files = $this->template->get_script_files();

		$groups = client_form::get_form_groups();
		$areas = client_form::get_form_areas();

		$form_question = array();
		if ($form->getAnswers()) {
			foreach ($form->getAnswers() as $id_question => $answer) {
				$question = new form_question($id_question);
				switch ($question->getType()) {
					case 'select':
						$form_question[$question->getId()] = $answer->getIdFormAnswer();
						break;
					case 'text':
					case 'date':
					case 'textarea':
						$form_question[$question->getId()] = $answer->getText();
						break;
					case 'table':
						if ($answer) foreach ($answer as $ans) {
							$form_question[$question->getId()][$ans->getIdFormTableColumn()][] = $ans->getText();
						}
						break;
				}
			}
		}

		$form_areas = $form->getAreas();
		$required_groups = array();
		if ($form_areas) foreach ($form_areas as $form_area) {
			$required_groups = array_merge($required_groups, $form_area->getGroupsIds());
		}
		$required_groups = array_unique($required_groups);

		include (registry::get('templates_directory') . DIRSEP . 'forms' . DIRSEP . $template . '.php');
	}

}

?>