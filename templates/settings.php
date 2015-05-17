<?php
$settings = settings::init()->get_all_settings();

if (get::passed('success')) {
	echo '<div class="bottom_row"><div class="system succes">Settings saved</div></div>';
}
?>

<form method=post action="/<?= $current_menu->get_name() ?>/remember_settings">

	<? if ($settings): ?>
		<? foreach ($settings as $i => $setting): ?>
			<div class="row" style="border-top: 1px solid #e5e5e5;">
				<label style="width: 300px;"><?= $setting['description'] ?></label>
				<div class="rowright">
					<? if ($setting['id'] < 13): ?>

					<input style="width: 300px;" name="<?= $setting['name'] ?>" value="<?= str_replace('`', "'", str_replace('``', '"', $setting['value'])) ?>" />
					<? else: ?>
					<textarea style="width: 300px;" name="<?= $setting['name'] ?>"><?= str_replace('`', "'", str_replace('``', '"', $setting['value'])) ?></textarea>
					<? endif; ?>
				</div>
				<small><?= $setting['details'] ?></small>
				<div style="clear: both;"></div>
			</div>
		<? endforeach; ?>
	<? endif; ?>
	<div class="row">
		<button type="submit" class="medium green"><span>Save</span></button>
	</div>
</form>
