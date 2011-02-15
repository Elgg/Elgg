<?php
/**
 * Widget object
 *
 * @uses $vars['entity']      ElggWidget
 * @uses $vars['show_access'] Show the access control in edit area? (true)
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
	$edit_area = elgg_view('layout/objects/widget/settings', array(
		'widget' => $widget,
		'show_access' => $show_access,
	));
}
$controls = elgg_view('layout/objects/widget/controls', array(
	'widget' => $widget,
	'show_edit' => $edit_area != '',
));


if (elgg_view_exists("widgets/$handler/content")) {
	$content = elgg_view("widgets/$handler/content", $vars);
} else {
	elgg_deprecated_notice("widgets use content as the display view", 1.8);
	$content = elgg_view("widgets/$handler/view", $vars);
}


$widget_id = "elgg-widget-$widget->guid";
$widget_instance = "elgg-widget-instance-$handler";
$widget_class = "elgg-module elgg-module-widget";
if ($can_edit) {
	$widget_class .= " elgg-state-draggable $widget_instance";
} else {
	$widget_class .= " elgg-state-fixed $widget_instance";
}

echo <<<HTML
<div class="$widget_class" id="$widget_id">
	<div class="elgg-head">
		<h3>$title</h3>
		$controls
	</div>
	<div class="elgg-body">
		$edit_area
		<div class="elgg-widget-content">
			$content
		</div>
	</div>
</div>
HTML;
