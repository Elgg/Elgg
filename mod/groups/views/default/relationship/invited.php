<?php
/**
 * Relationship view of a group membership invitation
 *
 * @note To add or remove from the relationship menu, register handlers for the menu:relationship hook.
 *
 * @uses $vars['relationship'] the group invitation
 */

$relationship = elgg_extract('relationship', $vars);
if (!$relationship instanceof ElggRelationship) {
	return;
}

$group = get_entity($relationship->guid_one);
$user = get_entity($relationship->guid_two);
if (!$group instanceof ElggGroup || !$user instanceof ElggUser) {
	return;
}

$page_owner = elgg_get_page_owner_entity();
if ($page_owner->guid === $group->guid) {
	$vars['icon_entity'] = $user;
	$vars['title'] = elgg_view_entity_url($user);
} elseif ($page_owner->guid === $user->guid) {
	$vars['icon_entity'] = $group;
	$vars['title'] = elgg_view_entity_url($group);
	$vars['subtitle'] = $group->briefdescription;
}

echo elgg_view('relationship/elements/summary', $vars);
