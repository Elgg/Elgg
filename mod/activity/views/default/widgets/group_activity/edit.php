<?php
/**
 * Group activity widget settings
 */

/* @var $widget \ElggWidget */
$widget = elgg_extract('entity', $vars);

// Widget owner might not be a user entity (e.g. on default widgets config page it's an ElggSite entity)
$owner = $widget->getOwnerEntity();
if (!$owner instanceof \ElggUser) {
	$owner = elgg_get_logged_in_user_entity();
}

/* @var $groups \ElggBatch */
$groups = $owner->getGroups([
	'limit' => false,
	'batch' => true,
	'sort_by' => [
		'property' => 'name',
		'direction' => 'ASC',
	],
]);

$mygroups = [];
if (!$widget->group_guid) {
	$mygroups[0] = '';
}

/* @var $group \ElggGroup */
foreach ($groups as $group) {
	if (!$group->isToolEnabled('activity')) {
		continue;
	}
	
	$mygroups[$group->guid] = $group->getDisplayName();
}

echo elgg_view_field([
	'#type' => 'select',
	'#label' => elgg_echo('widgets:group_activity:edit:select'),
	'name' => 'params[group_guid]',
	'value' => $widget->group_guid,
	'options_values' => $mygroups,
	'required' => true,
]);

echo elgg_view('object/widget/edit/num_display', [
	'entity' => $widget,
	'default' => 8,
]);
