<?php
/**
 * Removes an admin notice.
 */

$guid = get_input('guid');
$notice = get_entity($guid);

if (!(elgg_instanceof($notice, 'object', 'admin_notice') && $notice->delete())) {
	register_error(elgg_echo("admin:notices:could_not_delete"));
}

forward(REFERER);