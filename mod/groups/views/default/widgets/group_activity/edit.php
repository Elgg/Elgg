<?php
/**
 * Group activity widget settings
 */

$widget = elgg_extract('entity', $vars);

// Widget owner might not be a user entity (e.g. on default widgets config page it's an ElggSite entity)
$owner = $widget->getOwnerEntity();
if (!($owner instanceof ElggUser)) {
	$owner = elgg_get_logged_in_user_entity();
}
$groups = $owner->getGroups(['limit' => false]);
$mygroups = [];
if (!$widget->group_guid) {
	$mygroups[0] = '';
}
foreach ($groups as $group) {
	$mygroups[$group->guid] = $group->name;
}

echo elgg_view_field([
	'#type' => 'select',
	'name' => 'params[group_guid]',
	'#label' => elgg_echo('groups:widget:group_activity:edit:select'),
	'value' => $widget->group_guid,
	'options_values' => $mygroups,
]);

// set default value
if (!isset($widget->num_display)) {
	$widget->num_display = 8;
}

echo elgg_view('object/widget/edit/num_display', [
	'entity' => $widget,
]);

echo elgg_view('input/hidden', ['name' => 'title']);
