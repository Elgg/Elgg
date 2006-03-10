<?php

	global $page_owner;
	
	$weblog_username = run("users:id_to_name",$page_owner);
	
	if (context=="weblog") {
		
		if ($page_owner != -1) {
			
			if (run("permissions:check", "weblog") && logged_on) {
				
				$run_result .= run("templates:draw", array(
							'context' => 'submenuitem',
							'name' => gettext("Post a new entry"),
							'location' =>  url . "_weblog/edit.php?owner=" . $page_owner
						)
						);
					
			}
			
			$run_result .= run("templates:draw", array(
								'context' => 'submenuitem',
								'name' => gettext("View blog"),
								'location' =>  url . $weblog_username . "/weblog/"
							)
							);
			
			$run_result .= run("templates:draw", array(
								'context' => 'submenuitem',
								'name' => gettext("RSS feed"),
								'location' =>  url . $weblog_username . "/weblog/rss/"
							)
							);
			
			$run_result .= run("templates:draw", array(
								'context' => 'submenuitem',
								'name' => gettext("Archive"),
								'location' =>  url . $weblog_username . "/weblog/archive/"
							)
							);
			
			$run_result .= run("templates:draw", array(
							'context' => 'submenuitem',
							'name' => gettext("Friends blogs"),
							'location' =>  url . $weblog_username . "/weblog/friends/"
						)
						);
			
		}
		
		$run_result .= run("templates:draw", array(
						'context' => 'submenuitem',
						'name' => gettext("View all posts"),
						'location' =>  url . "_weblog/everyone.php"
					)
					);
		
		$run_result .= run("templates:draw", array(
						'context' => 'submenuitem',
						'name' => gettext("Page help"),
						'location' => url . 'help/blogs_help.php'
					)
					);
		
	}

?>