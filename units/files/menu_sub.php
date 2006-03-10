<?php

	if (context == "files") {
		
		global $page_owner;
		$files_username = run("users:id_to_name",$page_owner);
		
		if ($page_owner == $_SESSION['userid'] && $page_owner != -1) {
		
			$run_result .= run("templates:draw", array(
								'context' => 'submenuitem',
								'name' => gettext("Add a file or a folder"),
								'location' =>  "#addFile"

							)
							);

		}
		if ($page_owner != -1) {
			$run_result .= run("templates:draw", array(
								'context' => 'submenuitem',
								'name' => gettext("RSS feed for files"),
								'location' =>  url . $files_username . "/files/rss/"
							)
							);
		}

		$run_result .= run("templates:draw", array(
							'context' => 'submenuitem',
							'name' => gettext("Page help"),
							'location' =>  url . "help/files_help.php"

						)
						);

	}

?>