<?php

	// Cycle through all defined profile detail fields and display them

	if (isset($data['profile:details']) && sizeof($data['profile:details']) > 0) {
	
		global $profile_id;
		$allvalues = db_query("select * from profile_data where owner = '$profile_id'");
		
		if (sizeof($allvalues) > 0) {
			foreach($data['profile:details'] as $field) {
				$run_result .= run("profile:field:display",array($field, $allvalues));
			}
		}

	}

?>