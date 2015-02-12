<?php
/**
 * A user's group invitations
 *
 * @uses $vars['invitations'] Array of ElggGroups
 */

$invitations = elgg_extract('invitations', $vars, array());
unset($vars['invitations']);

$vars['items'] = $invitations;
$vars['entity_view'] = 'group/format/invitationrequest';
$vars['no_results'] = elgg_echo('groups:invitations:none');

echo elgg_view('page/components/list', $vars);