<?php

	// Flag functions: set
	// Ben Werdmuller, Sept 05
	
	/* Parameters:

			[0] - name of the flag
			[1] - user ID
			[2] - value to set
	
	*/
	
		$flagname = $parameter[0];
		$userid = (int) $parameter[1];
		$value = addslashes($parameter[2]);

	// Unset the flag first
		run("users:flags:unset",array($flagname, $userid));
		
	// Then add data
		db_query("insert into user_flags set flag = '$flagname', user_id = $userid, value = '$value'");
		
?>