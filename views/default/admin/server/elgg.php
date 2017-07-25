<?php

// Get version information
$code_version = elgg_get_version();
$release = elgg_get_version(true);

$db_version = elgg_get_config('version', null);

?>
<table class="elgg-table-alt">
	<tr>
		<td><b><?php echo elgg_echo('admin:statistics:label:version:release'); ?></b></td>
		<td><?php echo $release; ?></td>
	</tr>
	<tr>
		<td><b><?php echo elgg_echo('admin:statistics:label:version:version'); ?></b></td>
		<td><?php echo $db_version; ?></td>
	</tr>
	<tr>
		<td><b><?php echo elgg_echo('admin:statistics:label:version:code'); ?></b></td>
		<td><?php echo $code_version; ?></td>
	</tr>
</table>