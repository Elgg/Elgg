<?php

	// Name table
	
		global $name_to_id_table;

	// Returns user's ID from a given name
	
		if (isset($parameter) && $parameter != "") {
			
			$parameter = addslashes($parameter);
			if (!isset($name_to_id_table[$parameter])) {
				$result = db_query("select ident from users where username = '$parameter'");
				$name_to_id_table[$parameter] = $result[0]->ident;
			}
			$run_result = $name_to_id_table[$parameter];
			
		}
		
?>