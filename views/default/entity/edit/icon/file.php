<?php
/**
 * Input for new icon upload
 *
 * @uses $vars['name']     name of the input/file (default: icon)
 * @uses $vars['required'] is icon upload required (default: false)
 */

echo elgg_view_field([
	'#type' => 'file',
	'#label' => elgg_echo('entity:edit:icon:file:label'),
	'#help' => elgg_echo('entity:edit:icon:file:help'),
	'#class' => 'elgg-entity-edit-icon-file',
	'name' => elgg_extract('name', $vars, 'icon'),
	'accept' => 'image/*',
	'required' => (bool) elgg_extract('required', $vars, false),
]);
