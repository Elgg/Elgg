<?php

	if (run("permissions:check", "profile") && context == "profile") {

		global $page_owner;
		
			$run_result .= run("templates:draw", array(
							'context' => 'submenuitem',
							'name' => gettext("Edit this profile"),
							'location' => url . "profile/edit.php?profile_id=$page_owner"
						)
						);
						
			if (run("users:type:get", $page_owner) == "person") {
				$run_result .= run("templates:draw", array(
									'context' => 'submenuitem',
									'name' => gettext("Change site picture"),
									'location' => url . '_icons/?context=profile&amp;profile_id=' . $page_owner
								)
								);
			}

			$run_result .= run("templates:draw", array(
							'context' => 'submenuitem',
							'name' => gettext("Page help"),
							'location' => url . 'help/profile_help.php'
						)
						);


	}
					
?>