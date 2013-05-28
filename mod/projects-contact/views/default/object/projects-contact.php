<?php

$typeof = elgg_extract('typeof', $vars, FALSE);
$contact = elgg_extract('entity', $vars, FALSE);

if (!$contact) {
	return TRUE;
}

$fromuser = get_user($contact->fromGuid);
$date = elgg_view_friendly_time($contact->time_created); 
$subtitle = "$fromuserName $date";

$timestamp = elgg_view_friendly_time($contact->time_created);

if ($fromuser) {
	$icon = elgg_view_entity_icon($fromuser, 'tiny');
	$user_link = $icon . elgg_view('output/url', array(
		'href' => "messages/compose?send_to=" . $fromuser->guid,
		'text' => $fromuser->name,
		'is_trusted' => true,
	));
} else {
	$icon = '';
	$user_link = elgg_echo('projects_contact:deleted_sender');
}

///////////////////////////////////////////
$subject_info = '';
$delete_link = elgg_view("output/confirmlink", array(
	'href' => "action/projects-contact/delete?guid=" . $contact->guid,
	'text' => "<span class=\"elgg-icon elgg-icon-delete float-alt\"></span>",
	'confirm' => elgg_echo('deleteconfirm'),
	'encode_text' => false,
));

if ($typeof!='single') {
	$subject_info = "<input type='checkbox' name=\"message_id[]\" value=\"{$contact->guid}\" />&nbsp;";	
	$subject_info .= elgg_view('output/url', array(
		'href' => "projects_contact/view/$contact->guid",
		'text' => $contact->subject,
		'is_trusted' => true,
	));
} else{
	$subject_info .= elgg_echo('projects_contact:subject') . " <label>". $contact->subject . "</label>";
}	


$metadata = elgg_view_menu('entity', array(
	'entity' => $vars['entity'],
	'handler' => 'projects-contact',
	'sort_by' => 'priority',
	'class' => 'elgg-menu-hz',
));

$params = array(
	'entity' => $contact,
	'title' => $subject,
	'metadata' => $metadata,
	'subtitle' => $subtitle,
);

$toproject = get_entity($contact->toGuid);

$subject = elgg_get_excerpt($contact->subject);	
$message = elgg_get_excerpt($contact->message);
$readed = elgg_get_excerpt($contact->readed);

$params = $params + $vars;
$summary = elgg_view('object/elements/summary', $params);

$body = <<<HTML
<div class="projects_contact-owner">$user_link</div>
<div class="projects_contact-subject">$subject_info</div>
<div class="projects_contact-timestamp">$timestamp</div>
<div class="projects_contact-delete">$delete_link</div>
HTML;

if ($typeof=='single') {		
	$file_icon = elgg_view_entity_icon($contact, 'single');

	echo elgg_view_image_block($file_icon, $body);
	echo elgg_view('output/longtext', array('value' => $message));
			
} else {
	if ($readed) {
		$icotype = 'full';
	}else{
		$icotype = 'fullUnReaded';
	}
	$file_icon = elgg_view_entity_icon($contact, $icotype);

	echo elgg_view_image_block($file_icon, $body);
}
