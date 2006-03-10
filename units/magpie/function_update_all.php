<?php

	// $parameter = the ID number of the user
	
	// Convert $parameter to an integer, see if it exists
		$parameter = (int) $parameter;
		
	// Get all subscriptions
		$subscriptions = db_query("select feed_id from feed_subscriptions where user_id = $parameter");
		if (sizeof($subscriptions > 0)) {
			foreach($subscriptions as $subscription) {
				run("rss:update",$subscription->feed_id);
			}
		}

?>