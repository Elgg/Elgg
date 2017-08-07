<?php
/**
 * Elgg widget delete action
 */

$widget_guid = (int) get_input('widget_guid');

$widget = get_entity($widget_guid);
if (!($widget instanceof \ElggWidget)) {
	return elgg_error_response(elgg_echo('widgets:remove:failure'));
}

elgg_set_page_owner_guid($widget->getContainerGUID());

if (!elgg_can_edit_widget_layout($widget->context)) {
	return elgg_error_response(elgg_echo('widgets:remove:failure'));
}

if (!$widget->delete()) {
	return elgg_error_response(elgg_echo('widgets:remove:failure'));
}

return elgg_ok_response();
