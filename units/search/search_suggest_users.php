<?php

	if (isset($parameter)) {
		
		$name = addslashes($parameter);
		
		$searchline = "select distinct name, username, match(name) against ('".$name."') as score from users where ";
		$searchline .= "(match(name) against ('".$name."') > 0) limit 20";
		
		$results = db_query($searchline);
		
		if (sizeof($results) > 0) {
			
			$run_result .= "<h2>Matching users:</h2><p>";
			foreach($results as $returned_name) {
				$run_result .= "<a href=\"/".stripslashes($returned_name->username)."/\">" . stripslashes($returned_name->name) . "</a> <br />";
			}
			$run_result .= "</p>";
			
		}
		
	}

?>