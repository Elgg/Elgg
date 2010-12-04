<?php
/**
 * Unbans a user.
 *
 * @package Elgg.Core
 * @subpackage Administration.User
 */

$access_status = access_get_show_hidden_status();
access_show_hidden_entities(true);

$guid = get_input('guid');
$user = get_entity($guid);

if (($user instanceof ElggUser) && ($user->canEdit())) {
	if ($user->unban()) {
		system_message(elgg_echo('admin:user:unban:yes'));
	} else {
		register_error(elgg_echo('admin:user:unban:no'));
	}
} else {
	register_error(elgg_echo('admin:user:unban:no'));
}

access_show_hidden_entities($access_status);

forward(REFERER);
