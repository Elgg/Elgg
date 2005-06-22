<?php

		global $page_owner;
		$profile_id = $page_owner;
        $sitename = sitename;
        $url = url;

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
END;
				if (run("users:type:get",$page_owner) == "person") {
					$body .= <<< END
				<a href="{$url}{$weblog_username}/weblog/">Personal Weblog</a> (<a href="{$url}{$weblog_username}/weblog/rss">RSS</a>)<br />
END;
				} else if (run("users:type:get",$page_owner) == "community") {
					$body .= <<< END
				<a href="{$url}{$weblog_username}/weblog/">Community Weblog</a> (<a href="{$url}{$weblog_username}/weblog/rss">RSS</a>)<br />
END;
				}
				$body .= <<< END
				<a href="{$url}{$weblog_username}/weblog/archive/">Weblog Archive</a><br />
				<a href="{$url}{$weblog_username}/weblog/friends/">Friends Weblog</a>
			</p>
			<p align="center">
                           <a href="{$url}_weblog/everyone.php">All Weblog Posts</a>
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