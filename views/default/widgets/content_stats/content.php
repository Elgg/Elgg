<?php
/**
 * Content stats widget
 */

$entity_stats = elgg_get_entity_statistics();

$registered_entity_types = elgg_entity_types_with_capability('searchable');
if (empty($registered_entity_types)) {
	echo elgg_view('output/longtext', [
		'value' => elgg_echo('notfound'),
	]);
}

$stats = [];

foreach ($registered_entity_types as $type => $subtypes) {
	foreach ($subtypes as $subtype) {
		$value = elgg_extract($subtype, elgg_extract($type, $entity_stats), false);
		if ($value !== false) {
			$stats[elgg_echo("collection:{$type}:{$subtype}")] = $value;
		}
	}
}

arsort($stats);

echo '<table class="elgg-table">';
echo '<thead><tr>';
echo elgg_format_element('th', [], elgg_echo('admin:statistics:numentities:type'));
echo elgg_format_element('th', [], elgg_echo('admin:statistics:numentities:number'));
echo '</tr></thead>';
echo '<tbody>';

foreach ($stats as $name => $num) {
	echo "<tr><td>{$name}</td><td>{$num}</td></tr>";
}

echo '</tbody>';
echo '</table>';

echo elgg_view('page/components/list/widget_more', [
	'widget_more' => elgg_view_url('admin/statistics/numentities', elgg_echo('more')),
]);
