<?php
/**
 * Elgg invite friends action
 */

use Elgg\Email;

elgg_make_sticky_form('invitefriends');

if (!elgg_get_config('allow_registration')) {
	return elgg_error_response(elgg_echo('invitefriends:registration_disabled'));
}

$site = elgg_get_site_entity();
// create the from address
$from = \Elgg\Email\Address::getFormattedEmailAddress($site->getEmailAddress(), $site->getDisplayName());

$emails = get_input('emails');
$emailmessage = get_input('emailmessage');

$emails = trim($emails);
if (elgg_strlen($emails) > 0) {
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
foreach ($emails as $email_address) {
	$email_address = trim($email_address);
	if (empty($email_address)) {
		continue;
	}

	// send out other email addresses
	if (!is_email_address($email_address)) {
		$error = true;
		$bad_emails[] = $email_address;
		continue;
	}

	if (get_user_by_email($email_address)) {
		$error = true;
		$already_members[] = $email_address;
		continue;
	}

	$link = elgg_get_registration_url([
		'friend_guid' => $current_user->guid,
		'invitecode' => generate_invite_code($current_user->username),
	]);
	
	$email = Email::factory([
		'to' => $email_address,
		'from' => $from,
		'subject' => elgg_echo('invitefriends:subject', [$site->getDisplayName()]),
		'body' => elgg_echo('invitefriends:email', [
			$site->getDisplayName(),
			$current_user->getDisplayName(),
			$emailmessage,
			$link,
		]),
	]);
	
	elgg_send_email($email);
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
