<?php
/**
 * Add users to a project
 *
 * @package Coopfunding
 * @subpackage Projects
 */
$user_guid = get_input('user_guid');
if (!is_array($user_guid)) {
	$user_guid = array($user_guid);
}
$project_guid = get_input('project_guid');
$project = get_entity($project_guid);

if (sizeof($user_guid)) {
	foreach ($user_guid as $u_id) {
		$user = get_user($u_id);

		if ($user && $project && $project->canEdit()) {
			if (!$project->isMember($user)) {
				if (projects_join_project($project, $user)) {

					// send welcome email to user
					notify_user($user->getGUID(), $project->owner_guid,
							elgg_echo('projects:welcome:subject', array($project->name)),
							elgg_echo('projects:welcome:body', array(
								$user->name,
								$project->name,
								$project->getURL())
							));

					system_message(elgg_echo('projects:addedtoproject'));
				} else {
					// huh
				}
			}
		}
	}
}

forward(REFERER);
