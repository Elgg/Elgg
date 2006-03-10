<?php

		global $page_owner;
		$profile_id = $page_owner;
		$url = url;
		
		if ($page_owner != -1 && $page_owner != $_SESSION['userid']) {
		
			$posts = db_query("select count(*) as x from files where (".run("users:access_level_sql_where",$profile_id).") and files_owner = $profile_id");
			$posts = $posts[0]->x;
		
			if ($_SESSION['userid'] == $profile_id) {
				$title = gettext("Your Files");
			} else {
				$title = gettext("Files");
			}
	
			if ($posts == 1) {
				$filesstring = $posts . " file";
			} else {
				$filesstring = $posts . " files";
			}
			
			$weblog_username = run("users:id_to_name",$profile_id);
			$fileStorage = gettext("File Storage"); // gettext variable
			$body = <<< END
		<ul>
			<li><a href="{$url}{$weblog_username}/files/">$fileStorage</a> ($filesstring)</li>
			<li>(<a href="{$url}{$weblog_username}/files/rss/">RSS</a>)</li>
		</ul>
END;

			$run_result .= "<li id=\"sidebar_files\">";
			$run_result .= run("templates:draw", array(
									'context' => 'sidebarholder',
									'title' => $title,
									'body' => $body
								)
								);
			$run_result .= "</li>";

		}
		
?>