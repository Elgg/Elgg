<?php

function projects_contact_prepare_form_vars($contact = null) {
	
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
		$userGuid = (int) elgg_get_logged_in_user_guid();
		
		$userName = get_user($userGuid)->name;

		$projectGuid = get_input('project', '');
		$projectName = get_entity($projectGuid)->name;
		
		$values = array(
			'fromGuid' =>  $userGuid,
			'fromName' =>  $userName,
			'toGuid' =>  $projectGuid,
			'toName' =>  $projectName,
			'subject' => get_input('subject', ''),
			'message' => get_input('message', ''),
			'readed'  => get_input('readed', ''),
			'time_created' => '', 		
			'container_guid' => $projectGuid,
			'entity' => $contact,
		);
	}
	
	return $values;

}
