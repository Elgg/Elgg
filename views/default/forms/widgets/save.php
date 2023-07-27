<?php
/**
 * Elgg widget edit settings
 *
 * @uses $vars['entity']      The widget entity
 * @uses $vars['widget']      Deprecated; use 'entity' instead
 * @uses $vars['show_access'] (bool) should widget access setting be available default: true
 */

$widget = elgg_extract('widget', $vars);
if ($widget !== null) {
	elgg_deprecated_notice('Passing the widget entity in $vars["widget"] is deprecated. Update your code to provide it in $vars["entity"].', '5.1');
}

$widget = elgg_extract('entity', $vars, $widget);
if (!$widget instanceof \ElggWidget) {
	return;
}

$custom_form_section = '';
if (elgg_view_exists("widgets/{$widget->handler}/edit")) {
	$custom_form_section = elgg_view("widgets/{$widget->handler}/edit", ['entity' => $widget]);
}

$access = '';
if (elgg_extract('show_access', $vars, true)) {
	$access = elgg_view_field([
		'#type' => 'access',
		'#label' => elgg_echo('access'),
		'name' => 'params[access_id]',
		'value' => $widget->access_id,
		'entity' => $widget,
	]);
}

if (!$custom_form_section && !$access) {
	return;
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
	'text' => elgg_echo('save'),
]);

elgg_set_form_footer($footer);
