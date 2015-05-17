<?
if (get::passed('success')) {
	if (get::passed('delete')) {
		echo '<div class="bottom_row"><div class="system succes">Client form deleted</div></div>';
	} elseif (get::passed('add')) {
		echo '<div class="bottom_row"><div class="system succes">Client form added</div></div>';
	} elseif (get::passed('edit')) {
		echo '<div class="bottom_row"><div class="system succes">Client form saved</div></div>';
	} elseif (get::passed('send')) {
		echo '<div class="bottom_row"><div class="system succes">PDF sent</div></div>';
	}
}
if (user::init()->has_error()) {
	echo '<div class="content"><div class="system error">' . user::init()->get_error_message() . '</div></div>';
}
$current_form_id = isset($form) ? $form->id : 0;

?>
<script>

$(document).ready(function(){
	$('select[name^="question"], input[name^="question"]').each(function(){
		setQuestion($(this))
	});
})

</script>
<? if ($client): ?>

	<? if (isset($form)): ?>
		<? if (isset($analyze)): ?>
			<? include(SITE_PATH . registry::get('templates_directory')) . DIRSEP . 'forms' . DIRSEP . 'analyze.php'; ?>
		<? else: ?>
			<? include(SITE_PATH . registry::get('templates_directory')) . DIRSEP . 'forms' . DIRSEP . 'edit.php'; ?>
		<? endif; ?>

	<? else: ?>
		<? include(SITE_PATH . registry::get('templates_directory')) . DIRSEP . 'forms' . DIRSEP . 'add.php'; ?>
	<? endif; ?>
<? else: ?>
	<div class="row">No such client</div>
<? endif; ?>

<? if ($forms): ?>

	<div class="withpadding client_forms">
		<table cellspacing="0" cellpadding="0" border="0">
			<thead>
				<tr>
					<th style="width: 30px;">#</th>
					<th>Creation date</th>
					<th style="width: 50px;"></th>
				</tr>
			</thead>
			<tbody>
				<? $i = 1; ?>
				<? foreach ($forms as $form): ?>
					<tr <?= $form->id == $current_form_id ? 'class="selected"' : '' ?>>
					  <td><?= $i++ ?></td>
					  <td><?= control::get_date_from_db($form->date) ?></td>
					  <td>
						  <a href="javascript:void(0);" onclick="if (confirm('Are you sure you want to delete this form?')) {document.location.href='/<?= $current_menu->get_name() ?>/delete?id=<?= $form->id ?>'};"><img src="/images/thumb-delete.png" alt="delete" title="Delete form"></a>
						  <a href="/<?= $current_menu->get_name() ?>/edit?id=<?= $client->id ?>&id_form=<?= $form->id ?>"><img src="/images/thumb-edit.png" alt="edit" title="Edit form"></a>
					  </td>
					</tr>
				<? endforeach; ?>
			</tbody>
		</table>
	</div>

<? else: ?>
	<div class="row client_forms">This client has no forms</div>
<? endif; ?>

