<?php
/**
 * Show a remove icon toggle
 *
 * @uses $vars['entity']      entity to edit
 * @uses $vars['icon_type']   the type of the icon (default: icon)
 * @uses $vars['name']        name of the input/file (default: icon)
 * @uses $vars['remove_name'] name of the remove icon toggle (default: $vars['name'] . '_remove')
 * @uses $vars['show_remove'] show the remove icon option (default: true)
 */

if (elgg_extract('show_remove', $vars, true) === false) {
	return;
}

$entity = elgg_extract('entity', $vars);
if (!$entity instanceof ElggEntity) {
	// no way to check for icon
	return;
}

$icon_type = elgg_extract('icon_type', $vars, 'icon');
if (!$entity->hasIcon('master', $icon_type)) {
	// no icon uploaded
	return;
}

$name = elgg_extract('remove_name', $vars);
if (elgg_is_empty($name)) {
	// make name based on file name
	$name = elgg_extract('name', $vars, 'icon') . '_remove';
}

echo elgg_view_field([
	'#type' => 'checkbox',
	'#label' => elgg_echo('entity:edit:icon:remove:label'),
	'#class' => 'elgg-entity-edit-icon-remove',
	'name' => $name,
	'value' => 1,
	'switch' => true,
]);
