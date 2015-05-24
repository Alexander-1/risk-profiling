<?
if ($form->get_id()) {

	$answers = $form->getAnswers();
	$form_question = $form_questions;

	$letters = array('A', 'B', 'C', 'D', 'E', 'F', 'G', 'H', 'I', 'J');
	?>
	<script>

		var formChanged = false;
		var formArea = [];
		<? if ($areas) foreach ($areas as $area): ?>
		formArea[<?= $area->getId() ?>] = <?= json_encode($area->getGroupsIds()) ?>;
		<? endforeach; ?>

		var areYouSure = function () {
			return (!formChanged || confirm('Are you sure you want to do this without saving changes?'));
		}

		$(document).ready(function () {

			$('button[disabled="disabled"] span').click(function () {
					alert('All questions must be answered')
				}
			)

			changeFormArea();
		})
	</script>

	<div class="row">
		<a href="/clients">Back to List of Clients</a>
		<ul class="arrow">
			<li><strong>Client name: </strong><?= $client->last_name . ', ' . $client->first_name ?></li>
			<li><strong>Birth date: </strong><?= control::get_date_without_time_from_db($client->birth_date) ?></li>
		</ul>
	</div>

	<form action="/<?= $current_menu->get_name() ?>/save?id=<?= $client->id ?>" id="edit_client_form" class="client_form" method="post">
		<input type="hidden" name="id" value="<?= $client->id ?>"/>

		<div class="row">
			<h3>Edit client form <small><?= control::get_date_from_db($form->date) ?></small></h3>

			<div>
				<? if ($areas) foreach ($areas as $area): ?>
					<input type="checkbox" name="area[]" value="<?= $area->getId() ?>"
						   id="form-area-<?= $area->getId() ?>" data-id="<?= $area->getId() ?>"
						   <?= in_array($area->getId(), $form->getAreasIds()) ? 'checked="checked"' : "" ?>
						   onchange="changeFormArea();" class="form_area"/>
					<label for="form-area-<?= $area->getId() ?>" style="width: inherit;"><?= $area->getTitle() ?></label>
				<? endforeach; ?>
			</div>
		</div>

		<div class="tabs ui-tabs ui-widget ui-widget-content ui-corner-all">
			<div class="tabmenu bold-top-border">
				<ul class="ui-tabs-nav ui-helper-reset ui-helper-clearfix ui-widget-header ui-corner-all">
					<? if ($groups) foreach ($groups as $i => $group): ?>
						<li class="ui-state-default ui-corner-top <?= $i == 0 ? "ui-tabs-selected ui-state-active" : "" ?>">
							<a href="#tabs-<?= $i ?>"><?= $i + 1 ?><span
									class="group_star group_star_<?= $group->getId() ?>">*</span></a>
						</li>
					<? endforeach; ?>
				</ul>
			</div>

			<? if ($groups) foreach ($groups as $i => $group): ?>
				<div class="tab ui-tabs-panel ui-widget-content ui-corner-bottom" id="tabs-<?= $i ?>">
					<div class="row">
						<h4><?= $i + 1 ?>. <?= $group->getText() ?><span
								class="group_star group_star_<?= $group->getId() ?>">*</span></h4>
						<small class="group_text group_text_<?= $group->getId() ?>">* - Mandatory part</small>

						<?
						$current_group = $group;
						include (SITE_PATH . registry::get('templates_directory')) . DIRSEP . 'forms' . DIRSEP . 'form_group.php';
						?>

						<? foreach ($group->getSubgroups() as $subgroup): ?>
							<h5><?= $subgroup->getText() ?></h5>
							<?
							$current_group = $subgroup;
							include (SITE_PATH . registry::get('templates_directory')) . DIRSEP . 'forms' . DIRSEP . 'form_group.php';
							?>
						<? endforeach; ?>

					</div>
				</div>
			<? endforeach; ?>
		</div>

		<? if (user::init()->get_ifa()): ?>
			<div class="row bold-top-border">
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