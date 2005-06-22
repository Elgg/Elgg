<?php

	global $page_owner;
	
	if (run("users:type:get",$page_owner) == 'community' && run("permissions:check", "userdetails:change")) {
		
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
<form action="" method="post">

	<h3>
		Change your community name
	</h3>
	<p>
		This name will be displayed throughout the system.
	</p>

END;

	$body .= run("templates:draw", array(
			'context' => 'databox',
			'name' => 'Community name',
			'column1' => "<input type=\"text\" name=\"name\" value=\"$name\" />"
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