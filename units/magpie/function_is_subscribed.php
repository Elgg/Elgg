<?php

	$run_result = false;

	if (logged_on && isset($parameter)) {

		global $page_owner;
		$parameter = (int) $parameter;
		
		if ($page_owner == $_SESSION['userid']) {
		
			global $rss_subscriptions;
			
			run("rss:subscriptions:get");
			
			$run_result = in_array($parameter, $rss_subscriptions);
		
		} else if (run("permissions:check", "profile")) {
			
			$result = db_query("select count(*) as numsubs from feed_subscriptions where user_id = $page_owner and ident = $parameter");
			if ($result != false && sizeof($result) > 0) {
				
				if ($result[0]->numsubs > 0) {
					
					$run_result = true;
					
				}
				
			}
			
		}

	}
		
?>