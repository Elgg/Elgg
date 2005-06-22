<?php

	// Generate a new password
		
		$sitename = sitename;
			
		if (isset($_REQUEST['passwordcode'])) {
			
			$code = addslashes($_REQUEST['passwordcode']);
			$details = db_query("select password_requests.ident as passcodeid, users.* from password_requests left join users on users.ident = password_requests.owner where password_requests.code = '$code'");
			if (sizeof($details) > 0) {
				$details = $details[0];
				
				$run_result .= <<< END
				
	<p>
		A new password has been emailed to you. You should be able to
		use it immediately; your old one has been deactivated.
	</p>
END;
				$validcharset = "ABCDEFGHJKLMNPQRSTUVWXYZabcdefghijkmnpqrstuvwxyz234567898765432";
				$newpassword = "";
				for ($i = 0; $i < 8; $i++) {
					$newpassword .= $validcharset[mt_rand(0, (strlen($validcharset) - 1))]; 
				}
				$newpassword = strtolower($newpassword);
				
				$sitename = sitename;
				
				mail($details->email, "Your $sitename password", wordwrap("
Your $sitename password has been reset.

For your records, your new password is:

	Password: $newpassword
	
Please consider changing your password as soon as you have logged in
for security reasons.

We hope you continue to enjoy using the system.

Regards,
The $sitename Team"), "From: $sitename <".email.">");
				
				$newpassword = md5(($newpassword));
				db_query("update users set password = '$newpassword' where ident = " . $details->ident);
				db_query("delete from password_requests where owner = " . $details->ident);

			} else {
				
				$run_result .= <<< END
				
	<p>
		Your password request code appears to be invalid. Try generating a new one?
	</p>
				
END;
				
			}
			
		} else {
			
				$run_result .= <<< END
				
	<p>
		Sorry, your code was invalid.
	</p>
				
END;
			
		}

?>