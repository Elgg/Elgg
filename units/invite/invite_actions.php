<?php

	// Kill all old invitations
		
		db_query("delete from invitations where added < " . (time() - (86400 * 7)));
		
	// Get site name
	
		$sitename = sitename;

	// If $_REQUEST['action'] is specified, see what we can do ...
	
		if (isset($_REQUEST['action'])) {
			
			switch($_REQUEST['action']) {
				
				// Add a new invite code
					case "invite_invite":		if (
														isset($_REQUEST['invite_name'])
														&& isset($_REQUEST['invite_email'])
														&& isset($_REQUEST['invite_text'])
														&& $_REQUEST['invite_name'] != ""
														&& $_REQUEST['invite_email'] != ""
													) {
														$email = addslashes(stripslashes($_REQUEST['invite_email']));
														$strippedname = stripslashes($_REQUEST['invite_name']);
														$name = addslashes($strippedname);
														$invitations = db_query("select count(ident) as num_invitations from invitations where email = '$email'");
														$invitations = $invitations[0]->num_invitations;
														if ($invitations == 0) {
															$accounts = db_query("select ident, username from users where email = '$email'");
															if (sizeof($accounts) ==0) {
																$code = substr(md5(time() . $_SESSION['username']),0,7);
																db_query("insert into invitations set name = '$name', email = '$email', code='$code', added = " . time() . ", owner = " . $_SESSION['userid']);
																if ($_REQUEST['invite_text'] != "") {
																	$invitetext = "They included the following message:\n\n----------\n" . stripslashes($_REQUEST['invite_text']) . "\n----------";
																}
																$url = url . "_invite/join.php?invitecode=" . $code;
																if (!logged_on) {
																	$greetingstext = "Thank you for registering with $sitename.";
																	$subjectline = "$sitename account verification";
																	$from_email = email;
																} else {
																	$greetingstext = $_SESSION['name'] . " has invited you to join $sitename, a learning landscape system.";
																	$subjectline = $_SESSION['name'] . " has invited you to join $sitename";
																	$from_email = $_SESSION['email'];
																}
																$emailmessage = <<< END
Dear {$strippedname},

{$greetingstext} {$invitetext}

To join, visit the following URL:

	{$url}

Your email address has not been passed onto any third parties, and will be removed from our system within seven days.

Regards,

The $sitename team.
END;
																$emailmessage = wordwrap($emailmessage);
																$messages[] = "Your invitation was sent to $strippedname at $email. It will be valid for seven days.";
																mail($email,$subjectline,$emailmessage,"From: $sitename <".$from_email.">");
															} else {
																$messages[] = "User " . $accounts[0]->username . " already has that email address. Invitation not sent.";
															}
														} else {
															$messages[] = "Someone with that email address has already been invited to the system. Invitation not sent.";
														}
													} else {
														$messages[] = "Invitation failed: you must specify both a name and an email address.";
													}
												break;
				// Join using an invitation
					case "invite_join":			if (
														isset($_REQUEST['join_name']) &&
														isset($_REQUEST['invitecode']) &&
														isset($_REQUEST['over13']) &&
														isset($_REQUEST['join_username']) &&
														isset($_REQUEST['join_password1']) &&
														isset($_REQUEST['join_password2'])
													) {
														$code = addslashes($_REQUEST['invitecode']);
														$details = db_query("select * from invitations where code = '$code'");
														if (sizeof($details) == 0) {
															$messages[] = "Error! Invalid invite code.";
														} else {
															if ($_REQUEST['join_password1'] != $_REQUEST['join_password2']
																|| strlen($_REQUEST['join_password1']) < 6
																|| strlen($_REQUEST['join_password1']) > 16) {
																$messages[] = "Error! Invalid password. Your passwords must match and be between 6 and 16 characters in length.";
															} else {
																if (!preg_match("/^[A-Za-z0-9]{3,12}$/",$_REQUEST['join_username'])) {
																	$messages[] = "Error! Your username must contain letters and numbers only, cannot be blank, and must be between 3 and 12 characters in length.";
																} else {
																	$username = strtolower(addslashes($_REQUEST['join_username']));
																	$usernametaken = db_query("select count(ident) as taken from users where username = '$username'");
																	$usernametaken = $usernametaken[0]->taken;
																	if ($usernametaken > 0) {
																		$messages[] = "The username '$username' is already taken by another user. You will need to pick a different one.";
																	} else {
																		$name = addslashes($_REQUEST['join_name']);
																		$displaypassword = $_REQUEST['join_password1'];
																		$password = addslashes(md5($_REQUEST['join_password1']));
																		$details = $details[0];
																		$email = $details->email;
																		db_query("insert into users set name = '$name',
																									password='$password',
																									username = '$username',
																									email = '$email'");
																		$ident = db_id();
																		$owner = (int) $details->owner;
																		if ($owner != -1) {
																			db_query("insert into friends set owner = $owner, friend = $ident");
																			db_query("insert into friends set owner = $ident, friend = $owner");
																		}
																		if ($owner != 1) {
																			db_query("insert into friends set owner = $ident, friend = 1");
																		}
																		$_SESSION['messages'][] = "Your account was created! You can now log in using the username and password you supplied. You have been sent an email containing these details for reference purposes.";
																		db_query("delete from invitations where code = '$code'");
																		mail($email, "Your $sitename account", wordwrap("
Thanks for joining $sitename!

For your records, your $sitename username and password are:

	Username: $username
	Password: $displaypassword
	
You can log in at any time by visiting " . url . " and entering these details into the login form.

We hope you enjoy using the system.

Regards,
The $sitename Team"), "From: $sitename <".email.">");
																		header("Location: " . url);
																		exit();
																	}
																}
															}
														}
													} else {
														$messages[] = "You must indicate that you are at least 13 years old to join.";
													}
												break;
				// Request a new password
					case "invite_password_request":		if (isset($_REQUEST['password_request_name'])) {
														$users = db_query("select ident, email from users where username = '".addslashes($_REQUEST['password_request_name'])."' and user_type = 'person'");
														if (sizeof($users) > 0) {
															$code = substr(md5(time() . $_REQUEST['password_request_name']),0,7);
															$ident = $users[0]->ident;
															db_query("insert into password_requests set code = '$code', owner = $ident");
															$url = url . "_invite/new_password.php?passwordcode=" . $code;
															mail(stripslashes($users[0]->email), "Verify your $sitename account password request", wordwrap("
A request has been received to generate your account at
$sitename a new password.

To confirm this request and receive a new password by email, please
click the following link:

	$url

Please let us know if you have any further problems.

Regards,
The $sitename Team"), "From: $sitename <".email.">");
															$messages[] = "Your verification email was sent. Please check your inbox.";
														} else {
															$messages[] = "No user with that username was found.";
														}
													}
												break;
				
			}
			
		}

?>