<?php
// Work out number of users
$users_stats = get_number_users();
$total_users = get_number_users(true);

?>
<table class="elgg-table-alt">
	<tr>
		<td><b><?= elgg_echo('active'); ?> :</b></td>
		<td><?= $users_stats; ?></td>
	</tr>
	<tr>
		<td><b><?= elgg_echo('total'); ?> :</b></td>
		<td><?= $total_users; ?></td>
	</tr>
</table>
