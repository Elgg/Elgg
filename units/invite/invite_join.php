<?php

	// Join
		
		$sitename = sitename;
			
		if (isset($_REQUEST['invitecode'])) {
			
			$code = addslashes($_REQUEST['invitecode']);
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
				
				$run_result .= <<< END
				
	<p>
		Thank you for registering for an account with $sitename! Registration is completely free, but before
		you confirm your details, please take a moment to read the following documents:
	</p>
	<ul>
		<li><a href="/content/terms.php" target="_blank">$sitename terms and conditions</a></li>
		<li><a href="/content/privacy.php" target="_blank">Privacy policy</a></li>
	</ul>
	<p>
		Submitting the form below indicates acceptance of these terms. Please note that currently you must
		be at least 13 years of age to join the site.
	</p>

	<form action="" method="post">
				
END;
				
				$run_result .= run("templates:draw", array(
												'context' => 'databoxvertical',
												'name' => '<b>Your name</b>',
												'contents' => run("display:input_field",array("join_name",$name,"text"))
					)
					);
				$run_result .= run("templates:draw", array(
												'context' => 'databoxvertical',
												'name' => '<b>Your username</b><br /><i>Must be letters only</i>',
												'contents' => run("display:input_field",array("join_username",$username,"text"))
					)
					);
				$run_result .= run("templates:draw", array(
												'context' => 'databoxvertical',
												'name' => '<b>Enter a password</b>',
												'contents' => run("display:input_field",array("join_password1","","password"))
					)
					);
				$run_result .= run("templates:draw", array(
												'context' => 'databoxvertical',
												'name' => '<b>Your password again for verification purposes</b>',
												'contents' => run("display:input_field",array("join_password2","","password"))
					)
					);
				$run_result .= <<< END
			<p align="center">
				<input type="checkbox" name="over13" value="yes" /> <b>I am at least thirteen years of age.</b>
			</p>
			<p align="center">
				<input type="hidden" name="action" value="invite_join" />
				<input type="submit" value="Join" />
			</p>
		</form>
				
END;

			} else {
				
				$run_result .= <<< END
				
	<p>
		Your invitation code appears to be invalid. Codes only last for seven days; it's possible that
		yours is older. If you still want to join $sitename, it may be worth getting in touch with the person
		who invited you.
	</p>
				
END;
				
			}
			
		} else {
			
				$run_result .= <<< END
				
	<p>
		For the moment, joining $sitename requires a specially tailored invite code. If you know someone
		who's a member, it may be worth asking them for one.
	</p>
				
END;
			
		}

?>