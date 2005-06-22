<?php

	global $page_owner;
	
	if (run("users:type:get",$page_owner) == 'person' && run("permissions:check","userdetails:change")) {
	
	if ($page_owner == $_SESSION['userid']) {
		$name = htmlentities($_SESSION['name']);
		$email = htmlentities($_SESSION['email']);
	} else {
		$info = db_query("select * from users where ident = $page_owner");
		$info = $info[0];
		$name = htmlentities(stripslashes($info->name));
		$email = htmlentities(stripslashes($info->email));
	}
	
	$body = <<< END

<form action="index.php" method="post">

	<h3>
		Change your full name
	</h3>
	<p>
		This name will be displayed throughout the system.
	</p>

END;

	$body .= run("templates:draw", array(
			'context' => 'databox',
			'name' => 'Your full name',
			'column1' => "<input type=\"text\" name=\"name\" value=\"$name\" />"
		)
		);
	
	$body .= <<< END
			
	<h3>
		Your email address
	</h3>
	<p>
		This will not be displayed to other users; you can choose to make an email address available via the profile screen.
	</p>
	
END;

	$body .= run("templates:draw", array(
			'context' => 'databox',
			'name' => 'Your email address',
			'column1' => "<input type=\"text\" name=\"email\" value=\"$email\" />"
		)
		);

	$body .= <<< END
		
	<h3>
		Change your password
	</h3>
	<p>
		Leave this blank if you're happy to leave your password as it is.
	</p>
	
END;
	
	$body .= run("templates:draw", array(
			'context' => 'databox',
			'name' => 'Your password',
			'column1' => "<input type=\"password\" name=\"password1\" value=\"\" />"
		)
		);
		
	$body .= run("templates:draw", array(
			'context' => 'databox',
			'name' => 'Again for verification purposes',
			'column1' => "<input type=\"password\" name=\"password2\" value=\"\" />"
		)
		);
	
	// Allow plug-ins to add stuff ...
		$body .= run("userdetails:edit:details");

		$body .= <<< END
		
	<p align="center">
		<input type="hidden" name="action" value="userdetails:update" />
		<input type="submit" value="Save" />
	</p>
	
</form>

END;

	$run_result .= $body;
	}

?>