<?php
/**
 * Remove a user from a project
 *
 * @package ElggGroups
 */

$user_guid = get_input('user_guid');
$project_guid = get_input('project_guid');

$user = get_entity($user_guid);
$project = get_entity($project_guid);

elgg_set_page_owner_guid($project->guid);

if (($user instanceof ElggUser) && ($project instanceof ElggGroup) && $project->canEdit()) {
	// Don't allow removing project owner
	if ($project->getOwnerGUID() != $user->getGUID()) {
		if ($project->leave($user)) {
			system_message(elgg_echo("projects:removed", array($user->name)));
		} else {
			register_error(elgg_echo("projects:cantremove"));
		}
	} else {
		register_error(elgg_echo("projects:cantremove"));
	}
} else {
	register_error(elgg_echo("projects:cantremove"));
}

forward(REFERER);
