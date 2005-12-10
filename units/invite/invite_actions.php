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
																	$invitetext = gettext("They included the following message:") . "\n\n----------\n" . stripslashes($_REQUEST['invite_text']) . "\n----------";
																}
																$url = url . "_invite/join.php?invitecode=" . $code;
																if (!logged_on) {
																	$greetingstext = sprintf(gettext("Thank you for registering with %s."),$sitename);
																	$subjectline = sprintf(gettext("%s account verification"),$sitename);
																	$from_email = email;
																} else {
																	$greetingstext = $_SESSION['name'] . " " . gettext("has invited you to join") ." $sitename, ". gettext("a learning landscape system.") ."";
																	$subjectline = $_SESSION['name'] . " " . gettext("has invited you to join") ." $sitename";
																	$from_email = $_SESSION['email'];
																}
																$emailmessage = sprintf(gettext("Dear %s,\n\n%s %s\n\nTo join, visit the following URL:\n\n\t%s\n\nYour email address has not been passed onto any third parties, and will be removed from our system within seven days.\n\nRegards,\n\nThe %s team."),$strippedname,$greetingstext,$invitetext,$url, $sitename);
																$emailmessage = wordwrap($emailmessage);
																$messages[] = sprintf(gettext("Your invitation was sent to %s at %s. It will be valid for seven days."),$strippedname,$email);
																mail($email,$subjectline,$emailmessage,"From: $sitename <".$from_email.">");
															} else {
																$messages[] = sprintf(gettext("User %s already has that email address. Invitation not sent."),$accounts[0]->username);
															}
														} else {
															$messages[] = gettext("Someone with that email address has already been invited to the system. Invitation not sent.");
														}
													} else {
														$messages[] = gettext("Invitation failed: you must specify both a name and an email address.");
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
															$messages[] = gettext("Error! Invalid invite code.");
														} else {
															if ($_REQUEST['join_password1'] != $_REQUEST['join_password2']
																|| strlen($_REQUEST['join_password1']) < 6
																|| strlen($_REQUEST['join_password1']) > 16) {
																$messages[] = gettext("Error! Invalid password. Your passwords must match and be between 6 and 16 characters in length.");
															} else {
																if (!preg_match("/^[A-Za-z0-9]{3,12}$/",$_REQUEST['join_username'])) {
																	$messages[] = gettext("Error! Your username must contain letters and numbers only, cannot be blank, and must be between 3 and 12 characters in length.");
																} else {
																	$username = strtolower(addslashes($_REQUEST['join_username']));
																	$usernametaken = db_query("select count(ident) as taken from users where username = '$username'");
																	$usernametaken = $usernametaken[0]->taken;
																	if ($usernametaken > 0) {
																		$messages[] = gettext("The username '$username' is already taken by another user. You will need to pick a different one.");
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
																		$_SESSION['messages'][] = gettext("Your account was created! You can now log in using the username and password you supplied. You have been sent an email containing these details for reference purposes.");
																		db_query("delete from invitations where code = '$code'");
																		mail($email, sprintf(gettext("Your %s account"),$sitename), wordwrap(sprintf(gettext("Thanks for joining %s!\n\nFor your records, your %s username and password are:\n\n\tUsername: %s\n\tPassword: %s\n\nYou can log in at any time by visiting %s and entering these details into the login form.\n\nWe hope you enjoy using the system.\n\nRegards,\n\nThe %s Team"),$sitename,$sitename,$username,$displaypassword,url,$sitename)), "From: $sitename <".email.">");
																		header("Location: " . url);
																		exit();
																	}
																}
															}
														}
													} else {
														$messages[] = gettext("You must indicate that you are at least 13 years old to join.");
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
															mail(stripslashes($users[0]->email), sprintf(gettext("Verify your %s account password request"),$sitename), wordwrap(sprintf(gettext("A request has been received to generate your account at %s a new password.\n\nTo confirm this request and receive a new password by email, please click the following link:\n\n\t%s\n\nPlease let us know if you have any further problems.\n\nRegards,\n\nThe %s Team"),$sitename,$url,$sitename)), "From: $sitename <".email.">");
															$messages[] = gettext("Your verification email was sent. Please check your inbox.");
														} else {
															$messages[] = gettext("No user with that username was found.");
														}
													}
												break;
				
			}
			
		}

?>