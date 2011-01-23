<?php
/**
 * Elgg statistics screen
 *
 * @package Elgg
 * @subpackage Core
 */

// Work out number of users
$users_stats = get_number_users();
$total_users = get_number_users(true);

// Get version information
$version = get_version();
$release = get_version(true);

echo elgg_view('admin/statistics/extend');

?>
<div class="elgg-module elgg-inline-module">
	<div class="elgg-head">
		<h3><?php echo elgg_echo('admin:statistics:label:basic'); ?></h3>
	</div>
	<div class="elgg-body">
		<table class="elgg-table">
			<tr class="odd">
				<td><b><?php echo elgg_echo('admin:statistics:label:version'); ?> :</b></td>
				<td><?php echo elgg_echo('admin:statistics:label:version:release'); ?> - <?php echo $release; ?>, <?php echo elgg_echo('admin:statistics:label:version:version'); ?> - <?php echo $version; ?></td>
			</tr>
			<tr class="even">
				<td><b><?php echo elgg_echo('admin:statistics:label:numusers'); ?> :</b></td>
				<td><?php echo $users_stats; ?> <?php echo elgg_echo('active'); ?> / <?php echo $total_users; ?> <?php echo elgg_echo('total') ?></td>
			</tr>
		</table>
	</div>
</div>

<?php

// Get entity statistics
$entity_stats = get_entity_statistics();
$even_odd = "";
?>
<div class="elgg-module elgg-inline-module">
	<div class="elgg-head">
		<h3><?php echo elgg_echo('admin:statistics:label:numentities'); ?></h3>
	</div>
	<div class="elgg-body">
		<table class="elgg-table">
		<?php
			foreach ($entity_stats as $k => $entry) {
				arsort($entry);
				foreach ($entry as $a => $b) {

					//This function controls the alternating class
					$even_odd = ( 'odd' != $even_odd ) ? 'odd' : 'even';

					if ($a == "__base__") {
						$a = elgg_echo("item:{$k}");
						if (empty($a))
							$a = $k;
					} else {
							if (empty($a)) {
								$a = elgg_echo("item:{$k}");
							} else {
								$a = elgg_echo("item:{$k}:{$a}");
							}

							if (empty($a)) {
								$a = "$k $a";
							}
						}
					echo <<< END
						<tr class="{$even_odd}">
							<td>{$a}:</td>
							<td>{$b}</td>
						</tr>
END;
				}
			}
		?>
		</table>
	</div>
</div>