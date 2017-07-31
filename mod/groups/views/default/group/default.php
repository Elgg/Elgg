<?php
/**
 * Group entity view
 *
 * @package ElggGroups
 */

$group = elgg_extract('entity', $vars);
if (!($group instanceof \ElggGroup)) {
	return;
}

$icon = elgg_view_entity_icon($group, 'tiny', $vars);

$metadata = '';
if (!elgg_in_context('owner_block') && !elgg_in_context('widgets')) {
	// only show entity menu outside of widgets and owner block
	$metadata = elgg_view_menu('entity', [
		'entity' => $group,
		'handler' => 'groups',
		'sort_by' => 'priority',
		'class' => 'elgg-menu-hz',
	]);
}

if ($vars['full_view']) {
	echo elgg_view('groups/profile/summary', $vars);
} else {
	
	$subtitle = '';
	
	// membership type
	if ($group->isPublicMembership()) {
		$text = elgg_echo('groups:open');
		$icon_name = 'lock';
	} else {
		$text = elgg_echo('groups:closed');
		$icon_name = 'unlock-alt';
	}
	$subtitle .= elgg_format_element('span', ['class' => 'groups-membership'], elgg_view_icon($icon_name) . $text);
	
	// number of members
	$subtitle .= elgg_format_element('span', [
		'class' => 'groups-members',
	], elgg_view_icon('users') . $group->getMembers(['count' => true]) . ' ' . elgg_echo('groups:member'));
	
	$subtitle .= elgg_format_element('div', ['class' => 'groups-description'], $group->briefdescription);
	
	// brief view
	$params = [
		'entity' => $group,
		'metadata' => $metadata,
		'subtitle' => $subtitle,
	];
	$params = $params + $vars;
	$list_body = elgg_view('group/elements/summary', $params);

	echo elgg_view_image_block($icon, $list_body, $vars);
}
