<?php	
/**
 * Feature a project
 *
 * @package CoopFunding
 * @subpackage Projects
 */

$project_guid = get_input('project_guid');
$action = get_input('action_type');

$project = get_entity($project_guid);

if (!elgg_instanceof($project, 'project')) {
	register_error(elgg_echo('projects:featured_error'));
	forward(REFERER);
}

//get the action, is it to feature or unfeature
if ($action == "feature") {
	$project->featured_project = "yes";
	system_message(elgg_echo('projects:featuredon', array($project->name)));
} else {
	$project->featured_project = "no";
	system_message(elgg_echo('projects:unfeatured', array($project->name)));
}

forward(REFERER);
