<?php

// don't show content for default widgets
if (elgg_in_context('default_widgets')) {
	return;
} 

$widget = elgg_extract('entity', $vars);
if (!elgg_instanceof($widget, 'object', 'widget')) {
	return;
}

$handler = $widget->handler;

if (elgg_view_exists("widgets/$handler/content")) {
	echo elgg_view("widgets/$handler/content", $vars);
} else {
	echo elgg_view_deprecated("widgets/$handler/view", $vars, "Widgets use content as the display view", '1.8');
}