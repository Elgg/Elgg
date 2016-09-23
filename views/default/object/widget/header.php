<?php
/**
 * Widget object header
 *
 * @uses $vars['entity'] ElggWidget
 */

$widget = elgg_extract('entity', $vars);
if (!($widget instanceof \ElggWidget)) {
	return;
}

$title = "<h3 class='elgg-widget-title'>{$widget->getTitle()}</h3>";
$controls = elgg_view('object/widget/elements/controls', [
	'widget' => $widget,
	'show_edit' => $widget->canEdit(),
]);

echo "<div class='elgg-widget-handle clearfix'>{$title}{$controls}</div>";
