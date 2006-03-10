<?php

	global $page_owner;
	
	if (run("users:type:get",$page_owner) == 'community' && run("permissions:check", "userdetails:change")) {
		
	$info = db_query("select * from users where ident = $page_owner");
	$info = $info[0];
	$name = htmlentities(stripslashes($info->name));
	$email = htmlentities(stripslashes($info->email));
	
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
		
	$friendAddress = gettext("Membership restriction:"); // gettext variable
		$friendRules = gettext("This allows you to choose who can join this community."); // gettext variable
		$body .= <<< END
				
		<h2>
			$friendAddress
		</h2>
		<p>
			$friendRules
		</p>
		
END;

		$friendlevel = "<select name=\"moderation\">";
		$friendlevel .= "<option value=\"no\" ";
		if ($info->moderation == "no") {
			$friendlevel .= "selected=\"selected\"";
		}
		$friendlevel .= ">" . gettext("No moderation: anyone can join this community.") . "</option>";
		$friendlevel .= "<option value=\"yes\" ";
		if ($info->moderation == "yes") {
			$friendlevel .= "selected=\"selected\"";
		}
		$friendlevel .= ">" . gettext("Moderation: memberships must be approved by you.") . "</option>";
		$friendlevel .= "<option value=\"priv\" ";
		if ($info->moderation == "priv") {
			$friendlevel .= "selected=\"selected\"";
		}
		$friendlevel .= ">" . gettext("Private: nobody can join this community.") . "</option>";
		$friendlevel .= "</select>";
	
		$body .= run("templates:draw", array(
			'context' => 'databox',
			'name' => gettext("Membership restriction"),
			'column1' => $friendlevel
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