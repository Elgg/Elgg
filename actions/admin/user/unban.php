<?php
/**
 * Unbans a user.
 *
 * @package Elgg.Core
 * @subpackage Administration.User
 */

admin_gatekeeper();

$access_status = access_get_show_hidden_status();
access_show_hidden_entities(true);

$guid = get_input('guid');
$obj = get_entity($guid);

if (($obj instanceof ElggUser) && ($obj->canEdit())) {
	if ($obj->unban()) {
		system_message(elgg_echo('admin:user:unban:yes'));
	} else {
		register_error(elgg_echo('admin:user:unban:no'));
	}
} else {
	register_error(elgg_echo('admin:user:unban:no'));
}

access_show_hidden_entities($access_status);

forward($_SERVER['HTTP_REFERER']);
