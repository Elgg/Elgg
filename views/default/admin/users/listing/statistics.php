<?php
/**
 * Show user content statistics in admin listings
 *
 * @uses $vars['entity'] The user entity to show
 */

$entity = elgg_extract('entity', $vars);
if (!$entity instanceof \ElggUser) {
	return;
}

$entity_stats = get_entity_statistics($entity->guid);
if (empty($entity_stats)) {
	echo elgg_view('page/components/no_results', ['no_results' => elgg_echo('notfound')]);
	return;
}

$searchable_rows = [];
$other_rows = [];
foreach ($entity_stats as $type => $subtypes) {
	foreach ($subtypes as $subtype => $count) {
		$cells = [];
		
		$label = "{$type} {$subtype}";
		if (elgg_language_key_exists("collection:{$type}:{$subtype}")) {
			$label = elgg_echo("collection:{$type}:{$subtype}");
		} elseif (elgg_language_key_exists("item:{$type}:{$subtype}")) {
			$label = elgg_echo("item:{$type}:{$subtype}");
		}
		
		$cells[] = elgg_format_element('td', [], $label);
		$cells[] = elgg_format_element('td', [], $count);
		
		if (elgg_entity_has_capability($type, $subtype, 'searchable')) {
			$searchable_rows[] = elgg_format_element('tr', [], implode(PHP_EOL, $cells));
		} else {
			$other_rows[] = elgg_format_element('tr', [], implode(PHP_EOL, $cells));
		}
	}
}

if (!empty($searchable_rows)) {
	$content = elgg_format_element('table', ['class' => 'elgg-table-alt'], implode(PHP_EOL, $searchable_rows));
	echo elgg_view_module('info', elgg_echo('admin:statistics:numentities:searchable'), $content);
}

if (!empty($other_rows)) {
	$content = elgg_format_element('table', ['class' => 'elgg-table-alt'], implode(PHP_EOL, $other_rows));
	echo elgg_view_module('info', elgg_echo('admin:statistics:numentities:other'), $content);
}
