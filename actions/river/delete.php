<?php
/**
 * River item delete action
 *
 * @package Elgg
 * @subpackage Core
 */

$id = get_input('id', false);

if ($id !== false && elgg_is_admin_logged_in()) {
	if (elgg_delete_river(array('id' => $id))) {
		system_message(elgg_echo('river:delete:success'));
	} else {
		register_error(elgg_echo('river:delete:fail'));
	}
} else {
	register_error(elgg_echo('river:delete:fail'));
}

forward(REFERER);
