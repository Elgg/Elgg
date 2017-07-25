<?php
/**
 * Widget object body
 *
 * @uses $vars['entity']      ElggWidget
 * @uses $vars['show_access'] Show the access control in edit area? (true)
 */

$widget = elgg_extract('entity', $vars);
if (!($widget instanceof \ElggWidget)) {
	return;
}

$content = elgg_view('object/widget/elements/content', $vars);
echo elgg_format_element('div', [
	'class' => 'elgg-widget-content',
	'id' => "elgg-widget-content-{$widget->guid}",
], $content);
