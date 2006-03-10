<?php

	// Join
		$sitename = sitename;
		$desc = sprintf(gettext("To generate a new password at %s!, enter your username below. We will send the address of a unique verification page to you via email click on the link in the body of the message and a new password will be sent to you."), $sitename); // gettext variable
		$thismethod = gettext("This method reduces the chance of a mistakenly reset password.");
							
				$run_result .= <<< END
				
	<p>
		$desc
	</p>
	<p>
		$thismethod
	</p>
	<form action="" method="post">
				
END;
				
				$run_result .= run("templates:draw", array(
												'context' => 'databoxvertical',
												'name' => gettext("Your username"),
												'contents' => run("display:input_field",array("password_request_name","","text"))
					)
					);
				$request = gettext("Request new password"); // gettext variable
				$run_result .= <<< END
			<p align="center">
				<input type="hidden" name="action" value="invite_password_request" />
				<input type="submit" value=$request />
			</p>
		</form>
				
END;

?>