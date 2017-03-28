<?php
/**
 * Elgg widget edit settings
 *
 * @uses $vars['widget']
 * @uses $vars['show_access']
 */

$widget = $vars['widget'];
$show_access = elgg_extract('show_access', $vars, true);

$edit_view = "widgets/$widget->handler/edit";

$form = elgg_view($edit_view, ['entity' => $widget]);

if ($show_access) {
	$form .= elgg_view_field([
		'#type' => 'access',
		'#label' => elgg_echo('access'),
		'name' => 'params[access_id]',
		'value' => $widget->access_id,
	]);
}

if (empty($form)) {
	echo elgg_format_element('p', [
		'class' => 'elgg-no-results',
	], elgg_echo('widgets:settings:empty'));
	return;
}

echo $form;

echo elgg_view('input/hidden', ['name' => 'guid', 'value' => $widget->guid]);
if (elgg_in_context('default_widgets')) {
	echo elgg_view('input/hidden', ['name' => 'default_widgets', 'value' => 1]);
}

echo elgg_view_field([
	'#type' => 'submit',
	'#class' => 'elgg-foot',
	'value' => elgg_echo('save'),
]);

