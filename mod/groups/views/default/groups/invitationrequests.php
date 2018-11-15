<?php
/**
 * A user's group invitations
 *
 * @uses $vars['invitations'] Optional. Array of ElggGroups
 */

if (isset($vars['invitations'])) {
	$invitations = $vars['invitations'];
	unset($vars['invitations']);
} else {
	$user = elgg_get_page_owner_entity();
	$vars['limit'] = get_input('limit', elgg_get_config('default_limit'));
	$vars['offset'] = get_input('offset', 0);
	$vars['count'] = groups_get_invited_groups($user->guid, false, ['count' => true]);
	$invitations = groups_get_invited_groups($user->guid, false, [
		'limit' => $vars['limit'],
		'offset' => $vars['offset'],
	]);
}

$vars['item_view'] = 'group/format/invitationrequest';
$vars['no_results'] = elgg_echo('groups:invitations:none');

echo elgg_view_entity_list($invitations, $vars);
