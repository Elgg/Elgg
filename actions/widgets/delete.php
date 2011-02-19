<?php
/**
 * Elgg widget delete action
 *
 * @package Elgg.Core
 * @subpackage Widgets.Management
 */

$widget_guid = get_input('widget_guid');
$owner_guid = get_input('owner_guid', elgg_get_logged_in_user_guid());

$widget = get_entity($widget_guid);
$owner = get_entity($owner_guid);

if ($widget && $owner->canEdit() && $widget->delete()) {
	forward(REFERER);
}

register_error(elgg_echo('widgets:remove:failure'));
forward(REFERER);
