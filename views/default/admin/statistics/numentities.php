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
			$is_registered = in_array($subtype, $registered_entity_types[$type]);
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

$header = '<tr><th>' . elgg_echo('admin:statistics:numentities:type') . '</th>';
$header .= '<th>' . elgg_echo('admin:statistics:numentities:number') . '</th></tr>';

$rows = '';

foreach ($searchable as $name => $value) {
	$rows .= "<tr><td>{$name}</td><td>{$value}</td></tr>";
}
echo '<h4>' . elgg_echo('admin:statistics:numentities:searchable') . '</h4>';
echo "<table class='elgg-table-alt'>{$header}{$rows}</table>";
echo '<br />';


$rows = '';
foreach ($other as $name => $value) {
	$rows .= "<tr><td>{$name}</td><td>{$value}</td></tr>";
}
echo '<h4>' . elgg_echo('admin:statistics:numentities:other') . '</h4>';
echo "<table class='elgg-table-alt'>{$header}{$rows}</table>";
