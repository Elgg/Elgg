<?php
/**
 * Relationship view of a group membership request
 *
 * @note To add or remove from the relationship menu, register handlers for the menu:relationship hook.
 *
 * @uses $vars['relationship'] the group membership request
 */

$relationship = elgg_extract('relationship', $vars);
if (!$relationship instanceof ElggRelationship) {
	return;
}

$user = get_entity($relationship->guid_one);
$group = get_entity($relationship->guid_two);
if (!$group instanceof ElggGroup || !$user instanceof ElggUser) {
	return;
}

$page_owner = elgg_get_page_owner_entity();
if ($page_owner->guid === $group->guid) {
	$vars['icon_entity'] = $user;
	$vars['title'] = elgg_view_entity_url($user);
}

echo elgg_view('relationship/elements/summary', $vars);
