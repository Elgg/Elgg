<?php
/**
 * List replies RSS view
 *
 * @uses $vars['entity'] ElggEntity
 */

$options = [
	'container_guid' => $vars['topic']->getGUID(),
	'type' => 'object',
	'subtype' => 'discussion_reply',
	'distinct' => false,
];
echo elgg_list_entities($options);
