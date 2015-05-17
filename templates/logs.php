<?
if ($logs) {
	?>
	<div class="withpadding">
		<table cellspacing="0" cellpadding="0" border="0">
			<thead>
			<tr>
				<th style="width: 5%;">#</th>
				<th>User</th>
				<th>Client</th>
				<th>Client Form</th>
				<th>Action</th>
				<th>Params</th>
				<th>Date</th>
			</tr>
			</thead>
			<tbody>
			<?php
			$i = 1;
			foreach ($logs as $log) {
				$client_form = '-';
				if ($log->get_id_client_form()) {
					$client_form = "<a href='/forms/edit?id={$log->get_id_client()}&id_form={$log->get_id_client_form()}' target='_blank'>" . $log->get_id_client_form() . "</a>";
				}
				$post = "";
				$array = unserialize(str_replace("``", '"', $log->get_post()));
				if ($array) foreach ($array as $key => $value) {
					if (is_array($value)) {
						$post .= "$key: <br />";
						if ($value) foreach ($value as $k => $v) {
							$post .= "&nbsp;&nbsp;$k: $v<br />";
						}
					} else {
						$post .= "$key: $value<br />";
					}
				}

				echo '
              <tr>
                <td>' . $i++ . '</td>
                <td>' . $log->get_name_user() . '</td>
                <td>' . $log->get_last_name_client() . ', ' . $log->get_first_name_client() . '</td>
                <td>' . $client_form . '</td>
                <td>' . $log->get_action() . '</td>
                <td>' . $post . '</td>
                <td>' . $log->get_date() . '</td>
              </tr>
            ';
			}
			?>
			</tbody>
		</table>

		<div class="bottom_row">
			<div class="dataTables_paginate paging_full_numbers">
				<?php
					echo pages::get_pages(user::init()->get_show_count(), $count, $page,
						'<a href="'.$current_menu->get_name().'?page=%%prev_page%%"><span class="previous paginate_button">Пред</span></a><span>%%pages%%</span><a href="'.$current_menu->get_name().'?page=%%next_page%%"><span class="next paginate_button">След</span></a>',
						'<a href="'.$current_menu->get_name().'?page=%%page%%"><span class="paginate_button">%%page%%</span></a>',
						'<a href="'.$current_menu->get_name().'?page=%%page%%"><span class="paginate_active">%%page%%</span></a>',
						'...');
				?>
			</div>
		</div>
	</div>
	<?php
} else {
	echo '<div class="row">No action logs</div>';
}
?>
