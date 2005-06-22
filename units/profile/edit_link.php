<?php

	global $page_owner;
	$url = url;
	
	if (run("permissions:check", "profile")) {
		
		$run_result .= <<< END
		
		<p>
			<a href="{$url}profile/edit.php?profile_id=$page_owner">Click here to edit this profile.</a>
		</p>
		
END;
		$run_result .= run("profile:edit:link");
			
	}

?>