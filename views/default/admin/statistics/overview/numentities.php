<?php

$entity_stats = get_entity_statistics();
$even_odd = '';

$table_data = '';
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
		
		$rowdata = elgg_format_element('td', [], "{$a}:");
		$rowdata .= elgg_format_element('td', [], $b);
		
		$table_data .= elgg_format_element('tr', ['class' => $even_odd], $rowdata);
	}
}

echo elgg_format_element('table', ['class' => 'elgg-table-alt'], $table_data);