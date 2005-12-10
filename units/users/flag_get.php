<?php

	// Flag functions: get
	// Ben Werdmuller, Sept 05
	
	/* Parameters:

			[0] - name of the flag
			[1] - user ID
	
			
		Returns:
		
			$value - if the flag is set
			false - if it isn't		
	*/
	
	
		$flagname = $parameter[0];
		$userid = (int) $parameter[1];
		
		$result = db_query("select value from user_flags where flag = '$flagname' and user_id = $userid");
		if (sizeof($result) > 0) {
			$run_result = stripslashes($result[0]->value);
		} else {
			$run_result = false;
		}
		
?>