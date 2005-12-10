<?php

	// Display popular tags
	
		$run_result .= "<p>" . gettext("The following is a selection of keywords used within this site. Click one to see related users, weblog posts or objects.") . "</p>";
	
		$searchline = "(" . run("users:access_level_sql_where",$_SESSION['userid']) . ")";
		$tags = db_query("select distinct tag, count(ident) as number from tags where tags.access = 'PUBLIC' group by tag order by rand() limit 200");
		if (sizeof($tags) > 0) {
			
			$max = 0;
			foreach($tags as $tag) {
				if ($tag->number > $max) {
					$max = $tag->number;
				}
			}
			foreach($tags as $tag) {
				if ($tag->number > ($max * 0.5)) {
					$size = "160%";
				} else if ($tag->number > ($max * 0.35)) {
					$size = "140%";
				} else if ($tag->number > 4) {
					$size = "120%";
				} else if ($tag->number > 1) {
					$size = "100%";
				} else {
					$size = "80%";
				}
				$tag->tag = stripslashes($tag->tag);
				$run_result .= "<a href=\"".url."tag/".urlencode(htmlentities(strtolower(($tag->tag))))."\" style=\"font-size: $size\" title=\"".htmlentities($tag->tag)." (" .$tag->number. ")\">";
				$run_result .= $tag->tag . "</a> ";
			}
			
		}

?>