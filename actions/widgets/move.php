<?php
/**
 * Elgg widget move action
 *
 * @package Elgg.Core
 * @subpackage Widgets.Management
 */

$column = get_input('column', 1);
$position = get_input('position');
$widget = get_entity(get_input('widget_guid'));
if ($widget) {
	$layout_owner_guid = $widget->getContainerGUID();
	elgg_set_page_owner_guid($layout_owner_guid);
	if (elgg_can_edit_widget_layout($widget->context)) {
		$widget->move($column, $position);
		forward(REFERER);
	}
}

register_error(elgg_echo('widgets:move:failure'));
forward(REFERER);