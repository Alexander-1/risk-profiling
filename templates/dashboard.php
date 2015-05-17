<div class="row">
	<ul class="arrow">
		<li><strong>Username: </strong><?= user::init()->get_login(); ?></li>
		<li><strong>Name: </strong><?= user::init()->get_name(); ?></li>
		<li><strong>Count clients on page: </strong><?= user::init()->get_show_count(); ?></li>
		<li><strong>Email: </strong><?= user::init()->get_email(); ?></li>
	</ul>
</div>
<div class="row">
	<ul class="arrow">
		<li><strong>Last login: </strong><?= user::init()->get_last_login() ? date('d.m.Y H:i:s',user::init()->get_last_login()) : '-' ?>
		<li><strong>Last IP: </strong><?= user::init()->get_last_ip() ? user::init()->get_last_ip() : '-' ?>
	</ul>
</div><div class="row">
	<ul class="arrow">
		<li><strong>Count of clients: </strong><?= $count ?>
	</ul>
</div>

