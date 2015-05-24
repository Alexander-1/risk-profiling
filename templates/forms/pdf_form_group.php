<? foreach ($current_group->getQuestions() as $j => $question): ?>

	<div class="row row-border-top">
		<label><?= $question->getText() ?></label>
		<div <?= ($question->getType() == 'table' ? '' : 'class="rowright"' )?>>

			<? 	switch ($question->getType()) {

				case 'select': ?>
						<? if ($question->getAnswers()) foreach ($question->getAnswers() as $k => $answer): ?>
							<?= $form_question[$question->getId()] == $answer->getId() ? '<i class="tick"></i><strong>' : '' ?>
							<?= $letters[$k] ?>. <?= $answer->getText() ?><br />
							<?= $form_question[$question->getId()] == $answer->getId() ? '</strong>' : '' ?>
						<? endforeach; ?>
					<? break; ?>

				<?	case 'text':
					case 'date':
					case 'textarea': ?>
					<?= isset($form_question[$question->getId()]) ? $form_question[$question->getId()] : '' ?>
					<? break; ?>

				<? case 'table': ?>

					<? if ($question->getTableColumns()): ?>
						<table class="pdf_form_table">
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
											<td>
												<?= isset($form_question[$question->getId()][$table_column->getId()][$i-1]) ? $form_question[$question->getId()][$table_column->getId()][$i-1] : '' ?>&nbsp;
											</td>
											<? break; ?>

										<? case 'checkbox': ?>
											<td>
												<?= isset($form_question[$question->getId()][$table_column->getId()][$i-1]) ? '<i class="tick"></i>' : '' ?>&nbsp;
											</td>
											<? break; ?>

										<? default: ?>
											<? $values = explode(',',$table_column->getType()); ?>
											<td><?= $values[$i-1] ?>&nbsp;</td>
										<? break; ?>

									<? } ?>
									<? endif;?>

								<? endforeach; ?>
								</tr>
							<? endfor; ?>
							</tbody>
						</table>
					<? endif; ?>

					<? break; ?>

				<? } ?>

		</div>
		<div class="clear"></div>
	</div>

<? endforeach; ?>
