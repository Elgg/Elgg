<?php

	global $page_owner;
		
	// If this is someone else's portfolio, display the user's icon
		$run_result .= "<div class=\"box_user\">";
		if ($page_owner != -1) {
			if ($page_owner != $_SESSION['userid']) {
				$run_result .= run("users:infobox", array("Profile Owner",array($page_owner)));
			} else {
				$run_result .= run("users:infobox", array("You",array($page_owner)));
			}
		}
		$run_result .= "</div>";

?>