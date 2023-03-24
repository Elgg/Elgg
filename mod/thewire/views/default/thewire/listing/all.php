<?php
/**
 * Show a listing of Wire posts
 *
 * @uses $vars['options'] additional options for elgg_list_entitites()
 */

$defaults = [
	'type' => 'object',
	'subtype' => 'thewire',
	'limit' => max((int) get_input('limit'), (int) elgg_get_config('default_limit'), 15),
	'distinct' => false,
	'full_view' => false,
	'preload_owners' => true,
	'no_results' => elgg_echo('thewire:noposts'),
];

$options = (array) elgg_extract('options', $vars, []);
$options = array_merge($defaults, $options);

echo elgg_list_entities($options);
