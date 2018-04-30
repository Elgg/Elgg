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

$table = new \Elgg\Markup\Table();
$table->addClass('elgg-table');

$table->setHeadings(
	elgg_echo('admin:statistics:numentities:type'),
	elgg_echo('admin:statistics:numentities:number')
);

foreach ($stats as $name => $num) {
	$table->addRow([$name, $num]);
}

echo $table;

$more = new \Elgg\Markup\Block();
$more->addClass('elgg-widget-more');
$more->appendView('output/url', [
	'href' => 'admin/statistics/numentities',
	'text' => elgg_echo('more'),
	'is_trusted' => true,
]);

echo $more;