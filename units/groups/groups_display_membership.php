<?php

	// Display existing groups

		$groupdata = run("groups:get:external", array($_SESSION['userid']));
		if (sizeof($groupdata) > 0) {
                     $header = gettext("Group membership"); // gettext variable
			$body = <<< END
		<h2>$header</h2>
END;
						
			foreach($groupdata as $group) {
				$body .= run("templates:draw", array(
														'context' => 'databox1',
														'name' => $group->name,
														'column1' => sprintf(gettext("Owned by %s"),$group->ownername)
													)
													);
			}
			
			$run_result .= $body;
			
		}

?>