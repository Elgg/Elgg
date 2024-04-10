<?php
/**
 * Show a thread listing for a given wire post
 *
 * @uses $vars['options']   Additional listing options
 * @uses $vars['entity']    the thread entity (main wire post)
 * @uses $vars['thread_id'] the thread id
 */

$options = (array) elgg_extract('options', $vars);
$entity = elgg_extract('entity', $vars);
$thread_id = (int) elgg_extract('thread_id', $vars);
if (!$entity instanceof \ElggWire && empty($thread_id)) {
	return;
}

$thread_options = [
	'metadata_name_value_pairs' => [
		'name' => 'wire_thread',
		'value' => ($entity instanceof \ElggWire) ? $entity->guid : $thread_id,
		'type' => ELGG_VALUE_INTEGER,
	],
];

$vars['options'] = array_merge($options, $thread_options);

echo elgg_view('thewire/listing/all', $vars);
