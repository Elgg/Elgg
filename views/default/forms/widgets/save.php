<?php
/**
 * Elgg widget edit settings
 *
 * @uses $vars['widget']
 * @uses $vars['show_access']
 */

$widget = elgg_extract('widget', $vars);
if (!$widget instanceof ElggWidget) {
	return;
}

$show_access = elgg_extract('show_access', $vars, true);

$custom_form_section = '';
if (elgg_view_exists("widgets/{$widget->handler}/edit")) {
	$custom_form_section = elgg_view("widgets/{$widget->handler}/edit", ['entity' => $widget]);
}

$access = '';
if ($show_access) {
	$access = elgg_view_field([
		'#type' => 'access',
		'#label' => elgg_echo('access'),
		'name' => 'params[access_id]',
		'value' => $widget->access_id,
		'entity' => $widget,
	]);
}

if (!$custom_form_section && !$access) {
	return true;
}

echo $custom_form_section;
echo $access;

echo elgg_view_field([
	'#type' => 'hidden',
	'name' => 'guid',
	'value' => $widget->guid,
]);

if (elgg_in_context('default_widgets')) {
	echo elgg_view_field([
		'#type' => 'hidden',
		'name' => 'default_widgets',
		'value' => 1,
	]);
}

$footer = elgg_view_field([
	'#type' => 'submit',
	'value' => elgg_echo('save'),
]);

elgg_set_form_footer($footer);
