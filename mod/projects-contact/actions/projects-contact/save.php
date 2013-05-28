
<?php

$fromGuid = get_input('fromGuid');
$toGuid = get_input('toGuid');

$subject = get_input('subject');
$message = get_input('message');

$contact = new ElggObject;
$contact->subtype = 'projects-contact';
$contact->fromGuid = $fromGuid;
$contact->toGuid = $toGuid;
$contact->subject = $subject;
$contact->message = $message;
$contact->readed = false;

$contact->container_guid = $toGuid;

if ($contact->save()) {

	system_message(elgg_echo('projects_contact:save:success'));
	
	add_to_river(array(
		'view' => 'river/object/projects_contacts/create',
		'action_type' => 'create',
		'subject_guid' => elgg_get_logged_in_user_guid(),
		'object_guid' => $contact->guid,
	));
	
	forward($contact->getContainerEntity()->getURL());

} else {

	register_error(elgg_echo('projects_contacts:save:failed'));
	forward("projects_contacts");

}
