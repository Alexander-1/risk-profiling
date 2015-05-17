<?php
	if (user::init()->has_error()){
	  echo '<div class="content"><div class="system error">'.user::init()->get_error_message().'</div></div>';
	}
?>

<form action="/authorization/authorize" method="post">
	<div class="row">
		<label>Username</label>
		<div class="rowright"><input type="text" name="login" value="<?php if (post::passed('login') && post::is_only_char('login')){ echo post::get_only_char('login'); } ?>" /></div>
	</div>
	<div class="row">
		<label>Password</label>
		<div class="rowright"><input type="password" name="password" value="" /></div>
	</div>
	<div class="row">
		<div class="rowright button">
			<button type="submit" class="medium grey"><span>Login</span></button>
		</div>
	</div>
</form>
