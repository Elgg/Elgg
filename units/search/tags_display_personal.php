<?php

	// Display a user's tags

		global $page_owner;
		
		$searchline = "(" . run("users:access_level_sql_where",$page_owner) . ")";
		$searchline = str_replace("access","tags.access", $searchline);
		$tags = db_query("select distinct tag, count(ident) as number from tags where $searchline and tags.owner = $page_owner group by tags.tag order by tags.tag asc");
		if (sizeof($tags) > 0) {
			
			$max = 0;
			foreach($tags as $tag) {
				if ($tag->number > $max) {
					$max = $tag->number;
				}
			}
			foreach($tags as $tag) {
				if ($tag->number > ($max * 0.5)) {
					$size = "200%";
				} else if ($tag->number > ($max * 0.35)) {
					$size = "170%";
				} else if ($tag->number > 4) {
					$size = "140%";
				} else if ($tag->number > 1) {
					$size = "100%";
				} else {
					$size = "80%";
				}
				$tag->tag = stripslashes($tag->tag);
				$run_result .= "<a href=\"".url."/search/index.php?all=".urlencode(htmlentities(strtolower(($tag->tag))))."&owner=$page_owner\" style=\"font-size: $size\" title=\"".htmlentities($tag->tag)." (" .$tag->number. ")\">";
				$run_result .= $tag->tag . "</a> ";
			}
			
		} else {
			$run_result = "<p>" . gettext("No tags found for this user.") . "</p>";
		}

?>