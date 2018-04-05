<?php
/**
 * Content stats widget
 */

$widget = elgg_extract('entity', $vars);

$entity_stats = get_entity_statistics();

$registered_entity_types = get_registered_entity_types();

$stats = [];

foreach ($registered_entity_types as $type => $subtypes) {
	if (!empty($subtypes)) {
		foreach ($subtypes as $subtype) {
			$value = elgg_extract($subtype, elgg_extract($type, $entity_stats), false);
			if ($value !== false) {
				$stats[elgg_echo("collection:$type:$subtype")] = $value;
			}
		}
	} else {
		$value = elgg_extract('__base__', elgg_extract($type, $entity_stats), false);
		if ($value !== false) {
			$stats[elgg_echo("collection:$type")] = $value;
		}
	}
}

arsort($stats);

echo '<table class="elgg-table">';
echo '<thead><tr><th>' . elgg_echo('admin:statistics:numentities:type') . '</th>';
echo '<th>' . elgg_echo('admin:statistics:numentities:number') . '</th></tr></thead>';
foreach ($stats as $name => $num) {
	echo "<tr><td>$name</td><td>$num</td></tr>";
}
echo '</table>';

echo '<div class="mtm elgg-widget-more">';
echo elgg_view('output/url', [
	'href' => 'admin/statistics/numentities',
	'text' => elgg_echo('more'),
	'is_trusted' => true,
]);
echo '</div>';
