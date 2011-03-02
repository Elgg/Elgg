<?php
// Get entity statistics
$entity_stats = get_entity_statistics();
$even_odd = "";
?>		
<table class="elgg-table-alt">
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
