<?php

$contactGuid = get_input('guid');
$contact = get_entity($contactGuid);
$projectGuid = $contact->toGuid;
$project = get_entity($projectGuid); 

if ($contact->delete()){
	system_message(elgg_echo("projects_contact:delete:success"));
}else{
	register_error(elgg_echo("projects_contact:delete:failed"));
}

forward("projects_contact/owner/$projectGuid/{$project->name}");


