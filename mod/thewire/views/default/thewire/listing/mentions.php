<?php
/**
 * Show a list of Wire post which mention the given user
 *
 * @uses $vars['entity'] the user which should be mentioned
 */

$entity = elgg_extract('entity', $vars);
if (!$entity instanceof \ElggUser) {
	return;
}

$vars['options'] = [
	'metadata_name_value_pairs' => [
		'name' => 'description',
		'value' => "%@{$entity->username}%",
		'operand' => 'LIKE',
	],
];

echo elgg_view('thewire/listing/all', $vars);
