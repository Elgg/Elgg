<?php
/**
 * Widget object header
 *
 * @uses $vars['entity']        ElggWidget
 * @uses $vars['show_controls'] Boolean to control if you want control features on your widget (default true)
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

$title = elgg_format_element('h2', ['class' => 'elgg-widget-title'], $title_text);

echo elgg_format_element('div', ['class' => 'elgg-widget-handle'], $title);

if (elgg_extract('show_controls', $vars, true)) {
	echo elgg_view('object/widget/elements/controls', [
		'widget' => $widget,
		'show_edit' => elgg_extract('show_edit', $vars, $widget->canEdit()),
		'show_access' => elgg_extract('show_access', $vars),
	]);
}
