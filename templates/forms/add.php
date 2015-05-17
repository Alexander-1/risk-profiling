<?
	$letters = array('A', 'B', 'C', 'D', 'E', 'F', 'G', 'H', 'I', 'J');
?>
<script>

	var formArea = [];
	<? if ($areas) foreach ($areas as $area): ?>
		formArea[<?= $area->getId() ?>] = <?= json_encode($area->getGroupsIds()) ?>;
	<? endforeach; ?>


	$(document).ready(function(){

		$("#show_add_client_form").click(function(){
			$('.add_client_form').slideDown();
			$('.client_forms').slideUp();
		})
		$("#reset_form").click(function(){
			resetForm();
		})

		$("#hide_add_client_form").click(function(){
			$('.add_client_form').slideUp();
			$('.client_forms').slideDown();
		})

		<? if (post::passed('question') && !get::passed('success')): ?>
			$('.add_client_form').show();
			$('.client_forms').hide();
		<? else: ?>
			$('.add_client_form').hide();
			$('.client_forms').show();
		<? endif; ?>

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

	<div class="row client_forms">
		<button type="button" class="medium green" id="show_add_client_form"><span>Add new form</span></button>
		<form action="/<?=$current_menu->get_name()?>/upload" method="post" enctype="multipart/form-data" style="float: right;">
				<input type="file" name="file" />
				<button type="submit" class="medium green" style="margin-top: 0;"><span>Upload</span></button>
				<div><small>You can upload XML file with form</small></div>
		</form>
	</div>

	<form action="/<?= $current_menu->get_name() ?>/add?id=<?= $client->id ?>" class="client_form add_client_form" method="post">
		<input type="hidden" name="id" value="<?= $client->id ?>" />

		<div class="row">
			<h3>Add new client form</h3>
			<div>
				<? if ($areas) foreach ($areas as $area): ?>
					<input type="checkbox" name="area[]" value="<?= $area->getId() ?>" id="form-area-<?= $area->getId() ?>" data-id="<?= $area->getId() ?>" onchange="changeFormArea();" class="form_area" />
					<label for="form-area-<?= $area->getId() ?>"><?= $area->getTitle() ?></label>
				<? endforeach; ?>
			</div>
		</div>

		<div class="tabs ui-tabs ui-widget ui-widget-content ui-corner-all">
			<div class="tabmenu bold-top-border">
				<ul class="ui-tabs-nav ui-helper-reset ui-helper-clearfix ui-widget-header ui-corner-all">
					<? if ($groups) foreach ($groups as $i => $group): ?>
					<li class="ui-state-default ui-corner-top <?= $i == 0 ? "ui-tabs-selected ui-state-active" : "" ?>">
						<a href="#tabs-<?= $i ?>"><?= $i + 1 ?><span class="group_star group_star_<?= $group->getId() ?>">*</span></a>
					</li>
					<? endforeach; ?>
				</ul>
			</div>

			<? if ($groups) foreach ($groups as $i => $group): ?>
				<div class="tab ui-tabs-panel ui-widget-content ui-corner-bottom" id="tabs-<?= $i?>">
					<div class="row">
						<h4><?= $i + 1 ?>. <?= $group->getText() ?><span class="group_star group_star_<?= $group->getId() ?>">*</span></h4>
						<small class="group_text group_text_<?= $group->getId() ?>">* - Mandatory part</small>

						<?
						$current_group = $group;
						include(SITE_PATH . registry::get('templates_directory')) . DIRSEP . 'forms' . DIRSEP . 'form_group.php';
						?>

						<? foreach ($group->getSubgroups() as $subgroup): ?>
							<h5><?= $subgroup->getText() ?></h5>
							<?
							$current_group = $subgroup;
							include(SITE_PATH . registry::get('templates_directory')) . DIRSEP . 'forms' . DIRSEP . 'form_group.php';
							?>
						<? endforeach; ?>

					</div>
				</div>
			<? endforeach; ?>
		</div>

	<? if (user::init()->get_ifa()): ?>
		<div class="row bold-top-border">
			<label>Comment:</label>
			<textarea name="comment"><?= $comment ?></textarea>
		</div>
	<? endif; ?>

		<div class="row">
			<button type="submit" class="medium green"><span>Add</span></button>
			<button type="button" class="medium grey" id="reset_form"><span>Reset</span></button>
			<button type="button" class="medium grey" id="hide_add_client_form"><span>Cancel</span></button>
		</div>
	</form>
