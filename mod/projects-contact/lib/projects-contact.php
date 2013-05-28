<?php

function projects_contact_prepare_form_vars($project_guid, $contact = null) {
	
	if ($contact) {

		$values = array(
			'from' => $contact->from,
			'subject' => $contact->subject,
			'message' => $contact->message,
			'readed'  => $contact->readed,
			'url' =>  projects_contact_url($contact),
			'time_created' => $contact->time_created, 		
			'guid' => $contact->getGUID(), 		
			'container_guid' => $contact->container_guid,			
			'entity' => $contact,
		);

	}else {
		$user = elgg_get_logged_in_user_entity();
		$project = get_entity($project_guid);
		
		$values = array(
			'from_entity' =>  $user,
			'to_entity' =>  $project,
			'subject' => get_input('subject', ''),
			'message' => get_input('message', ''),
			'readed'  => get_input('readed', ''),
			'time_created' => '', 		
			'container_guid' => $project->guid,
			'entity' => $contact,
		);
	}
	
	return $values;

}
