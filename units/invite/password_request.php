<?php

	// Join
		
		$sitename = sitename;
							
				$run_result .= <<< END
				
	<p>
		To generate a new password at $sitename, enter your username below. We
		will send the address of a unique verification page to you via email;
		click on the link in the body of the message and a new password will be
		sent to you.
	</p>
	<p>
		This method reduces the chance of a mistakenly reset password.
	</p>
	<form action="" method="post">
				
END;
				
				$run_result .= run("templates:draw", array(
												'context' => 'databoxvertical',
												'name' => '<b>Your username</b>',
												'contents' => run("display:input_field",array("password_request_name","","text"))
					)
					);
				$run_result .= <<< END
			<p align="center">
				<input type="hidden" name="action" value="invite_password_request" />
				<input type="submit" value="Request new password" />
			</p>
		</form>
				
END;

?>