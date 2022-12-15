<?php
/**
 * Elgg statistics screen
 *
 * @uses $vars['entity'] The user entity for whom to show statistics
 */

$user = elgg_extract('entity', $vars, elgg_get_page_owner_entity()); // page owner for BC reasons
if (!$user instanceof ElggUser) {
	return;
}

$entity_stats = elgg_get_entity_statistics($user->guid);
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
		
		$cells[] = elgg_format_element('td', [], elgg_format_element('b', [], "{$label}:"));
		$cells[] = elgg_format_element('td', [], $count);
		
		if (elgg_entity_has_capability($type, $subtype, 'searchable') || elgg_is_admin_logged_in()) {
			$rows[] = elgg_format_element('tr', [], implode(PHP_EOL, $cells));
		}
	}
}

if (empty($rows)) {
	return;
}

if ($user->guid === elgg_get_logged_in_user_guid()) {
	$title = elgg_echo('usersettings:statistics:label:numentities');
} else {
	$title = elgg_echo('usersettings:statistics:numentities:user', [$user->getDisplayName()]);
}

$content = elgg_format_element('table', ['class' => 'elgg-table-alt'], implode(PHP_EOL, $rows));

echo elgg_view_module('info', $title, $content);
