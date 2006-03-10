<?php

	// $parameter = the ID number of the user
	
	// Convert $parameter to an integer, see if it exists
		$parameter = (int) $parameter;
		
	// Get all subscriptions
		$subscriptions = db_query("select ident, url, last_updated from feeds where last_updated <= (UNIX_TIMESTAMP() - 0)");
		if (sizeof($subscriptions > 0)) {
			foreach($subscriptions as $subscription) {
				run("rss:update",$subscription->ident);
				echo stripslashes($subscription->url) . " checked (last updated ".date("l dS \of F Y h:i:s A",$subscription->last_updated).")<br />";
			}
		}

?>