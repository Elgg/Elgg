<?php

	if (substr_count($parameter, "group") > 0 && logged_on) {
		$groupnum = (int) substr($parameter, 5, 15);
		$result = db_query("select ident from group_membership where user_id = " . $_SESSION['userid'] . "
													   and group_id = $groupnum");
		if (sizeof($result) > 0) {
			$run_result = true;
		} else {
			
			$result = db_query("select ident from groups where ident = $groupnum and owner = " . $_SESSION['userid']);
			if (sizeof($result) > 0) {
				$run_result = true;
			}
			
		}
	}

?>