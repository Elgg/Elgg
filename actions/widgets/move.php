<?php
/**
 * Elgg widget move action
 */

$column = (int) get_input('column', 1);
$position = (int) get_input('position');
$widget_guid = (int) get_input('widget_guid');

$widget = get_entity($widget_guid);
if (!($widget instanceof \ElggWidget)) {
	return elgg_error_response(elgg_echo('widgets:move:failure'));
}

elgg_set_page_owner_guid($widget->getContainerGUID());

if (!elgg_can_edit_widget_layout($widget->context)) {
	return elgg_error_response(elgg_echo('widgets:move:failure'));
}

$widget->move($column, $position);
return elgg_ok_response();
