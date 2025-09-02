<?php
/**
 * Get entity statistics
 */

use Elgg\Database\QueryBuilder;

$entity_stats = elgg_call(ELGG_SHOW_DELETED_ENTITIES, function() {
	return elgg_get_entity_statistics();
});
$trashed_stats = elgg_call(ELGG_SHOW_DELETED_ENTITIES, function() {
	return elgg_get_entity_statistics([
		'wheres' => [
			function(QueryBuilder $qb, $main_alias) {
				return $qb->compare("{$main_alias}.deleted", '=', 'yes', ELGG_VALUE_STRING);
			},
		],
	]);
});

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

		if (elgg_entity_has_capability($type, $subtype, 'searchable')) {
			$searchable[$name] = [
				$value,
				elgg_extract($subtype, elgg_extract($type, $trashed_stats, [])),
			];
		} else {
			$other[$name] = [
				$value,
				elgg_extract($subtype, elgg_extract($type, $trashed_stats, [])),
			];
		}
	}
}

arsort($searchable);
arsort($other);

$header = elgg_format_element('th', [], elgg_echo('admin:statistics:numentities:type'));
$header .= elgg_format_element('th', [], elgg_echo('total'));

$header = elgg_format_element('thead', [], elgg_format_element('tr', [], $header));

// searchable entity stats
$rows = [];
foreach ($searchable as $name => $value) {
	$cells = [];
	$cells[] = elgg_format_element('td', [], $name);
	
	$entity_total = elgg_number_format($value[0]);
	$entity_trashed = elgg_number_format($value[1] ?? 0);
	
	$number = $entity_total . ($entity_trashed ? elgg_format_element('span', ['class' => ['elgg-quiet', 'mls']], elgg_echo('status:trashed') . ': ' . $entity_trashed) : null);
	$cells[] = elgg_format_element('td', [], $number);
	
	$rows[] = elgg_format_element('tr', [], implode('', $cells));
}

$rows = elgg_format_element('tbody', [], implode(PHP_EOL, $rows));

$body = elgg_format_element('table', ['class' => 'elgg-table'], $header . $rows);
echo elgg_view_module('info', elgg_echo('admin:statistics:numentities:searchable'), $body);

// remaining entity stats
$rows = [];
foreach ($other as $name => $value) {
	$cells = [];
	$cells[] = elgg_format_element('td', [], $name);
	
	$entity_total = elgg_number_format($value[0]);
	$entity_trashed = elgg_number_format($value[1] ?? 0);
	
	$number = $entity_total . ($entity_trashed ? elgg_format_element('span', ['class' => ['elgg-quiet', 'mls']], elgg_echo('status:trashed') . ': ' . $entity_trashed) : null);
	$cells[] = elgg_format_element('td', [], $number);
	
	$rows[] = elgg_format_element('tr', [], implode('', $cells));
}

$rows = elgg_format_element('tbody', [], implode(PHP_EOL, $rows));

$body = elgg_format_element('table', ['class' => 'elgg-table'], $header . $rows);
echo elgg_view_module('info', elgg_echo('admin:statistics:numentities:other'), $body);
