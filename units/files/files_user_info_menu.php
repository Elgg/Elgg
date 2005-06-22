<?php

		global $page_owner;
		$profile_id = $page_owner;
		$url = url;
		
		if (logged_on && $page_owner != -1) {
		
			$posts = db_query("select count(ident) as x from files where (".run("users:access_level_sql_where",$profile_id).") and files_owner = $profile_id");
			$posts = $posts[0]->x;
		
			if ($_SESSION['userid'] == $profile_id) {
				$title = "Your Files";
			} else {
				$title = "Files";
			}
	
			if ($posts == 1) {
				$filesstring = $posts . " file";
			} else {
				$filesstring = $posts . " files";
			}
			
			$weblog_username = run("users:id_to_name",$profile_id);
			$body = <<< END
		<p align="center">
			<a href="{$url}{$weblog_username}/files/">File Storage</a> ($filesstring)<br />
			(<a href="{$url}{$weblog_username}/files/rss/">RSS</a>)
		</p>
END;

			$run_result .= "<div class=\"box_files\">";
			$run_result .= run("templates:draw", array(
									'context' => 'infobox',
									'name' => $title,
									'contents' => $body
								)
								);
			$run_result .= "</div>";

		}
		
?>