<?php
/**
 * Elgg widget add action
 */

$page_owner_guid = (int) get_input('page_owner_guid');
$handler = (string) get_input('handler');
$context = (string) get_input('context');
$show_access = (bool) get_input('show_access', true);
$column = (int) get_input('new_widget_column', 1);
$position = get_input('new_widget_position', 'top') === 'top' ? 0 : -1;
$default_widgets = (int) get_input('default_widgets', 0);

elgg_set_page_owner_guid($page_owner_guid);

$page_owner = elgg_get_page_owner_entity();
if (!$page_owner) {
	return elgg_error_response(elgg_echo('widgets:add:failure'));
}

if (!elgg_can_edit_widget_layout($context)) {
	// logged in user must be able to edit the layout to add a widget
	return elgg_error_response(elgg_echo('widgets:add:failure'));
}

$guid = elgg_create_widget($page_owner->guid, $handler, $context);
if ($guid === false) {
	return elgg_error_response(elgg_echo('widgets:add:failure'));
}

$widget = get_entity($guid);

// position the widget
$widget->move($column, $position);

$context_stack = [];

if ($default_widgets) {
	$context_stack[] = 'default_widgets';
}

$context_stack[] = 'widgets';
if ($context) {
	$context_stack[] = $context;
}

foreach ($context_stack as $ctx) {
	elgg_push_context($ctx);
}

$result = elgg_view_entity($widget, ['show_access' => $show_access]);

return elgg_ok_response($result);
