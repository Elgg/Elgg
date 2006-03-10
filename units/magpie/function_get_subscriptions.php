<?php

	if (logged_on) {

		global $rss_subscriptions;
		$parameter = (int) $parameter;
		
		if (!isset($rss_subscriptions)) {
			$rss_subscriptions = array();
			$subscriptions_var = db_query("select * from feed_subscriptions where user_id = " . $_SESSION['userid']);
			if (sizeof($subscriptions_var) > 0) {
				foreach($subscriptions_var as $subscription) {
					$rss_subscriptions[] = $subscription->feed_id;
				}
			}
			
		}
		$run_result = $rss_subscriptions;
	}

?>