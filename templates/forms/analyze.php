<?
	$program = array('Defensive', 'Prudent', 'Balanced', 'Growth', 'Generation');
	$program1 = array('Asset Liability', 'Knowledge', 'Comfort with risk', 'Investment Decisions', 'Regret');
	if (!user::init()->has_error()) {
?>

	<script src="/scripts/highcharts/highcharts.js"></script>
	<script type="text/javascript">
		$(function () {
			$('#container').highcharts({
				chart: {
					type: 'column'
				},
				title: {
					text: ''
				},
				xAxis: {
					categories: [ 'Defensive', 'Prudent', 'Balanced', 'Growth', 'Generation' ]
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
	<style>
		.chart1, .chart2, .chart3, .chart4, .chart5 {
			float: left;
			width: 210px;
			height: 210px;
			margin-right: 10px;
		}
		.red {
			font-weight: normal;
			color: #cc0509;
		}
	</style>

	<div class="tabs ui-tabs ui-widget ui-widget-content ui-corner-all">
		<div class="tabmenu">
			<ul class="ui-tabs-nav ui-helper-reset ui-helper-clearfix ui-widget-header ui-corner-all">
				<li class="ui-state-default ui-corner-top ui-tabs-selected ui-state-active"><a href="#tabs-1">Tables</a></li>
				<? if (user::init()->get_ifa()): ?><li class="ui-state-default ui-corner-top"><a href="#tabs-2">Charts</a></li><? endif; ?>
			</ul>
		</div>

		<div class="tab ui-tabs-panel ui-widget-content ui-corner-bottom" id="tabs-1">
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
				<div id="container" style="height: 300px; margin: auto; width: 600px;"></div>
			</div>
		</div>

		<div class="tab ui-tabs-panel ui-widget-content ui-corner-bottom ui-tabs-hide" id="tabs-2">
			<div class="row">
				<div class="chart1"></div>
				<div class="chart2"></div>
				<div class="chart3"></div>
				<div class="chart4"></div>
				<div class="chart5"></div>

				<div style="clear: both;"></div>
			</div>
		</div>
	</div>

	<?
	}
	?>

	<div class="row">
		<a href="/<?=$current_menu->get_name()?>/edit?id=<?= $client->id ?>&id_form=<?= $form->id ?>"><button type="button" class="medium grey"><span>Back to Form</span></button></a>
	</div>