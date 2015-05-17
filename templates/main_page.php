<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">

	<head>

		<meta http-equiv="Content-Type" content="text/html;charset=utf-8" />
		<title><?= isset($current_menu) ? $current_menu->get_title() . ' | ' : '' ?><?= settings::init()->get('site_title') ?></title>

		<?= $css_files ?>
		<!--[if lte IE 8]>
			<script type="text/javascript" src="js/excanvas.min.js"></script>
		<![endif]-->
		<?= $script_files ?>
		<script>
			var controller_name = '<?= isset($current_menu) ? $current_menu->get_name() : '' ?>';
		</script>

	</head>

	<body onload="prettyPrint()">
		<div id="wrapper">
			<div id="left">
				<h1><a href="/"><b>Loyal North</b><br/> Agent Tool</a></h1>
				<ul>
					<?php
					$menu = menu::init()->get_first_level_items();
					if (user::init()->is_authorized() && $menu)
						foreach ($menu as $item) {
							echo '<li ' . ((isset($current_menu) && $current_menu->get_id() == $item->get_id()) ? 'class="active"' : '') . '><a href="/' . (!$item->is_default() ? $item->get_name() : '') . '"><div>' . $item->get_title() . '</div></a>';
							$submenu = $item->get_submenu();
							if ($submenu) {
								echo '<ul>';
								foreach ($submenu as $subitem) {
									if (isset($current_menu) && $current_menu->get_id() == $subitem->get_id()) echo '<li ' . ((isset($current_menu) && $current_menu->get_id() == $subitem->get_id()) ? 'class="active"' : '') . '><a href="javascript:void(0);"><div>' . $subitem->get_title() . '</div></a>';
								}
								echo '</ul>';
							}
							echo '</li>';
						}
					?>
				</ul>
			</div>
			<div id="right">
				<? if (user::init()->is_authorized()): ?>
					<div id="top-bar">
						<ul>
							<li>Welcome, <?= user::init()->get_name(); ?></li>
							<li><a href="/logout"><img src="/images/icon-logout.png" alt="Logout" /> Logout</a></li>
						</ul>
					</div>
					<div id="breadcrumbs">
						<ul><li><?= isset($current_menu) ? $current_menu->get_title() : '' ?></li></ul>
					</div>
				<? endif; ?>
				<div id="main">
					<div class="section">
						<div class="box">
							<div class="title">
								<h2><?= user::init()->is_authorized() ? (isset($current_menu) ? (isset($title) ? $title : $current_menu->get_title()) : '404 ERROR') : 'Welcome to '.settings::init()->get('site_title'); ?></h2>
							</div>
							<div class="content nopadding">
								<?php echo $content; ?>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>

	</body>
</html>