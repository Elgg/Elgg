<?php

	//	ELGG weblog view page

	// Run includes
		require("../includes.php");
		
		run("profile:init");
		run("friends:init");
		run("weblogs:init");
		
		global $profile_id;
		global $individual;
		
		$individual = 1;
		
		if (isset($_REQUEST['post'])) {
			
			$post = (int) $_REQUEST['post'];
			
			$where = run("users:access_level_sql_where",$_SESSION['userid']);
			$post = db_query("select * from weblog_posts where ($where) and ident = $post");
			$post = $post[0];
			
			global $page_owner;
			global $profile_id;
			$profile_id = $post->owner;
			$page_owner = $post->owner;
			
			$title = run("profile:display:name") . " :: Weblog :: " . stripslashes($post->title);
			
			$time = gmdate("F d, Y",$post->posted);
			$body = "<h2 class=\"weblogdateheader\">$time</h2>\n";
			
			$body .= run("weblogs:posts:view",$post);
			
			$body = run("templates:draw", array(
							'context' => 'infobox',
							'name' => $title,
							'contents' => $body
						)
						);
			
			echo run("templates:draw:page", array(
							$title, $body
						)
						);

		}

?>