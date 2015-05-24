<? foreach ($current_group->getQuestions() as $j => $question): ?>

	<div class="row no-border">
		<label><?= $question->getText() ?></label>
		<div <?= ($question->getType() == 'table' ? '' : 'class="rowright"' )?>>

			<? 	switch ($question->getType()) {

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

					<? if ($question->getTableColumns()): ?>
						<table class="form_table">
							<thead>
							<? for ($i = 0; $i < 5; $i++): ?>
								<? if ($i == 1): ?>
									</thead>
									<tbody>
								<? endif;?>
								<tr>
								<? foreach ($question->getTableColumns() as $table_column): ?>

									<? if ($i == 0): ?>
										<th><?= $table_column->getTitle() ?></th>
									<? else: ?>

									<? switch ($table_column->getType()) {

										case 'text': ?>
											<td><input type="text"
												   name="question[<?= $question->getId() ?>][<?= $table_column->getId() ?>][<?= $i-1 ?>]"
												   value="<?= isset($form_question[$question->getId()][$table_column->getId()][$i-1]) ? $form_question[$question->getId()][$table_column->getId()][$i-1] : '' ?>" /></td>
											<? break; ?>

										<? case 'checkbox': ?>
											<td><input type="checkbox"
												name="question[<?= $question->getId() ?>][<?= $table_column->getId() ?>][<?= $i-1 ?>]"
												<?= isset($form_question[$question->getId()][$table_column->getId()][$i-1]) ? 'checked="checked"' : '' ?>/></td>
											<? break; ?>

										<? default: ?>
											<? $values = explode(',',$table_column->getType()); ?>
											<td><?= $values[$i-1] ?></td>
										<? break; ?>

									<? } ?>
									<? endif;?>

								<? endforeach; ?>
								</tr>
							<? endfor; ?>
							</tbody>
						</table>
					<? endif; ?>

					<img src="/images/icons/system-tick-alt-02.png" id="img<?= $question->getId() ?>" class="icon check_question" alt="" />
					<? break; ?>

				<? } ?>

		</div>
		<div class="clear"></div>
	</div>

<? endforeach; ?>
