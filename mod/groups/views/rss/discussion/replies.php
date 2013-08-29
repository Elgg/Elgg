<?php
/**
 * List replies RSS view
 *
 * @uses $vars['entity'] ElggEntity
 */

$options = array(
	'container_guid' => $vars['topic']->getGUID(),
	'type' => 'object',
	'subtype' => 'discussion_reply',
);
echo elgg_list_entities($options);
