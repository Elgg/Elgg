<?php
/**
 * List all user files
 *
 * Note: this view has a corresponding view in the default view type, changes should be reflected
 *
 * @uses $vars['entity'] the user or group to list for
 */

$owner = elgg_extract('entity', $vars);

// List files
$options = [
	'type' => 'object',
	'subtype' => 'file',
	'distinct' => false,
	'pagination' => false,
];

if ($owner instanceof ElggGroup) {
	$options['container_guid'] = $owner->guid;
} else {
	$options['owner_guid'] = $owner->guid;
}

echo elgg_list_entities($options);
