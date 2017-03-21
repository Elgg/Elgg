<?php
// Get entity statistics
$entity_stats = get_entity_statistics();
$rows = '';
foreach ($entity_stats as $k => $entry) {
	arsort($entry);
	foreach ($entry as $a => $b) {
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
		
		$rows .= "<tr><td>{$a}:</td><td>{$b}</td></tr>";
	}
}

echo "<table class='elgg-table-alt'>$rows</table>";
