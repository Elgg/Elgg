<?php

	// Function to log on
	
	// Are the username and password entered?
	
		if (isset($_POST['username']) && isset($_POST['password']) && $_POST['username'] != "" && $_POST['password'] != "") {
			
			$username = addslashes($_POST['username']);
			$password = addslashes(md5($_POST['password']));
			$code = addslashes(md5($username . time()));
			
			db_query("update users set code = '$code' where username = '$username' and password = '$password'");
			
			if (db_affected_rows() > 0) {
				
				$result = db_query("select ident, username, name, email, icon, icon_quota from users where code = '$code' and username = '$username' and password = '$password' and active = 'yes'");
				$result = $result[0];
				
				$_SESSION['userid'] = (int) $result->ident;
				$_SESSION['usercode'] = $code;
				$_SESSION['username'] = stripslashes($result->username);
				$_SESSION['name'] = stripslashes($result->name);
				$_SESSION['email'] = stripslashes($result->email);
				$iconid = (int) $result->icon;
				if ($iconid == -1) {
					$_SESSION['icon'] = "default.png";
				} else {
					$icon = db_query("select filename from icons where ident = $iconid");
					$_SESSION['icon'] = $icon[0]->filename;
				}
				$_SESSION['icon_quota'] = (int) $result->icon_quota;
				
				$messages[] = "You have been logged on.";
				// define('redirect_url',url . $_SESSION['username'] . "/");
				define('redirect_url',url . "home.php");
				
			} else {
				
				$messages[] = "Unrecognised username or password. The system could not log you on, or you may not have activated your account.";
				
			}
			
		} else {
			
			$messages[] = "Either the username or password were not specified. The system could not log you on.";
			
		}

?>