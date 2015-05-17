<?
if ($form->get_id()) {
	$letters = array('A', 'B', 'C', 'D', 'E', 'F', 'G', 'H', 'I', 'J');
	?>
	<script>
		var formChanged = false;
		function areYouSure() {
			return (!formChanged || confirm('Are you sure you want to do this without saving changes?'));
		}
		$(document).ready(function () {
			$('button[disabled="disabled"] span').click(function () {
					alert('All questions must be answered')
				}
			)
		})
	</script>
	<form action="/<?= $current_menu->get_name() ?>/save?id=<?= $client->id ?>" id="edit_client_form" method="post">
		<? foreach ($questions as $question): ?>
			<div class="row">
				<label style="width: 450px; padding-right: 10px;"><?= $question['text'] ?></label>

				<div class="rowright">
					<select name="question[<?= $question['id'] ?>]" onchange="setQuestion($(this)); formChanged = true;"
							data-id="<?= $question['id'] ?>">
						<option value="0">&nbsp;</option>
						<? foreach ($question['answers'] as $i => $answer): ?>
							<option
								value="<?= $answer['id'] ?>" <?= (isset($form->answers[$question['id']]) && $answer['id'] == $form->answers[$question['id']]['id_answer']) ? 'selected="selected"' : '' ?>><?= $letters[$i] ?></option>
						<? endforeach; ?>
					</select>
					<img src="/images/icons/system-tick-alt-02.png" id="img<?= $question['id'] ?>" class="icon" alt=""
						 style="display: none;">

					<div style="margin-top: 15px;">
						<? foreach ($question['answers'] as $i => $answer): ?>
							<?= $letters[$i] ?>. <?= $answer['text'] ?><br/>
						<? endforeach; ?>
					</div>
				</div>
				<div style="clear: both;"></div>
			</div>
		<? endforeach; ?>

		<? if (user::init()->get_ifa()): ?>
			<div class="row">
				<label>Comment:</label>
				<textarea name="comment" onchange="formChanged = true;"><?= $form->comment ?></textarea>
			</div>
		<? else: ?>
			<div class="row">
				<label>Comment:</label>

				<div style="padding-top: 6px;"><?= $form->comment ?>&nbsp;</div>
			</div>
		<? endif; ?>

		<div class="row">
			<input type="hidden" name="id" value="<?= $client->id ?>"/>
			<input type="hidden" name="id_form" value="<?= $form->id ?>"/>
			<button type="submit" class="medium green"><span>Save</span></button>
			<button type="button" class="medium grey" onclick="if (areYouSure()) {resetForm();}"><span>Reset</span>
			</button>
			<button type="button" class="medium grey"
					onclick="if (areYouSure()) {document.location.href='/<?= $current_menu->get_name() ?>?id=<?= $client->id ?>'}">
				<span>Cancel</span></button>
			<button
				<?= $form->is_full() ? '' : 'disabled="disabled"' ?>
				type="button" class="medium grey" style="float: right"
				onclick="if (areYouSure()) {document.location.href='/<?= $current_menu->get_name() ?>/xml?id=<?= $client->id ?>&id_form=<?= $form->id ?>'}">
				<span>Download XML</span>
			</button>

			<? if (user::init()->get_ifa()): ?>
				<button
					<?= $form->is_full() ? '' : 'disabled="disabled"' ?>
					type="button" class="medium grey" style="float: right"
					onclick="if (areYouSure()) {document.location.href='/<?= $current_menu->get_name() ?>/pdf?id=<?= $client->id ?>&id_form=<?= $form->id ?>'}">
					<span>Download PDF</span>
				</button>
				<button
					<?= $form->is_full() ? '' : 'disabled="disabled"' ?>
					type="button" class="medium grey" style="float: right"
					onclick="if (areYouSure()) {document.location.href='/<?= $current_menu->get_name() ?>/pdf?id=<?= $client->id ?>&id_form=<?= $form->id ?>&ifa'}">
					<span>Download PDF (IFA)</span>
				</button>
			<? else: ?>
				<button
					<?= $form->is_full() ? '' : 'disabled="disabled"' ?>
					type="button" class="medium grey" style="float: right"
					onclick="if (areYouSure()) {document.location.href='/<?= $current_menu->get_name() ?>/pdf?id=<?= $client->id ?>&id_form=<?= $form->id ?>'}">
					<span>Download PDF</span>
				</button>
			<? endif; ?>

			<!--<button type="button" class="medium grey" style="float: right" onclick="document.location.href='/<?= $current_menu->get_name() ?>/send?id=<?= $client->id ?>&id_form=<?= $form->id ?>'"><span>Send PDF by Email</span></button>-->
			<button
				<?= $form->is_full() ? '' : 'disabled="disabled"' ?>
				type="button" class="medium grey" style="float: right"
				onclick="if (areYouSure()) {document.location.href='/<?= $current_menu->get_name() ?>/analyze?id=<?= $client->id ?>&id_form=<?= $form->id ?>'}">
				<span>Analyze</span>
			</button>
		</div>
	</form>
<?
} else {
	echo '<div class="row">There is no such form. Possibly it was deleted.</div>';
}
?>
