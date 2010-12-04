<?php
/**
 * Elgg statistics screen
 *
 * @package Elgg
 * @subpackage Core
 */

// Get entity statistics
$entity_stats = get_entity_statistics(get_loggedin_userid());

if ($entity_stats) {
?>
<div class="user-settings statistics">
	<h3><?php echo elgg_echo('usersettings:statistics:label:numentities'); ?></h3>
	<table class="styled">
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
<?php
}