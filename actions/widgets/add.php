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
	if ($user = get_entity($user_guid)) {
		if ($user->canEdit()) {
			$guid = add_widget($user->getGUID(), $handler, $context, 0, $column);
		}
	}
}

if ($guid) {
	system_message(elgg_echo('widgets:add:success'));

	// send widget html for insertion
	$widget = get_entity($guid);
	echo elgg_view_entity($widget);
} else {
	register_error(elgg_echo('widgets:add:failure'));
}

forward(REFERER);