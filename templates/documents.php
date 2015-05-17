<div class="tabs ui-tabs ui-widget ui-widget-content ui-corner-all" id = "tabs">

	<div class="tabmenu">
		<ul class="ui-tabs-nav ui-helper-reset ui-helper-clearfix ui-widget-header ui-corner-all">
			<? $tab_index = 0; ?>
			<? foreach ($documents as $key => $value): ?>
				<li class="ui-state-default ui-corner-top"><a href="#tab-<?= $tab_index++; ?>"><?= $key; ?></a></li>
			<? endforeach; ?>
		</ul>
	</div>

	<? $type_id = 0 ?>
	<? foreach ($documents as $folder => $files): ?>
		<div class="tab ui-tabs-panel ui-widget-content ui-corner-bottom" id="tab-<?= $type_id; ?>">

			<? if (user::init()->get_ifa()): ?>
				<div class="row">
					<form action="/<?= $current_menu->get_name() ?>/upload?type=<?= $type_id; ?>" method="post" enctype="multipart/form-data">
						<label>Document title:</label>
						<div class="rowright"><input type="text" value="" name="title" /></div>
						<label>Choose file</label>
						<input type="file" name="document" />
						<div style="clear: both"></div>
						<button type="submit" class="medium green"><span>Upload</span></button>
					</form>
				</div>
			<? endif; ?>

			<div onselect="alert('test')" class="withpadding">
				<table cellspacing="0" cellpadding="0" border="0">
					<thead>
					<tr>
						<th style="width: 30px;">#</th>
						<th>Title</th>
						<th>Version</th>
						<th>Creation date</th>
						<th style="width: 50px;"></th>
					</tr>
					</thead>
					<tbody>
					<? for ($sequence_number = 1; $sequence_number <= count($files); $sequence_number++): ?>
						<? $document = $files[$sequence_number - 1]; ?>
						<tr>
							<td><?= $sequence_number ?></td>
							<td>
								<?= $document->get_title() ?>
							</td>
							<td><?= $document->get_version(); ?></td>
							<td><?= control::get_date_from_db($document->get_date()) ?></td>
							<td>
								<a href="/<?= $current_menu->get_name() ?>/download?id=<?= $document->get_id(); ?>">
									<img src="/images/thumb-download.png" alt="download" title="Download document">
								</a>
								<? if (user::init()->get_ifa()): ?>
									<a href="javascript:void(0);" onclick="if (confirm('Are you sure you want to delete this document?')) {
										document.location.href = '/<?= $current_menu->get_name() ?>/delete?id=<?= $document->get_id(); ?>'
										}
										;">
										<img src="/images/thumb-delete.png" alt="delete" title="Delete document"></a>
								<? endif; ?>
							</td>
						</tr>
					<? endfor; ?>
					</tbody>
				</table>
			</div>
		</div>
		<? $type_id++ ?>
	<? endforeach; ?>

</div>

<script type="text/javascript">

	function getCookie(name) {

		var matches = document.cookie.match(new RegExp(
			"(?:^|; )" + name.replace(/([\.$?*|{}\(\)\[\]\\\/\+^])/g, '\\$1') + "=([^;]*)"));

		return matches ? decodeURIComponent(matches[1]) : undefined;
	}

	function setCookie(name, value, options) {

		options = options || {};

		var expires = options.expires;
		if (typeof expires == "number" && expires) {
			var d = new Date();
			d.setTime(d.getTime() + expires * 1000);
			expires = options.expires = d;
		}

		if (expires && expires.toUTCString) {
			options.expires = expires.toUTCString();
		}

		value = encodeURIComponent(value);

		var updatedCookie = name + "=" + value;

		for (var propName in options) {
			updatedCookie += "; " + propName;
			var propValue = options[propName];
			if (propValue !== true) {
				updatedCookie += "=" + propValue;
			}
		}

		document.cookie = updatedCookie;
	}

	$(window).load(function () {

		var tabIndex = getCookie("selectected_documents_tab");
		if (tabIndex !== undefined) {

			$("#tabs").tabs("select", "tab-" + tabIndex);
		}

		$('#tabs').tabs({

			select: function (event, ui) {

				setCookie("selectected_documents_tab", ui.index, null);
			}
		});
	});

</script>
