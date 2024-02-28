<?php
/**
 * Show a thread listing for a given wire post
 *
 * @uses $vars['options'] Additional listing options
 * @uses $vars['entity']  the thread entity
 */

$options = (array) elgg_extract('options', $vars);
$entity = elgg_extract('entity', $vars);
if (!$entity instanceof \ElggWire) {
	return;
}

$thread_options = [
	'metadata_name_value_pairs' => [
		'name' => 'wire_thread',
		'value' => $entity->guid,
		'type' => ELGG_VALUE_INTEGER,
	],
];

$vars['options'] = array_merge($options, $thread_options);

echo elgg_view('thewire/listing/all', $vars);
