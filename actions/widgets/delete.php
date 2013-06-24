<?php
/**
 * Elgg widget delete action
 *
 * @package Elgg.Core
 * @subpackage Widgets.Management
 */

$widget = get_entity(get_input('widget_guid'));
if ($widget) {
	$layout_owner_guid = $widget->getContainerGUID();
	elgg_set_page_owner_guid($layout_owner_guid);
	if (elgg_can_edit_widget_layout($widget->context) && $widget->delete()) {
		forward(REFERER);
	}
}

register_error(elgg_echo('widgets:remove:failure'));
forward(REFERER);
