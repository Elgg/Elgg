<?php

	global $page_owner;
	
	if (logged_on && $page_owner != $_SESSION['userid']) {

		$page_url = $_SERVER['REQUEST_URI'];
	
		$run_result .= "<p>&nbsp;</p>";
		$run_result .= "<form action=\"\" method=\"post\" >";
	
		
		$run_result .= run("templates:draw", array(
							'context' => 'flagContent',
							'name' => "<h5>" . gettext("Flag content") . "</h5>",
							'column1' => "<p>" . gettext("To mark this content as obscene or inappropriate, click the 'Flag' button and an administrator will view it in due course.") . "</p>",
							'column2' => "<input type=\"submit\" value=\"" . gettext("Flag") . "\" /><input type=\"hidden\" name=\"action\" value=\"content:flag\" /><input type=\"hidden\" name=\"address\" value=\"$page_url\" />"
						)
						);
		$run_result .= "</form>";
		
	}

?>