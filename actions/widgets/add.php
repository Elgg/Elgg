<?php
/**
 * Elgg widget add action
 *
 * @package Elgg.Core
 * @subpackage Widgets.Management
 */

$user_guid = get_input('user');
$handler = get_input('handler');
$context = get_input('context');
$column = get_input('column', 1);

$guid = false;
if (!empty($user_guid)) {
	$user = get_entity($user_guid);
	if ($user && $user->canEdit()) {
		$guid = elgg_create_widget($user->getGUID(), $handler);
		if ($guid) {
			$widget = get_entity($guid);
			elgg_move_widget($widget, $context, $column, 0);

			// send widget html for insertion
			echo elgg_view_entity($widget);

			system_message(elgg_echo('widgets:add:success'));
			forward(REFERER);
		}
	}
}

register_error(elgg_echo('widgets:add:failure'));
forward(REFERER);