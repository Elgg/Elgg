<?php
/**
 * Elgg statistics screen
 *
 * @package Elgg
 * @subpackage Core
 * @author Curverider Ltd
 * @link http://elgg.org/
 */

// Get entity statistics
$entity_stats = get_entity_statistics();
$even_odd = "";
?>
<div class="admin_statistics">
	<h3><?php echo elgg_echo('admin:statistics:label:numentities'); ?></h3>
	<table>
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
							<td class="column_one">{$a}:</td>
							<td>{$b}</td>
						</tr>
END;
				}
			}
		?>
	</table>
</div>