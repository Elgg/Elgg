<?php
/**
 * Elgg widget edit settings
 *
 * @uses $vars['widget']
 */

$widget = elgg_extract('widget', $vars);
if (!$widget instanceof ElggWidget) {
	return;
}

$form = elgg_view_form('widgets/save', [
	'class' => [
		preg_replace('/[^a-z0-9-]/i', '-', "elgg-form-widgets-save-{$widget->handler}"),
	],
	'prevent_double_submit' => false,
], $vars);

if (empty($form)) {
	return;
}

echo elgg_format_element('div', [
	'class' => 'elgg-widget-edit',
	'id' => "widget-edit-{$widget->guid}",
], $form);
