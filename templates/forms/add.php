<?
	$letters = array('A', 'B', 'C', 'D', 'E', 'F', 'G', 'H', 'I', 'J');
?>
	<div class="row">
		<a href="clients">Back to List of Clients</a>
		<ul class="arrow">
			<li><strong>Client name: </strong><?= $client->last_name . ', ' . $client->first_name ?></li>
			<li><strong>Birth date: </strong><?= control::get_date_without_time_from_db($client->birth_date) ?></li>
		</ul>
	</div>
	<div class="row" id="add_client_form_button" <?= (post::passed('first_name') && !get::passed('success')) ? 'style="display: none;"' : '' ?>>
		<button type="button" class="medium green" onclick="$('#add_client_form').slideDown(); $('#add_client_form_button').slideUp();"><span>Add new form</span></button>
		<form action="/<?=$current_menu->get_name()?>/upload" method="post" enctype="multipart/form-data" style="float: right;">
				<input type="file" name="file" />
				<button type="submit" class="medium green" style="margin-top: 0;"><span>Upload</span></button>
				<div><small>You can upload XML file with form</small></div>
		</form>
	</div>

	<form action="/<?= $current_menu->get_name() ?>/add?id=<?= $client->id ?>" <?= (post::passed('question') && !get::passed('success')) ? '' : 'style="display: none;"' ?> id="add_client_form" method="post">
		<input type="hidden" name="id" value="<?= $client->id ?>" />
	<? foreach ($questions as $question): ?>
			<div class="row" style="border-top: 1px solid #e5e5e5;">
				<label style="width: 450px; padding-right: 10px;"><?= $question['text'] ?></label>
				<div class="rowright">
					<select name="question[<?= $question['id'] ?>]" onchange="setQuestion($(this));" data-id="<?= $question['id'] ?>">
						<option value="0">&nbsp;</option>
						<? foreach ($question['answers'] as $i => $answer): ?>
							<option value="<?= $answer['id'] ?>" <?= isset($form_question[$question['id']]) && $form_question[$question['id']] == $answer['id'] ? 'selected="selected"' : '' ?>><?= $letters[$i] ?></option>
						<? endforeach; ?>
					</select>
					<img src="/images/icons/system-tick-alt-02.png" id="img<?= $question['id'] ?>" class="icon" alt="" style="display: none;">
					<div style="margin-top: 15px;">
						<? foreach ($question['answers'] as $i => $answer): ?>
							<?= $letters[$i] ?>. <?= $answer['text'] ?><br />
						<? endforeach; ?>
					</div>
				</div>
				<div style="clear: both;"></div>
			</div>
	<? endforeach; ?>

	<? if (user::init()->get_ifa()): ?>
		<div class="row">
			<label>Comment:</label>
			<textarea name="comment"><?= $comment ?></textarea>
		</div>
	<? endif; ?>

		<div class="row">
			<button type="submit" class="medium green"><span>Add</span></button>
			<button type="button" class="medium grey" onclick="resetForm();"><span>Reset</span></button>
			<button type="button" class="medium grey" onclick="$('#add_client_form').slideUp(); $('#add_client_form_button').slideDown();"><span>Cancel</span></button>
		</div>
	</form>
