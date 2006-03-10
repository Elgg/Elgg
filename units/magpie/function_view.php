<?php

	// $parameter = the ID number of the user
	
	// Convert $parameter to an integer, see if it exists
		$parameter = (int) $parameter;

	// If the feed offset hasn't been set, it's 0
		if (!isset($_REQUEST['feed_offset'])) {
			$feed_offset = 0;
		} else {
			$feed_offset = (int) $_REQUEST['feed_offset'];
		}

		$numposts = db_query("select count(*) as num from feed_subscriptions join feed_posts on feed_posts.feed = feed_subscriptions.feed_id where feed_subscriptions.user_id = $parameter");
		$numposts = $numposts[0]->num;
		
		$posts = db_query("select feed_posts.*, feeds.name, feeds.siteurl, feeds.tagline, feeds.url as feedurl from feed_subscriptions join feed_posts on feed_posts.feed = feed_subscriptions.feed_id join feeds on feeds.ident = feed_subscriptions.feed_id where feed_subscriptions.user_id = $parameter order by feed_posts.added desc limit 25 offset $feed_offset");
		
		if (sizeof($posts) > 0) {
			foreach($posts as $post) {

				$run_result .= run("rss:view:post",$post);
				
			}
		}
		
		$url = url;
		$profile_name = htmlentities(stripslashes($_REQUEST['profile_name']));
		
		if ($numposts - ($feed_offset + 25) > 0) {
			$display_feed_offset = $feed_offset + 25;
			$back = gettext("Back");
			$run_result .= <<< END
				
				<a href="{$url}{$profile_name}/feeds/all/skip={$display_feed_offset}">&lt;&lt; $back</a>
				
END;
		}
		if ($feed_offset > 0) {
			$display_feed_offset = $feed_offset - 25;
			if ($display_feed_offset < 0) {
				$display_feed_offset = 0;
			}
			$next = gettext("Next");
			$run_result .= <<< END
				
				<a href="{$url}{$profile_name}/feeds/all/skip={$display_feed_offset}">$next &gt;&gt;</a>
				
END;
		}
		
?>