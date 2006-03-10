<?php
	
	global $page_owner;
	
	$calendar_username = run("users:id_to_name",$page_owner);
	
	if (context=="calendar") {
	
		if ($page_owner != -1) {
			
			if (logged_on) {
				//TODO need access control for this
					
				$run_result .= run("templates:draw", array(
							'context' => 'submenuitem',
							'name' => str_replace(" ", "&nbsp;", gettext("Post a new event")),
							'location' =>  url . "_calendar/add_event.php"
						)
						);
				
			}
			
			$run_result .= run("templates:draw", array(
								'context' => 'submenuitem',
								'name' => str_replace(" ", "&nbsp;", gettext("View calendar")),
								'location' =>  url . $calendar_username . "/calendar/"
							)
							);
			
			$run_result .= run("templates:draw", array(
								'context' => 'submenuitem',
								'name' => gettext("RSS feed"),
								'location' =>  url . $calendar_username . "/calendar/rss/"
							)
							);
			
			$run_result .= run("templates:draw", array(
								'context' => 'submenuitem',
								'name' => gettext("Archive"),
								'location' =>  url . $calendar_username . "/calendar/archive/"
							)
							);
			
			$run_result .= run("templates:draw", array(
							'context' => 'submenuitem',
							'name' => str_replace(" ", "&nbsp;", gettext("Friends calendars")),
							'location' =>  url . $calendar_username . "/calendar/friends/"
						)
						);
						
			$run_result .= run("templates:draw", array(
							'context' => 'submenuitem',
							'name' => str_replace(" ", "&nbsp;", gettext("Community calendars")),
							'location' =>  url . $calendar_username . "/calendar/communities/"
						)
						);
						
			$run_result .= run("templates:draw", array(
							'context' => 'submenuitem',
							'name' => str_replace(" ", "&nbsp;", gettext("Import calendar")),
							'location' =>  url . $calendar_username . "/calendar/import/"
						)
						);
			
		}
		
		$run_result .= run("templates:draw", array(
							'context' => 'submenuitem',
							'name' => gettext("Page help"),
							'location' => url . 'help/calendar_help.php'
						)
						);
		
		
	}
?>