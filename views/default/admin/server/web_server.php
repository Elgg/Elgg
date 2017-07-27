<?php
/**
 * Web server info
 */

?>
<table class="elgg-table-alt">
	<tr>
		<td><b><?= elgg_echo('admin:server:label:server'); ?></b></td>
		<td><?= $_SERVER['SERVER_SOFTWARE']; ?></td>
	</tr>
	<tr>
		<td><b><?= elgg_echo('admin:server:label:log_location'); ?></b></td>
		<td><?= getenv('APACHE_LOG_DIR'); ?></td>
	</tr>
</table>
