<?php
/**
 * Elgg widget add action
 *
 * @package Elgg.Core
 * @subpackage Widgets.Management
 */

$user_guid = get_input('user_guid');
$handler = get_input('handler');
$context = get_input('context');
$column = get_input('column', 1);

if (!empty($user_guid)) {
	$user = get_entity($user_guid);
	if ($user && $user->canEdit()) {
		$guid = elgg_create_widget($user->getGUID(), $handler, $context);
		if ($guid) {
			$widget = get_entity($guid);

			// position the widget
			$widget->move($column, 0);

			// send widget html for insertion
			echo elgg_view_entity($widget);

			system_message(elgg_echo('widgets:add:success'));
			forward(REFERER);
		}
	}
}

register_error(elgg_echo('widgets:add:failure'));
forward(REFERER);