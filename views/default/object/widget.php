<?php
/**
 * Widget object
 *
 * @uses $vars['entity']      ElggWidget
 * @uses $vars['show_access'] Show the access control in edit area? (true)
 * @uses $vars['class']       Optional additional CSS class
 */

$widget = elgg_extract('entity', $vars);
if (!$widget instanceof \ElggWidget) {
	return;
}

$widget_instance = preg_replace('/[^a-z0-9-]/i', '-', "elgg-widget-instance-{$widget->handler}");
$widget_class = elgg_extract_class($vars, $widget_instance);

if ($widget->canEdit()) {
	$widget_class[] = 'elgg-state-draggable';
	
	if (!elgg_view_exists("widgets/{$widget->handler}/edit") && elgg_extract('show_access', $vars) === false) {
		// store for determining the edit menu item
		$vars['show_edit'] = false;
	}
}

$body = elgg_view('object/widget/body', $vars);

echo elgg_view_module('widget', '', $body, [
	'class' => $widget_class,
	'id' => "elgg-widget-{$widget->guid}",
	'header' => elgg_view('object/widget/header', $vars),
]);
