<?php


	if (logged_on && isset($_REQUEST['action']) && run("permissions:check", "profile")) {
		
		global $page_owner;
		
		if ($page_owner != $_SESSION['userid']) {
			$page_username = db_query("select username from users where ident = $page_owner");
			$page_username = $page_username->username;
		} else {
			$page_username = $_SESSION['username'];
		}
		
		switch($_REQUEST['action']) {
			
			// Subscribe to an existing feed
				case "subscribe":						if (isset($_REQUEST['feed']) && !run("rss:subscribed",$_REQUEST['feed'])) {
															$feed = $_REQUEST['feed'];
															db_query("insert into feed_subscriptions set feed_id = $feed, user_id = $page_owner");
															$messages[] = gettext("Your feed subscription was successful.");
														} else {
															$messages[] = gettext("Feed subscription failed: you are already subscribed to this feed.");
														}
														break;
			// Unsubscribe from an existing feed
				case "unsubscribe":						if (isset($_REQUEST['feed']) && (run("rss:subscribed",$_REQUEST['feed']))) {
															$feed = $_REQUEST['feed'];
															db_query("delete from feed_subscriptions where feed_id = $feed and user_id = $page_owner");
															$messages[] = gettext("Your have successfully removed this feed from your subscriptions.");
														} else {
															$messages[] = gettext("Feed unsubscription failed: you are not subscribed to this feed.");
														}
														break;
				case "subscribe-new":					if (isset($_REQUEST['url'])) {
															$url = trim($_REQUEST['url']);
															if (substr($url,0,7) != "http://") {
																$url = "http://" . $url;
															}
															// $url = str_replace("@","",$url);
															$feed_exists = db_query("select * from feeds where url = '$url'");
															if (sizeof($feed_exists) > 0) {
																$feed_exists = $feed_exists[0]->ident;
																if (!run("rss:subscribed",$feed_exists)) {
																	db_query("insert into feed_subscriptions set feed_id = $feed_exists, user_id = $page_owner");
																	$messages[] = gettext("Your feed subscription was successful.");
																} else {
																	$messages[] = gettext("Feed subscription failed: this feed subscription already exists.");
																}
															} else if ($rss = @file_get_contents($url)) {
																
																if (substr_count($rss,"<channel") > 0 || substr_count($rss,"<feed") > 0) {
																	db_query("insert into feeds set url = '$url'");
																	$ident = db_id();
																	db_query("insert into feed_subscriptions set feed_id = $ident, user_id = $page_owner");
																	$messages[] = gettext("Your feed subscription was successful.");
																} else {
																	$messages[] = gettext("Feed subscription failed: feed appears to be invalid. Please check your link or try later.");
																}
																
															} else {
																$messages[] = gettext("Feed subscription failed: could not get feed. Please check your link or try later.");
															}
														}
														break;
				case "rss:subscriptions:update":		if (isset($_REQUEST['keywords'])) {
															db_query("update feed_subscriptions set autopost = 'no' where user_id = '$page_owner'");
															if (is_array($_REQUEST['keywords']) && sizeof($_REQUEST['keywords']) > 0) {
																foreach($_REQUEST['keywords'] as $key => $keyword_set) {
																	$keyword_set = trim($keyword_set);
																	if (strlen($keyword_set) > 128) {
																		$keyword_set = substr($keyword_set, 0, 128);
																	}
																	$key = (int) $key;
																	db_query("update feed_subscriptions set autopost_tag = \"".addslashes($keyword_set)."\" where ident = $key and user_id = $page_owner");
																}
															}
															if (isset($_REQUEST['feedautopost']) && is_array($_REQUEST['feedautopost']) && sizeof($_REQUEST['feedautopost']) > 0) {
																foreach($_REQUEST['feedautopost'] as $autopost) {
																	$autopost = (int) $autopost;
																	$feedurl = db_query("select feeds.url from feed_subscriptions left join feeds on feeds.ident = feed_subscriptions.feed_id where feed_subscriptions.ident = $autopost");
																	if (is_array($feedurl) && sizeof($feedurl) > 0) {
																		$feedurl = $feedurl[0]->url;
																		if (substr_count($feedurl,url) > 0 && substr_count($feedurl,"/".$page_username."/") > 0) {
																			$messages[] = gettext("Feed not imported to blog: You cannot import a feed from this account.");
																			echo $feedurl;
																		} else {
																			db_query("update feed_subscriptions set autopost = 'yes' where ident = $autopost and user_id = $page_owner");
																		}
																	}
																}
															}
															$messages[] = "Your changes were saved.";
														}
			
		}
		
	}

?>