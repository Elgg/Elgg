<?php
/**
 * Invite users to join a project
 *
 * @package Coopfunding
 * @subpackage Projects
 */

$logged_in_user = elgg_get_logged_in_user_entity();

$user_guid = get_input('user_guid');
if (!is_array($user_guid)) {
	$user_guid = array($user_guid);
}
$project_guid = get_input('project_guid');

if (sizeof($user_guid)) {
	foreach ($user_guid as $u_id) {
		$user = get_entity($u_id);
		$project = get_entity($project_guid);

		if ($user && $project && ($project instanceof ElggGroup) && $project->canEdit()) {

			if (!check_entity_relationship($project->guid, 'invited', $user->guid)) {

				// Create relationship
				add_entity_relationship($project->guid, 'invited', $user->guid);

				// Send email
				$url = elgg_normalize_url("projects/invitations/$user->username");
				$result = notify_user($user->getGUID(), $project->owner_guid,
						elgg_echo('projects:invite:subject', array($user->name, $project->name)),
						elgg_echo('projects:invite:body', array(
							$user->name,
							$logged_in_user->name,
							$project->name,
							$url,
						)),
						NULL);
				if ($result) {
					system_message(elgg_echo("projects:userinvited"));
				} else {
					register_error(elgg_echo("projects:usernotinvited"));
				}
			} else {
				register_error(elgg_echo("projects:useralreadyinvited"));
			}
		}
	}
}

forward(REFERER);
