<?php

	// Users panel
	
	// $parameter = a row from the elgg.users database
	
	if (isset($parameter)) {
		
		$run_result .= run("templates:draw", array(
						'context' => 'databox',
						'name' => stripslashes($parameter->username),
						'column1' => "<a href=\"" . url . "_userdetails/?profile_id=" .$parameter->ident . "&context=admin\" >" . stripslashes($parameter->name) . "</a> [<a href=\"".url . stripslashes($parameter->username) ."/\">" . gettext("Profile") . "</a>]",
						'column2' => "<a href=\"mailto:" . $parameter->email. "\" >" . $parameter->email . "</a>"
					)
					);
		
	}

?>