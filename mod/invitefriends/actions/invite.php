<?php

	/**
	 * Elgg invite action
	 * 
	 * @package ElggFile
	 * @author Curverider Ltd
	 * @copyright Curverider Ltd 2008-2010
	 * @license http://www.gnu.org/licenses/old-licenses/gpl-2.0.html GNU Public License version 2
	 * @link http://elgg.org/
	 */

	global $CONFIG;
	
	gatekeeper();
	
	$emails = get_input('emails');
	$emailmessage = get_input('emailmessage');

	$emails = trim($emails);
	if (strlen($emails) > 0) {
		$emails = preg_split('/\\s+/', $emails, -1, PREG_SPLIT_NO_EMPTY);
	}
	
	if (!is_array($emails) || count($emails) == 0) {
		register_error(elgg_echo('invitefriends:failure'));
		forward($_SERVER['HTTP_REFERER']);
	}
	
	$error = FALSE;
	$bad_emails = array();
	foreach($emails as $email) {
				
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
		
		$link = $CONFIG->wwwroot . 'pg/register?friend_guid=' . $_SESSION['guid'] . '&invitecode=' . generate_invite_code($_SESSION['user']->username);
		$message = sprintf(elgg_echo('invitefriends:email'),
							$CONFIG->site->name,
							$_SESSION['user']->name,
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
	}

	if ($error) {
		register_error(sprintf(elgg_echo('invitefriends:email_error'), implode(', ', $bad_emails)));
	} else {	
		system_message(elgg_echo('invitefriends:success'));
	}

	forward($_SERVER['HTTP_REFERER']);
