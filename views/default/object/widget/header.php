<?php
/**
 * Widget object header
 *
 * @uses $vars['entity'] ElggWidget
 */

$widget = elgg_extract('entity', $vars);
if (!$widget instanceof \ElggWidget) {
	return;
}

$title_text = $widget->getDisplayName();
$url = $widget->getURL();
if (!empty($url)) {
	$title_text = elgg_view_url($url, $title_text);
}

$title = elgg_format_element('h3', ['class' => 'elgg-widget-title'], $title_text);

echo elgg_format_element('div', ['class' => 'elgg-widget-handle'], $title);

echo elgg_view('object/widget/elements/controls', [
	'widget' => $widget,
	'show_edit' => elgg_extract('show_edit', $vars, $widget->canEdit()),
]);
