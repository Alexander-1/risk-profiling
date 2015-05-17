<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
<head>
	<meta http-equiv="Content-Type" content="text/html;charset=utf-8" />
	<?= $css_files ?>
	<!--[if lte IE 8]>
	<script type="text/javascript" src="js/excanvas.min.js"></script>
	<![endif]-->
	<?= $script_files ?>
	<style>
		html, body {
			background: none;
		}

		#right {
			position: initial;
			margin: auto;
			border: none;
			background: none;
		}
		#right #main {
			padding: 0;
		}
		h1 {
			text-align: center;
			margin: 10px;
		}
	</style>
</head>
<body>
<div id="wrapper">
	<div id="right">
		<div id="main">
			<div class="content nopadding">

				<div style="margin-top: 5px; font-size: 7px;">
					<div>
						<?= settings::init()->get('pdf_footer'); ?>
					</div>
				</div>


			</div>
		</div>
	</div>
</div>

</body>
</html>