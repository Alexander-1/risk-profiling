<?
if (get::passed('success')) {
	if (get::passed('delete')) {
		echo '<div class="bottom_row"><div class="system succes">Client file deleted</div></div>';
	} elseif (get::passed('add')) {
		echo '<div class="bottom_row"><div class="system succes">Client file added</div></div>';
	} elseif (get::passed('edit')) {
		echo '<div class="bottom_row"><div class="system succes">Client status changed</div></div>';
	}
}
if (user::init()->has_error()) {
	echo '<div class="content"><div class="system error">' . user::init()->get_error_message() . '</div></div>';
}

?>

<? if ($client): ?>
	<div class="row">
		<a href="clients">Back to List of Clients</a>
		<ul class="arrow">
			<li><strong>Client name: </strong><?= $client->last_name . ', ' . $client->first_name ?></li>
			<li><strong>Birth date: </strong><?= control::get_date_without_time_from_db($client->birth_date) ?></li>
			<li><strong>Status: </strong><?= $client->get_status() ?></li>
		</ul>
	</div>

	<div class="tabs ui-tabs ui-widget ui-widget-content ui-corner-all">
		<div class="tabmenu">
			<ul class="ui-tabs-nav ui-helper-reset ui-helper-clearfix ui-widget-header ui-corner-all">
				<li class="ui-state-default ui-corner-top ui-tabs-selected ui-state-active"><a href="#tabs-1">Status changes</a></li>
				<li class="ui-state-default ui-corner-top"><a href="#tabs-2">Upload LOA file</a></li>
				<li class="ui-state-default ui-corner-top"><a href="#tabs-3">Upload file</a></li>
				<? if (user::init()->get_ifa() || $client->id_status <= 2 ): ?>
				<li class="ui-state-default ui-corner-top"><a href="#tabs-4">Change status</a></li>
				<? endif; ?>
                                <!--
				<li class="ui-state-default ui-corner-top"><a href="#tabs-5">Upload XML</a></li>
                                -->
			</ul>
		</div>

		<div class="tab ui-tabs-panel ui-widget-content ui-corner-bottom" id="tabs-1">
			<div class="row">
				<? if ($status_changes): ?>
					<? foreach ($status_changes as $change): ?>
						<small><?= control::get_date_from_db($change['date']) ?> - status changed to "<?= $change['title'] ?>"</small><br />
					<? endforeach; ?>
				<? else: ?>
					No status changes.
				<? endif; ?>
			</div>
		</div>

		<div class="tab ui-tabs-panel ui-widget-content ui-corner-bottom" id="tabs-2">
			<div class="row">
				<form action="/<?=$current_menu->get_name()?>/upload" method="post" enctype="multipart/form-data">
					<input type="hidden" name="id" value="<?= $client->get_id() ?>" />
					<input type="hidden" name="loa" value="1" />

					<label>Title</label>
					<div class="rowright"><input type="text" value="" name="title" /></div>

					<label>Choose file</label>
					<input type="file" name="file" />
					<div style="clear: both"></div>
					<button type="submit" class="medium green"><span>Upload</span></button>
					<button type="button" class="medium grey" onclick="$('.upload_client_file_form').slideUp(); $('.upload_client_file_form_button').slideDown();"><span>Cancel</span></button>
				</form>
			</div>
		</div>

		<div class="tab ui-tabs-panel ui-widget-content ui-corner-bottom" id="tabs-3">
			<div class="row">
				<form action="/<?=$current_menu->get_name()?>/upload" method="post" enctype="multipart/form-data">
					<input type="hidden" name="id" value="<?= $client->get_id() ?>" />

					<label>Title</label>
					<div class="rowright"><input type="text" value="" name="title" /></div>

					<label>Choose file</label>
					<input type="file" name="file" />
					<div style="clear: both"></div>
					<button type="submit" class="medium green"><span>Upload</span></button>
					<button type="button" class="medium grey" onclick="$('.upload_client_file_form').slideUp(); $('.upload_client_file_form_button').slideDown();"><span>Cancel</span></button>
				</form>
			</div>
		</div>

		<? if (user::init()->get_ifa() || $client->id_status <= 2 ): ?>
		<div class="tab ui-tabs-panel ui-widget-content ui-corner-bottom" id="tabs-4">
			<div class="row">
				<form action="/<?=$current_menu->get_name()?>/save" method="post" enctype="multipart/form-data">
					<input type="hidden" name="id" value="<?= $client->get_id() ?>" />

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

					<button type="submit" class="medium green"><span>Save</span></button>
					<button type="button" class="medium grey" onclick="$('.upload_client_file_form').slideUp(); $('.upload_client_file_form_button').slideDown();"><span>Cancel</span></button>
				</form>
			</div>
		</div>
		<? endif; ?>
		<!--
		<div class="tab ui-tabs-panel ui-widget-content ui-corner-bottom" id="tabs-5">
			<div class="row">
				<form action="/<?=$current_menu->get_name()?>/upload_xml" method="post" enctype="multipart/form-data">
					<input type="file" name="file" />
					<button type="submit" class="medium green" style="margin-top: 0;"><span>Upload</span></button>
					<div><small>You can upload XML file with form</small></div>
				</form>
			</div>
		</div>
		-->
	</div>

<? else: ?>
	<div class="row">No such client</div>
<? endif; ?>

<? if ($files): ?>

	<div class="withpadding">
		<h4>Files uploaded by agent</h4>

		<table cellspacing="0" cellpadding="0" border="0">
			<thead>
				<tr>
					<th style="width: 30px;">#</th>
					<th>Title</th>
					<th>Creation date</th>
					<th style="width: 50px;"></th>
				</tr>
			</thead>
			<tbody>
				<? $i = 1; ?>
				<? foreach ($files as $file): ?>
					<tr>
					  <td><?= $i++ ?></td>
						<td>
							<?= $file->title ?>
							<?= $file->is_loa ? ' (LOA)' : '' ?>
						</td>
						<td><?= control::get_date_from_db($file->date) ?></td>
					  <td>
						  <a href="/<?=$current_menu->get_name()?>/download?id=<?= $client->id ?>&id_file=<?= $file->id ?>"><img src="/images/thumb-download.png" alt="download" title="Download file"></a>
						  <a href="javascript:void(0);" onclick="if (confirm('Are you sure you want to delete this file?')) {document.location.href='/<?= $current_menu->get_name() ?>/delete?id=<?= $client->id ?>&id_file=<?= $file->id ?>'};"><img src="/images/thumb-delete.png" alt="delete" title="Delete file"></a>
					  </td>
					</tr>
				<? endforeach; ?>
			</tbody>
		</table>
	</div>

<? else: ?>
	<div class="row">No files were uploaded by agent.</div>
<? endif; ?>


<? if ($ifa_files): ?>

	<div class="withpadding">
		<h4>Files uploaded by IFA</h4>

		<table cellspacing="0" cellpadding="0" border="0">
			<thead>
			<tr>
				<th style="width: 30px;">#</th>
				<th>Title</th>
				<th>Creation date</th>
				<th style="width: 50px;"></th>
			</tr>
			</thead>
			<tbody>
			<? $i = 1; ?>
			<? foreach ($ifa_files as $file): ?>
				<tr>
					<td><?= $i++ ?></td>
					<td>
						<?= $file->title ?>
						<?= $file->is_loa ? ' (LOA)' : '' ?>
					</td>
					<td><?= control::get_date_from_db($file->date) ?></td>
					<td>
						<a href="/<?=$current_menu->get_name()?>/download?id=<?= $client->id ?>&id_file=<?= $file->id ?>"><img src="/images/thumb-download.png" alt="download" title="Download file"></a>
						<? if (user::init()->get_ifa()): ?>
						<a href="javascript:void(0);" onclick="if (confirm('Are you sure you want to delete this file?')) {document.location.href='/<?= $current_menu->get_name() ?>/delete?id=<?= $client->id ?>&id_file=<?= $file->id ?>'};"><img src="/images/thumb-delete.png" alt="delete" title="Delete file"></a>
						<? endif; ?>
					</td>
				</tr>
			<? endforeach; ?>
			</tbody>
		</table>
	</div>

<? else: ?>
	<div class="row">No files were uploaded by IFA.</div>
<? endif; ?>

