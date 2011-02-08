<?php
/**
 * Elgg widget move action
 *
 * @package Elgg.Core
 * @subpackage Widgets.Management
 */

$guid = get_input('guid');
$column = get_input('column', 1);
$position = get_input('position');

$user = elgg_get_logged_in_user_entity();

$widget = get_entity($guid);
if ($widget && $user->canEdit()) {
	$widget->move($column, $position);
	forward(REFERER);
}

register_error(elgg_echo('widgets:move:failure'));
forward(REFERER);