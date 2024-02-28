<?php
/**
 * List all discussions in the users groups
 *
 * Note: this view has a corresponding view in the default view type, changes should be reflected
 *
 * @uses $vars['options'] Additional listing options
 * @uses $vars['entity']  the user to list group content for
 */

$options = (array) elgg_extract('options', $vars);
$entity = elgg_extract('entity', $vars);
if (!$entity instanceof \ElggUser) {
	return;
}

// check of the user is a member of any groups
$user_groups = $entity->getGroups([
	'limit' => false,
	'callback' => function ($row) {
		return (int) $row->guid;
	},
]);

if (empty($user_groups)) {
	return;
}

$user_options = [
	'container_guids' => $user_groups,
];

$vars['options'] = array_merge($options, $user_options);

echo elgg_view('discussion/listing/all', $vars);
