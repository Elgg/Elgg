<?php
/**
 * Elgg statistics screen
 *
 * @package Elgg
 * @subpackage Core
 */

// Get entity statistics
$entity_stats = get_entity_statistics(elgg_get_logged_in_user_guid());

if ($entity_stats) {
?>
<div class="elgg-module elgg-module-info">
	<div class="elgg-head">
		<h3><?php echo elgg_echo('usersettings:statistics:label:numentities'); ?></h3>
	</div>
	<div class="elgg-body">
		<table class="elgg-table-alt">
		<?php
			foreach ($entity_stats as $k => $entry) {
				foreach ($entry as $a => $b) {

					//This function controls the alternating class
					$even_odd = ( 'odd' != $even_odd ) ? 'odd' : 'even';

					if ($a == "__base__") {
						$a = elgg_echo("item:{$k}");
						if (empty($a)) {
							$a = $k;
						}
					} else {
						$a = elgg_echo("item:{$k}:{$a}");
						if (empty($a)) {
							$a = "$k $a";
						}
					}
					echo <<< END
						<tr class="{$even_odd}">
							<td class="column-one"><b>{$a}:</b></td>
							<td>{$b}</td>
						</tr>
END;
				}
			}
		?>
		</table>
	</div>
</div>
<?php
}