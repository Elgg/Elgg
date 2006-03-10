<?php

	// Join
		
		$sitename = sitename;
		$url = url;
			
		if (isset($_REQUEST['invitecode'])) {
			
			$code = trim($_REQUEST['invitecode']);
			$details = db_query("select * from invitations where code = '$code'");
			if (sizeof($details) > 0) {
				
				$details = $details[0];
				
				if (isset($_REQUEST['join_name'])) {
					$name = stripslashes($_REQUEST['join_name']);
				} else {
					$name = stripslashes($details->name);
				}
				
				if (isset($_REQUEST['join_username'])) {
					$username = stripslashes($_REQUEST['join_username']);
				} else {
					$username = "";
					$namebits = explode(" ", $name);
					foreach($namebits as $key => $bit) {
						if ($key == 0) {
							$username .= strtolower($bit);
						} else {
							$username .= strtolower(substr($bit,0,1));
						}
					}
					$username = preg_replace("/[^A-Za-z]/","",$username);
				}
				
				$invite_id = (int) ($details->ident);
				$thankYou = sprintf(gettext("Thank you for registering for an account with %s! Registration is completely free, but before you confirm your details, please take a moment to read the following documents:"), $sitename);
				$terms = gettext("terms and conditions"); // gettext variable
				$privacy = gettext("Privacy policy"); // gettext variable
				$age = gettext("Submitting the form below indicates acceptance of these terms. Please note that currently you must be at least 13 years of age to join the site."); // gettext variable
				$run_result .= <<< END
				
	<p>
		$thankYou
	</p>
	<ul>
		<li><a href="{$url}content/terms.php" target="_blank">$sitename $terms</a></li>
		<li><a href="{$url}content/privacy.php" target="_blank">$privacy</a></li>
	</ul>
	<p>
		$age
	</p>

	<form action="" method="post">
				
END;
				
				$run_result .= run("templates:draw", array(
												'context' => 'databoxvertical',
												'name' => gettext("Your name"),
												'contents' => run("display:input_field",array("join_name",$name,"text"))
					)
					);
				$run_result .= run("templates:draw", array(
												'context' => 'databoxvertical',
												'name' => gettext("Your username - (Must be letters only)"),
												'contents' => run("display:input_field",array("join_username",$username,"text"))
					)
					);
				$run_result .= run("templates:draw", array(
												'context' => 'databoxvertical',
												'name' => gettext("Enter a password"),
												'contents' => run("display:input_field",array("join_password1","","password"))
					)
					);
				$run_result .= run("templates:draw", array(
												'context' => 'databoxvertical',
												'name' => gettext("Your password again for verification purposes"),
												'contents' => run("display:input_field",array("join_password2","","password"))
					)
					);
			$correctAge = gettext("I am at least thirteen years of age."); // gettext variable
			$buttonValue = gettext("Join"); // gettext variable
			$run_result .= <<< END
			<p align="center">
				<label for="over13checkbox"><input type="checkbox" id="over13checkbox" name="over13" value="yes" /> <strong>$correctAge</strong></label>
			</p>
			<p align="center">
				<input type="hidden" name="action" value="invite_join" />
				<input type="submit" value="$buttonValue" />
			</p>
		</form>
				
END;

			} else {
				
				$invalid = sprintf(gettext("Your invitation code appears to be invalid. Codes only last for seven days; it's possible that yours is older. If you still want to join %s, it may be worth getting in touch with the person who invited you."),$sitename);
				$run_result .= <<< END
				
	<p>
		$invalid
	</p>
				
END;
				
			}
			
		} else {
				$invite = sprintf(gettext("For the moment, joining %s requires a specially tailored invite code. If you know someone who's a member, it may be worth asking them for one."),$sitename);
				$run_result .= <<< END
				
	<p>
		$invite
	</p>
				
END;
			
		}

?>