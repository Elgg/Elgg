<?php

	if (context == "admin" && logged_on && run("users:flags:get",array("admin", $_SESSION['userid']))) {
		
		$run_result .= run("templates:draw", array(
					'context' => 'submenuitem',
					'name' => gettext("Main"),
					'location' =>  url . "_admin/"
				)
				);
				
		$run_result .= run("templates:draw", array(
					'context' => 'submenuitem',
					'name' => gettext("Add users"),
					'location' =>  url . "_admin/users_add.php"
				)
				);
				
		$run_result .= run("templates:draw", array(
					'context' => 'submenuitem',
					'name' => gettext("Manage users"),
					'location' =>  url . "_admin/users.php"
				)
				);
				
		$run_result .= run("templates:draw", array(
					'context' => 'submenuitem',
					'name' => gettext("Manage flagged content"),
					'location' =>  url . "_admin/flags.php"
				)
				);
				
		$run_result .= run("templates:draw", array(
					'context' => 'submenuitem',
					'name' => gettext("Spam control"),
					'location' =>  url . "_admin/antispam.php"
				)
				);
			
	}

?>