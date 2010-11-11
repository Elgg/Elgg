<?php
/**
 * Manage group invitation requests.
 *
 * @package ElggGroups
 */

require_once(dirname(dirname(dirname(__FILE__))) . "/engine/start.php");
gatekeeper();

set_page_owner(get_loggedin_userid());

$user = get_loggedin_user();

$title = elgg_echo('groups:invitations');

$area2 = elgg_view_title($title);

if ($user) {
	// @todo temporary workaround for exts #287.
	$invitations = groups_get_invited_groups($user->getGUID());

	$area2 .= elgg_view('groups/invitationrequests',array('invitations' => $invitations));
	elgg_set_ignore_access($ia);
} else {
	$area2 .= elgg_echo("groups:noaccess");
}

$body = elgg_view_layout('one_column_with_sidebar', array('content' => $area1));

echo elgg_view_page($title, $body);