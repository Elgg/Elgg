<?php
/**
 * Show user attributes in admin listings
 *
 * @uses $vars['entity'] The user entity to show
 */

$entity = elgg_extract('entity', $vars);
if (!$entity instanceof \ElggUser) {
	return;
}

$data = [
	[
		elgg_echo('table_columns:fromProperty:name'),
		$entity->getDisplayName(),
	],
	[
		elgg_echo('table_columns:fromProperty:username'),
		$entity->username,
	],
	[
		elgg_echo('table_columns:fromProperty:email'),
		$entity->email,
	],
	[
		elgg_echo('table_columns:fromView:time_created'),
		elgg_view('output/datetime-local', ['value' => $entity->time_created, 'format' => elgg_echo('friendlytime:date_format')]),
	],
	[
		elgg_echo('table_columns:fromView:time_updated'),
		elgg_view('output/datetime-local', ['value' => $entity->time_updated, 'format' => elgg_echo('friendlytime:date_format')]),
	],
	[
		elgg_echo('table_columns:fromView:last_action'),
		elgg_view('output/datetime-local', ['value' => $entity->last_action, 'format' => elgg_echo('friendlytime:date_format')]),
	],
	[
		elgg_echo('table_columns:fromView:last_login'),
		elgg_view('output/datetime-local', ['value' => $entity->last_login, 'format' => elgg_echo('friendlytime:date_format')]),
	],
	[
		elgg_echo('table_columns:fromView:prev_last_login'),
		elgg_view('output/datetime-local', ['value' => $entity->prev_last_login, 'format' => elgg_echo('friendlytime:date_format')]),
	],
	[
		elgg_echo('table_columns:fromView:admin'),
		$entity->isAdmin() ? elgg_echo('option:yes') : elgg_echo('option:no'),
	],
	[
		elgg_echo('table_columns:fromView:banned'),
		$entity->isBanned() ? elgg_echo('option:yes') : elgg_echo('option:no'),
	],
	[
		elgg_echo('table_columns:fromProperty:validated'),
		$entity->isValidated() ? elgg_echo('option:yes') : elgg_echo('option:no'),
	],
];

$rows = [];
foreach ($data as $row) {
	$cells = [];
	
	foreach ($row as $cell) {
		$cells[] = elgg_format_element('td', [], (string) $cell);
	}
	
	$rows[] = elgg_format_element('tr', [], implode(PHP_EOL, $cells));
}

echo elgg_format_element('table', ['class' => 'elgg-table-alt'], implode(PHP_EOL, $rows));
