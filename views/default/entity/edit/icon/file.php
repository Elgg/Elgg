<?php
/**
 * Input for new icon upload
 *
 * @uses $vars['name']     name of the input/file (default: icon)
 * @uses $vars['required'] is icon upload required (default: false)
 */

$icon_type = elgg_extract('icon_type', $vars, 'icon');
if (elgg_language_key_exists("entity:edit:{$icon_type}:file:label")) {
	$label = elgg_echo("entity:edit:{$icon_type}:file:label");
} else {
	$label = elgg_echo('entity:edit:icon:file:label');
}

if (elgg_language_key_exists("entity:edit:{$icon_type}:file:help")) {
	$help = elgg_echo("entity:edit:{$icon_type}:file:help");
} else {
	$help = elgg_echo('entity:edit:icon:file:help');
}

echo elgg_view_field([
	'#type' => 'file',
	'#label' => $label,
	'#help' => $help,
	'#class' => 'elgg-entity-edit-icon-file',
	'name' => elgg_extract('name', $vars, 'icon'),
	'accept' => 'image/*',
	'required' => (bool) elgg_extract('required', $vars, false),
]);
