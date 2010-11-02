<?php

/**
 * Elgg invite friends action
 *
 * @package ElggInviteFriends
 */

$emails = get_input('emails');
$emailmessage = get_input('emailmessage');

$emails = trim($emails);
if (strlen($emails) > 0) {
	$emails = preg_split('/\\s+/', $emails, -1, PREG_SPLIT_NO_EMPTY);
}

if (!is_array($emails) || count($emails) == 0) {
	register_error(elgg_echo('invitefriends:noemails'));
	forward(REFERER);
}

$current_user = get_loggedin_user();

$error = FALSE;
$bad_emails = array();
$already_members = array();
$sent_total = 0;
foreach ($emails as $email) {

	$email = trim($email);
	if (empty($email)) {
		continue;
	}

	// send out other email addresses
	if (!is_email_address($email)) {
		$error = TRUE;
		$bad_emails[] = $email;
		continue;
	}

	if (get_user_by_email($email)) {
		$error = TRUE;
		$already_members[] = $email;
		continue;
	}

	$link = elgg_get_site_url() . 'pg/register?friend_guid=' . $current_user->guid . '&invitecode=' . generate_invite_code($current_user->username);
	$message = sprintf(elgg_echo('invitefriends:email'),
					$CONFIG->site->name,
					$current_user->name,
					$emailmessage,
					$link
	);

	$subject = sprintf(elgg_echo('invitefriends:subject'), $CONFIG->site->name);

	// create the from address
	$site = get_entity($CONFIG->site_guid);
	if (($site) && (isset($site->email))) {
		$from = $site->email;
	} else {
		$from = 'noreply@' . get_site_domain($CONFIG->site_guid);
	}

	elgg_send_email($from, $email, $subject, $message);
	$sent_total++;
}

if ($error) {
	register_error(sprintf(elgg_echo('invitefriends:invitations_sent'), $sent_total));

	if (count($bad_emails) > 0) {
		register_error(sprintf(elgg_echo('invitefriends:email_error'), implode(', ', $bad_emails)));
	}

	if (count($already_members) > 0) {
		register_error(sprintf(elgg_echo('invitefriends:already_members'), implode(', ', $already_members)));
	}
	
} else {
	system_message(elgg_echo('invitefriends:success'));
}

forward(REFERER);
