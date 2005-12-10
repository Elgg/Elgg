<?php

	if (context == "network") {

		global $page_owner;
		
		if (run("users:type:get", $page_owner) == "person") {
		
			$friends_username = run("users:id_to_name",$page_owner);
			
			$run_result .= run("templates:draw", array(
								'context' => 'submenuitem',
								'name' => gettext("Friends"),
								'location' => url . $friends_username . '/friends/'
							)
							);
			
			$run_result .= run("templates:draw", array(
								'context' => 'submenuitem',
								'name' => gettext("Friend of"),
								'location' => url . '_friends/friendsof.php?owner=' . $page_owner
							)
							);

			$run_result .= run("templates:draw", array(
								'context' => 'submenuitem',
								'name' => gettext("FOAF"),
								'location' => url . $friends_username . '/foaf/'
							)
							);

		}
					
	}

?>