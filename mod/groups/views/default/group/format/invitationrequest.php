<?php
/**
 * Group view for an invitation request
 *
 * @uses $vars['entity'] Group entity
 */

$user = elgg_get_page_owner_entity();
if (!$user instanceof \ElggUser || !$user->canEdit()) {
	return;
}

$group = elgg_extract('entity', $vars);
if (!$group instanceof \ElggGroup) {
	return;
}

$menu = elgg_view_menu('invitationrequest', [
	'entity' => $group,
	'user' => $user,
	'order_by' => 'priority',
	'class' => 'elgg-menu-hz float-alt',
]);

echo elgg_view('group/elements/summary', [
	'entity' => $group,
	'icon' => true,
	'subtitle' => $group->briefdescription,
	'metadata' => $menu,
]);
