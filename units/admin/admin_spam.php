<?php

	// Users panel
	
	if (logged_on && run("users:flags:get", array("admin", $_SESSION['userid']))) {
		
		$run_result .= "<p>" . gettext("Add regular expressions below, one per line, to block spam. For example, 'foo' will block all comments containing the word foo, (foo|bar) will block comments containing the word foo or bar.") . "</p>";
		$run_result .= "<p>" . gettext("Blank lines and lines starting with # will be ignored.") . "</p>";
		
		$spam = db_query("select * from datalists where name = 'antispam'");
		if (sizeof($spam) > 0) {
			$spam = htmlentities(stripslashes($spam[0]->value));
		} else {
			$spam = "";
		}
		
		$run_result .= "<form action=\"\" method=\"post\">";
		
		$run_result .= run("templates:draw", array(
						'context' => 'databox',
						'name' => gettext("Regular expressions"),
						'column1' => run("display:input_field",array("antispam",$spam,"longtext","antispam")),
						'column2' => "<input type=\"hidden\" name=\"action\" value=\"admin:antispam:save\" /><input type=\"submit\" value=\"" . gettext("Save") . "\" />"
					)
					);
					
		$run_result .= "</form>";
		
	}

?>