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

		// **** this should be replaced by a core function for sending emails to people who are not members
		$site = get_entity($CONFIG->site_guid);
		// If there's an email address, use it - but only if its not from a user.
		if (($site) && (isset($site->email))) {
			// Has the current site got a from email address?
			$from = $site->email;
		} else if (isset($from->url))  {
			// If we have a url then try and use that.
			$breakdown = parse_url($from->url);
			$from = 'noreply@' . $breakdown['host']; // Handle anything with a url
		} else {
			// If all else fails, use the domain of the site.
			$from = 'noreply@' . get_site_domain($CONFIG->site_guid);
		}
	
		if (is_callable('mb_internal_encoding')) {
			mb_internal_encoding('UTF-8');
		}
		$site = get_entity($CONFIG->site_guid);
		$sitename = $site->name;
		if (is_callable('mb_encode_mimeheader')) {
			$sitename = mb_encode_mimeheader($site->name,"UTF-8", "B");
		}
	
		$header_eol = "\r\n";
		if ((isset($CONFIG->broken_mta)) && ($CONFIG->broken_mta)) {
			// Allow non-RFC 2822 mail headers to support some broken MTAs
			$header_eol = "\n";
		}
	
		$from_email = "\"$sitename\" <$from>";
		if (strtolower(substr(PHP_OS, 0 , 3)) == 'win') {
			// Windows is somewhat broken, so we use a different format from header
			$from_email = "$from";
		}
	
		$headers = "From: $from_email{$header_eol}"
			. "Content-Type: text/plain; charset=UTF-8; format=flowed{$header_eol}"
			. "MIME-Version: 1.0{$header_eol}"
			. "Content-Transfer-Encoding: 8bit{$header_eol}";
	
		$subject = sprintf(elgg_echo('invitefriends:subject'), $CONFIG->site->name);
		if (is_callable('mb_encode_mimeheader')) {
			$subject = mb_encode_mimeheader($subject,"UTF-8", "B");
		}
	
		// Format message
		$message = html_entity_decode($message, ENT_COMPAT, 'UTF-8'); // Decode any html entities
		$message = strip_tags($message); // Strip tags from message
		$message = preg_replace("/(\r\n|\r)/", "\n", $message); // Convert to unix line endings in body
		$message = preg_replace("/^From/", ">From", $message); // Change lines starting with From to >From

		mail($email, $subject, wordwrap($message), $headers);							
	}

	if ($error) {
		register_error(sprintf(elgg_echo('invitefriends:email_error'), implode(', ', $bad_emails)));
	} else {	
		system_message(elgg_echo('invitefriends:success'));
	}

	forward($_SERVER['HTTP_REFERER']);

?>
