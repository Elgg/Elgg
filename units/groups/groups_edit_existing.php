<?php

	// Display existing groups

		$groupdata = run("groups:get", array($_SESSION['userid']));
		if (sizeof($groupdata) > 0) {

			$body = <<< END
		<h2>Groups you own</h2>
END;
						
			foreach($groupdata as $group) {
				
				$body .= run("groups:edit:display",array($group));
				
			}
			
			$run_result .= $body;
			
		}

?>