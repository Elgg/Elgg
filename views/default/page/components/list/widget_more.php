<?php
/**
 * Widget more
 *
 * @uses $vars['widget_more'] More link used in widget content listings
 */

$widget_more = elgg_extract('widget_more', $vars);
if (empty($widget_more)) {
	return;
}

echo elgg_format_element('div', ['class' => 'elgg-widget-more'], $widget_more);
