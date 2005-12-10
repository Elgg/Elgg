<?php

	global $page_owner;
	$url = url;
	
	if (run("permissions:check", "profile")) {
		
		$editMsg = gettext("Click here to edit this profile.");

		$run_result .= <<< END
		
		<p>
			<a href="{$url}profile/edit.php?profile_id=$page_owner">$editMsg</a>
		</p>
		
END;
		$run_result .= run("profile:edit:link");
			
	}

?>