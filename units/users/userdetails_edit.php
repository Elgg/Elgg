<?php

	global $page_owner;
	
	if (run("users:type:get",$page_owner) == 'person' && run("permissions:check",array("userdetails:change", $page_owner))) {
	
	$info = db_query("select * from users where ident = $page_owner");
	$info = $info[0];
	$name = htmlentities(stripslashes($info->name));
	$email = htmlentities(stripslashes($info->email));
	
	$changeName = gettext("Change your full name:"); // gettext variable
	$displayed = gettext("This name will be displayed throughout the system."); // gettext variable
	$body = <<< END

<form action="index.php" method="post">

	<h2>
		$changeName
	</h2>
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
			
	<h2>
		$emailAddress
	</h2>
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

		$friendAddress = gettext("Friendship moderation:"); // gettext variable
		$friendRules = gettext("This allows you to choose who can list you as a friend."); // gettext variable
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
		$friendlevel .= ">" . gettext("No moderation: anyone can list you as a friend. (Recommended)") . "</option>";
		$friendlevel .= "<option value=\"yes\" ";
		if ($info->moderation == "yes") {
			$friendlevel .= "selected=\"selected\"";
		}
		$friendlevel .= ">" . gettext("Moderation: friendships must be approved by you.") . "</option>";
		$friendlevel .= "<option value=\"priv\" ";
		if ($info->moderation == "priv") {
			$friendlevel .= "selected=\"selected\"";
		}
		$friendlevel .= ">" . gettext("Private: nobody can list you as a friend.") . "</option>";
		$friendlevel .= "</select>";
	
		$body .= run("templates:draw", array(
			'context' => 'databox',
			'name' => gettext("Friendship moderation"),
			'column1' => $friendlevel
			)
			);
		
		$emailReplies = gettext("Make comments public");
		$emailRules = gettext("Set this to 'yes' if you would like anyone to be able to comment on your resources (by default only logged-in users can). Note that this may make you vulnerable to spam.");
		
		$body .= <<< END
		
		<h2>$emailReplies</h2>
		<p>
			$emailRules
		</p>
		
END;

	$emailreplies = run("users:flags:get",array("publiccomments",$page_owner));
	if ($emailreplies) {
		$body .= run("templates:draw", array(
			'context' => 'databox',
			'name' => gettext("Public comments: "),
			'column1' => "<label><input type=\"radio\" name=\"publiccomments\" value=\"yes\" checked=\"checked\" /> " . gettext("Yes") . "</label> <label><input type=\"radio\" name=\"publiccomments\" value=\"no\" /> " . gettext("No") . "</label>"
		)
		);
	} else {
		$body .= run("templates:draw", array(
			'context' => 'databox',
			'name' => gettext("Public comments: "),
			'column1' => "<label><input type=\"radio\" name=\"publiccomments\" value=\"yes\" /> " . gettext("Yes") . "</label> <label><input type=\"radio\" name=\"publiccomments\" value=\"no\" checked=\"checked\" /> " . gettext("No") . "</label>"
		)
		);
	}
		
		$emailReplies = gettext("Receive email messages");
		$emailRules = gettext("Set this to 'yes' if you would like to receive comments, replies and interesting discussion through your email. You can also access them by clicking on <a href=\"". url ."_activity/\">view your activity</a>.");
		
		$body .= <<< END
		
		<h2>$emailReplies</h2>
		<p>
			$emailRules
		</p>
		
END;

	$emailreplies = run("users:flags:get",array("emailreplies",$page_owner));
	if ($emailreplies) {
		$body .= run("templates:draw", array(
			'context' => 'databox',
			'name' => gettext("Receive messages: "),
			'column1' => "<label><input type=\"radio\" name=\"receiveemails\" value=\"yes\" checked=\"checked\" /> " . gettext("Yes") . "</label> <label><input type=\"radio\" name=\"receiveemails\" value=\"no\" /> " . gettext("No") . "</label>"
		)
		);
	} else {
		$body .= run("templates:draw", array(
			'context' => 'databox',
			'name' => gettext("Receive messages: "),
			'column1' => "<label><input type=\"radio\" name=\"receiveemails\" value=\"yes\" /> " . gettext("Yes") . "</label> <label><input type=\"radio\" name=\"receiveemails\" value=\"no\" checked=\"checked\" /> " . gettext("No") . "</label>"
		)
		);
	}
		
	$password = gettext("Change your password:"); // gettext variable
	$passwordRules = gettext("Leave this blank if you're happy to leave your password as it is."); // gettext variable
	$body .= <<< END
		
	<h2>
		$password
	</h2>
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