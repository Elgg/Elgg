<?php
/**
 * Elgg (group) member relationship view
 *
 * @note To add or remove from the relationship menu, register handlers for the menu:relationship hook.
 *
 * @uses $vars['relationship']
 */

$relationship = elgg_extract('relationship', $vars);
if (!$relationship instanceof ElggRelationship) {
	return;
}

$entity_one = get_entity($relationship->guid_one);
$entity_two = get_entity($relationship->guid_two);
if (!$entity_one instanceof ElggUser || !$entity_two instanceof ElggGroup) {
	return;
}

$user_params = [
	'entity' => $entity_one,
	'full_view' => false,
];

$user_params['title'] = elgg_format_element('h3', [], elgg_view_entity_url($entity_one));

$subtitle = '';
$location = $entity_one->getProfileData('location');
if (is_string($location) && $location !== '') {
	$location = elgg_view_icon('map-marker') . ' ' . $location;
	$subtitle .= elgg_format_element('div', [], $location);
}

$briefdescription = $entity_one->getProfileData('briefdescription');
if (is_string($briefdescription) && $briefdescription !== '') {
	$subtitle .= elgg_format_element('div', [], $briefdescription);
}

$user_params['subtitle'] = $subtitle ?: false;

if (elgg_view_exists('user/status')) {
	$user_params['content'] = elgg_view('user/status', ['entity' => $entity_one]);
}

$user_params = $user_params + $vars;
echo elgg_view('relationship/elements/summary', $user_params);
