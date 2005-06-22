<?php

	global $page_owner;

	if (run("permissions:check", "profile")) {
	
		$profile_username = run("users:id_to_name",$page_owner);
		
		$body = "<form action=\"".url . $profile_username ."/\" method=\"post\">";
	
		// Cycle through all defined profile detail fields and display them
	
		if (isset($data['profile:details']) && sizeof($data['profile:details']) > 0) {
		
			foreach($data['profile:details'] as $field) {
				$body .= run("profile:editfield:display",$field);
			}
	
		}
	
		$body .= <<< END

	<p align="center">
		<label>
			Submit details:
			<input type="submit" name="submit" value="Go" />
		</label>
		<input type="hidden" name="action" value="profile:edit" />
		<input type="hidden" name="profile_id" value="$page_owner" />
	</p>

</form>
END;

		$run_result .= $body;
	
	}
	
?>