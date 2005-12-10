<?php

	// Content flag list
	
	if (logged_on && run("users:flags:get", array("admin", $_SESSION['userid']))) {
	
		$run_result .= "<p>" . gettext("The following pages have been flagged as having obscene or inappropriate content. They are ordered by number of complaints.") . "</p>";
		$run_result .= "<p>" . gettext("To view the pages in question, click the following links. To remove the flags, for example if the flag is a false positive or if you've deleted the offending content, check the appropriate box and click the 'delete' button below.") . "</p>";
	
		$run_result .= "<form action=\"\" method=\"post\">";
		
		$flags = db_query("select distinct url, count(ident) as totalflags from content_flags group by url order by totalflags desc");
		if (sizeof($flags) > 0) {
		
			$run_result .= run("templates:draw", array(
									'context' => 'databox',
									'name' => "&nbsp;",
									'column1' => "<b>" . gettext("Page URL") . "</b>",
									'column2' => "<b>" . gettext("Number of objections") . "</b>"
								)
								);
								
			foreach($flags as $flag) {
				
				$run_result .= run("templates:draw", array(
										'context' => 'databox',
										'name' => "<input type=\"checkbox\" name=\"remove[]\" value=\"" . $flag->url . "\" />",
										'column1' => "<a href=\"" . $flag->url . "\" target=\"_blank\">" . $flag->url . "</a>",
										'column2' => $flag->totalflags
									)
									);
				
			}
			
			$run_result .= run("templates:draw", array(
									'context' => 'databox',
									'name' => "&nbsp;",
									'column1' => "<input type=\"submit\" value=\"".gettext("Remove flag(s)")."\" />",
									'column2' => "<input type=\"hidden\" name=\"action\" value=\"content:flags:delete\" />"
								)
								);
								
		} else {
			$run_result .= "<p>" . gettext("No content flags were found at present.") . "</p>";
		}
							
		$run_result .= "</form>";
		
	}
	
?>