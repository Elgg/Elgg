<?php

// don't show content for default widgets
if (elgg_in_context('default_widgets')) {
	return;
}

$widget = elgg_extract('entity', $vars);
if (!$widget instanceof ElggWidget) {
	return;
}

$handler = $widget->handler;

echo elgg_view("widgets/$handler/content", $vars);
