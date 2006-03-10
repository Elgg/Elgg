<?php

	global $page_owner;

	if (logged_on) {
		
		global $rss_subscriptions;
		run("rss:subscriptions:get");
		run("rss:update:all",$page_owner);
		
		if ($_SESSION['userid'] == $page_owner) {
			$body .= "<p>". gettext("Feeds are information streams from other sites. You will often see a link to an 'RSS' feed while browsing; enter the link address into the 'add feed' box at the bottom of the page to read that information from within your learning landscape.") . "</p>";
			$body .= "<p>". gettext("Click a box below to automatically import content from a feed into your blog. You can also add default keywords for content from that feed. (You should only do this if you have the legal right to use this resource.)") . "</p>";
		}
		
		$feed_subscriptions = db_query("select feed_subscriptions.ident as subid, feed_subscriptions.autopost, feed_subscriptions.autopost_tag, feeds.* from feed_subscriptions join feeds on feeds.ident = feed_subscriptions.feed_id where feed_subscriptions.user_id = ". $page_owner . " order by feeds.name asc");
		if (sizeof($feed_subscriptions) > 0) {
			
			if (run("permissions:check", "profile")) {
				$body .= "<form action=\"\" method=\"post\" >";
			}
			
			$body .= run("templates:draw", array(
															'context' => 'adminTable',
															'name' => "<b>" . gettext("Last updated") . "</b>",
															'column1' => "<b>" . gettext("Resource name") . "</b>",
															'column2' => "&nbsp;"
														)
														);
			
				foreach($feed_subscriptions as $feed) {
					
						if (run("permissions:check", "profile")) {
							$name = "<input type=\"checkbox\" name=\"feedautopost[]\" value=\"" . $feed->subid . "\" ";
							if ($feed->autopost == "yes") {
								$name .= " checked=\"checked\"";
								
							}
							$name .= " />";
						}
						$name .= "<a href=\"".$feed->siteurl."\">" . stripslashes($feed->name) . "</a>";
						if (run("permissions:check", "profile")) {
							$name .= "<br />";
							$name .= gettext("Keywords: ") . "<input type=\"text\" name=\"keywords[" . $feed->subid . "]\" value=\"" . htmlentities(stripslashes($feed->autopost_tag)) . "\" />";
						}
						
						$column2 = "<a href=\"".url."_rss/individual.php?feed=".$feed->ident."\">". gettext("View content") . "</a>";
						if (run("permissions:check", "profile")) {
							$column2 .= " | <a href=\"".url."_rss/subscriptions.php?action=unsubscribe&amp;feed=".$feed->ident."&amp;profile_id=$page_owner\" onClick=\"return confirm('".gettext("Are you sure you want to unsubscribe from this feed?")."')\">" . gettext("Unsubscribe") . "</a>";
						}
						
						$body .= run("templates:draw", array(
																	'context' => 'adminTable',
																	'name' => date("F j, Y, g:i a",$feed->last_updated),
																	'column1' => $name,
																	'column2' => $column2
																)
																);
																
					}
					
					if (run("permissions:check", "profile")) {
						
						$body .= run("templates:draw", array(
															'context' => 'adminTable',
															'name' => "<input type=\"hidden\" name=\"action\" value=\"rss:subscriptions:update\" />",
															'column1' => "<input type=\"submit\" value=\"" . gettext("Update") . "\" />",
															'column2' => ""
														)
														);
						
						$body .= "</form>";
					}
					
			} else {
				if ($_SESSION['userid'] == $page_owner) {
					$body .= "<p>" . gettext("You are not subscribed to any feeds.") . "</p>";
				} else {
					$body .= "<p>" . gettext("No feeds were found.") . "</p>";
				}
			}
			
			if (run("permissions:check", "profile")) {
				$body .= "<p>". gettext("To subscribe to a new feed, enter its address below:") . "</p>";
				$body .= "<form action=\"\" method=\"post\">";
				$body .= run("templates:draw", array(
																	'context' => 'adminTable',
																	'name' => "&nbsp;",
																	'column1' => "<input type=\"text\" name=\"url\" value=\"http://\" style=\"width: 100%\" />",
																	'column2' => "<input type=\"submit\" value=\"".gettext("Subscribe") . "\" />"
																)
																);
				$body .= "<input type=\"hidden\" name=\"action\" value=\"subscribe-new\" /></form>";
			}
			$run_result .= $body;
		
	}

?>