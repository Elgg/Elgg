<?php
/**
 * Feature a group
 *
 * @package ElggGroups
 */

$group_guid = (int) get_input('group_guid');
$action = get_input('action_type');

$group = get_entity($group_guid);

if (!($group instanceof \ElggGroup)) {
	return elgg_error_response(elgg_echo('groups:featured_error'));
}

//get the action, is it to feature or unfeature
if ($action == 'feature') {
	$group->featured_group = 'yes';
	$message = elgg_echo('groups:featuredon', [$group->getDisplayName()]);
} else {
	$group->deleteMetadata('featured_group');
	$message = elgg_echo('groups:unfeatured', [$group->getDisplayName()]);
}

return elgg_ok_response('', $message);
