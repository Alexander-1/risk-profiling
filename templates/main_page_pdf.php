<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">

	<head>

		<meta http-equiv="Content-Type" content="text/html;charset=utf-8" />

		<?= $css_files ?>
		<!--[if lte IE 8]>
			<script type="text/javascript" src="js/excanvas.min.js"></script>
		<![endif]-->
		<?= $script_files ?>

	</head>

	<body onload="prettyPrint()">
		<div id="wrapper">
			<div id="right">
				<div id="main">
					<div class="section">
						<div class="box">
							<div class="title">
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