<?php

	// Join
		
		$sitename = sitename;
              $partOne = sprintf(gettext("Thank you for registering for an account with %s! Registration is completely free, but before you fill in your details, please take a moment to read the following documents:"),$sitename); // gettext variable
             $terms = gettext("terms and conditions"); // gettext variable
              $privacy = gettext("Privacy policy"); // gettext variable
             $partFour = gettext("When you fill in the details below, we will send an \"invitation code\" to your email address in order to validate it. You must then click on this within seven days to create your account."); // gettext variable
							
				$run_result .= <<< END
				
	<p>
		$partOne
	</p>
	<ul>
		<li><a href="/content/terms.php" target="_blank">$sitename $terms</a></li>
		<li><a href="/content/privacy.php" target="_blank">$privacy</a></li>
	</ul>
	<p>
		$partFour
	</p>
	<form action="" method="post">
				
END;
				
				$run_result .= run("templates:draw", array(
												'context' => 'databoxvertical',
												'name' => gettext("Your name"),
												'contents' => run("display:input_field",array("invite_name","","text"))
					)
					);
				$run_result .= run("templates:draw", array(
												'context' => 'databoxvertical',
												'name' => gettext("Your email address"),
												'contents' => run("display:input_field",array("invite_email","","text"))
					)
					);
			$buttonValue = gettext("Register");
                     $run_result .= <<< END
			<p align="center">
				<input type="hidden" name="invite_text" value="" />
				<input type="hidden" name="action" value="invite_invite" />
				<input type="submit" value=$buttonValue />
			</p>
		</form>
				
END;

?>