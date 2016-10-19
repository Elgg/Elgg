<?php
/**
 * Memcache info
 */
$servers = elgg_get_config('memcache_servers');
if (!elgg_get_config('memcache') || empty($servers) || !is_memcache_available()) {
	echo '<p>' . elgg_echo('admin:server:memcache:inactive') . '</p>';
	return;
}

$memcache = new Memcache;

foreach ($servers as $server) {

	$title = "{$server[0]}:{$server[1]}";

	$memcache->connect($server[0], $server[1]);
	$stats = $memcache->getStats();
	$memcache->close();

	ob_start();
	?>
	<table class="elgg-table-alt">
		<?php
		foreach ($stats as $key => $value) {
			?>
			<tr>
				<td><b><?= $key ?> :</b></td>
				<td><?= $value ?></td>
			</tr>
			<?php
		}
		?>
	</table>

	<?php
	$table = ob_get_clean();

	echo elgg_view_module('info', $title, $table);
}
