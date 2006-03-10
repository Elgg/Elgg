<?php

	// $parameter = the ID number of the feed
	
	// Convert $parameter to an integer, see if it exists
		$parameter = (int) $parameter;
		
	// Check database, get feed items
		$feed = db_query("select * from feeds where ident = $parameter");
		$subscribers = db_query("select count(*) as num from feed_subscriptions where feed_id = $parameter");
		$subscribers = $subscribers[0]->num;
						
		if (sizeof($feed) > 0 && $subscribers > 0) {
			
			if ($subscribers > 10) {
				$update_time = 3600;
			} else if ($subscribers > 5) {
				$update_time = 4800;
			} else if ($subscribers > 1) {
				$update_time = 7200;
			} else {
				$update_time = 14400;
			}

			$feed = $feed[0];
			if ($feed->last_updated < (time() - $update_time)) {
				
				db_query("update feeds set last_updated = " . time() . " where ident = $parameter");
				if ($rss = run("rss:get", $feed->url)) {
					
					$feedtitle = stripslashes($rss->channel['title']);
					$feedtagline = stripslashes($rss->channel['tagline']);
					if (strlen($feedtagline) > 120) {
						$feedtagline = "";
					}
					$feedurl = stripslashes($rss->channel['link']);
					
					db_query("update feeds set siteurl = '$feedurl', name = '". addslashes($feedtitle) . "', tagline = '" . addslashes($feedtagline) . "' where ident = $parameter");
					
					$feeditemstemp = db_query("select url from feed_posts where feed = $parameter");
					$feeditems = array();
					if (sizeof($feeditemstemp) > 0) {
						foreach($feeditemstemp as $feeditem) {
							$feeditems[] = stripslashes($feeditem->url);
						}
					}
					unset($feeditemstemp);
										
					if (sizeof($rss->items > 0)) {
						foreach($rss->items as $item) {
							$title = stripslashes($item['title']);
							$description = stripslashes($item['description']);
							if (isset($item['atom_content'])) {
								$description = stripslashes($item['atom_content']);
							}
							$posted = stripslashes($item['pubdate']);
							if (isset($item['dc']['date'])) {
								$posted = stripslashes($item['dc']['date']);
							}
							if (isset($item['issued'])) {
								$posted = stripslashes($item['issued']);
							}
							$posted = str_replace("T"," ",$posted);
							$posted = str_replace("Z"," ",$posted);
							$posted = str_replace("GM"," ",$posted);
							$posted = str_replace("ES"," ",$posted);
							$posted = str_replace("PS"," ",$posted);
							$posted = str_replace("ue","Tue",$posted);
							$posted = str_replace("hu","Thu",$posted);
							$posted = preg_replace('/(\d\d\d\d)\-(\d\d)\-(\d\d)/','$1/$2/$3',$posted);
							$posted = preg_replace('/(\-.*)/','',$posted);
							
							$url = stripslashes($item['link']);
							if (!($added = @strtotime($posted)) || $posted == "") {
								$added = time();
							}
							if ($added > time() || $added == -1) {
								$added = time();
							}
							
							if (in_array($url,$feeditems)) {
								db_query("update feed_posts set title = '".addslashes($title)."', body = '".addslashes($description)."', posted = '".addslashes($posted)."' where url = '".addslashes($url)."' and feed = $parameter");
							} else {
								db_query("insert into feed_posts set title = '".addslashes($title)."', body = '".addslashes($description)."', posted = '".addslashes($posted)."', url = '".addslashes($url)."', feed = $parameter, added = $added");
								$weblogs = db_query("select user_id, autopost_tag from feed_subscriptions where feed_id = $parameter and autopost = 'yes'");
								if (is_array($weblogs) && sizeof($weblogs) > 0) {
									$body = "<p><a href=\"$url\">$url</a></p>" . addslashes($description);
									foreach($weblogs as $weblog) {
										db_query("insert into weblog_posts set title = '".addslashes($title)."', body = '$body', access = 'PUBLIC', owner = " . $weblog->user_id . ", weblog = " . $weblog->user_id . ", posted = $added");
										$id = db_id();
										$tags = trim($weblog->autopost_tag);
										if ($tags != "") {
											$tags = explode(",",$tags);
											foreach($tags as $tag) {
												$tag = trim($tag);
												if ($tag != "") {
													$tag = addslashes($tag);
													db_query("insert into tags set tag = '$tag', tagtype = 'weblog', ref = $id, access = 'PUBLIC', owner = " . $weblog->user_id);
												}
											}
										}
									}
								}
							}
						}
					}
				}
			}
		}

?>