<?php
/**
 * Elgg statistics screen
 */

$user = elgg_get_page_owner_entity();
if (!$user instanceof ElggUser) {
	return;
}

$entity_stats = get_entity_statistics($user->guid);
if (empty($entity_stats)) {
	return;
}

$rows = [];
foreach ($entity_stats as $type => $subtypes) {
	foreach ($subtypes as $subtype => $count) {
		$cells = [];
		
		$label = "{$type} {$subtype}";
		if (elgg_language_key_exists("collection:{$type}:{$subtype}")) {
			$label = elgg_echo("collection:{$type}:{$subtype}");
		} elseif (elgg_language_key_exists("item:{$type}:{$subtype}")) {
			$label = elgg_echo("item:{$type}:{$subtype}");
		}
		
		$cells[] = elgg_format_element('td', ['class' => 'column-one'], elgg_format_element('b', [], "{$label}:"));
		$cells[] = elgg_format_element('td', [], $count);
		
		$rows[] = elgg_format_element('tr', [], implode(PHP_EOL, $cells));
	}
}

$content = elgg_format_element('table', ['class' => 'elgg-table-alt'], implode(PHP_EOL, $rows));

echo elgg_view_module('info', elgg_echo('usersettings:statistics:label:numentities'), $content);
