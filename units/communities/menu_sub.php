<?php

	global $page_owner;

	if (context == "network") {
		
		if (run("users:type:get", $page_owner) == "person") {
			$run_result .= run("templates:draw", array(
								'context' => 'submenuitem',
								'name' => gettext("Communities"),
								'location' => url . '_communities/?owner=' . $page_owner
							)
							);
							
			$run_result .= run("templates:draw", array(
								'context' => 'submenuitem',
								'name' => gettext("Owned Communities"),
								'location' => url . '_communities/owned.php?owner=' . $page_owner
							)
							);
		} else if (run("users:type:get", $page_owner) == "community") {
			
			$run_result .= run("templates:draw", array(
								'context' => 'submenuitem',
								'name' => gettext("Community Members"),
								'location' => url . '_communities/members.php?owner=' . $page_owner
							)
							);
			
		}
		
	}
	if (context == "profile" && run("users:type:get", $page_owner) == "community") {
		
		if (run("permissions:check", "profile")) {
			$run_result .= run("templates:draw", array(
									'context' => 'submenuitem',
									'name' => gettext("Community site picture"),
									'location' => url . '_icons/?context=profile&profile_id=' . $page_owner
								)
								);
			$run_result .= run("templates:draw", array(
									'context' => 'submenuitem',
									'name' => gettext("Edit community details"),
									'location' => url . '_userdetails/?context=profile&profile_id=' . $page_owner
								)
								);
		}
		
	}

?>