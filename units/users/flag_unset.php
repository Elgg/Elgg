<?php

	// Flag functions: unset
	// Ben Werdmuller, Sept 05
	
	/* Parameters:

			[0] - name of the flag
			[1] - user ID
	
	*/
	
		$flagname = $parameter[0];
		$userid = (int) $parameter[1];
		
	// Then add data
		db_query("delete from user_flags where flag = '$flagname' and user_id = $userid");
		
?>