<?php

	// Search criteria are passed in $parameter from run("search:display")
	
		$handle = 0;
		foreach($data['profile:details'] as $profiletype) {
			if ($profiletype[1] == $parameter[0] && $profiletype[2] == "keywords") {
				$handle = 1;
			}
		}
	
		if ($handle) {
			
			$searchline = "tagtype = '".addslashes($parameter[0])."' and tag = '".addslashes($parameter[1])."'";
			$searchline = "(" . run("users:access_level_sql_where",$_SESSION['userid']) . ") and " . $searchline;
			$searchline = str_replace("owner", "tags.owner", $searchline);
			$result = db_query("select distinct users.* from tags join users on users.ident = tags.owner where $searchline");

			$parameter[1] = stripslashes($parameter[1]);
			
			if ($result && sizeof($result) > 0) {
				foreach($result as $key => $info) {
					$run_result .= "\t<item>\n";
					$run_result .= "\t\t<title><![CDATA['" . htmlentities($parameter[0]) . "' = " . htmlentities($parameter[1]) . " :: " . htmlentities(stripslashes($info->name)) . "]]></title>\n";
					$run_result .= "\t\t<link>" . url . htmlentities(stripslashes($info->username)) . "</link>\n";
					$run_result .= "\t</item>\n";
				}
			}
		}

?>