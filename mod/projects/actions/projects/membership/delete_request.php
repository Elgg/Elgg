<?php
/**
 * Delete a request to join a closed project.
 *
 * @package ElggGroups
 */

$user_guid = get_input('user_guid', elgg_get_logged_in_user_guid());
$project_guid = get_input('project_guid');

$user = get_entity($user_guid);
$project = get_entity($project_guid);

// If join request made
if (check_entity_relationship($user->guid, 'membership_request', $project->guid)) {
	remove_entity_relationship($user->guid, 'membership_request', $project->guid);
	system_message(elgg_echo("projects:joinrequestkilled"));
}

forward(REFERER);
