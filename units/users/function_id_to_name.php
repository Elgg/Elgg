<?php

	// Name table
	
		global $id_to_name_table;

	// Returns user's username from a given ID
	
		if (isset($parameter) && $parameter != "") {
			
			$parameter = (int) $parameter;
			if (!isset($id_to_name_table[$parameter])) {
				$result = db_query("select username from users where ident = '$parameter'");
				$id_to_name_table[$parameter] = $result[0]->username;
			}
			$run_result = $id_to_name_table[$parameter];
			
		}
		
?>