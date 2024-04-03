<?php
/**
 * Group edit form
 *
 * @uses $vars['entity'] The group being edited (empty during creation)
 */

/* @var ElggGroup $entity */
$entity = elgg_extract('entity', $vars, false);

$sections = [
	100 => 'profile',
	150 => 'images',
	200 => 'access',
	300 => 'tools',
	400 => 'settings',
];

// build group edit tabs
$tabs = [];

foreach ($sections as $priority => $name) {
	$content = elgg_view("groups/edit/{$name}", $vars);
	if (empty($content)) {
		continue;
	}
	
	$tabs[] = [
		'name' => $name,
		'priority' => $priority,
		'text' => elgg_echo("groups:edit:{$name}"),
		'content' => $content,
	];
}

// show tabs
echo elgg_view('page/components/tabs', [
	'id' => 'groups-edit',
	'tabs' => $tabs,
]);

// display the save button and some additional form data
$footer = '';
$submit_classes = ['elgg-groups-edit-footer-submit'];
if ($entity instanceof \ElggGroup) {
	echo elgg_view('input/hidden', [
		'name' => 'group_guid',
		'value' => $entity->guid,
	]);
} else {
	elgg_import_esm('forms/groups/create_navigation');
	
	$footer .= elgg_view_field([
		'#type' => 'fieldset',
		'#class' => 'elgg-groups-edit-footer-navigate',
		'fields' => [
			[
				'#type' => 'button',
				'id' => 'elgg-groups-edit-footer-navigate-next',
				'icon_alt' => 'chevron-right',
				'text' => elgg_echo('next'),
				'class' => 'elgg-button-action',
			],
		],
		'align' => 'horizontal',
	]);
	
	$submit_classes[] = 'hidden';
}

// build form footer
$footer .= elgg_view_field([
	'#type' => 'fieldset',
	'#class' => $submit_classes,
	'fields' => [
		[
			'#type' => 'submit',
			'text' => elgg_echo('save'),
		],
	],
	'align' => 'horizontal',
]);

elgg_set_form_footer($footer);
