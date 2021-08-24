<?php
/**
 * Group edit form
 *
 * @uses $vars['entity'] The group being edited (empty during creation)
 */

/* @var ElggGroup $entity */
$entity = elgg_extract('entity', $vars, false);

// context needed for input/access view
elgg_push_context('group-edit');

// build group edit tabs
$tabs = [];

// build the group profile fields
$tabs[] = [
	'name' => 'profile',
	'priority' => 100,
	'text' => elgg_echo('groups:edit:profile'),
	'content' => elgg_view('groups/edit/profile', $vars),
];

// build the group access options
$tabs[] = [
	'name' => 'access',
	'priority' => 200,
	'text' => elgg_echo('groups:edit:access'),
	'content' => elgg_view('groups/edit/access', $vars),
];

// build the group tools options
$tabs[] = [
	'name' => 'tools',
	'priority' => 300,
	'text' => elgg_echo('groups:edit:tools'),
	'content' => elgg_view('groups/edit/tools', $vars),
];

// build the group settings options
$settings = elgg_view('groups/edit/settings', $vars);
if (!empty($settings)) {
	$tabs[] = [
		'name' => 'settings',
		'priority' => 400,
		'text' => elgg_echo('groups:edit:settings'),
		'content' => $settings,
	];
}

// show tabs
echo elgg_view('page/components/tabs', [
	'id' => 'groups-edit',
	'tabs' => $tabs,
]);

// display the save button and some additional form data
if ($entity instanceof \ElggGroup) {
	echo elgg_view('input/hidden', [
		'name' => 'group_guid',
		'value' => $entity->guid,
	]);
}

// build form footer
$footer = elgg_view_field([
	'#type' => 'submit',
	'value' => elgg_echo('save'),
]);

elgg_set_form_footer($footer);

elgg_pop_context();
