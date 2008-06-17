<?php
	$title = __gettext("Comment wall");
	$blurb = __gettext("This setting allows you to configure who can post to your comment wall.");
	
	$access = user_flag_get("commentwall_access", $page_owner);
	if (!$access) $access = "LOGGED_IN"; // If no access controls set then assume public

	$pub = "";
	$logi = "";
	$pri = "";
        $fo = "";
	if ($access == "PUBLIC") $pub = " selected=\"y\" ";
	if ($access == "LOGGED_IN") $logi = " selected=\"y\" ";
	if ($access == "PRIVATE") $pri = " selected=\"y\" ";
	if ($access == "FRIENDS_ONLY") $fo = " selected=\"y\" ";
	
	$run_result .= "<h2>$title</h2>";
	$run_result .= "<p>$blurb</p>";
	
	$pubtext = __gettext("Public");
	$logitext = __gettext("Logged in users");
	$pritext = __gettext("Private");
	$fotext = __gettext("Friends only");
	
	$select_box = <<< END
		<select name="flag[commentwall_access]">
			<option name="flag[commentwall_access]" value="PUBLIC" $pub>$pubtext</option>
			<option name="flag[commentwall_access]" value="LOGGED_IN" $logi>$logitext</option>
			<option name="flag[commentwall_access]" value="FRIENDS_ONLY" $fo>$fotext</option>
			<option name="flag[commentwall_access]" value="PRIVATE" $pri>$pritext</option>
		</select>
END;
	
	$run_result .= templates_draw(array(
                                            'context' => 'databox',
                                            'name' => __gettext("Access level: "),
                                            'column1' => $select_box
                                            )
                                      );
?>