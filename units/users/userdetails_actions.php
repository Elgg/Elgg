<?php

	// Userdetails actions
	
		if (logged_on && isset($_REQUEST['action'])) {
			
			switch($_REQUEST['action']) {
				
				// Update user details
					case "userdetails:update":
													if (isset($_REQUEST['name']) && isset($_REQUEST['email'])) {
														$userdetails_ok = "yes";
														$name = addslashes($_REQUEST['name']);
														if (strlen($name) > 64) {
															$messages[] = "Your suggested name was too long. Please try something shorter.";
															$userdetails_ok = "no";
														}
														$email = $_REQUEST['email'];
														if (!@preg_match("/^[a-zA-Z][\w\.-]*[a-zA-Z0-9]@[a-zA-Z0-9][\w\.-]*[a-zA-Z0-9]\.[a-zA-Z][a-zA-Z\.]*[a-zA-Z]$/i",$email)) {
															$messages[] = "Your suggested email address $email doesn't appear to be valid.";
															$userdetails_ok = "no";
														} else {
															$email = addslashes($email);
														}
														
														if ($userdetails_ok == "yes") {
															$messages[] = "Your name and email address were updated.";
															$id = (int) $_SESSION['userid'];
															db_query("update users set name = '$name', email = '$email' where ident = $id");
															$_SESSION['name'] = stripslashes($name);
															$_SESSION['email'] = stripslashes($email);
														} else {
															$messages[] = "Your user details were not changed.";
														}
														
													}
													
													if (isset($_REQUEST['password1']) && isset($_REQUEST['password2']) && $_REQUEST['password1'] != "") {
														$password1 = $_REQUEST['password1'];
														$password2 = $_REQUEST['password2'];
														if (($password1 == $password2)) {
															if (strlen($password1) < 4 || strlen($password1) > 32) {
																$messages[] = "Password not changed: Your password is either too short or too long. It must be between 4 and 32 characters in length.";
															} else if (!preg_match("/^[a-zA-Z0-9]*$/i",$password1)) {
																$messages[] = "Password not changed: Your password can only consist of letters or numbers.";
															} else {
																$messages[] = "Your password was updated.";
																db_query("update users set password = '$password1' where ident = " . $_SESSION['userid']);
															}
														} else {
															$messages[] = "Password not changed: The password and its verification string did not match.";
														}
													}
													break;
				
			}
			
		}
?>