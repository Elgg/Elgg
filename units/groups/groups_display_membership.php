<?php

	// Display existing groups

		$groupdata = run("groups:get:external", array($_SESSION['userid']));
		if (sizeof($groupdata) > 0) {

			$body = <<< END
		<h2>Group membership</h2>
END;
						
			foreach($groupdata as $group) {
				$body .= run("templates:draw", array(
														'context' => 'databox1',
														'name' => $group->name,
														'column1' => "Owned by " . $group->ownername
													)
													);
			}
			
			$run_result .= $body;
			
		}

?>