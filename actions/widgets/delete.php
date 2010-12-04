<?php
/**
 * Elgg widget delete action
 *
 * @package Elgg.Core
 * @subpackage Widgets.Management
 */

$guid = get_input('guid');

$user = get_loggedin_user();

$widget = get_entity($guid);
if ($widget && $user->canEdit() && $widget->delete()) {
	forward(REFERER);
}

register_error(elgg_echo('widgets:remove:failure'));
forward(REFERER);
