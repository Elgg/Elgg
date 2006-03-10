<?php

	// Userdetails actions
	
	global $page_owner;

	$id = (int) $_REQUEST['id'];
	
	if (logged_on && isset($_REQUEST['action']) && run("permissions:check", array("userdetails:change",((int) $_REQUEST['id'])))) {
		
		switch($_REQUEST['action']) {
			
			// Update user details
			case "userdetails:update":
				if (isset($_REQUEST['name'])) {
					$userdetails_ok = "yes";
					$name = trim($_REQUEST['name']);
					if (strlen($name) > 64) {
						$messages[] = gettext("Your suggested name was too long. Please try something shorter.");
						$userdetails_ok = "no";
					}
					
					$usertype = run("users:type:get",$page_owner);
					
					if ($usertype == 'person' && isset($_REQUEST['email'])) {
						$email = trim(stripslashes($_REQUEST['email']));
						if (!@preg_match("/^[a-zA-Z][\w\.-]*[a-zA-Z0-9]@[a-zA-Z0-9][\w\.-]*[a-zA-Z0-9]\.[a-zA-Z][a-zA-Z\.]*[a-zA-Z]$/i",$email)) {
							$messages[] = gettext("Your suggested email address $email doesn't appear to be valid.");

							$userdetails_ok = "no";
						} else {
							$email = addslashes($email);
							db_query("update users set email = '$email' where ident = $id");
							if ($_SESSION['userid'] == $page_owner) {
								$_SESSION['email'] = stripslashes($email);
							}
							$messages[] = gettext("Email address updated.");
						}
					}
					
					if (isset($_REQUEST['moderation']) && ($_REQUEST['moderation'] == "yes"
							|| $_REQUEST['moderation'] == "no"
							|| $_REQUEST['moderation'] == "priv")) {
								db_query("update users set moderation = '" . $_REQUEST['moderation'] . "' where ident = $id");
								$messages[] = gettext("Your moderation preferences have been changed.");
					}
					
					if ($usertype == 'person' && isset($_REQUEST['publiccomments'])) {
						if ($_REQUEST['publiccomments'] == "yes") {
							run("users:flags:set",array("publiccomments",$id,"1"));
							$messages[] = gettext("Public comments and discussion set to 'on'.");
						} else {
							run("users:flags:unset",array("publiccomments",$id));
							$messages[] = gettext("Public comments and discussion set to 'off'.");
						}
					}
					
					if ($usertype == 'person' && isset($_REQUEST['receiveemails'])) {
						if ($_REQUEST['receiveemails'] == "yes") {
							run("users:flags:set",array("emailreplies",$id,"1"));
							$messages[] = gettext("Email comments and discussion set to 'on'.");
						} else {
							run("users:flags:unset",array("emailreplies",$id));
							$messages[] = gettext("Email comments and discussion set to 'off'.");
						}
					}
					
					if ($userdetails_ok == "yes") {
						$messages[] = "Name updated.";
						db_query("update users set name = '$name' where ident = $id");
						if ($_SESSION['userid'] == $page_owner) {
							$_SESSION['name'] = stripslashes($name);
						}
					} else {
						$messages[] = gettext("Details were not changed.");
					}
					
				}
				
				if (isset($_REQUEST['password1']) && isset($_REQUEST['password2']) && $_REQUEST['password1'] != "") {
					$password1 = $_REQUEST['password1'];
					$password2 = $_REQUEST['password2'];
					if (($password1 == $password2)) {
						if (strlen($password1) < 4 || strlen($password1) > 32) {
							$messages[] = gettext("Password not changed: Your password is either too short or too long. It must be between 4 and 32 characters in length.");
						} else if (!preg_match("/^[a-zA-Z0-9]*$/i",$password1)) {
							$messages[] = gettext("Password not changed: Your password can only consist of letters or numbers.");
						} else {
							$messages[] = gettext("Your password was updated.");
							db_query("update users set password = '".md5($password1)."' where ident = " . $page_owner);
						}
					} else {
						$messages[] = gettext("Password not changed: The password and its verification string did not match.");
					}
				}
				break;
			
		}
		
	}
?>