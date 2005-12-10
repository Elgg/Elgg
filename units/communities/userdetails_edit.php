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
	
	$header = gettext("Change your community name"); // gettext variable
       $desc = gettext("This name will be displayed throughout the system."); // gettext variable
       $body = <<< END
<form action="" method="post">

	<h3>
		$header
	</h3>
	<p>
		$desc
	</p>

END;

	$body .= run("templates:draw", array(
			'context' => 'databox',
			'name' => gettext("Community name"),
			'column1' => "<input type=\"text\" name=\"name\" value=\"$name\" />"
		)
		);
		
	// Allow plug-ins to add stuff ...
		$body .= run("userdetails:edit:details");
             
             $save = gettext("Save"); // gettext variable
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