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

		.chart1, .chart2, .chart3, .chart4, .chart5 {
			float: left;
			width: 210px;
			height: 210px;
			margin-right: 10px;
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
		.tick {
			width: 10px;
			height: 10px;
			background: url(/images/list-tick.gif);
			display: inline-block;
			margin-left: -10px;
		}
		h1 {
			text-align: center;
			margin: 10px;
		}
		.red {
			font-weight: normal;
			color: #cc0509;
		}

		.form_table td {
			padding: 0;
			text-align: center;
		}
		.form_table td input {
			width: 90%;
			border: none;;
		}
		.row-border-top {
			border-top: 1px solid #e5e5e5;
		}
		.row-border-top .rowright {
			margin-top: 5px;
		}
	</style>

	<script>

		$(document).ready(function(){
			$(".group_star, .group_text").hide();

			var i;
			var requiredGroups = <?= json_encode($required_groups) ?>;
			for (i in requiredGroups) {
				$(".group_star_" + requiredGroups[i]).show();
				$(".group_text_" + requiredGroups[i]).show();
			}
		})
	</script>
</head>
<?
$letters = array('A', 'B', 'C', 'D', 'E', 'F', 'G', 'H', 'I', 'J');
?>
<body>
<div id="wrapper">
	<div id="right">
		<div id="main">
			<div class="content nopadding">

				<div style="margin-bottom: 5px;">
					<div style="float: left;">
						<?= settings::init()->get('pdf_header_left'); ?>
					</div>
					<div style="float: right; padding: 10px; font-weight: bold;">
						<?= settings::init()->get('pdf_header_right'); ?>
					</div>
					<div style="clear: both;"></div>
				</div>

				<h1>Loyal North Agent Tool</h1>

				<div class="row">
					<p><strong>Client name:</strong> <?= $client->last_name ?>, <?= $client->first_name ?></p>
					<p><strong>Adviser name:</strong> <?= $form->get_user()->name ?></p>
					<p><strong>Date:</strong> <?= control::get_date_from_db($form->date) ?></p>
					<!--<p><strong>Birth date:</strong> <?= control::get_date_without_time_from_db($client->birth_date) ?></p>-->
					<p></p>
				</div>

				<? if ($groups) foreach ($groups as $i => $group): ?>
					<div class="tab ui-tabs-panel ui-widget-content ui-corner-bottom" id="tabs-<?= $i?>">
						<div class="row client_form">
							<h4><?= $i + 1 ?>. <?= $group->getText() ?><span class="group_star group_star_<?= $group->getId() ?>">*</span></h4>
							<small class="group_text group_text_<?= $group->getId() ?>">* - Mandatory part</small>

							<?
							$current_group = $group;
							include(SITE_PATH . registry::get('templates_directory')) . DIRSEP . 'forms' . DIRSEP . 'pdf_form_group.php';
							?>

							<? foreach ($group->getSubgroups() as $subgroup): ?>
								<h5><?= $subgroup->getText() ?></h5>
								<?
								$current_group = $subgroup;
								include(SITE_PATH . registry::get('templates_directory')) . DIRSEP . 'forms' . DIRSEP . 'pdf_form_group.php';
								?>
							<? endforeach; ?>

						</div>
					</div>
				<? endforeach; ?>

				<div class="row">
					<p><strong>Comment:</strong> <?= $form->comment ?></p>
				</div>
				<div style="page-break-after: always;"><span style="display: none;">&nbsp;</span></div>

				<?
				$program = array('Defensive', 'Prudent', 'Balanced', 'Growth', 'Generation');
				$program1 = array('Asset Liability', 'Knowledge', 'Comfort with risk', 'Investment Decisions', 'Regret');
				if (!user::init()->has_error()):
					?>

					<script src="/scripts/highcharts/highcharts.js"></script>
					<script type="text/javascript">
						$(function() {
							$('#container').highcharts({
								chart: {
									type: 'column'
								},
								title: {
									text: ''
								},
								xAxis: {
									categories: ['Defensive', 'Prudent', 'Balanced', 'Growth', 'Generation']
								},
								yAxis: {
									min: 0,
									title: {
										enabled: false
									}
								},
								exporting: {
									enabled: false
								},
								tooltip: {
									formatter: function() {
										return '<strong>' + this.key + '</strong><br />' + this.y + ' %'
									}
								},
								plotOptions: {
									column: {
										pointPadding: 0.2,
										borderWidth: 0
									}
								},
								legend: {
									enabled: false
								},
								series: [{
									data: <?= json_encode($data->IPMbship) ?>
								}]
							});

							<?	$i = 0;
								foreach ($data->CatMbship as $key => $chart):
				foreach ($chart as $k => $v) {
					$chart[$k] = (float) $v;
				}
?>
							$('.chart<?= ++$i ?>').highcharts({
								title: {
									text: '<?= $program1[$key] ?>',
									style: {
										fontSize: 12
									}
								},
								xAxis: {},
								yAxis: {
									title: {
										text: ''
									}
								},
								tooltip: {
									formatter: function() {
										return this.y;
									}
								},
								exporting: {
									enabled: false
								},
								legend: {
									enabled: false
								},
								series: [{
									data: <?= json_encode($chart) ?>
								}]
							});
							<? endforeach; ?>
						});
					</script>

					<div class="row">
						<table  cellspacing="0" cellpadding="0" border="0">
							<thead>
							<tr><th width="20%">Category</th><th style="width: 10%;">Red Flag</th><th>Comment</th></tr>
							</thead>
							<tbody>
							<? foreach ($program1 as $key => $pr): ?>
								<tr>
									<td><?= $pr ?></td>
									<td><?= ($data->RedFlag[$key] ? '+' : '-') ?></td>
									<td>
										<?= ($data->RedFlag[$key] && isset($data->RedFlagQ) && isset($data->CounterQ)) ? '<strong class="red">Questions ' . $data->RedFlagQ[$key] . ' and ' . $data->CounterQ[$data->RedFlagQ[$key]-1] . ' are inconsistent: discuss it to your IFA</strong>' : '' ?>
									</td>
								</tr>
							<? endforeach; ?>
							</tbody>
						</table>
					</div>
					<div class="row">
						<table  cellspacing="0" cellpadding="0" border="0">
							<thead>
							<tr><th>Programme</th><th>Defensive</th><th>Prudent</th><th>Balanced</th><th>Growth</th><th>Generation</th></tr>
							</thead>
							<tbody>
							<tr>
								<td>Return</td>
								<? for ($i = 0; $i < 5; $i++): ?>
									<td><?= settings::init()->get('return_' . $i) ?> %</td>
								<? endfor; ?>
							</tr>
							<tr>
								<td>Volatility</td>
								<? for ($i = 0; $i < 5; $i++): ?>
									<td><?= settings::init()->get('volatility_' . $i) ?> %</td>
								<? endfor; ?>
							</tr>
							<tr>
								<td>Allocation of Funds</td>
								<? foreach ($data->IPMbship as $value): ?>
									<td><?= $value ?> %</td>
								<? endforeach; ?>
							</tr>
							</tbody>
						</table>
					</div>
					<div class="row">
						<table>
							<tbody>
							<tr style="border-top: 1px solid #e5e5e5;"><td>Portfolio Return</td><td><?= $portfolio['return'] ?> %</td></tr>
							<tr><td>Portfolio Volatility</td><td><?= $portfolio['volatility'] ?> %</td></tr>
							</tbody>
						</table>

					</div>
					<div class="row">
						<div id="container" style="height: 300px; width: 600px;"></div>
					</div>

				<? if (isset($_GET['ifa'])): ?>
					<div class="row">
						<div class="chart1"></div>
						<div class="chart2"></div>
						<div class="chart3"></div>
						<div class="chart4"></div>
						<div class="chart5"></div>

						<div style="clear: both;"></div>
					</div>
				<? endif;?>

				<? endif; ?>

			</div>
		</div>
	</div>
</div>

</body>
</html>