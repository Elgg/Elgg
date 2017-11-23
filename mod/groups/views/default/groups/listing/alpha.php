<?php
/**
 * Renders a list of groups ordered alphabetically
 */
echo elgg_list_entities([
	'type' => 'group',
	'order_by_metadata' => [
		'name' => 'name',
		'direction' => 'ASC',
	],
	'full_view' => false,
	'no_results' => elgg_echo('groups:none'),
]);
