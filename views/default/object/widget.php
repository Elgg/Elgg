<?php
/**
 * Widget object
 *
 * @uses $vars['entity']      ElggWidget
 * @uses $vars['show_access'] Show the access control in edit area? (true)
 * @uses $vars['class']       Optional additional CSS class
 */

$widget = $vars['entity'];
if (!elgg_instanceof($widget, 'object', 'widget')) {
	return true;
}

$show_access = elgg_extract('show_access', $vars, true);

// @todo catch for disabled plugins
$widget_types = elgg_get_widget_types('all');

$handler = $widget->handler;

$title = $widget->getTitle();

$edit_area = '';
$can_edit = $widget->canEdit();
if ($can_edit) {
	$edit_area = elgg_view('object/widget/elements/settings', array(
		'widget' => $widget,
		'show_access' => $show_access,
	));
}
$controls = elgg_view('object/widget/elements/controls', array(
	'widget' => $widget,
	'show_edit' => $edit_area != '',
));

$content = elgg_view('object/widget/elements/content', $vars);

$widget_id = "elgg-widget-$widget->guid";
$widget_instance = preg_replace('/[^a-z0-9-]/i', '-', "elgg-widget-instance-$handler");
if ($can_edit) {
	$widget_class = "elgg-state-draggable $widget_instance";
} else {
	$widget_class = "elgg-state-fixed $widget_instance";
}

$additional_class = elgg_extract('class', $vars, '');
if ($additional_class) {
	$widget_class = "$widget_class $additional_class";
}

$widget_header = <<<HEADER
	<div class="elgg-widget-handle clearfix"><h3 class="elgg-widget-title">$title</h3>
	$controls
	</div>
HEADER;

$widget_body = <<<BODY
	$edit_area
	<div class="elgg-widget-content" id="elgg-widget-content-$widget->guid">
		$content
	</div>
BODY;

echo elgg_view_module('widget', '', $widget_body, array(
	'class' => $widget_class,
	'id' => $widget_id,
	'header' => $widget_header,
));
