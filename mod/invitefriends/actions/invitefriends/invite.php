<?php

/**
 * Elgg invite friends action
 *
 * @package ElggInviteFriends
 */

elgg_make_sticky_form('invitefriends');

if (!elgg_get_config('allow_registration')) {
	return elgg_error_response(elgg_echo('invitefriends:registration_disabled'));
}

$site = elgg_get_site_entity();
// create the from address
$from = \Elgg\Mail\Address::getFormattedEmailAddress($site->getEmailAddress(), $site->getDisplayName());

$emails = get_input('emails');
$emailmessage = get_input('emailmessage');

$emails = trim($emails);
if (strlen($emails) > 0) {
	$emails = preg_split('/\\s+/', $emails, -1, PREG_SPLIT_NO_EMPTY);
}

if (!is_array($emails) || count($emails) == 0) {
	return elgg_error_response(elgg_echo('invitefriends:noemails'));
}

$current_user = elgg_get_logged_in_user_entity();

$error = false;
$bad_emails = [];
$already_members = [];
$sent_total = 0;
foreach ($emails as $email) {
	$email = trim($email);
	if (empty($email)) {
		continue;
	}

	// send out other email addresses
	if (!is_email_address($email)) {
		$error = true;
		$bad_emails[] = $email;
		continue;
	}

	if (get_user_by_email($email)) {
		$error = true;
		$already_members[] = $email;
		continue;
	}

	$link = elgg_get_registration_url([
		'friend_guid' => $current_user->guid,
		'invitecode' => generate_invite_code($current_user->username),
	]);
	
	$message = elgg_echo('invitefriends:email', [
		$site->name,
		$current_user->name,
		$emailmessage,
		$link,
	]);

	$subject = elgg_echo('invitefriends:subject', [$site->getDisplayName()]);

	elgg_send_email($from, $email, $subject, $message);
	$sent_total++;
}

if ($error) {
	register_error(elgg_echo('invitefriends:invitations_sent', [$sent_total]));

	if (count($bad_emails) > 0) {
		register_error(elgg_echo('invitefriends:email_error', [implode(', ', $bad_emails)]));
	}

	if (count($already_members) > 0) {
		register_error(elgg_echo('invitefriends:already_members', [implode(', ', $already_members)]));
	}
	
	return elgg_error_response();
}

elgg_clear_sticky_form('invitefriends');

return elgg_ok_response('', elgg_echo('invitefriends:success'));
