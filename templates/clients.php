<?

if (get::passed('success')) {
	if (get::passed('delete')) {
		echo '<div class="bottom_row"><div class="system succes">Client deleted</div></div>';
	} elseif (get::passed('add')) {
		echo '<div class="bottom_row"><div class="system succes">Client added</div></div>';
	} elseif (get::passed('edit')) {
		echo '<div class="bottom_row"><div class="system succes">Client saved</div></div>';
	} elseif (get::passed('upload')) {
		echo '<div class="bottom_row"><div class="system succes">File uploaded</div></div>';
	}
}
if (user::init()->has_error()){
  echo '<div class="content"><div class="system error">'.user::init()->get_error_message().'</div></div>';
}
$current_client_id = isset($client) ? $client->id : 0;
?>

<? if (isset($client)): ?>
<form action="/<?=$current_menu->get_name()?>/save" id="edit_client" method="post">
	<div class="row">
		<label>First name</label>
		<div class="rowright"><input type="text" value="<?= $client->first_name ?>" name="first_name" /></div>
	</div>
	<div class="row">
		<label>Last name</label>
		<div class="rowright"><input type="text" value="<?= $client->last_name ?>" name="last_name" /></div>
	</div>
	<div class="row">
		<label>Birth date</label>
		<div class="rowright"><input type="text" placeholder="DD.MM.YYYY" value="<?= control::get_date_without_time_from_db($client->birth_date) ?>" name="birth_date" /></div>
	</div>
	<div class="row">
		<label>Status</label>
		<div class="rowright">
				<? if (user::init()->get_ifa()): ?>

					<select name="id_status">
					<? foreach ($statuses as $status): ?>
						<option value="<?= $status['id'] ?>" <?= $status['id'] == $client->id_status ? 'selected="selected"' : '' ?>>
							<?= $status['title'] ?>
						</option>
					<? endforeach; ?>
					</select>

				<? else: ?>

					<? if ($client->id_status <= 2 ): ?>
						<? $statuses = array_slice($statuses, 0, 2); ?>
						<select name="id_status">
						<? foreach ($statuses as $status): ?>
							<option value="<?= $status['id'] ?>" <?= $status['id'] == $client->id_status ? 'selected="selected"' : '' ?>>
								<?= $status['title'] ?>
							</option>
						<? endforeach; ?>
						</select>
					<? else: ?>
						<?= $statuses[$client->id_status]['title'] ?>
					<? endif;?>

				<? endif; ?>
		</div>
	</div>
	<div class="row">
		<input type="hidden" name="id" value="<?= $client->id ?>" />
		<button type="submit" class="medium green"><span>Save</span></button>
		<button type="button" class="medium grey" onclick="document.location.href='/<?=$current_menu->get_name()?>'"><span>Cancel</span></button>
	</div>
</form>

<? else: ?>
<div class="bottom_row" id="add_client_button" <?= (post::passed('first_name') && !get::passed('success')) ? 'style="display: none;"' : '' ?>>
	<button type="button" class="medium green" onclick="$('#add_client').slideDown(); $('#add_client_button').slideUp();"><span>New client</span></button>
	<!--
	<form action="/<?=$current_menu->get_name()?>/upload" method="post" enctype="multipart/form-data" style="float: right;">
		<input type="file" name="file" />
		<button type="submit" class="medium green" style="margin-top: 0;"><span>Upload</span></button>
		<div><small>You can upload XML file with form</small></div>
	</form>
	-->
</div>

<form action="/<?=$current_menu->get_name()?>/add" <?= (post::passed('first_name') && !get::passed('success')) ? '' : 'style="display: none;"' ?> id="add_client" method="post">
	<div class="row">
		<label>First name</label>
		<div class="rowright"><input type="text" value="<?=$first_name?>" name="first_name" /></div>
	</div>
	<div class="row">
		<label>Last name</label>
		<div class="rowright"><input type="text" value="<?=$last_name?>" name="last_name" /></div>
	</div>
	<div class="row">
		<label>Birth date</label>
		<div class="rowright"><input type="text" placeholder="DD.MM.YYYY" value="<?=$birth_date?>" name="birth_date" /></div>
	</div>
	<div class="row">
		<button type="submit" class="medium green"><span>Add</span></button>
		<button type="button" class="medium grey" onclick="$('#add_client').slideUp(); $('#add_client_button').slideDown();"><span>Cancel</span></button>
	</div>
</form>
<? endif; ?>

<?
if ($clients) {
	?>
	<div class="withpadding">
		<table cellspacing="0" cellpadding="0" border="0">
			<thead>
			<tr>
				<th style="width: 30px;">#</th>
				<th>Name</th>
				<th>Birth date</th>
				<? if (user::init()->get_ifa()): ?>
					<th>Created by</th>
				<? endif; ?>
				<th>Status</th>
				<th style="width: 102px;"></th>
			</tr>
			</thead>
			<tbody>
			<?
			$i = 1;
			foreach ($clients as $client) {
				echo '
              <tr ' . ($client['id'] == $current_client_id ? 'class="selected"' : '') . '>
                <td>' . $i++ . '</td>
                <td>' . $client['last_name'] . ', ' . $client['first_name'] . '</td>
                <td>' . control::get_date_without_time_from_db($client['birth_date']) . '</td>
		' . ( (user::init()->get_ifa()) ? '<td>' . $client['user_name'] . '</td>' : '') . '
				<td>' . $client['status'] . '</td>
                <td>
					<a href="javascript:void(0);" onclick="if (confirm(\'Are you sure you want to delete this client?\')) {document.location.href=\'/' . $current_menu->get_name() . '/delete?id=' . $client['id'] . '\'};"><img src="/images/thumb-delete.png" alt="delete" title="Delete client"></a>
					<a href="/' . $current_menu->get_name() . '/edit?id=' . $client['id'] . '"><img src="/images/thumb-edit.png" alt="edit" title="Edit client"></a>
					<a href="/downloads?id=' . $client['id'] . '"><img src="/images/thumb-file.png" alt="edit" title="Files"></a>
					<a href="/forms?id=' . $client['id'] . '"><img src="/images/thumb-zoom.png" alt="forms" title="Forms"></a>
				</td>
              </tr>
            ';
			}
			?>
			</tbody>
		</table>

		<div class="bottom_row">
			<div class="dataTables_paginate paging_full_numbers">
				<?
					echo pages::get_pages(user::init()->get_show_count(), $count, $page,
						'<a href="/'.$current_menu->get_name().'?page=%%prev_page%%"><span class="previous paginate_button">Prev</span></a><span>%%pages%%</span><a href="/'.$current_menu->get_name().'?page=%%next_page%%"><span class="next paginate_button">Last</span></a>',
						'<a href="/'.$current_menu->get_name().'?page=%%page%%"><span class="paginate_button">%%page%%</span></a>',
						'<a href="/'.$current_menu->get_name().'?page=%%page%%"><span class="paginate_active">%%page%%</span></a>',
						'...');
				?>
			</div>
		</div>
	</div>
	<?
} else {
	echo '<div class="row">No clients</div>';
}
?>
