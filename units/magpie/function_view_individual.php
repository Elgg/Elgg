<?php

	// $parameter = the ID number of the feed
	
	// Convert $parameter to an integer, see if it exists
		$parameter = (int) $parameter;
		
	// If the feed offset hasn't been set, it's 0
		if (!isset($_REQUEST['feed_offset'])) {
			$feed_offset = 0;
		} else {
			$feed_offset = $_REQUEST['feed_offset'];
		}
		$feed_offset = (int) $feed_offset;

		$numposts = db_query("select count(*) as num from feed_posts join feeds on feeds.ident = feed_posts.feed where feeds.ident = $parameter");
		$numposts = $numposts[0]->num;
		
		$posts = db_query("select feed_posts.*, feeds.name, feeds.siteurl, feeds.tagline from feed_posts join feeds on feeds.ident = feed_posts.feed where feeds.ident = $parameter order by feed_posts.added desc, feed_posts.ident asc limit 25 offset " . $feed_offset);
		
		if (sizeof($posts) > 0) {
			foreach($posts as $post) {

				$run_result .= run("rss:view:post",$post);
				
			}
		}
		
		$url = url;
		
		if ($numposts - ($feed_offset + 25) > 0) {
			$display_feed_offset = $feed_offset + 25;
			$back = gettext("Back");
			$run_result .= <<< END
				
				<a href="{$url}_rss/individual.php?feed={$parameter}&amp;feed_offset={$display_feed_offset}">&lt;&lt; $back</a>
				
END;
		}
		if ($feed_offset > 0) {
			$display_feed_offset = $feed_offset - 25;
			if ($display_feed_offset < 0) {
				$display_feed_offset = 0;
			}
			$next = gettext("Next");
			$run_result .= <<< END
				
				<a href="{$url}_rss/individual.php?feed={$parameter}&amp;feed_offset={$display_feed_offset}">$next &gt;&gt;</a>
				
END;
		}
		
?>