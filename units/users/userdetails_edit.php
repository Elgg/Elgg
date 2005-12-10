<?php

	global $page_owner;
	
	if (run("users:type:get",$page_owner) == 'person' && run("permissions:check",array("userdetails:change", $page_owner))) {
	
	if ($page_owner == $_SESSION['userid']) {
		$name = htmlentities($_SESSION['name']);
		$email = htmlentities($_SESSION['email']);
	} else {
		$info = db_query("select * from users where ident = $page_owner");
		$info = $info[0];
		$name = htmlentities(stripslashes($info->name));
		$email = htmlentities(stripslashes($info->email));
	}
	
       $changeName = gettext("Change your full name:"); // gettext variable
       $displayed = gettext("This name will be displayed throughout the system."); // gettext variable
	$body = <<< END

<form action="index.php" method="post">

	<h5>
		$changeName
	</h5>
	<p>
		$displayed
	</p>

END;

	$body .= run("templates:draw", array(
			'context' => 'databox',
			'name' => gettext("Your full name "),
			'column1' => "<input type=\"text\" name=\"name\" value=\"$name\" />"
		)
		);
	
	$emailAddress = gettext("Your email address:"); // gettext variable
       $emailRules = gettext("This will not be displayed to other users; you can choose to make an email address available via the profile screen."); // gettext variable
       $body .= <<< END
			
	<h5>
		$emailAddress
	</h5>
	<p>
		$emailRules
	</p>
	
END;

	$body .= run("templates:draw", array(
			'context' => 'databox',
			'name' => gettext("Your email address "),
			'column1' => "<input type=\"text\" name=\"email\" value=\"$email\" />"
		)
		);

	$password = gettext("Change your password:"); // gettext variable
       $passwordRules = gettext("Leave this blank if you're happy to leave your password as it is."); // gettext variable
       $body .= <<< END
		
	<h5>
		$password
	</h5>
	<p>
		$passwordRules
	</p>
	
END;
	
	$body .= run("templates:draw", array(
			'context' => 'databox',
			'name' => gettext("Your password: "),
			'column1' => "<input type=\"password\" name=\"password1\" value=\"\" />"
		)
		);
		
	$body .= run("templates:draw", array(
			'context' => 'databox',
			'name' => gettext("Again for verification purposes: "),
			'column1' => "<input type=\"password\" name=\"password2\" value=\"\" />"
		)
		);
	
	// Allow plug-ins to add stuff ...
		$body .= run("userdetails:edit:details");

		$id = $page_owner;

		$save = gettext("Save");
		
		$body .= <<< END
		
	<p align="center">
		<input type="hidden" name="action" value="userdetails:update" />
		<input type="hidden" name="id" value="$page_owner" />
		<input type="hidden" name="profile_id" value="$page_owner" />
		<input type="submit" value="$save" />
	</p>
	
</form>

END;

	$run_result .= $body;
	}

?>