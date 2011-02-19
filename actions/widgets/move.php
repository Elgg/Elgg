<?php
/**
 * Elgg widget move action
 *
 * @package Elgg.Core
 * @subpackage Widgets.Management
 */

$widget_guid = get_input('widget_guid');
$column = get_input('column', 1);
$position = get_input('position');
$owner_guid = get_input('owner_guid', elgg_get_logged_in_user_guid());

$widget = get_entity($widget_guid);
$owner = get_entity($owner_guid);


if ($widget && $owner->canEdit()) {
	$widget->move($column, $position);
	forward(REFERER);
}

register_error(elgg_echo('widgets:move:failure'));
forward(REFERER);