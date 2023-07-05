<?php
/**
 * Elgg widget edit settings
 *
 * @uses $vars['entity'] the widget entity
 */

use Elgg\Exceptions\Http\EntityPermissionsException;

$widget = elgg_extract('entity', $vars);
if (!$widget instanceof \ElggWidget) {
	return;
}

if (!$widget->canEdit()) {
	throw new EntityPermissionsException();
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
