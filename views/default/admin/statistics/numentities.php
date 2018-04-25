<?php
// Get entity statistics
$entity_stats = get_entity_statistics();

$registered_entity_types = get_registered_entity_types();

$searchable = [];
$other = [];

foreach ($entity_stats as $type => $subtypes) {
	foreach ($subtypes as $subtype => $value) {
		$is_registered = false;
		if ($subtype == '__base__') {
			$is_registered = array_key_exists($type, $registered_entity_types);
			$name = elgg_echo("collection:$type");
		} else {
			$is_registered = in_array($subtype, elgg_extract($type, $registered_entity_types, []));
			$name = elgg_echo("collection:$type:$subtype");
		}

		if ($is_registered) {
			$searchable[$name] = $value;
		} else {
			$other[$name] = $value;
		}
	}
}

arsort($searchable);
arsort($other);

$table = new \Elgg\Markup\Table();
$table->addClass('elgg-table-alt')
	->setHeadings(
		elgg_echo('admin:statistics:numentities:type'),
		elgg_echo('admin:statistics:numentities:number')
	);

foreach ($searchable as $name => $value) {
	$table->addRow([
		$name,
		$value,
	]);
}

echo elgg_view_module('info', elgg_echo('admin:statistics:numentities:searchable'), $table->render());

$table = new \Elgg\Markup\Table();
$table->addClass('elgg-table-alt')
	->setHeadings(
		elgg_echo('admin:statistics:numentities:type'),
		elgg_echo('admin:statistics:numentities:number')
	);

foreach ($other as $name => $value) {
	$table->addRow([
		$name,
		$value,
	]);
}

echo elgg_view_module('info', elgg_echo('admin:statistics:numentities:other'), $table->render());
