<?php

		global $page_owner;
		$profile_id = $page_owner;
	
		$posts = db_query("select count(ident) as x from files where (".run("users:access_level_sql_where",$profile_id).") and owner = $profile_id");
		$posts = $posts[0]->x;
		
		if ($posts > 0) {
	
			if ($_SESSION['userid'] == $profile_id) {
				$title = "Your Files";
			} else {
				$title = "Files";
			}
	
			$weblog_username = run("users:id_to_name",$profile_id);
			$body = <<< END
			<p align="center">
				<a href="/{$weblog_username}/files/">File Storage</a>
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