<?php

class analyzer {
	public static function get_data($form) {
		$letters = array("A", "B", "C", "D", "E", "F", "G", "H", "I", "J");

		$parameters = array();
		$parameters['Answers'] = array();

		$questions = client_form::get_questions_for_analizer();
		$form_answers = $form->getAnswers();

		foreach ($questions as $question) {
			$letter = "";
			if ($question['answers']) foreach ($question['answers'] as $key => $answer) {
				if ($answer['id'] == $form_answers[$question['id']]->getIdFormAnswer()) {
					$letter = $letters[$key];
					break;
				}
			}
			$parameters['Answers'][] = $letter;
		}

		for ($i = 0; $i < 5; $i++) {
			$parameters['ExpectedVols'][] = settings::init()->get('volatility_' . $i);
			$parameters['ExpectedReturns'][] = settings::init()->get('return_' . $i);
		}

		$parameters['VolCoefficient'] = 3;
		$parameters['VolMin'] = 4;
		$parameters['VolMax'] = 20;
		$parameters['TimeMin'] = 2;
		$parameters['TimeMax'] = 10;

		$parameters = json_encode($parameters);

		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, ANALYZER_HOST);
		curl_setopt($ch, CURLOPT_FAILONERROR, 1);
		curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($ch, CURLOPT_TIMEOUT, 20);
		curl_setopt($ch, CURLOPT_POST, 1);
		curl_setopt($ch, CURLOPT_POSTFIELDS, "$parameters");

		if (!curl_errno($ch)) {
			$output = curl_exec($ch);
		} else {
			user::init()->set_error('An error has occurred: ' . curl_error($ch));
		}

		curl_close($ch);

		$data = json_decode($output);

		return $data;
	}
}

?>
