<?php
/**
 * Server PHP info
 */

$php_log = ini_get('error_log');
if (!$php_log) {
	$php_log = elgg_echo('admin:server:error_log');
}

?>
<table class="elgg-table-alt">
	<tr class="odd">
		<td><b><?php echo elgg_echo('admin:server:label:php_version'); ?> :</b></td>
		<td><?php echo phpversion(); ?></td>
	</tr>
	<tr class="even">
		<td><b><?php echo elgg_echo('admin:server:label:php_ini'); ?> :</b></td>
		<td><?php echo php_ini_loaded_file(); ?></td>
	</tr>
	<tr class="odd">
		<td><b><?php echo elgg_echo('admin:server:label:php_log'); ?> :</b></td>
		<td><?php echo $php_log; ?></td>
	</tr>
	<tr class="even">
		<td><b><?php echo elgg_echo('admin:server:label:mem_avail'); ?> :</b></td>
		<td><?php echo number_format(elgg_get_ini_setting_in_bytes('memory_limit')); ?></td>
	</tr>
	<tr class="odd">
		<td><b><?php echo elgg_echo('admin:server:label:mem_used'); ?> :</b></td>
		<td><?php echo number_format(memory_get_peak_usage()); ?></td>
	</tr>
</table>
