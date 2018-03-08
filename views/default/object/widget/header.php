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

$title_text = $widget->getDisplayName();
$url = $widget->getURL();
if (!empty($url)) {
	$title_text = elgg_view('output/url', [
		'text' => $title_text,
		'href' => $url,
		'is_trusted' => true,
	]);
}

$title = "<h3 class='elgg-widget-title'>{$title_text}</h3>";
$controls = elgg_view('object/widget/elements/controls', [
	'widget' => $widget,
	'show_edit' => elgg_extract('show_edit', $vars, $widget->canEdit()),
]);

echo "<div class='elgg-widget-handle'>{$title}</div>{$controls}";
