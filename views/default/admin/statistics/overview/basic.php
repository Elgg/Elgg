<?php
// Work out number of users
$users_stats = get_number_users();
$total_users = get_number_users(true);

// Get version information
$code_version = elgg_get_version();
$release = elgg_get_version(true);

$db_version = elgg_get_config('version', null);

$version_info = elgg_echo('admin:statistics:label:version:release') . ' - ' . $release . ', ';
$version_info .= elgg_echo('admin:statistics:label:version:version') . ' - ' . $db_version . ', ';
$version_info .= elgg_echo('admin:statistics:label:version:code') . ' - ' . $code_version;

?>
<table class="elgg-table-alt">
	<tr>
		<td><b><?php echo elgg_echo('admin:statistics:label:version'); ?> :</b></td>
		<td><?php echo $version_info; ?></td>
	</tr>
	<tr>
		<td><b><?php echo elgg_echo('admin:statistics:label:numusers'); ?> :</b></td>
		<td><?php echo $users_stats; ?> <?php echo elgg_echo('active'); ?> / <?php echo $total_users; ?> <?php echo elgg_echo('total') ?></td>
	</tr>
</table>
