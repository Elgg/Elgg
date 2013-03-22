<?php
/**
 * Delete an invitation to join a project.
 *
 * @package ElggGroups
 */

$user_guid = get_input('user_guid', elgg_get_logged_in_user_guid());
$project_guid = get_input('project_guid');

$user = get_entity($user_guid);

// invisible projects require overriding access to delete invite
$old_access = elgg_set_ignore_access(true);
$project = get_entity($project_guid);
elgg_set_ignore_access($old_access);

// If join request made
if (check_entity_relationship($project->guid, 'invited', $user->guid)) {
	remove_entity_relationship($project->guid, 'invited', $user->guid);
	system_message(elgg_echo("projects:invitekilled"));
}

forward(REFERER);
