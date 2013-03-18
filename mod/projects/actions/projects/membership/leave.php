<?php
/**
 * Leave a project action.
 *
 * @package ElggGroups
 */

$user_guid = get_input('user_guid');
$project_guid = get_input('project_guid');

$user = NULL;
if (!$user_guid) {
	$user = elgg_get_logged_in_user_entity();
} else {
	$user = get_entity($user_guid);
}

$project = get_entity($project_guid);

elgg_set_page_owner_guid($project->guid);

if (($user instanceof ElggUser) && ($project instanceof ElggGroup)) {
	if ($project->getOwnerGUID() != elgg_get_logged_in_user_guid()) {
		if ($project->leave($user)) {
			system_message(elgg_echo("projects:left"));
		} else {
			register_error(elgg_echo("projects:cantleave"));
		}
	} else {
		register_error(elgg_echo("projects:cantleave"));
	}
} else {
	register_error(elgg_echo("projects:cantleave"));
}

forward(REFERER);
