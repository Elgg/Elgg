<?php
/**
 * Show a list of Wire post which mention the given user
 *
 * @uses $vars['options'] Additional listing options
 * @uses $vars['entity'] the user which should be mentioned
 */

$options = (array) elgg_extract('options', $vars);
$entity = elgg_extract('entity', $vars);
if (!$entity instanceof \ElggUser) {
	return;
}

$mention_options = [
	'metadata_name_value_pairs' => [
		'name' => 'description',
		'value' => "%@{$entity->username}%",
		'operand' => 'LIKE',
	],
];

$vars['options'] = array_merge($options, $mention_options);

echo elgg_view('thewire/listing/all', $vars);
