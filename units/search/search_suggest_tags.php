<?php

	if (isset($parameter)) {
		
		$tag = addslashes($parameter);
		
		$searchline = "select distinct tag, match(tag) against ('".$tag."') as score from tags where ";
		$searchline .= "(" . run("users:access_level_sql_where",$_SESSION['userid']) . ")";
		$searchline .= " and (match(tag) against ('".$tag."') > 0) limit 10";
		
		$results = db_query($searchline);
		
		if (sizeof($results) > 1) {
			
			$run_result .= "<h2>Automatic tag suggestion:</h2><p>";
			foreach($results as $returned_tag) {
				if ($returned_tag->tag != $tag) {
					$run_result .= "<a href=\"/tag/".stripslashes($returned_tag->tag)."\">" . stripslashes($returned_tag->tag) . "</a> <br />";
				}
			}
			$run_result .= "</p>";
			
		}
		
	}

?>