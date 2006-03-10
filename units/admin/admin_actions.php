<?php

global $messages;

if (isset($_REQUEST['action'])) {
	
	if (logged_on) {
		
		switch($_REQUEST['action']) {
			
			case "content:flag":
				if (isset($_REQUEST['address'])) {
					$address = trim($_REQUEST['address']);
					db_query("insert into content_flags set url = '$address'");
					$messages[] = "You have flagged this page as being obscene or inappropriate. An administrator will investigate this shortly.";
				}
				break;
			
		}
		
		
		if (run("users:flags:get", array("admin", $_SESSION['userid']))) {
			
			switch($_REQUEST['action']) {
				
				case "content:flags:delete":
					if (isset($_REQUEST['remove'])) {
						$remove = $_REQUEST['remove'];
						if (sizeof($remove) > 0) {
							foreach ($remove as $remove_url) {
								$remove_url = trim($remove_url);
								db_query("delete from content_flags where url = '$remove_url'");
							}
						}
						$messages[] = "The selected content flags were deleted.";
					}
					break;
					
					
					
				// Manage users
				case "userdetails:update":
					if (isset($_REQUEST['id'])) {
						$id = (int) $_REQUEST['id'];
						if (isset($_REQUEST['change_username'])) {
							if (!preg_match("/^[A-Za-z0-9]{3,12}$/",$_REQUEST['change_username'])) {
								$messages[] = gettext("Error! The new username must contain letters and numbers only, cannot be blank, and must be between 3 and 12 characters in length.");
							} else {
								db_query("update users set username = '".$_REQUEST['change_username']."' where ident = $id");
								$messages[] = sprintf(gettext("Username was changed to %s."),$_REQUEST['change_username']);
							}
						}
						if (isset($_REQUEST['change_filequota'])) {
							$file_quota = (int) $_REQUEST['change_filequota'];
							db_query("update users set file_quota = $file_quota where ident = $id");
							$messages[] = sprintf(gettext("File quota was changed to %d."),$file_quota);
						}
						if (isset($_REQUEST['change_iconquota'])) {
							$icon_quota = (int) $_REQUEST['change_iconquota'];
							db_query("update users set icon_quota = $icon_quota where ident = $id");
							$messages[] = sprintf(gettext("Icon quota was changed to %d."),$icon_quota);
						}
						// Alter flags for users, including granting and denying admin access,
						// banning users etc
						if (isset($_REQUEST['flag']) && sizeof($_REQUEST['flag'] > 0)) {
							foreach($_REQUEST['flag'] as $flag => $value) {
								$flag = trim(stripslashes($flag)); // users:flags:set escapes its params
								$value = trim(stripslashes($value)); // users:flags:set escapes its params
								run("users:flags:set",array($flag,$id,$value));
								$messages[] = sprintf(gettext("User flag '%s' set to '%s'"), $flag, $value);
							}
						}
					}
					break;
					
					
					
				// Antispam save
				case "admin:antispam:save":
					if (isset($_REQUEST['antispam'])) {
						$antispam = trim($_REQUEST['antispam']);
						db_query("delete from datalists where name = 'antispam'");
						db_query("insert into datalists set name = 'antispam', value='$antispam'");
						$messages[] = gettext("Spam list updated.");
					}
					break;
					
					
					
				// Add bulk users
				case "admin:users:add":
					if (isset($_REQUEST['new_username']) &&
						isset($_REQUEST['new_name']) &&
						isset($_REQUEST['new_email']) &&
						sizeof($_REQUEST['new_username']) == 12 &&
						sizeof($_REQUEST['new_name']) == 12 &&
						sizeof($_REQUEST['new_email']) == 12
					) {
							
						$new_username = $_REQUEST['new_username'];
						$new_name = $_REQUEST['new_name'];
						$new_email = $_REQUEST['new_email'];
						
						global $admin_add_users;
						$admin_add_users = array();
						
						for ($i = 0; $i < 12; $i++) {
							
							$ok = false;
							
							if (trim($new_username[$i]) != "" &&
								trim($new_name[$i]) != "" &&
								trim($new_email[$i]) != "") {
									
									if (!preg_match("/^[A-Za-z0-9]{3,12}$/",$new_username[$i])) {
										$messages[] = sprintf(gettext("New username %d (%s) was invalid; usernames must contain letters and numbers only, cannot be blank, and must be between 3 and 12 characters in length."),($i + 1),$new_username[$i]);
									} else {
										
										$exists = db_query("select count(*) as num from users where username = '" . $new_username[$i] . "'");
										$exists = $exists[0]->num;
										
										if ($exists) {
											$messages[] = sprintf(gettext("User addition %d failed: username %s is already in use."),($i + 1),$new_username[$i]);
										} else if( !preg_match( "/^([a-zA-Z0-9])+([a-zA-Z0-9\._-])*@([a-zA-Z0-9_-])+([a-zA-Z0-9\._-]+)+$/", $new_email[$i])) {
											$messages[] = sprintf(gettext("User addition %d failed: email address %s appears to be invalid."),($i + 1),$new_email[$i]);
										} else {
											
											$password = "";
											// add random characters to $password until $length is reached
											while ($j < 8) { 
												// pick a random character from the possible ones
												$char = substr("abcdefghjkmnpqrstuvwxyz23456789", mt_rand(0, strlen("abcdefghjkmnpqrstuvwxyz23456789")-1), 1);
												// we don't want this character if it's already in the password
												if (!strstr($password, $char)) { 
													$password .= $char;
													$j++;
												}
											}
											
											$ok = true;
											$md5password = md5($password);
											
											db_query("insert into users set username = '" . $new_username[$i] . "', name = '" . $new_name[$i] . "', email = '" . $new_email[$i] . "', password = '".$md5password."', active = 'yes', user_type = 'person'");
											$newid = db_id();
											
											// Calendar code shouldn't go here! But its here anyways so just checking
											// the global function array to check to see if calendar module is loaded.
											
											global $function;
											if(isset($function["calendar:init"]))  
												db_query("insert into calendar(owner) values({$newid})");
											
											$rssresult = run("weblogs:rss:publish", array($newid, false));
											$rssresult = run("files:rss:publish", array($newid, false));
											$rssresult = run("profile:rss:publish", array($newid, false));
											$sitename = sitename;
											$username = stripslashes($new_username[$i]);
											mail($new_email[$i],sprintf(gettext("Your new %s account"),sitename), wordwrap(sprintf(gettext("You have been added to %s!\n\nFor your records, your %s username and password are:\n\n\tUsername: %s\n\tPassword: %s\n\nYou can log in at any time by visiting %s and entering these details into the login form.\n\nWe hope you enjoy using the system.\n\nRegards,\n\nThe %s Team"),$sitename,$sitename,$username,stripslashes($password),url,$sitename)), "From: $sitename <".email.">");
											$messages[] = sprintf(gettext("User %s was created."),$username);
										}
									}
									
							} else {
								
								if (trim($new_username[$i]) != "" or
								trim($new_name[$i]) != "" or
								trim($new_email[$i]) != "") {
									$messages[] = sprintf(gettext("User addition %d failed: at least one field was blank. Username: %s, name: %s, email: %s"),($i + 1),$new_username[$i],$new_name[$i],$new_email[$i]);
									if (!$ok) {
										$data = "";
										$data->username = $new_username[$i];
										$data->name = $new_name[$i];
										$data->email = $new_email[$i];
										$admin_add_users[] = $data;
										$messages[] = "NOT OK";
									}
								}
							}
						}
						
					}
					break;
				
			}
			
		}
		
	}
	
}

?>