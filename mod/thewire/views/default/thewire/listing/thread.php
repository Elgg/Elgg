<?php
/**
 * Show a thread listing for a given wire post
 *
 * @uses $vars['entity'] the thread entity
 */

$entity = elgg_extract('entity', $vars);
if (!$entity instanceof \ElggWire) {
	return;
}

$vars['options'] = [
	'metadata_name_value_pairs' => [
		'name' => 'wire_thread',
		'value' => $entity->guid,
		'type' => ELGG_VALUE_INTEGER,
	],
];

echo elgg_view('thewire/listing/all', $vars);
