<?php

	global $page_owner;
	$url=url;

	if (run("users:type:get",$page_owner) == 'community') {

		if (run("permissions:check", "uploadicons")) {
		
			global $page_owner;
			
			$run_result .= <<< END
			
			<p>
				<a href="{$url}_icons/?profile_id={$page_owner}">Upload / edit site pictures for this community.</a>
			</p>
			
END;
		
		}
		if (run("permissions:check", "userdetails:change")) {
		
			global $page_owner;
			
			$run_result .= <<< END
			
			<p>
				<a href="{$url}_userdetails/?profile_id={$page_owner}">Change this community's name.</a>
			</p>
			
END;
		
		}
	}

?>