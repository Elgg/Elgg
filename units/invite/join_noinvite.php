<?php

	// Join
		
		$sitename = sitename;
							
				$run_result .= <<< END
				
	<p>
		Thank you for registering for an account with $sitename! Registration is completely free, but before
		you fill in your details, please take a moment to read the following documents:
	</p>
	<ul>
		<li><a href="/content/terms.php" target="_blank">$sitename terms and conditions</a></li>
		<li><a href="/content/privacy.php" target="_blank">Privacy policy</a></li>
	</ul>
	<p>
		When you fill in the details below, we will send an "invitation code" to your email address in order
		to validate it. You must then click on this within seven days to create your account.
	</p>
	<form action="" method="post">
				
END;
				
				$run_result .= run("templates:draw", array(
												'context' => 'databoxvertical',
												'name' => '<b>Your name</b>',
												'contents' => run("display:input_field",array("invite_name","","text"))
					)
					);
				$run_result .= run("templates:draw", array(
												'context' => 'databoxvertical',
												'name' => '<b>Your email address</b><br />',
												'contents' => run("display:input_field",array("invite_email","","text"))
					)
					);
				$run_result .= <<< END
			<p align="center">
				<input type="hidden" name="invite_text" value="" />
				<input type="hidden" name="action" value="invite_invite" />
				<input type="submit" value="Register" />
			</p>
		</form>
				
END;

?>