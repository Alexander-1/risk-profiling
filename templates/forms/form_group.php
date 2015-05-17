<? foreach ($current_group->getQuestions() as $j => $question): ?>

	<div class="row no-border">
		<label><?= $question->getText() ?></label>
		<div class="rowright">

			<? switch ($question->getType()) {

				case 'select': ?>
					<select name="question[<?= $question->getId() ?>]"
							onchange="setQuestion($(this));"
							data-id="<?= $question->getId() ?>"
							data-type="select" >
						<option value="0">&nbsp;</option>
						<? if ($question->getAnswers()) foreach ($question->getAnswers() as $k => $answer): ?>
							<option
								value="<?= $answer->getId() ?>"
								<?= isset($form_question[$question->getId()]) && $form_question[$question->getId()] == $answer->getId() ? 'selected="selected"' : '' ?>
								><?= $letters[$k] ?></option>
						<? endforeach; ?>
					</select>
					<img src="/images/icons/system-tick-alt-02.png" id="img<?= $question->getId() ?>" class="icon check_question" alt="" />
					<div style="margin-top: 15px;">
						<? if ($question->getAnswers()) foreach ($question->getAnswers() as $k => $answer): ?>
							<?= $letters[$k] ?>. <?= $answer->getText() ?><br />
						<? endforeach; ?>
					</div>
					<? break; ?>

				<? case 'text': ?>
					<input type="text" name="question[<?= $question->getId() ?>]"
						   value="<?= isset($form_question[$question->getId()]) ? $form_question[$question->getId()] : '' ?>"
						   onchange="setQuestion($(this));"
						   data-id="<?= $question->getId() ?>"
						   data-type="text" />
					<img src="/images/icons/system-tick-alt-02.png" id="img<?= $question->getId() ?>" class="icon check_question" alt="" />
					<? break; ?>

				<? case 'date': ?>
					<input type="text" name="question[<?= $question->getId() ?>]"
						   value="<?= isset($form_question[$question->getId()]) ? $form_question[$question->getId()] : '' ?>"
						   onchange="setQuestion($(this));"
						   placeholder="DD.MM.YYYY"
						   data-id="<?= $question->getId() ?>"
						   data-type="date"/>
					<img src="/images/icons/system-tick-alt-02.png" id="img<?= $question->getId() ?>" class="icon check_question" alt="" />
					<? break; ?>

				<? case 'textarea': ?>
					<textarea name="question[<?= $question->getId() ?>]" data-id="<?= $question->getId() ?>" data-type="textarea"><?= isset($form_question[$question->getId()]) ? $form_question[$question->getId()] : '' ?></textarea>
					<? break; ?>
					<img src="/images/icons/system-tick-alt-02.png" id="img<?= $question->getId() ?>" class="icon check_question" alt="" />

				<? case 'table': ?>
					table
					<img src="/images/icons/system-tick-alt-02.png" id="img<?= $question->getId() ?>" class="icon check_question" alt="" />
					<? break; ?>

				<? } ?>

		</div>
		<div class="clear"></div>
	</div>

<? endforeach; ?>
