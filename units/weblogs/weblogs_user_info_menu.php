<?php

		global $page_owner;
		$profile_id = $page_owner;
	
		$posts = db_query("select count(ident) as x from weblog_posts where (".run("users:access_level_sql_where",$profile_id).") and owner = $profile_id");
		$posts = $posts[0]->x;
		
		if (logged_on || (isset($page_owner) && $page_owner != -1)) {
	
			if ($_SESSION['userid'] == $profile_id) {
				$title = "Your Weblog";
			} else {
				$title = "Weblog";
			}
	
			$weblog_username = run("users:id_to_name",$profile_id);
			$body = <<< END
			<p align="center">
				<a href="/{$weblog_username}/weblog/">Personal Weblog</a> (<a href="/{$weblog_username}/weblog/rss">RSS</a>)<br />
				<a href="/{$weblog_username}/weblog/archive/">Weblog Archive</a><br />
				<a href="/{$weblog_username}/weblog/friends/">Friends Weblog</a>
			</p>
END;

			$run_result .= "<div class=\"box_weblog\">";
			$run_result .= run("templates:draw", array(
									'context' => 'infobox',
									'name' => $title,
									'contents' => $body
								)
								);
			$run_result .= "</div>";

		}

?>