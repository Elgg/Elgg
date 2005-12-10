<?php

	// Search criteria are passed in $parameter from run("search:display")
	
		$handle = 0;
		foreach($data['profile:details'] as $profiletype) {
			if ($profiletype[1] == $parameter[0] && $profiletype[2] == "keywords") {
				$handle = 1;
			}
		}
	
		if ($handle) {
			
			$sub_result = "";
			
			$searchline = "tagtype = '".addslashes($parameter[0])."' and tag = '".addslashes($parameter[1])."'";
			$searchline = "(" . run("users:access_level_sql_where",$_SESSION['userid']) . ") and " . $searchline;
			$searchline = str_replace("owner", "tags.owner", $searchline);
			$result = db_query("select distinct users.* from tags left join users on users.ident = tags.owner where $searchline");

			$parameter[1] = stripslashes($parameter[1]);
			
			if ($result && sizeof($result) > 0) {
				foreach($result as $key => $info) {
					
					if ($post->icon == -1) {
						$icon = "default.png";
					} else {
						$icon = db_query("select filename from icons where ident = " . $post->icon);
						$icon = $icon[0]->filename;
					}
					$icon = url . "_icons/data/" . $icon;
					
					$sub_result .= "\t\t\t<item>\n";
					$sub_result .= "\t\t\t\t<name><![CDATA[" . htmlentities(stripslashes($info->name)) . "]]></name>\n";
					$sub_result .= "\t\t\t\t<link>" . url . htmlentities(stripslashes($info->username)) . "</link>\n";
					$sub_result .= "\t\t\t\t<link>$icon</link>\n";
					$sub_result .= "\t\t\t</item>\n";
				}
			}
			
			if ($sub_result != "") {
				
				$run_result .= "\t\t<profiles tagtype=\"".addslashes(htmlentities($parameter[0]))."\">\n" . $sub_result . "\t\t</profiles>\n";
				
			}
			
		}

?>