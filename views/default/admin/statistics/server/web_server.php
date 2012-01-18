<?php
/**
 * Web server info
 */

?>
<table class="elgg-table-alt">
	<tr class="odd">
		<td><b><?php echo elgg_echo('admin:server:label:server'); ?> :</b></td>
		<td><?php echo $_SERVER['SERVER_SOFTWARE']; ?></td>
	</tr>
	<tr class="even">
		<td><b><?php echo elgg_echo('admin:server:label:log_location'); ?> :</b></td>
		<td><?php echo getenv('APACHE_LOG_DIR'); ?></td>
	</tr>
</table>
