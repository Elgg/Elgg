<?php
/**
 * Removes an admin notice.
 */

elgg_deprecated_notice("The action 'admin/delete_admin_notice' is deprecated. Use 'entity/delete'.", '3.1');

$guid = (int) get_input('guid');
$notice = get_entity($guid);

if (!$notice instanceof \ElggAdminNotice || !$notice->delete()) {
	return elgg_error_response(elgg_echo('admin:notices:could_not_delete'));
}

return elgg_ok_response();
