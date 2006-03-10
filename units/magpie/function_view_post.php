<?php

	if (isset($parameter)) {
		
		$post = $parameter;
		
		global $prevdate;
		
		if (!isset($prevdate)) {
			$prevdate = "";
		}
		
		$author = "";
		$usericon = "default.png";
		$post_authors[$post->owner] = $author;
		
		// $date = stripslashes($post->posted);
		$date = date("F j, Y",$post->added);
		if ($date != $prevdate) {
			$run_result .= "<div class=\"feed_date\"><h2>" . $date . "</h2></div>";

		}
		$prevdate = $date;
		
		if (trim($date) == "") {
			$date = gettext("unknown");
		}
		// $date = stripslashes($post->added);
		
		$fullname =  DoWordEntities(stripslashes($post->name));
		$tagline = DoWordEntities(stripslashes($post->tagline));
		$title = DoWordEntities(stripslashes($post->title));

		$body = stripslashes($post->body);
		
		$controls = "";
		
		$controls .= "<a href=\"".$post->siteurl."\">[" . gettext("View site") . "]</a><br /><br />";
		
		if (logged_on) {
			if (run("rss:subscribed",$post->feed)) {
				$controls .= "<a href=\"".url."_rss/subscriptions.php?action=unsubscribe&amp;feed=".$post->feed."\" onClick=\"return confirm('".gettext("Are you sure you want to unsubscribe from this feed?")."')\">[" . gettext("Unsubscribe") . "]</a>";
			} else {
				$controls .= "<a href=\"".url."_rss/subscriptions.php?action=subscribe&amp;feed=".$post->feed."\" onClick=\"return confirm('".gettext("Are you sure you want to subscribe to this feed?")."')\">[" . gettext("Subscribe") . "]</a>";
			}
		}
		
		$run_result .= run("templates:draw", array(
								'context' => 'rsspost',
								'usericon' => $usericon,
								'body' => $body,
								'fullname' => $fullname,
								'title' => $title,
								'sitelink' => $post->siteurl,
								'feedlink' => url."_rss/individual.php?feed=".$post->feed,
								'link' => $post->url,
								'tagline' => $tagline,
								'controls' => $controls
							)
							);
	}

?>