<?php

	// Users panel
	
	// $parameter = a row from the elgg.users database
	
	if (isset($parameter)) {
		
		$run_result .= run("templates:draw", array(
						'context' => 'adminTable',
						'name' => "<p>" . stripslashes($parameter->username) . "</p>",
						'column1' => "<a href=\"" . url . "_userdetails/?profile_id=" .$parameter->ident . "&amp;context=admin\" >" . stripslashes($parameter->name) . "</a> [<a href=\"".url . stripslashes($parameter->username) ."/\">" . gettext("Profile") . "</a>]",
						'column2' => "<a href=\"mailto:" . $parameter->email. "\" >" . $parameter->email . "</a>"
					)
					);
		
	}

?>