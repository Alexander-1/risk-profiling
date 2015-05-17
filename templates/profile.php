<?php
if (get::passed('success')) {
	echo '<div class="bottom_row"><div class="system succes">Profile saved</div></div>';
}
if (user::init()->has_error()){
  echo '<div class="content"><div class="system error">'.user::init()->get_error_message().'</div></div>';
}
?>

<form method=post action="/<?= $current_menu->get_name() ?>/remember_settings" class="settings_form">
	<div class="row">
		<label>Username</label>
		<div class="rowright"><input type="text" value="<?= user::init()->get_login(); ?>" name="login" /></div>
	</div>
	<div class="row">
		<label>Name</label>
		<div class="rowright"><input type="text" value="<?= user::init()->get_name(); ?>" name="name" /></div>
	</div>
	<div class="row">
		<label>Count clients on page</label>
		<div class="rowright"><input type="text" value="<?= user::init()->get_show_count(); ?>" name="show_count" /></div>
	</div>
	<div class="row">
		<label>Email</label>
		<div class="rowright"><input type="text" value="<?= user::init()->get_email(); ?>" name="email" /></div>
	</div>

	<div class="row"><button type="submit" class="medium green"><span>Save</span></button></div>
</form>
