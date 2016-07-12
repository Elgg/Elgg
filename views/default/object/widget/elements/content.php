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

echo elgg_view("widgets/$handler/content", $vars);
