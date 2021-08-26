<?php
// Get entity statistics
$entity_stats = get_entity_statistics();

$searchable = [];
$other = [];

foreach ($entity_stats as $type => $subtypes) {
	foreach ($subtypes as $subtype => $value) {
		$name = "{$type} - {$subtype}";
		if (elgg_language_key_exists("collection:{$type}:{$subtype}")) {
			$name = elgg_echo("collection:{$type}:{$subtype}");
		} elseif (elgg_language_key_exists("item:{$type}:{$subtype}")) {
			$name = elgg_echo("item:{$type}:{$subtype}");
		}

		if (is_registered_entity_type($type, $subtype)) {
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

$body = "<table class='elgg-table'><thead>{$header}</thead><tbody>{$rows}</tbody></table>";
echo elgg_view_module('info', elgg_echo('admin:statistics:numentities:searchable'), $body);


$rows = '';
foreach ($other as $name => $value) {
	$rows .= "<tr><td>{$name}</td><td>{$value}</td></tr>";
}

$body = "<table class='elgg-table'><thead>{$header}</thead><tbody>{$rows}</tbody></table>";
echo elgg_view_module('info', elgg_echo('admin:statistics:numentities:other'), $body);
