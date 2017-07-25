<?php
/**
 * Widget object
 *
 * @uses $vars['entity']      ElggWidget
 * @uses $vars['show_access'] Show the access control in edit area? (true)
 * @uses $vars['class']       Optional additional CSS class
 */

$widget = elgg_extract('entity', $vars);
if (!($widget instanceof \ElggWidget)) {
	return;
}

$handler = $widget->handler;

$widget_instance = preg_replace('/[^a-z0-9-]/i', '-', "elgg-widget-instance-$handler");

$widget_class = elgg_extract_class($vars, $widget_instance);
$widget_class[] = $widget->canEdit() ? 'elgg-state-draggable' : 'elgg-state-fixed';

$body = '';
if ($widget->canEdit()) {
	$settings = elgg_view('object/widget/elements/settings', [
		'widget' => $widget,
		'show_access' => elgg_extract('show_access', $vars, true),
	]);
	$body .= $settings;
	
	if (empty($settings)) {
		// store for determining the edit menu item
		$vars['show_edit'] = false;
	}
}

$body .= elgg_view('object/widget/body', $vars);

echo elgg_view_module('widget', '', $body, [
	'class' => $widget_class,
	'id' => "elgg-widget-$widget->guid",
	'header' => elgg_view('object/widget/header', $vars),
]);
