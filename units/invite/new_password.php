<?php

	// Generate a new password
		
		$sitename = sitename;
			
		if (isset($_REQUEST['passwordcode'])) {
			
			$code = trim($_REQUEST['passwordcode']);
			$details = db_query("select password_requests.ident as passcodeid, users.* from password_requests join users on users.ident = password_requests.owner where password_requests.code = '$code' and users.user_type = 'person'");
			if (sizeof($details) > 0) {
				$details = $details[0];
				
				$passwordDesc = sprintf(gettext("A new password has been emailed to you at %s. You should be able to use it immediately; your old one has been deactivated."),$details->email);
				$run_result .= <<< END
				
	<p>
		$passwordDesc
	</p>
END;
				$validcharset = "ABCDEFGHJKLMNPQRSTUVWXYZabcdefghijkmnpqrstuvwxyz234567898765432";
				$newpassword = "";
				for ($i = 0; $i < 8; $i++) {
					$newpassword .= $validcharset[mt_rand(0, (strlen($validcharset) - 1))]; 
				}
				$newpassword = strtolower($newpassword);
				
				$sitename = sitename;
				
				mail($details->email, sprintf(gettext("Your %s password"), $sitename), wordwrap(sprintf(gettext("Your %s password has been reset.\n\nFor your records, your new password is:\n\n\tPassword: %s\n\nPlease consider changing your password as soon as you have logged in for security reasons.\n\nWe hope you continue to enjoy using the system.\n\nRegards,\n\nThe %s Team"), $sitename, $newpassword, $sitename)), "From: $sitename <".email.">");
				
				$newpassword = md5(($newpassword));
				db_query("update users set password = '$newpassword' where ident = " . $details->ident);
				db_query("delete from password_requests where owner = " . $details->ident);

			} else {
				
				$passwordDesc2 = gettext("Your password request code appears to be invalid. Try generating a new one?");
				$run_result .= <<< END
				
	<p>
		$passwordDesc
	</p>
				
END;
				
			}
			
		} else {
				$passwordDesc3 = gettext("Sorry, your code was invalid.");
				$run_result .= <<< END
				
	<p>
		$passwordDesc3
	</p>
				
END;
			
		}

?>