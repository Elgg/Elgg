<?php
/**
 * A persistent admin notice to be displayed on all admin pages until cleared.
 */

if (!isset($vars['entity'])
		|| !($vars['entity'] instanceof ElggObject)
		|| ($vars['entity']->getSubtype() !== 'admin_notice')) {
	return;
}

$notice = $vars['entity'];
$message = $notice->description;

global $CONFIG;

$delete_url = "{$CONFIG->wwwroot}action/admin/delete_admin_notice?guid=$notice->guid";
$delete_url = elgg_add_action_tokens_to_url($delete_url);
$delete = "<a href='$delete_url' class='elgg-admin-notice'>x</a>";

echo "<p>$delete$message</p>";
